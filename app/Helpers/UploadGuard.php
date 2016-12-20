<?php
/**
 * Created by PhpStorm.
 * User: JonnyNguyen
 * Date: 18/07/2016
 * Time: 09:19
 */

namespace App\Helpers;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class UploadGuard
{
    const SUB_ARTICLE = 'article';
    const SUB_AVATAR = 'avatar';
    const SUB_POI = 'poi';
    const SUB_ROUTE = 'route';
    const SUB_TRACK = 'track';
    const SUB_LEAD = 'lead';
    const SUB_PRODUCT = 'product';
    const SUB_CONTACT = 'contact';

    protected $mime;
    protected $ext;
    protected $subs = [
        self::SUB_ARTICLE,
        self::SUB_AVATAR,
        self::SUB_POI,
        self::SUB_ROUTE,
        self::SUB_TRACK,
        self::SUB_LEAD,
        self::SUB_PRODUCT,
        self::SUB_CONTACT
    ];
    protected $allowExt = [
        'image' => ['png', 'jpg', 'jpeg', 'gif', 'bmp'],
        'route' => ['gpx'],
        'track' => ['gpx'],
        'poi'   => ['gpx'],
        'lead' => ['csv', 'txt'],
    ];
    protected $response;
    protected $author;
    protected $originName;
    protected $newName;
    protected $file;
    protected $realPath;
    protected $savePath;
    protected $sub;

    public function __construct(UploadedFile $file, $sub)
    {
        //check file extension
        $this->ext = $file->getClientOriginalExtension();
        if (!in_array($this->ext, ArrayHelper::flatten($this->allowExt)))
        {
            $this->response = Response::responseFileTypeNotAllowed();

            return false;
        }

        //check subject
        $this->sub = $sub;
        if (!in_array($this->sub, $this->subs))
        {
            $this->response = Response::responseNotSupportedType();

            return false;
        }

        $this->mime = $file->getClientMimeType();
        $this->author = UserHelper::getAuthenticatedUser();
        $this->originName = $file->getClientOriginalName();
        $this->file = $file;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function getExt()
    {
        return $this->ext;
    }

    public function getSub()
    {
        return $this->sub;
    }

    public function getMime()
    {
        return $this->mime;
    }

    public function getOriginName()
    {
        return $this->originName;
    }

    public function getAuthor()
    {
        return $this->author;
    }

    public function executeImage($thumbSizes = [])
    {
        $this->getContainerFolder();

        //get and create container folder if needed
        if (!is_dir($this->realPath))
        {
            File::makeDirectory($this->realPath, 0775, true);
        }

        //full path
        $this->newName = $this->generateFileName();

        //Image::make($this->file->getRealPath())->save($this->realPath . '/' . "{$this->newName}.{$this->getExt()}");
        $this->file->move($this->realPath . '/', "{$this->newName}.{$this->getExt()}");

        //create and save thumbnails
        if (!empty($thumbSizes) && false)
        {
            foreach ($thumbSizes as $key => $size)
            {
                if ($key === 'crop')
                {
                    self::createThumb($size, true);
                } else
                {
                    self::createThumb($size);
                }
            }
        }

        return $this->getDbPath();
    }

    public function executeGpx()
    {
        //get root folder path
        $this->getContainerFolder($this->getAuthor()->id, 'gpx');

        $this->newName = $this->generateFileName();

        //get and create container folder if needed
        if (!is_dir($this->realPath))
        {
            File::makeDirectory($this->realPath, 0775, true);
        }

        $this->file->move($this->realPath . '/', "{$this->newName}.{$this->getExt()}");

        return $this->getDbPath();
    }

    public function executeCsv()
    {
        //get root folder path
        $this->getContainerFolder();

        $this->newName = $this->generateFileName();

        //get and create container folder if needed
        if (!is_dir($this->realPath))
        {
            File::makeDirectory($this->realPath, 0775, true);
        }

        $this->file->move($this->realPath . '/', "{$this->newName}.{$this->getExt()}");

        return $this->getDbPath();
    }

    public function getDbPath()
    {
        return $this->savePath . "{$this->newName}.{$this->getExt()}";
    }

    protected function createThumb($size = FileManager::RS_SMALL, $crop = false)
    {
        $sizeThumb = explode('x', $size);

        //crop or not
        if (!$crop)
        {
            $file = $this->iResize($this->file, $sizeThumb[0], $sizeThumb[1]);
        } else
        {
            $file = $this->ImageCrop($this->file, $sizeThumb[0], $sizeThumb[1]);
        }

        return $file->save($this->realPath . "/{$this->newName}_{$size}.{$this->getExt()}");
    }

    private function ImageCrop(UploadedFile $file, $newW, $newH)
    {
        list($width, $height) = getimagesize($file->getRealPath());

        //if old width is bigger than old height, and new width is smaller than new height
        if (($width > $height && $newW < $newH) || ($width < $height && $newW > $newH))
        {
            $temp = $newH;
            $newH = $newW;
            $newW = $temp;
        }

        //resize first
        $widthRatio = $width / $newW;
        $heightRatio = $height / $newH;

        if ($widthRatio > $heightRatio)
        {
            $resized = Image::make($file->getRealPath())->resize(null, $newW, function ($constraint)
            {
                $constraint->aspectRatio();
            });
        } else
        {
            $resized = Image::make($file->getRealPath())->resize($newH, null, function ($constraint)
            {
                $constraint->aspectRatio();
            });
        }

        return $resized->crop($newW, $newH);
    }

    private function iResize(UploadedFile $file, $newW, $newH)
    {
        list($width, $height) = getimagesize($file->getRealPath());

        //if old width is bigger than old height, and new width is smaller than new height
        if (($width > $height && $newW < $newH) || ($width < $height && $newW > $newH))
        {
            $temp = $newH;
            $newH = $newW;
            $newW = $temp;
        }

        //resize first
        $widthRatio = $width / $newW;
        $heightRatio = $height / $newH;

        if ($widthRatio > $heightRatio)
        {
            $resized = Image::make($file->getRealPath())->resize($newW, null, function ($constraint)
            {
                $constraint->aspectRatio();
            });
        } else
        {
            $resized = Image::make($file->getRealPath())->resize(null, $newH, function ($constraint)
            {
                $constraint->aspectRatio();
            });
        }

        return $resized;
    }

    protected function generateFileName()
    {
        $filename = str_replace('-', '_', str_replace(' ', '_', str_replace('.' . trim($this->getExt()), '', $this->getOriginName())));
        $filename .= '_' . rand(10000, 99999);

        return $filename;
    }

    protected function getContainerFolder()
    {
        $folder = $this->getFileFolder();
        $savePath = $this->getAuthor()->id . '/' . date('Y_m_d') . '/';
        $path = config('upload.upload_base_path') . $folder . '/' . $savePath;

        $this->realPath = $path;
        $this->savePath = $savePath;
    }

    protected function getFileFolder()
    {
        return $this->sub . 's';
    }

}