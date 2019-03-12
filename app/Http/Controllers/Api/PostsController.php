<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Posts;
use App\User;

class PostsController extends BaseController
{
    /**
     * Get category all.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return Json
     */
    public function all(Request $request)
    {
        $order       = $request->get('order', 'new');
        $page        = $request->get('p', 1);
        $categoryId  = $request->get('cId', ''); // Category id.
        $filter      = $request->get('filter', '');

        $where[] = ['status', '=', 1];
        if ($categoryId != '') {
            $where[] = ['fk_category_id', '=', $categoryId];
        }
        if ($filter != '') {
            $where[] = ['title', 'like', '%' . $filter . '%'];
        }

        // Order by.
        $orderBy = 'weight,id';
        if ($order == 'top') {
            $orderBy = 'weight,pv';
        }

        $query = Posts::where($where)->select([
            'id as pid',
            'fk_category_id as cid',
            'fk_user_id as uid',
            'read_level',
            'coin',
            'title',
            'cover',
            'tags',
            'remark',
            'pv',
            'updated_at'
        ])->orderBy('weight', 'desc');
        if ($order == 'top') {
            $query = $query->orderBy('pv', 'desc');
        } else {
            $query = $query->orderBy('pid', 'desc');
        }

        $posts = $query->paginate(config('admin.pageSize'))->toArray();
        $posts['data'] = User::extendProfile($posts['data'], 'uid', ['name']);
        $result = [
            'item' => $posts['data'],
            'page' => [
                'current_page' => $posts['current_page'],
                'prev' => $posts['prev_page_url'] === null ? false : true,
                'next' => $posts['next_page_url'] === null ? false : true,
                'total' => $posts['total']
            ]
        ];

        return ['status' => true, 'data' => $result];
    }

    /**
     * Get post by id.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return Json
     */
    public function detail(Request $request, int $id)
    {
        if ($id == '') {
            return ['status' => false, 'code' => -1, 'msg' => __('api.no_permission_to_read_the_post')];
        }

        $post = Posts::find($id);

        // Post status error.
        if ($post['status'] != 1) {
            return ['status' => false, 'code' => -1, 'msg' => __('api.no_permission_to_read_the_post')];
        }
        
        // Read after logging in
        if ($post['read_level'] == 1) {
            // Not login.
            if ($request->header('user-token', '') == '') {
                return ['status' => true, 'code' => 1001, 'msg' => __('api.not_login')];
            }

            // Login date.
            if (!Cache::has($request->header('user-token'))) {
                return ['status' => true, 'code' => 1002, 'msg' => __('api.login_date')];
            }
        }

        // Vip users can read (no coins consumption)
        if ($post['read_level'] == 2) {
            // TODO:
        }

        // Vip users can read (coins consumption)
        if ($post['read_level'] == 3) {
            // RODO:
        }

        $user = User::find($post['fk_user_id']);
        return [
            'status' => true,
            'code' => 0,
            'data' => [
                'post' => [
                    'id'         => $post['id'],
                    'title'      => $post['title'],
                    'content'    => $post['content'],
                    'cover'      => $post['cover'],
                    'cid'        => $post['fk_category_id'],
                    'uid'        => $post['fk_user_id'],
                    'nickname'   => $user['name'],
                    'avatar'     => $user['avatar'],
                    'pv'         => $post['pv'],
                    'coin'       => $post['coin'],
                    'tags'       => $post['tags'],
                    'created_at' => strtotime($post['created_at'])
                ],
                'comments' => [],
            ]
        ];
    }
}
