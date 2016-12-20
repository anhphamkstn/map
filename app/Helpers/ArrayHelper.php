<?php
/**
 * Created by PhpStorm.
 * User: JonnyNguyen
 * Date: 18/07/2016
 * Time: 09:33
 */

namespace App\Helpers;


class ArrayHelper
{
    public static function flatten(array $array)
    {
        $value_return = array ();
        array_walk_recursive($array, function ($a) use (&$value_return) { $value_return[] = $a; });

        return $value_return;
    }

}