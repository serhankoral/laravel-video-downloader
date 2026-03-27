<?php

namespace App\Jobs;

use App\Models\Download;
use App\Services\YtDlpService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessDownload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 3600;
    public int $tries = 2;

    public function __construct(public Download $download) {}

    public function handle(): void
    {
        $svc = new YtDlpService();
        $svc->download($this->download);
    }

    public function failed(\Throwable $e): void
    {
        $this->download->update([
            'status' => 'failed',
            'error_message' => $e->getMessage(),
        ]);
    }
}
