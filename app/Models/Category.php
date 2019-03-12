<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'category';
    public $timestamps = true;

    protected static function attributeLabels()
    {
        return [
            'parent_id' => 0,
            'category'  => '',
            'weight'    => 0,
            'alias'     => '',
            'color'     => '',
            'pic'       => '',
            'remark'    => '',
            'enabled'   => 0
        ];
    }

    protected static function getCategory(array $where = [])
    {
        return static::where($where)->orderBy('id', 'desc')->paginate(config('admin.pageSize'));
    }
}
