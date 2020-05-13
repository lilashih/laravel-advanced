<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

abstract class BaseModel extends Model
{
    protected $guarded = ['id'];

    public static function getTableName()
    {
        return with(new static)->getTable();
    }

    public static function instance()
    {
        return new static();
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

}
