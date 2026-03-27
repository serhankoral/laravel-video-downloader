<?php

namespace App\Services;

use App\Models\Download;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class YtDlpService
{
    protected string $ytdlpPath;
    protected string $ffmpegPath;
    protected string $downloadPath;

    public function __construct()
    {
        $this->ytdlpPath = env('YTDLP_PATH', 'yt-dlp');
        $this->ffmpegPath = env('FFMPEG_PATH', 'ffmpeg');
        $this->downloadPath = storage_path('app/downloads');

        if (!is_dir($this->downloadPath)) {
            mkdir($this->downloadPath, 0755, true);
        }
    }

    /**
     * URL hakkında bilgi getir (indirmeden)
     */
    public function getInfo(string $url): array
    {
        $process = new Process([
            $this->ytdlpPath,
            '--dump-json',
            '--no-playlist',
            '--no-warnings',
            $url
        ]);

        $process->setTimeout(60);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new \RuntimeException('Video bilgisi alınamadı: ' . $process->getErrorOutput());
        }

        $data = json_decode($process->getOutput(), true);

        return [
            'title'     => $data['title'] ?? 'Bilinmeyen Başlık',
            'thumbnail' => $data['thumbnail'] ?? null,
            'duration'  => $data['duration'] ?? 0,
            'uploader'  => $data['uploader'] ?? null,
            'formats'   => $this->parseFormats($data['formats'] ?? []),
            'is_playlist' => false,
        ];
    }

    /**
     * Playlist bilgisi getir
     */
    public function getPlaylistInfo(string $url): array
    {
        $process = new Process([
            $this->ytdlpPath,
            '--dump-json',
            '--flat-playlist',
            '--no-warnings',
            $url
        ]);

        $process->setTimeout(120);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new \RuntimeException('Playlist bilgisi alınamadı: ' . $process->getErrorOutput());
        }

        $lines = array_filter(explode("\n", trim($process->getOutput())));
        $items = array_map(fn($line) => json_decode($line, true), $lines);

        return [
            'title'    => 'Playlist',
            'count'    => count($items),
            'items'    => $items,
            'is_playlist' => true,
        ];
    }

    /**
     * Video indir ve progress güncelle
     */
    public function download(Download $download): void
    {
        $output = $this->downloadPath . '/%(title)s.%(ext)s';

        $cmd = [
            $this->ytdlpPath,
            '--ffmpeg-location', $this->ffmpegPath,
            '--newline',
            '--progress',
            '-o', $output,
        ];

        // Format seçimi
        if ($download->format === 'mp3') {
            $cmd = array_merge($cmd, ['-x', '--audio-format', 'mp3']);
        } else {
            $quality = match($download->quality) {
                '1080p' => 'bestvideo[height<=1080]+bestaudio/best',
                '720p'  => 'bestvideo[height<=720]+bestaudio/best',
                '480p'  => 'bestvideo[height<=480]+bestaudio/best',
                '360p'  => 'bestvideo[height<=360]+bestaudio/best',
                default => 'bestvideo+bestaudio/best',
            };
            $cmd = array_merge($cmd, ['-f', $quality]);
        }

        $cmd[] = $download->url;

        $download->update(['status' => 'processing', 'progress' => 0]);

        $process = new Process($cmd);
        $process->setTimeout(3600);

        $process->run(function ($type, $buffer) use ($download) {
            // Progress'i parse et
            if (str_contains($buffer, '[download]') && str_contains($buffer, '%')) {
                preg_match('/(\d+\.?\d*)%/', $buffer, $matches);
                if (!empty($matches[1])) {
                    $progress = (int) round((float) $matches[1]);
                    $download->update(['progress' => $progress]);
                }
            }
        });

        if (!$process->isSuccessful()) {
            $download->update([
                'status' => 'failed',
                'error_message' => $process->getErrorOutput()
            ]);
            return;
        }

        $download->update([
            'status'   => 'completed',
            'progress' => 100,
        ]);
    }

    /**
     * Mevcut formatları parse et
     */
    protected function parseFormats(array $formats): array
    {
        $available = [];
        $heights = [2160, 1080, 720, 480, 360];

        foreach ($heights as $h) {
            foreach ($formats as $f) {
                if (isset($f['height']) && $f['height'] === $h && isset($f['vcodec']) && $f['vcodec'] !== 'none') {
                    $available[] = ['label' => "{$h}p", 'value' => "{$h}p"];
                    break;
                }
            }
        }

        if (empty($available)) {
            $available[] = ['label' => 'En İyi', 'value' => 'best'];
        }

        $available[] = ['label' => 'MP3 (Ses)', 'value' => 'mp3'];

        return $available;
    }

    /**
     * yt-dlp sürümünü kontrol et
     */
    public function checkInstallation(): bool
    {
        try {
            $process = new Process([$this->ytdlpPath, '--version']);
            $process->setTimeout(10);
            $process->run();
            return $process->isSuccessful();
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * FFmpeg sürümünü kontrol et
     */
    public function checkFfmpeg(): bool
    {
        try {
            $process = new Process([$this->ffmpegPath, '-version']);
            $process->setTimeout(10);
            $process->run();
            return $process->isSuccessful();
        } catch (\Exception $e) {
            return false;
        }
    }
}
