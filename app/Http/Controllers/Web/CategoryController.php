<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;

class CategoryController extends Controller
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
            $category = Category::getCategory();

            return view('admin.category.list', compact(
                'category'
            ));
        }

        // Enabled
        if ($request->post('action') == 'enabled') {
            $category = Category::find($request->post('id'));
            $category->enabled = $request->post('enabled');

            return $category->save()
                ? ['status' => true, 'msg' => 'success']
                : ['status' => false, 'msg' => 'Failed to enabled.'];
        }

        // Remove
        if ($request->post('action') == 'remove') {
            return Category::destroy($request->post('id'))
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
                $m = Category::attributeLabels();
            } else {
                $m = Category::find(($request->get('id')));
            }

            return view('admin.category.save', compact('m'));
        }

        /**
         * Save
         */
        if ($request->post('action') == 'save') {
            $data = $request->post('data');
            if (!isset($data['id']) || empty($data['id'])) {
                // Create
                $m = new Category;
                $m->category = $data['category'];
                $m->alias = $data['alias'];
                $m->color = $data['color'];
                $m->enabled = $data['enabled'];
                $m->parent_id = $data['parent_id'];
                $m->weight = $data['weight'];
                $m->pic = empty($data['pic']) ? '' : $data['pic'];
                $m->remark = empty($data['remark']) ? '' : $data['remark'];

                return $m->save()
                    ? ['status' => true, 'msg' => 'success']
                    : ['status' => false, 'msg' => 'Failed to create.'];
            } else {
                // Update
                $m = Category::find($data['id']);
                $m->category = $data['category'];
                $m->alias = $data['alias'];
                $m->color = $data['color'];
                $m->enabled = $data['enabled'];
                $m->parent_id = $data['parent_id'];
                $m->weight = $data['weight'];
                $m->pic = empty($data['pic']) ? '' : $data['pic'];
                $m->remark = empty($data['remark']) ? '' : $data['remark'];

                return $m->save()
                    ? ['status' => true, 'msg' => 'success']
                    : ['status' => false, 'msg' => 'Failed to update.'];
            }
        }
    }
}
