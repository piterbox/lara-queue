<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 11.02.2019
 * Time: 14:42
 */

namespace App\Service;
use App\DataFile;
use App\Interfaces\FileReaderInterface;
use App\Interfaces\RepositoryInterface;
use App\Jobs\ReadDataFile;
use App\Member;
use App\Repositories\DataFileRepository;
use App\Repositories\MemberRepository;
use Illuminate\Http\UploadedFile;

class FileReader implements FileReaderInterface
{
    const MAX_READ_ROWS = 6;

    /**
     * @var DataFile
     */
    private $dataFile;
    /**
     * @var UploadedFile
     */
    private $file;
    /**
     * @var resource
     */
    private $handler;
    /**
     * @var int
     */
    private $counter = 1;
    /**
     * @var int
     */
    private $ready = 0;
    /**
     * @var int
     */
    private $step = 0;

    public function __construct(UploadedFile $file, DataFile $dataFile)
    {
        $this->dataFile = $dataFile;
        $this->file = $file;
    }

    public function readFile()
    {

        $rowsCount = sizeof(file($this->file));

        $steps = 0;
        if($rowsCount > self::MAX_READ_ROWS){
            $steps = round($rowsCount/self::MAX_READ_ROWS);
        }

        $this->handler = fopen($this->file, "rb");

        app()->instance('App\Service\FileReader', $this);

        for($i = 0; $i <= $steps; $i++) {
            $this->step = $i;
            dispatch((new ReadDataFile())->delay(60));

        }

        DataFileRepository::save(['ready_members' => $this->ready], $this->dataFile);
        fclose($this->handler);

    }

    /**
     * @param $row
     * @param $handle
     * @return mixed
     */
    public function readRow($row, $handle)
    {

        $result = preg_replace("/[\t\r\n]/",'~',$row);
        $arr = explode('~', $result);

        $newArr = [];
        foreach ($arr as $item) {
            if(trim($item) != '') $newArr[] = trim($item);
        }

        $data['full_name'] = $newArr[5];
        $data['address'] = $newArr[6] . '' . $newArr[7];
        $data['city'] = $newArr[8];
        $data['state'] = $newArr[9];
        $data['zipcode'] = $newArr[10];
        $data['is_union'] = $newArr[11];
        $data['member_number'] = $newArr[3];
        $data['email'] = $newArr[24];
        $data['phone'] = $newArr[25];
        $data['data_file_id'] = $this->dataFile->id;

        return $data;
    }

    /**
     * @throws \Exception
     */
    public function handlePartOfFile( )
    {
        $this->counter = 1;
        while (!feof($this->handler) && $this->counter <= self::MAX_READ_ROWS){

            try{
                $row = fgets($this->handler);
                $data = $this->readRow($row, $this->handler);
                MemberRepository::save($data);
                DataFileRepository::save([
                    'ready_members' => $this->ready
                ], $this->dataFile);
            } catch(\Exception $exception){
                DataFileRepository::save([
                    'state' => DataFile::STATUS_ERROR,
                    'message' => $exception->getMessage(). ' Error throw in line ' . $this->ready
                ], $this->dataFile);
                throw new \Exception('Something was wrong');

            }
            $this->counter = $this->counter+1;
            $this->ready = $this->step*self::MAX_READ_ROWS + $this->counter;

        }

    }
}