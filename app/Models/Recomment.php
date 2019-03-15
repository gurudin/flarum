<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Posts;
use App\Models\Category;

class Recomment extends Model
{
    protected $table = 'recomment';
    public $timestamps = true;

    /** Filed the `type` options. */
    const TYPE_OPTIONS = [
        'posts'    => '帖子',
        'category' => '类别',
        'url'      => '链接'
    ];

    /** Filed the `position` options. */
    const POSITIONS = [
        '1' => '首页左部幻灯片',
        '2' => '首页中部'
    ];

    protected static function attributeLabels()
    {
        return [
            'recomment_id' => '',
            'url' => '',
            'type' => 'posts',
            'position' => '',
            'cover' => '',
            'status' => 0,
            'remark' => '',
        ];
    }

    /**
     * Frontend
     * Get recomment
     */
    protected static function getRecomment()
    {
        $recomments = static::where(['status' => 1])
            ->select([
                'recomment_id',
                'url',
                'type',
                'position',
                'cover',
                'remark'
            ])->orderBy('id', 'desc')->get()->toArray();
        
        $post_ids = array_column(array_filter($recomments, function ($row) {
            if ($row['type'] == 'posts') {
                return $row;
            }
        }), 'recomment_id');
        $category_ids = array_column(array_filter($recomments, function ($row) {
            if ($row['type'] == 'category') {
                return $row;
            }
        }), 'recomment_id');

        $posts = Posts::select(['id as post_id', 'title', 'cover'])->whereIn('id', $post_ids)->get()->toArray();
        $categorys = Category::select(['id as cid', 'category', 'alias', 'remark'])->whereIn('id', $category_ids)->get()->toArray();
        foreach ($recomments as &$recomment) {
            foreach ($posts as $post) {
                if ($recomment['type'] == 'posts' && $recomment['recomment_id'] == $post['post_id']) {
                    $recomment['title'] = $post['title'];
                    $recomment['cover'] = empty($recomment[ 'cover']) ? $post['cover'] : $recomment['cover'];
                }
            }

            foreach ($categorys as $category) {
                if ($recomment['type'] == 'category' && $recomment['recomment_id'] == $category['cid']) {
                    $recomment['title'] = $category['category'];
                    $recomment['alias'] = $category['alias'];
                }
            }
        }
        unset($recomment);

        return $recomments;
    }
}
