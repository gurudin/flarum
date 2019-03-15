<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Posts;
use App\Models\Recomment;

class RecommentController extends Controller
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
            $categorys    = Category::getAll();
            $recomments   = Recomment::orderBy('id', 'desc')->paginate(config('admin.pageSize'));
            $positions    = Recomment::POSITIONS;
            $type_options = Recomment::TYPE_OPTIONS;

            return view('admin.recomment.list', compact(
                'categorys',
                'recomments',
                'positions',
                'type_options'
            ));
        }

        // Enabled
        if ($request->post('action') == 'enabled') {
            $category = Recomment::find($request->post('id'));
            $category->status = $request->post('enabled');

            return $category->save()
                ? ['status' => true, 'msg' => 'success']
                : ['status' => false, 'msg' => 'Failed to enabled.'];
        }

        // Remove
        if ($request->post('action') == 'remove') {
            return Recomment::destroy($request->post('id'))
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
                $m = Recomment::attributeLabels();
            } else {
                $m = Recomment::find(($request->get('id')));
            }

            $categorys    = Category::getAll();
            $type_options = Recomment::TYPE_OPTIONS;
            $positions    = Recomment::POSITIONS;

            return view('admin.recomment.save', compact(
                'm',
                'categorys',
                'type_options',
                'positions'
            ));
        }

        /**
         * Posts
         */
        if ($request->post('action') == 'posts') {
            return ['status' => true, 'data' => Posts::searchPost($request->post('key'))];
        }

        /**
         * Save
         */
        if ($request->post('action') == 'save') {
            $data = $request->post('data');
            if (!isset($data['id']) || empty($data['id'])) {
                // Create
                $m = new Recomment;
                $m->type         = $data['type'];
                $m->recomment_id = !empty($data['recomment_id']) ? $data['recomment_id'] : null;
                $m->url          = !empty($data['url']) ? $data['url'] : '';
                $m->position     = $data['position'];
                $m->cover        = !empty($data['cover']) ? $data['cover'] : '';
                $m->status       = $data[ 'status'];
                $m->remark       = !empty($data['remark']) ? $data['remark'] : '';

                return $m->save()
                    ? ['status' => true, 'msg' => 'success']
                    : ['status' => false, 'msg' => 'Failed to create.'];
            } else {
                // Update
                $m = Recomment::find($data['id']);
                $m->type         = $data['type'];
                $m->recomment_id = !empty($data['recomment_id']) ? $data['recomment_id'] : '';
                $m->url          = !empty($data['url']) ? $data['url'] : '';
                $m->position     = $data['position'];
                $m->cover        = !empty($data['cover']) ? $data['cover'] : '';
                $m->status       = $data['status'];
                $m->remark       = !empty($data['remark']) ? $data['remark'] : '';

                return $m->save()
                    ? ['status' => true, 'msg' => 'success']
                    : ['status' => false, 'msg' => 'Failed to update.'];
            }
        }
    }
}
