<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;

class SiteController
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('frontend.index');
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
