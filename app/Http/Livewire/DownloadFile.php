<?php

namespace App\Http\Livewire;

use App\Http\Traits\AppErrorLog;
use Exception;
use Livewire\Component;
use Storage;

class DownloadFile extends Component
{
    public string $filePath;

    public function downloadFile($filePath)
    {
        try {
            sleep(1);
            if (Storage::exists($filePath)) {
                return Storage::download($filePath);
            } else {
                noty()->addInfo("File not found");
            }
        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.download-file');
    }
}
