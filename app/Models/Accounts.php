<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Accounts extends Model
{
    public $timestamps = true;

    /** Filed sort the options. */
    const INCOME = 'income';
    const EXPEND = 'expend';

    /** Filed type the options. */
    const LOGIN = 'login';
    const INVITE = 'invite';
    const POSTS = 'posts';
    const BUY = 'buy';

    /**
     * Add account.
     *
     * @param $data = [
     *   'user_id' => 1,
     *   'sort' => '',
     *   'type' => '',
     *   'coin' => '',
     *
     *   'balance' => '',
     *   'post_id' => 1
     * ];
     */
    protected static function add(array $data)
    {
        $m = new static;
        $m->fk_user_id = $data['user_id'];
        $m->sort = $data['sort'];
        $m->type = $data['type'];
        $m->coin = $data['coin'];
        if (isset($data['balance'])) {
            $m->balance = $data['balance'];
        } else {
            $user = User::find($data['user_id']);
            $m->balance = $user->coins;
        }
        if (isset($data['post_id'])) {
            $m->fk_posts_id = $data['post_id'];
        }

        return $m->save();
    }
}
