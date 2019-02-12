<?php

namespace App\Jobs;

use App\DataFile;
use App\Services\FileReader;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ReadDataFile implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * @param $handle
     * @param DataFile $dataFile
     */
    public function handle(FileReader $fileReader)
    {
        $fileReader->handlePartOfFile();
    }
}
