<?php

namespace App\Http\Controllers;

use App\DataFile;
use App\Interfaces\FileReaderInterface;
use App\Jobs\ReadDataFile;
use App\Repositories\DataFileRepository;
use App\Service\FileReader;
use Illuminate\Http\Request;
use Symfony\Component\VarDumper\Cloner\Data;

class MemberDataController extends Controller
{

    public function loadFile(Request $request)
    {
        $file = $request->file('members_data');
        if(!$file) return redirect()->back()->withErrors('File not found');

        $rowsCount = sizeof(file($file));
        $dataFile = DataFileRepository::save(
            [
                'name' => $file->getClientOriginalName() . '_' . (new \DateTime())->getTimestamp(),
                'all_members' => $rowsCount,
                'ready_members' => 0,
                'state' => DataFile::STATUS_PENDING,

            ]
        );

        $fileReader = new FileReader($file, $dataFile);
        $fileReader->readFile();
        DataFileRepository::save(
            [
                'state' => DataFile::STATUS_READY,
                'read_at' => (new \DateTime())
            ], $dataFile);


        //session()->flash('status', 'Data from file was read!');
        //return redirect('/');
    }

    public function getData()
    {
        $files = DataFileRepository::all();
        return view('table')->with(['files' => $files]);
    }
}
