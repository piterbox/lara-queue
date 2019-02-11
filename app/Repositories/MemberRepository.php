<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 11.02.2019
 * Time: 15:20
 */

namespace App\Repositories;


use App\Interfaces\RepositoryInterface;
use App\Member;
use Illuminate\Database\Eloquent\Model;

class MemberRepository implements RepositoryInterface
{
    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public static function all()
    {
        return Member::all();
    }

    /**
     * @param $id
     * @return mixed
     */
    public static function get($id)
    {
        return Member::findOrFail($id);
    }

    /**
     * @param $data
     * @param Member|null $model
     * return void
     */
    public static function save($data, $model = null)
    {
        if(is_null($model)) return Member::create($data);
        return $model->update($data);

    }

    public static function remove($id)
    {
        $member = Member::find($id);
        if($member) $member->delete();

    }
}