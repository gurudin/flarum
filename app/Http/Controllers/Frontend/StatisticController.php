<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Models\Statistic;

class StatisticController
{
    /**
     * Show the application dashboard.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function hm(Request $request)
    {
        try {
            $m = new Statistic;
            $m->url = urldecode($request->get('url'));
            $m->referrer = urldecode($request->get('referrer', null));
            $m->useragent = urldecode($request->get('useragent', null));
            $m->platform = urldecode($request->get('platform', null));
            $m->language = urldecode($request->get('language', null));
            $m->width = $request->get('width', null);
            $m->height = $request->get('height', null);
            $m->ip = $request->getClientIp();
            $m->created_at = date('Y-m-d H:i:s');
            $m->save();
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
