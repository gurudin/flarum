<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Posts extends Model
{
    const READ_LEVEL = [
        0 => 'All users',
        1 => 'Read after logging in',
        2 => 'Vip users can read (no coins consumption)',
        3 => 'Vip users can read (coins consumption)'
    ];

    protected static function attributeLabels()
    {
        return [
            'fk_category_id' => '',
            'fk_user_id' => 1,
            'read_level' => 0,
            'coin' => 0,
            'title' => '',
            'weight' => 0,
            'cover' => '',
            'remark' => '',
            'content' => '',
            'tags' => '',
            'pv' => rand(20, 500),
            'status' => 1,
        ];
    }
}
