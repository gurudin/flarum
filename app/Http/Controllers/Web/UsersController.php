<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\InviteCode;
use App\User;

class UsersController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request)
    {
        if ($request->isMethod('get')) {
            $search = [
                'status' => $request->get('status', ''),
                'vip'    => $request->get('vip', ''),
                'key'    => $request->get('key', ''),
            ];
            $where = [];
            if ($search['status'] != '') {
                if ($search['status'] == 'normal') {
                    $where[] = ['status', '=', 1];
                }
                if ($search['status'] == 'blacklist') {
                    $where[] = ['status', '=', 0];
                }
            }
            if ($search['vip'] != '') {
                if ($search['vip'] == 'yes') {
                    $where[] = ['vip_start_at', '<>', null];
                    $where[] = ['vip_end_at', '<>', null];
                }
                if ($search['vip'] == 'no') {
                    $where[] = ['vip_start_at', '=', null];
                    $where[] = ['vip_end_at', '=', null];
                }
            }

            $query = User::where($where)->orderBy('id', 'desc');
            if ($search['key'] != '') {
                $query = $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search['key'] . '%')
                        ->orWhere('email', 'like', '%' . $search['key'] . '%');
                });
            }

            $users = $query->paginate(config('admin.pageSize'));

            return view('admin.users.list', compact(
                'users',
                'search'
            ));
        }

        /**
         * Forbided
         */
        if ($request->post('action') == 'forbid') {
            $user = User::find($request->post('id'));
            $user->status = $request->post('status');

            return $user->save()
                ? ['status' => true, 'msg' => 'success']
                : ['status' => false, 'msg' => 'Failed to forbid.'];
        }
    }

    /**
     * Show the application dashboard.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function code(Request $request)
    {
        if ($request->isMethod('get')) {
            $search = [
                'status' => $request->get('status', ''),
                'uid' => $request->get('uid', ''),
            ];

            $where = [];
            if ($search['status'] != '') {
                if ($search['status'] == 'expired') {
                    $where[] = ['code_expiration_time', '<', date('Y-m-d H:i:s')];
                }
                if ($search['status'] == 'used') {
                    $where[] = ['fk_user_id', '<>', null];
                }
            }
            if ($search['uid'] != '') {
                $where[] = ['fk_user_id', '=', $search['uid']];
            }

            $invite_codes = InviteCode::where($where)
                ->orderBy('id', 'desc')
                ->paginate(config('admin.pageSize'));

            $codes = User::extendProfile($invite_codes->toArray()['data'], 'fk_user_id', ['name']);

            return view('admin.users.code', compact(
                'invite_codes',
                'codes',
                'search'
            ));
        }

        /**
         * Create
         */
        if ($request->post('action') == 'create') {
            $data = $request->post('data');
            $codes = InviteCode::generateCode($data['number']);

            foreach ($codes as $code) {
                $m = new InviteCode;
                $m->code = $code;
                $m->code_expiration_at = date('Y-m-d H:i:s', time() + 3600 * 24 * $data['expiration_day']);
                $m->vip_valid = $data['valid_month'];
                $m->created_at = date('Y-m-d H:i:s');
                $m->save();
            }

            return ['status' => true, 'msg' => 'success'];
        }

        // Remove
        if ($request->post('action') == 'remove') {
            return InviteCode::destroy($request->post('id'))
                ? ['status' => true, 'msg' => 'success']
                : ['status' => false, 'msg' => 'Failed to delete.'];
        }
    }
}
