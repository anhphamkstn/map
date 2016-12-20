<?php namespace App\Services;

use App\Helpers\FileManager;
use App\Helpers\UploadGuard;
use App\UploadFile;
use App\User;
use Carbon\Carbon;
use DateTime;
use Illuminate\Database\Eloquent\Model;

class BaseService
{

    public function transformDate($timestamp)
    {
        return $timestamp instanceof Carbon ? $timestamp->format(DateTime::W3C) : $timestamp;
    }

    public function transformArticle($instance, $attribute)
    {
        return $this->transformImage($instance, $attribute, UploadGuard::SUB_ARTICLE);
    }

    public function transformAvatar($instance, $attribute)
    {
        return $this->transformImage($instance, $attribute, UploadGuard::SUB_AVATAR);
    }

    public function transformImage(Model $instance, $attribute, $sub)
    {
        return [
            'origin' => $this->fullUrl($instance->$attribute, $sub),
            'crop' => $this->fullUrl(FileManager::getThumb($instance, 'crop'), $sub),
            'small' => $this->fullUrl(FileManager::getThumb($instance, 'small'), $sub),
            'medium' => $this->fullUrl(FileManager::getThumb($instance, 'medium'), $sub),
            'large' => $this->fullUrl(FileManager::getThumb($instance, 'large'), $sub),
        ];
    }

    public function transformPoi($path)
    {
        return $this->transformUploadedGpx($path, UploadGuard::SUB_POI);
    }

    public function transformRoute($path)
    {
        return $this->transformUploadedGpx($path, UploadGuard::SUB_ROUTE);
    }

    public function transformTrack($path)
    {
        return $this->transformUploadedGpx($path, UploadGuard::SUB_TRACK);
    }

    public function transformUploadedGpx($path, $sub)
    {
        return [
            'origin' => $this->fullUrl($path, $sub)
        ];
    }

    public function transformStaticIcon($path, $fullUrl = true)
    {
        return [
            'origin' => $fullUrl ? $this->fullUrl($path, 'icon') : $path,
        ];
    }

    public function transformUser(User $user, $fields = [])
    {
        $fullTransformedUser = [
            'id' => $user->id,
            'name' => $user->name,
            'role' => $user->role_id,
            'tel' => $user->tel,
            'email' => $user->email,
        ];

        if (empty($fields))
            return $fullTransformedUser;
        else {
            $transformedUser = [
                'id' => $user->id
            ];

            foreach ($fields as $field) {
                if(array_key_exists($field, $fullTransformedUser))
                    $transformedUser[$field] = $user->$field;
            }
            
            return $transformedUser;
        }
    }

    public function transformGpx(UploadFile $file)
    {
        return [
            'id' => $file->id,
            'path' => $this->fullUrl($file->path, 'route'),
            'type' => 'gpx',
        ];
    }

    protected function fullUrl($path, $sub)
    {
        $baseUrl = config('app.file_url');
        switch ($sub) {
            case 'avatar': {
                $baseUrl .= 'avatars/';
                break;
            }
            case 'article': {
                $baseUrl .= 'articles/';
                break;
            }
            case 'route': {
                $baseUrl .= 'routes/';
                break;
            }
            case 'poi': {
                $baseUrl .= 'pois/';
                break;
            }
            case 'icon': {
                $baseUrl .= 'icons/';
                break;
            }
        }

        return $baseUrl . $path;
    }

    public function decodeParams($input)
    {
        return json_decode(base64_decode($input), true);
    }

    public function transformCsvLeads($csvInput)
    {
        $csvLeadFields = ['lead_source', 'lead_source_description', 'status', 'status_description', 'reference_source_by', 'position', 'account_name', 'title', 'department', 'website', 'office_phone', 'mobile_phone', 'home_phone', 'other_phone', 'fax_number', 'email', 'other_email', 'do_not_call', 'sms_opt_in', 'email_opt_out', 'invoice_email'];
        $csvNameFields = ['prefix', 'first_name', 'last_name'];
        $csvAddressFields = ['zip_code', 'district', 'state_province', 'country', 'street'];

        $responseLeads = [];
        $leadRecords = [];

        $leadLines = explode(PHP_EOL, $csvInput);

        if (count($leadLines) > 1) {
            $line = $leadLines[0];
            $headers = str_getcsv($line);
        } else
            return $leadRecords;

        for ($i = 1; $i < count($leadLines); $i++) {
            $line = $leadLines[$i];
            $leadRecordArray = str_getcsv($line);
            $leadRecord = [];

            for ($j = 0; $j < count($headers); $j++) {
                if (!isset($leadRecordArray[$j]))
                    continue;

                $leadRecord[$headers[$j]] = $leadRecordArray[$j];
            }

            $leadRecords[] = $leadRecord;
        }

        foreach ($leadRecords as $leadRecord) {
            $responseLead = [];

            foreach ($csvLeadFields as $csvLeadField) {
                if (isset($leadRecord[$csvLeadField]))
                    $responseLead[$csvLeadField] = $leadRecord[$csvLeadField];
            }

            foreach ($csvNameFields as $csvNameField) {
                if (isset($leadRecord['name_' . $csvNameField])) {
                    $responseLead['name'][$csvNameField] = $leadRecord['name_' . $csvNameField];
                }
            }

            foreach ($csvAddressFields as $csvAddressField) {
                if (isset($leadRecord[$csvAddressField])) {
                    $responseLead['address'][0][$csvAddressField] = $leadRecord[$csvAddressField];
                }

                if (isset($leadRecord[$csvAddressField . '2'])) {
                    $responseLead['address'][1][$csvAddressField] = $leadRecord[$csvAddressField . '2'];
                }
            }

            if (!empty($responseLead))
                $responseLeads[] = $responseLead;
        }

        return $responseLeads;
    }
}