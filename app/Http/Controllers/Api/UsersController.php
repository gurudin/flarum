<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\Validator\Api\UsersValidator;
use App\User;
use App\Models\Accounts;

class UsersController extends BaseController
{
    /**
     * Register.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return Json
     */
    public function register(Request $request)
    {
        $validator = UsersValidator::register($request);

        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->getMessageBag()->toArray() as $key => $err) {
                $errors[$key] = Arr::first($err);
            }

            return ['status' => true, 'code' => -1, 'data' => $errors];
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
                    'sort' => Accounts::INCOME,
                    'type' => Accounts::INVITE,
                    'coin' => config('api.reward.share_coins'),
                    'balance' => $share_user->coins
                ]);

                $user->coins = $user->coins + config('api.reward.register_coins');
            } else {
                $user->coins = $user->coins + config('api.reward.normal');
            }
            $user->save();
        }

        return ['status' => true, 'msg' => 'success'];
    }

    /**
     * Register.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return Json
     */
    public function login(Request $request)
    {
        if (Auth::attempt([
            'email' => $request->post('email'),
            'password' => $request->post('password')
        ], $request->post('remember_me', null) === true ? true : false)) {
            if (Auth::user()['status'] == 0) {
                return ['status' => false, 'msg' => __('api.validation.the_user_has_been_blocked')];
            }
            
            $api_token = Str::uuid();
            $user = User::find(Auth::id());
            $user->api_token = $api_token;
            $user->save();

            Auth::user()['api_token'] = $api_token;

            // Add cache (default 7days)
            Cache::add($api_token, Auth::user(), 10080);

            return [
                'status' => true,
                'data' => [
                    'name'         => Auth::user()['name'],
                    'avatar'       => Auth::user()['avatar'],
                    'email'        => Auth::user()['email'],
                    'token'        => Auth::user()['api_token'],
                    'coins'        => Auth::user()['coins'],
                    'vip_start_at' => Auth::user()['vip_start_at'],
                    'vip_end_at'   => Auth::user()['vip_end_at'],
                ]
            ];
        } else {
            return ['status' => true, 'code' => -1, 'data' => ['password' => __('api.validation.login_error')]];
        }
    }

    /**
     * Logout.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return Json
     */
    public function logout(Request $request)
    {
        if (Cache::has($request->post('token'))) {
            $cache_user = Cache::get($request->post('token'));
            
            if ($user = User::find($cache_user['id'])) {
                $user->api_token = '';
                $user->remember_token = '';
                
                if ($user->save()) {
                    Cache::forget($request->post('token'));
                }
            }
        }

        return ['status' => true, 'msg' => 'success'];
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
