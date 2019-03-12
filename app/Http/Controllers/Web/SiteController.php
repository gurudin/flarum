<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

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
        $file = $request->file('file');
        $realPath = $file->getRealPath();
        $extension = $file->getClientOriginalExtension();
        $targetPath = sprintf(
            '/public/images/%s/%s',
            date('Y'),
            date('m')
        );
        $fileName = time() . random_int(1000, 9999) . '.' . $extension;

        $path =  $request->file('file')->storeAs(
            $targetPath,
            $fileName
        );

        if ($path) {
            return ['status' => true, 'path' => str_replace('public', 'storage', $targetPath) . '/' . $fileName];
        } else {
            return ['status' => false, 'msg' => 'upload error.'];
        }
    }
}
