<?php

namespace App\Helpers;

use GuzzleHttp\Client;

class CategoriesHelper
{
    public static function output($items)
    {
        if ($items != "[]") {
            return json_decode($items,true);
        }else return 0;
     
    }
}
