<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 11.02.2019
 * Time: 14:42
 */

namespace App\Services;
use App\DataFile;
use App\Interfaces\FileReaderInterface;
use App\Jobs\ReadDataFile;
use App\Repositories\DataFileRepository;
use App\Repositories\MemberRepository;
use Illuminate\Http\UploadedFile;

class FileReader implements FileReaderInterface
{
    const MAX_READ_ROWS = 6;

    const INDEX_FULL_NAME = 5;
    const INDEX_ADDRESS_FIRST_PART = 6;
    const INDEX_ADDRESS_SECOND_PART = 7;
    const INDEX_CITY = 8;
    const INDEX_STATE = 9;
    const INDEX_ZIP_CODE = 10;
    const INDEX_IS_UNION = 11;
    const INDEX_MEMBER_NUMBER = 3;
    const INDEX_EMAIL = 24;
    const INDEX_PHONE = 25;
    const MIN_COUNT_COLUMNS = 25;


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

        app()->instance('App\Services\FileReader', $this);

        for($i = 0; $i <= $steps; $i++) {
            $this->step = $i;
            dispatch(new ReadDataFile());

        }

        DataFileRepository::save(['ready_members' => $this->ready], $this->dataFile);
        fclose($this->handler);

    }

    /**
     * @param $row
     * @return mixed
     */
    public function readRow($row)
    {

        $result = preg_replace("/[\t\r\n]/",'~',$row);
        $arr = explode('~', $result);

        $newArr = [];
        foreach ($arr as $item) {
            if(trim($item) != '') $newArr[] = trim($item);
        }
        $data = [];
        if(count($newArr) >= self::MIN_COUNT_COLUMNS){
            $data['full_name'] = $newArr[self::INDEX_FULL_NAME];
            $data['address'] = $newArr[self::INDEX_ADDRESS_FIRST_PART] . ' ' . $newArr[self::INDEX_ADDRESS_SECOND_PART];
            $data['city'] = $newArr[self::INDEX_CITY];
            $data['state'] = $newArr[self::INDEX_STATE];
            $data['zipcode'] = $newArr[self::INDEX_ZIP_CODE];
            $data['is_union'] = $newArr[self::INDEX_IS_UNION];
            $data['member_number'] = $newArr[self::INDEX_MEMBER_NUMBER];
            $data['email'] = $newArr[self::INDEX_EMAIL];
            $data['phone'] = $newArr[self::INDEX_PHONE];
            $data['data_file_id'] = $this->dataFile->id;
        }

        return $data;
    }

    /**
     * @throws \Exception
     */
    public function handlePartOfFile()
    {
        $this->counter = 1;
        while (!feof($this->handler) && $this->counter <= self::MAX_READ_ROWS){

            try{
                $row = fgets($this->handler);
                $data = $this->readRow($row);
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