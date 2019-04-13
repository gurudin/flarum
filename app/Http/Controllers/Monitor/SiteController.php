<?php

namespace App\Http\Controllers\Monitor;

use Illuminate\Http\Request;
use App\Models\Monitor;
use Illuminate\Support\Facades\DB;

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
        $blocks = config('common.block');

        return view('monitor.index', compact(
            'blocks'
        ));
    }

    /**
     * Show the application dashboard.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function detail(Request $request, string $token)
    {
        $blocks = config('common.block');
        $currentBlock = [];
        foreach ($blocks as $key => $value) {
            if ($value['addr'] == $token) {
                $value['title'] = $key . ' (' . strtoupper($value['short']) . ')';
                $value['name'] = $key;
                $currentBlock = $value;
            }
        }

        return view('monitor.detail', compact(
            'blocks',
            'currentBlock'
        ));
    }

    public function getBaseData(Request $request)
    {
        sleep(1);
        $resList = Monitor::where([
            ['token_name', '=', $request->post('block')],
            ['created_at', '>=', $request->post('form') . ' 00:00:00'],
            ['created_at', '<=', $request->post('to') . ' 23:59:59']
        ])->get();

        $list = [];
        foreach ($resList as $item) {
            $list[] = [
                'id' => $item['id'],
                'market_cap' => $item['market_cap'],
                'price_usd'  => $item['price_usd'],
                'price_eth'  => $item['price_eth'],
                'applies'    => $item['applies'],
                'addresses'  => $item['addresses'],
                'volume'     => $item['volume'],
                'date'       => date('m/d', strtotime($item['created_at'])),
                'created_at' => date('Y-m-d', strtotime($item['created_at'])),
            ];
        }

        return ['status' => true, 'data' => $list];
    }
}
