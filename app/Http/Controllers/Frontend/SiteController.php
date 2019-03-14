<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $blade = $request->input('blade', '');

        return view('frontend.index', compact(
            'blade'
        ));
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
