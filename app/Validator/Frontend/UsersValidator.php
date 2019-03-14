<?php

namespace App\Validator\Frontend;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\User;

class UsersValidator extends Validator
{
    /**
     * Validator login.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public static function login(Request $request)
    {
        return static::make($request->all(), [
            'email'    => 'required|string|email|max:100',
            'password' => 'required|string|min:6',
        ], [
            'email.email' => __('frontend.inOrUp.email-error'),
            'email.*' => __('frontend.inOrUp.enter-email'),
            'password.min' => __('frontend.inOrUp.enter-password'),
            'password.*' => __('frontend.inOrUp.password-error'),
        ]);
    }

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
                        return $fail(__('frontend.inOrUp.shareCode'));
                    }
                }
            ]
        ], [
            'email.required'     => __('frontend.inOrUp.enter-email'),
            'email.max'          => __('frontend.inOrUp.email_max'),
            'email.unique'       => __('frontend.inOrUp.email_unique'),
            'password.confirmed' => __('frontend.inOrUp.the-passwords-are-different'),
            'nickname.*'         => __('frontend.inOrUp.nickname_error'),
            'email.*'            => __('frontend.inOrUp.email-error'),
            'password.*'         => __('frontend.inOrUp.password-format-error'),
            'shareCode.*'        => __('frontend.inOrUp.shareCode'),
        ]);
    }
}
