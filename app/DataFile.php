<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DataFile extends Model
{
    const STATUS_PENDING = 'pending';
    const STATUS_READY = 'completed';
    const STATUS_ERROR = 'error';

    protected $table = 'data_files';
    protected $guarded = [];
}
