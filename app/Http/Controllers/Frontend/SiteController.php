<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
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
        $categorys = Category::getAll($request->get('alias'));

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
