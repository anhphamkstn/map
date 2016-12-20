<?php
/**
 * Created by PhpStorm.
 * User: JonnyNguyen
 * Date: 18/07/2016
 * Time: 09:27
 */

namespace App\Helpers;
use App\UploadFile;

class FileManager
{
    //article image
    const RS_CROP = '90x90';
    const RS_SMALL = '300x300';
    const RS_MEDIUM = '600x600';
    const RS_LARGE = '900x900';

    static $sizes = [
        'crop'   => self::RS_CROP,
        'small'  => self::RS_SMALL,
        'medium' => self::RS_MEDIUM,
        'large'  => self::RS_LARGE,
    ];

    public static function upload($file, $sub)
    {
        if (!$guard = new UploadGuard($file, $sub))
        {
            return $guard->getResponse();
        }

        switch ($sub)
        {
            case UploadGuard::SUB_CONTACT:
            case UploadGuard::SUB_PRODUCT:
            case UploadGuard::SUB_AVATAR: {
                $guard->executeImage(self::$sizes);
                break;
            }
            case UploadGuard::SUB_ROUTE:
            case UploadGuard::SUB_TRACK:
            case UploadGuard::SUB_POI: {
                $guard->executeGpx();
                break;
            }
            case UploadGuard::SUB_LEAD: {
                $guard->executeCsv();
                break;
            }
        }

        return $guard;
    }

    public static function getThumb(UploadFile $file, $needed = 'small')
    {
        foreach (self::$sizes as $key => $size)
        {
            if ($needed === $key)
                $thumbSize = $size;
        }

        if (strpos($file->path, '.'))
        {
            $temp = explode('.', $file->path);
            $thumb = $temp[0] . '_' . $thumbSize . '.' . $temp[1];
        }

        return $thumb;
    }

    public static function uploadShippingProduct($file)
    {
        return self::upload($file, 'shipping');
    }

}