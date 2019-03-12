<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InviteCode extends Model
{
    protected $table = 'invite_code';
    public $timestamps = false;

    /**
     * Get invite code.
     *
     * @param int $number
     * @param int $len
     *
     * @return array
     */
    protected static function generateCode($number = 1, $len = 5)
    {
        $generate = function () use ($len) {
            $chars  = 'ABCDEFGHIGKLMNOPQRSTUVWXYZ123456789';
            $string =time();

            for (; $len>=1; $len--) {
                $position  = rand()%strlen($chars);
                $position2 = rand()%strlen($string);
                $string    = substr_replace($string, substr($chars, $position, 1), $position2, 0);
            }

            return $string;
        };

        $codes = [];
        for ($i=0; $i<$number; $i++) {
            $codes[] = $generate();
            $codes = array_unique($codes);
            if (count($codes) < ($i + 1)) {
                $i = count($codes);
            }
        }
        
        return $codes;
    }
}
