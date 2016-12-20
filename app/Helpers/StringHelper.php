<?php
/**
 * Created by PhpStorm.
 * User: JonnyNguyen
 * Date: 18/07/2016
 * Time: 09:33
 */

namespace App\Helpers;


class StringHelper
{
    public static function pascalCaseToCamelCase($string, $capitalizeFirstCharacter = false)
    {
        $str = str_replace('_', '', ucwords($string, '_'));

        if (!$capitalizeFirstCharacter) {
            $str[0] = strtolower($str[0]);
        }

        return $str;
    }
}