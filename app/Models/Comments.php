<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Comments extends Model
{
    protected static function getAllByPosts(int $post_id)
    {
        $comments = static::where(['fk_posts_id' => $post_id])->orderBy('id', 'asc')->get()->toArray();

        return User::extendProfile($comments, 'fk_user_id', ['name', 'avatar']);
    }
}
