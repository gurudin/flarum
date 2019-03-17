<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Category;
use App\Models\Recomment;
use App\Models\Posts;

class SiteController
{
    /**
     * Show the application dashboard.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Category
        $categorys  = Category::getAll($request->get('alias'));
        $group_posts = Posts::select(DB::raw('MAX(updated_at) as latest_at, COUNT(id) as total, fk_category_id'))
            ->where(['status' => 1])
            ->groupBy('fk_category_id')
            ->get();
        foreach ($categorys as $i => $category) {
            foreach ($category['children'] as $j => $v) {
                foreach ($group_posts as $group) {
                    if ($v['id'] == $group->fk_category_id) {
                        $categorys[$i]['children'][$j]['latest_at'] = $group->latest_at;
                        $categorys[$i]['children'][$j]['total'] = $group->total;
                    }
                }
                unset($group);
            }
        }
        unset($category);

        // Recomment
        $recomments = Recomment::getRecomment();

        // The latest posts. (6 rows)
        $posts['latest'] = Posts::getLaetstOrHots('latest');

        // This hots posts. (6 rows)
        $posts['hots'] = Posts::getLaetstOrHots('hots');

        $blade = $request->input('blade', '');
        return view('frontend.index', compact(
            'blade',
            'categorys',
            'recomments',
            'posts'
        ));
    }
}
