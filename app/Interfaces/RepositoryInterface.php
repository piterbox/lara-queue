<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 11.02.2019
 * Time: 15:20
 */

namespace App\Interfaces;


interface RepositoryInterface
{
    public static function all();

    public static function get($id);

    public static function save($data, $model = null);

    public static function remove($id);
}