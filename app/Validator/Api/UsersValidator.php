<?php

namespace App\Validator\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\User;

class UsersValidator extends Validator
{
    /**
     * Get a validator for an incoming registration request.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public static function register(Request $request)
    {
        return static::make($request->all(), [
            'nickname'  => 'required|string|min:4|max:50',
            'email'     => 'required|string|email|max:100|unique:users',
            'password'  => 'required|string|min:6|confirmed',
            'shareCode' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if (!is_numeric(base64_decode($value))) {
                        return $fail(__('api.validation.shareCode'));
                    }
                }
            ]
        ], [
            'nickname.*'   => __('api.validation.nickname'),
            'email.max'    => __('api.validation.email_max'),
            'email.unique' => __('api.validation.email_unique'),
            'password.*'   => __('api.validation.password'),
            'shareCode.*'  => __('api.validation.shareCode'),
        ]);
    }
}
