<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use QL\QueryList;
use App\Models\Monitor;
use App\Models\MonitorDetail;

class MonitorToken extends Command
{
    private $urls = [
        'base' => 'https://etherscan.io',
        'info' => '/token/%s',
        'holders' => '/token/generic-tokenholders2?a=%s',
    ];

    private $tokens = [
        'Mithril' => [
            'addr'  => '0x3893b9422cd5d70a81edeffe3d5a1c6a978310bb',
            'total' => '1000000000',
            'short' => 'mith',
        ],
        'Measurable Data Token' => [
            'addr'  => '0x814e0908b12a99fecf5bc101bb5d0b8b5cdf7d26',
            'total' => '1000000000',
            'short' => 'mdt',
        ],
    ];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:monitor';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    private function getUrl(string $token)
    {
        return [
            'base' => $this->urls['base'] . str_replace('%s', $token, $this->urls['info']),
            'holders' => $this->urls['base'] . str_replace('%s', $token, $this->urls['holders']),
        ];
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        header("Content-type: text/html; charset=utf-8");

        foreach ($this->tokens as $key => $token) {
            $monitor_id = $this->collect($this->getUrl($token['addr']), $key);
            $this->collectDetail($this->getUrl($token['addr']), $key, $monitor_id);
        }
    }

    private function collectDetail(array $urls, string $token_name, int $monitor_id)
    {
        $ql = QueryList::get($urls['holders']);

        $table = $ql->find('table tbody');
        $tablerow = $table->find('tr')->map(function ($row) {
            $tds = $row->find('td')->texts()->all();
            return [
                'rank'     => $tds[0],
                'address'  => explode(' ', $tds[1])[0],
                'belong'   => isset(explode(' ', $tds[1])[1]) ? str_replace(')', '', str_replace('(', '', explode(' ', $tds[1])[1])) : '',
                'quantity' => str_replace(',', '', $tds[2])
            ];
        });

        foreach ($tablerow as $row) {
            $m = new MonitorDetail;
            $m->fk_monitor_id = $monitor_id;
            $m->rank = $row['rank'];
            $m->address = $row['address'];
            $m->belong = $row['belong'];
            $m->quantity = round($row['quantity'], 2);
            $m->percentage = round($row['quantity'] / $this->tokens[$token_name]['total'] * 100, 4);

            $m->save();
        }
    }

    private function collect(array $urls, string $token_name)
    {
        $m['token_name'] = $token_name;
        $m['transfers'] = 0;

        $ql = QueryList::get($urls['base']);
        $remark = $ql->find('div#ContentPlaceHolder1_tr_valuepertoken span.d-block')->html();
        $arr = explode("<span", $remark);
        $m['price_usd'] = str_replace('$', '', $arr[0]);
        
        $str = $ql->find('div#ContentPlaceHolder1_tr_valuepertoken span.d-block span.small')->html();
        $arr = explode(" ", $str);
        $m['price_eth'] = $arr[1];

        $arr = explode("(", $arr[2]);
        $m['applies'] = str_replace('%)', '', $arr[1]);

        $str = $ql->find('div.mx-gutters-md-1 div.col-6 span.d-block')->html();
        $arr = explode('$', $str);
        $m['market_cap'] = str_replace(',', '', $arr[2]);

        $str = $ql->find('div#ContentPlaceHolder1_tr_tokenHolders div.col-md-8')->html();
        $m['addresses'] = str_replace(',', '', explode(" ", $str)[0]);

        $table = $ql->find('div#ContentPlaceHolder1_maintab div#tokenInfo')->find('table');
        $tablerow = $table->find('tbody')->map(function ($row) {
            $tmp = [];
            $tmp_tab = $row->find('td')->texts()->all();
            if (in_array('Volume (24H)', $tmp_tab)) {
                foreach ($tmp_tab as $inx => $val) {
                    switch ($val) {
                        case 'Volume (24H)':
                            $tmp['volume'] = str_replace(',', '', str_replace('$', '', $tmp_tab[$inx + 2]));
                            break;
                        case 'Market Capitalization':
                            $tmp['market_capitalization'] = str_replace(',', '', str_replace('$', '', $tmp_tab[$inx + 2]));
                            break;
                        case 'Circulating Supply':
                            $tmp['supply'] = str_replace(',', '', explode(' ', $tmp_tab[$inx + 2])[0]);
                            break;
                        default:
                            # code...
                            break;
                    }
                }

                return $tmp;
            }

            return false;
        });
        $info = current(array_filter($tablerow->all()));
        $m['volume'] = isset($info['volume']) ? $info['volume'] : 0;
        $m['market_capitalization'] = isset($info['market_capitalization']) ? $info['market_capitalization'] : 0;
        $m['supply'] = isset($info['supply']) ? $info['supply'] : 0;

        return Monitor::insertGetId($m);
    }
}
