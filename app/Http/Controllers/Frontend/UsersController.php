<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;
use App\Validator\Frontend\UsersValidator;
use App\User;
use App\Models\Accounts;

class UsersController
{
    /**
     * Show the application dashboard.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $validator = UsersValidator::login($request);

        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->getMessageBag()->toArray() as $key => $err) {
                $errors[$key] = Arr::first($err);
            }

            return ['status' => false, 'code' => -1, 'data' => $errors];
        }

        if (Auth::attempt([
            'email' => $request->post('email'),
            'password' => $request->post('password'),
            'status' => User::NORMAL
        ], $request->post('remember_me', null) === true ? true : false)) {
            return ['status' => true, 'msg' => 'success'];
        } else {
            return ['status' => false, 'msg' => __('frontend.inOrUp.sign-error')];
        }
    }

    /**
     * Show the application dashboard.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = UsersValidator:: register($request);

        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->getMessageBag()->toArray() as $key => $err) {
                $errors[$key] = Arr::first($err);
            }

            return ['status' => false, 'code' => -1, 'data' => $errors];
        }

        $uid = User::insertGetId([
            'name'       => $request->post('nickname'),
            'email'      => $request->post('email'),
            'password'   => Hash::make($request->post('password')),
            'avatar'     => $this->getAvatar(),
            'share_code' => $request->post('shareCode', ''),
            'status'     => User::NORMAL,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        // Increase the coins.
        if ($uid && $request->post('shareCode', '') != '') {
            $share_id = base64_decode($request->post('shareCode'));
            $user = User::find($uid);
            if ($share_user = User::find($share_id)) {
                $share_user->coins = $share_user->coins + config('api.reward.share_coins');
                $share_user->save();
                // Share account.
                Accounts::add([
                    'user_id' => $share_id,
                    'sort'    => Accounts::INCOME,
                    'type'    => Accounts::INVITE,
                    'coin'    => config('api.reward.share_coins'),
                    'balance' => $share_user->coins
                ]);

                $user->coins = $user->coins + config('api.reward.register_coins');
                $coins = config('api.reward.register_coins');
            } else {
                $user->coins = $user->coins + config('api.reward.normal');
                $coins = config('api.reward.normal');
            }
            $user->save();

            // Register account.
            Accounts::add([
                'user_id' => $uid,
                'sort'    => Accounts::INCOME,
                'type'    => Accounts::REGISTER,
                'coin'    => $coins,
                'balance' => $user->coins
            ]);
        }

        return ['status' => true, 'msg' => 'success'];
    }

    public function logout()
    {
        Auth::logout();

        return redirect('/');
    }

    /**
     * Get random avatar.
     */
    private function getAvatar()
    {
        $avatar = [
            'default',
            'elephant',
            'giraffe',
            'horse',
            'monkey',
            'owl',
            'panda',
            'rabbit',
            'sheep',
            'whale'
        ];

        return '/storage/avatar/' . $avatar[rand(0, 9)] . '.png';
    }
}
