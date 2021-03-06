<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Posts extends Model
{
    const READ_LEVEL = [
        0 => '所有用户', // All users
        1 => '登陆后查看', // Read after logging in
        2 => 'Vip用户(不消耗金币)', // Vip users can read (no coins consumption)
        3 => 'Vip用户(消耗金币)', // Vip users can read (coins consumption)
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

    /**
     * Search posts.
     */
    protected static function searchPost($key = '')
    {
        $query = static::where(['status' => 1])->orderBy('weight', 'desc')->orderBy('id', 'desc');

        if (is_numeric($key)) {
            $query = $query->where(['id' => $key]);
        } else {
            $query = $query->where('title', 'like', '%' . $key . '%');
        }

        return $query->get();
    }

    /**
     * Frontend latest or hots.
     */
    protected static function getLaetstOrHots(string $type = 'latest')
    {
        $query = static::where(['status' => 1]);
        if ($type == 'latest') {
            $query = $query->orderBy('id', 'desc');
        }
        if ($type = 'hots') {
            $query = $query->orderBy('real_pv', 'desc');
        }

        return $query->offset(0)->limit(6)->get();
    }
}
