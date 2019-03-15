<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Models\Category;

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

        $blade = $request->input('blade', '');
        return view('frontend.index', compact(
            'blade',
            'categorys'
        ));
    }
}
