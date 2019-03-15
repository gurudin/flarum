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
            $categorys = Category::getAll();

            return view('admin.recomment.list', compact(
                'categorys'
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
            $categorys = Category::getAll();

            $m = Recomment::attributeLabels();
            $type_options = Recomment::TYPE_OPTIONS;
            $positions = Recomment::POSITIONS;

            return view('admin.recomment.save', compact(
                'm',
                'categorys',
                'type_options',
                'positions'
            ));
        }

        /**
         * Save
         */
        if ($request->post('action') == 'save') {
            $data = $request->post('data');
            if (!isset($data['id']) || empty($data['id'])) {
                // Create
            } else {
                // Update
                
            }
        }
    }
}
