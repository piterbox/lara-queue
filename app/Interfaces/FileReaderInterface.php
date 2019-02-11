<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 11.02.2019
 * Time: 14:44
 */

namespace App\Interfaces;


use Illuminate\Http\UploadedFile;

interface FileReaderInterface
{
    /**
     * @param $row
     * @param $handle
     * @return mixed
     */
    public function readRow($row, $handle);
}