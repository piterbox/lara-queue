<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 11.02.2019
 * Time: 16:26
 */

namespace App\Repositories;


use App\DataFile;
use App\Interfaces\RepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class DataFileRepository implements RepositoryInterface
{
    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public static function all()
    {
        return DataFile::all();
    }

    /**
     * @param integer $id
     * @return DataFile|null
     */
    public static function get($id)
    {
        return DataFile::findOrFail($id);
    }

    /**
     * @param array $data
     * @param DataFile $dataFile|null
     * @return DataFile|null
     */
    public static function save($data, $dataFile = null)
    {
        if(is_null($dataFile)) {
            return Datafile::create($data);
        }
        return $dataFile->update($data);

    }

    /**
     * @param integer $id
     */
    public static function remove($id)
    {
        $member = DataFile::find($id);
        if($member) $member->delete();
    }
}