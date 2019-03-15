<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Upload;

class SiteController extends \App\Http\Controllers\Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.site');
    }

    /**
     * Upload file.
     *
     * @return \Illuminate\Http\Response
     */
    public function upload(Request $request)
    {
        if ($request->file('file')) {
            return (new Upload)->upload($request);
        } else {
            $result =  (new Upload)->upload($request);
            return ['uploaded' => true, 'url' => $result['path']];
        }
    }
    

    public function generate(Request $request)
    {
        return (new Upload)->generate($request);
    }
}
