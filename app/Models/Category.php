<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'category';
    public $timestamps = true;

    protected static function attributeLabels()
    {
        return [
            'parent_id' => 0,
            'category'  => '',
            'weight'    => 0,
            'alias'     => '',
            'color'     => '',
            'pic'       => '',
            'remark'    => '',
            'enabled'   => 0
        ];
    }

    protected static function getCategory(array $where = [])
    {
        return static::where($where)->orderBy('id', 'desc')->paginate(config('admin.pageSize'));
    }

    /**
     * Frontend
     *
     * @param string $alias
     *
     * @return array
     */
    protected static function getAll($alias = '')
    {
        $categorys = static::where(['enabled' => 1])->orderBy('weight', 'desc')->orderBy('id', 'desc')->get()->toArray();

        $parents = [];
        foreach ($categorys as $category) {
            if ($category['parent_id'] == 0) {
                $category['children'] = [];
                $parents[] = $category;
            }
        }
        unset($category);

        if ($alias != '') {
            foreach ($parents as $k => $parent) {
                if ($alias != $parent['alias']) {
                    unset($parents[$k]);
                }
            }
            unset($parent);
        }

        foreach ($parents as &$parent) {
            foreach ($categorys as $category) {
                if ($parent['id'] == $category['parent_id']) {
                    $parent['children'][] = $category;
                }
            }
        }
        unset($parent);
        unset($category);

        return $parents;
    }
}
