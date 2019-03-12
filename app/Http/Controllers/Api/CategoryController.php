<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends BaseController
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
        $categorys = Category::select(['id', 'category', 'alias', 'color'])
            ->where(['enabled' => 1])
            ->orderBy('weight', 'desc')
            ->get();

        return ['status' => true, 'data' => $categorys];
    }
}
