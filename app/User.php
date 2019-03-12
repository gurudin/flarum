<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    const BLACKLIST = 0;
    const NORMAL = 1;
    const ADMINISTRATOR = 10;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Extends profile
     *
     * @param array $oriArray
     * @param string $key
     * @param array $fileds
     *
     * @return array
     */
    protected static function extendProfile($oriArray, $key = 'fk_user_id', $fileds = [])
    {
        array_push($fileds, 'id');

        $uids = array_map('intval', array_values(array_filter(array_column($oriArray, $key))));

        $obj_res = static::select($fileds)->whereIn('id', $uids)->get()->toArray();

        $ret = [];
        foreach ($oriArray as $ori) {
            $found = 0;
            foreach ($obj_res as $obj) {
                if ($obj['id'] == $ori[$key]) {
                    array_push($ret, array_merge($ori, $obj));
                    $found = 1;
                }
            }
            if ($found == 0) {
                array_push($ret, $ori);
            }
        }

        return $ret;
    }
}
