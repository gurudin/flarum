<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Posts;

class PostsController extends Controller
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
            $where = [];
            $search = [
                'fk_category_id' => $request->get('fk_category_id', ''),
                'status' => $request->get('status', ''),
            ];
            if ($search['fk_category_id'] != '') {
                $where[] = ['fk_category_id', '=', $search['fk_category_id']];
            }
            if ($search['status'] != '') {
                $where[] = ['status', '=', $search['status']];
            }

            $posts = Posts::where($where)->orderBy('id', 'desc')->paginate(config('admin.pageSize'));

            $categorys = Category::getAll();

            $levels = Posts::READ_LEVEL;

            return view('admin.posts.list', compact(
                'posts',
                'categorys',
                'search',
                'levels'
            ));
        }

        /**
         * Change status.
         */
        if ($request->post('action') == 'status') {
            $posts = Posts::find($request->post('id'));
            $posts->status = $request->post('status');

            return $posts->save()
                ? ['status' => true, 'msg' => 'success']
                : ['status' => false, 'msg' => 'Failed to enabled.'];
        }

        /**
         * Remove
         */
        if ($request->post('action') == 'remove') {
            return Posts::destroy($request->post('id'))
                ? ['status' => true, 'msg' => 'success']
                : ['status' => false, 'msg' => 'Failed to delete.'];
        }
    }

    /**
     * Show the application dashboard.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function save(Request $request)
    {
        if ($request->isMethod('get')) {
            $m = [];
            if ($request->get('id', '') == '') {
                $m = Posts::attributeLabels();
            } else {
                $m = Posts::find(($request->get('id')));
            }

            $categorys = Category::getAll();

            $levels = Posts::READ_LEVEL;

            return view('admin.posts.save', compact('m', 'categorys', 'levels'));
        }

        /**
         * Save
         */
        if ($request->post('action') == 'save') {
            $data = $request->post('data');
            if (!isset($data['id']) || empty($data['id'])) {
                // Create
                $m = new Posts;
                $m->fk_category_id = $data['fk_category_id'];
                $m->fk_user_id = 1;
                $m->read_level = empty($data['read_level']) ? 0 : $data['read_level'];
                $m->coin = empty($data['coin']) ? 0 : $data['coin'];
                $m->title = $data['title'];
                $m->weight = empty($data['weight']) ? 0 : $data['weight'];
                $m->cover = $data['cover'];
                $m->remark = empty($data['remark']) ? '' : $data['remark'];
                $m->content = $data['content'];
                $m->tags = empty($data['tags']) ? '' : $data['tags'];
                $m->pv = $data['pv'];
                $m->real_pv = 0;
                $m->status = $data['status'];

                return $m->save()
                    ? ['status' => true, 'msg' => 'success']
                    : ['status' => false, 'msg' => 'Failed to create.'];
            } else {
                // Update
                $m = Posts::find($data['id']);
                $m->fk_category_id = $data['fk_category_id'];
                $m->fk_user_id = 1;
                $m->read_level = empty($data['read_level']) ? 0 : $data['read_level'];
                $m->coin = empty($data['coin']) ? 0 : $data['coin'];
                $m->title = $data['title'];
                $m->weight = empty($data['weight']) ? 0 : $data['weight'];
                $m->cover = $data['cover'];
                $m->remark = empty($data['remark']) ? '' : $data['remark'];
                $m->content = $data['content'];
                $m->tags = empty($data['tags']) ? '' : $data['tags'];
                $m->pv = $data['pv'];
                $m->status = $data['status'];

                return $m->save()
                    ? ['status' => true, 'msg' => 'success']
                    : ['status' => false, 'msg' => 'Failed to update.'];
            }
        }
    }
}
