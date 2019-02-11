<?php

namespace App\Jobs;

use App\DataFile;
use App\Interfaces\FileReaderInterface;
use App\Repositories\DataFileRepository;
use App\Repositories\MemberRepository;
use App\Service\FileReader;
use Illuminate\Bus\Queueable;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;
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
