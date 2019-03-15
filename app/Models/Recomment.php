<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recomment extends Model
{
    public $timestamps = true;

    /** Filed the `type` options. */
    const TYPE_OPTIONS = [
        'posts'    => '帖子',
        'category' => '类别',
        'url'      => '链接'
    ];

    /** Filed the `position` options. */
    const POSITIONS = [
        '1' => '首页左部幻灯片',
        '2' => '首页中部'
    ];

    protected static function attributeLabels()
    {
        return [
            'recomment_id' => '',
            'url' => '',
            'type' => 'posts',
            'position' => '',
            'cover' => '',
            'status' => 0,
            'remark' => '',
        ];
    }
}
