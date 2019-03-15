<?php
namespace App\Utils;

class Common
{
    public function randColor()
    {
        $colors = ['#FF9966', '#FF6666', '#996699', '#0099CC', '#99CC66', '#CC9999', '#CCCC99', '#999933', '#99CCCC', '#CC9999', '#CC9966', '#99CC00'];
        
        return $colors[rand(0, 11)];
    }
}
