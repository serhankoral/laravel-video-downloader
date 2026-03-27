<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Download;
use App\Services\YtDlpService;

class VideoDownloader extends Component
{
    public string $url = '';
    public array $videoInfo = [];
    public string $selectedFormat = 'mp4';
    public string $selectedQuality = 'best';
    public bool $loading = false;
    public string $error = '';
    public bool $isPlaylist = false;

    protected $rules = [
        'url' => 'required|url',
    ];

    public function fetchInfo(): void
    {
        $this->validate();
        $this->loading = true;
        $this->error = '';
        $this->videoInfo = [];

        try {
            $svc = new YtDlpService();

            // Playlist mi kontrol et
            if (str_contains($this->url, 'playlist') || str_contains($this->url, 'list=')) {
                $this->videoInfo = $svc->getPlaylistInfo($this->url);
                $this->isPlaylist = true;
            } else {
                $this->videoInfo = $svc->getInfo($this->url);
                $this->isPlaylist = false;
            }
        } catch (\Exception $e) {
            $this->error = 'Hata: ' . $e->getMessage();
        } finally {
            $this->loading = false;
        }
    }

    public function startDownload(): void
    {
        if (empty($this->videoInfo)) {
            $this->error = 'Önce video bilgisini çekin.';
            return;
        }

        $download = Download::create([
            'url'         => $this->url,
            'title'       => $this->videoInfo['title'] ?? 'Bilinmeyen',
            'thumbnail'   => $this->videoInfo['thumbnail'] ?? null,
            'format'      => $this->selectedFormat,
            'quality'     => $this->selectedQuality,
            'status'      => 'pending',
            'is_playlist' => $this->isPlaylist,
            'playlist_count' => $this->videoInfo['count'] ?? 0,
        ]);

        // Kuyruğa gönder
        dispatch(new \App\Jobs\ProcessDownload($download));

        $this->reset(['url', 'videoInfo', 'selectedFormat', 'selectedQuality', 'isPlaylist']);
        $this->dispatch('download-started', id: $download->id);
    }

    public function render()
    {
        return view('livewire.video-downloader');
    }
}
