<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Download;

class DownloadHistory extends Component
{
    use WithPagination;

    public function getListeners()
    {
        return ['download-started' => '$refresh'];
    }

    public function deleteDownload(int $id): void
    {
        $download = Download::findOrFail($id);
        if ($download->file_path && file_exists($download->file_path)) {
            unlink($download->file_path);
        }
        $download->delete();
    }

    public function render()
    {
        return view('livewire.download-history', [
            'downloads' => Download::latest()->paginate(10),
        ]);
    }
}
