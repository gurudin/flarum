<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use QL\QueryList;
use App\Models\Posts;

class QueryImg extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:query';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Query images';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        header("Content-type: text/html; charset=utf-8");
        $posts = [];

        $url = 'https://www.2717.com/ent/meinvtupian/';

        $items = QueryList::get($url)->rules([
            'href'  => ['div.MeinvTuPianBox ul li a.MMPic', 'href'],
            'title' => ['div.MeinvTuPianBox ul li a.MMPic', 'title'],
            'cover' => ['div.MeinvTuPianBox ul li a.MMPic img', 'src'],
        ])->query()->getData();
        
        foreach ($items as $item) {
            $ql = QueryList::get('https://www.2717.com' . $item['href']);

            $reamrk = $ql->find('.articleV4Desc p')->html();
            $content = '<p>' . mb_convert_encoding($reamrk, "UTF-8", "GBK") . '</p>';

            $total_page = mb_convert_encoding($ql->find('.articleV4Page li#pageinfo')->attr('pageinfo'), "UTF-8", "GBK");
            $source = mb_convert_encoding($ql->find('.articleV4Body p a img')->attr('src'), "UTF-8", "GBK");
            $imgs = [];

            if ($total_page > 1) {
                $ext = substr(strrchr($source, '.'), 1);
                for ($i=2; $i <= $total_page; $i++) {
                    $tmp_source = str_replace('1.' . $ext, $i . '.' .$ext, $source);
                    $imgs[] = $tmp_source;

                    $content .= '<p><img src="' . $tmp_source . '"></p>';
                }
            }

            $posts[] = [
                'fk_category_id' => 2,
                'fk_user_id' => rand(1, 3),
                'tags' => '',
                'pv' => rand(20, 500),
                'title' => $item['title'],
                'cover' => $item['cover'],
                'content' => $content,
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
        }

        Posts::insert($posts);

        $this->info('Success');
    }
}
