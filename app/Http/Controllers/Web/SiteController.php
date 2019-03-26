<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Upload;
use App\Models\Statistic;

class SiteController extends \App\Http\Controllers\Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->isMethod('get')) {
            return view('admin.site');
        }

        if ($request->post('action') == 'charts') {
            $hours = [];
            if ($request->post('date') == 'today') {
                $date = date('Ymd');
            } else {
                $date = date('Ymd', time() - 24 * 3600);
            }
            for ($i=0; $i<24; $i++) {
                $hour = $i < 10 ? '0' . $i : $i;
                $hours[$date . $hour] = ['cnt' => 0, 'date' => $hour];
            }
            // return $hours;
            $query = Statistic::select(DB::raw("DATE_FORMAT(created_at,'%Y%m%d%H') hours, count(*) as cnt"))
                ->groupBy('hours');
            if ($request->post('date') == 'today') {
                $query = $query->where([
                    ['created_at', '>=', date('Y-m-d 00:00:00')],
                    ['created_at', '<=', date('Y-m-d 23:59:59')],
                ]);
            } else {
                $query = $query->where([
                    ['created_at', '>=', date('Y-m-d 00:00:00', time() - 24 * 3600)],
                    ['created_at', '<=', date('Y-m-d 23:59:59', time() - 24 * 3600)],
                ]);
            }
            $pv = $query->get();
            
            foreach ($pv as $val) {
                $hours[$val['hours']]['cnt'] = $val['cnt'];
            }

            return ['status' => true, 'data' => $hours];
        }
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
