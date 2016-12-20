<?php namespace App\Services;

use App\Helpers\Response;
use Illuminate\Support\Facades\Validator;
use Mockery\CountValidator\Exception;
use App\TrackingLocation;
use App\User;
use App\Task;


class TrackingLocationService extends SharedService
{
    protected $fillable = ['user_id', 'lat', 'lon', 'note','source','bearing'];

    public function findResource($id)
    {
        return TrackingLocation::find($id);
    }

    public function getUserTrackingLocations($userId)
    {
        return User::find($userId)->trackingLocations()->get();
    }

    public function getSalesmansFromBounds($bounds)
    {
        $query = TrackingLocation::query();
        $query->where('created_at', '>=', new \DateTime('-3 days'));
        $query->whereBetween('lat', array($bounds['southwest']['lat'], $bounds['northeast']['lat']));
        $query->whereBetween('lon', array($bounds['southwest']['lon'], $bounds['northeast']['lon']));

        $query->orderBy('created_at', 'desc');

        return $query->get();
    }

    public function getUserTrackingHistory($userId, $startDate, $endDate)
    {
        $query = TrackingLocation::query();
        $query->where('user_id', '=', $userId);
        $query->whereBetween('created_at', array(date('Y-m-d H:i:s', $startDate), date('Y-m-d H:i:s', $endDate)));

        return $query->get();
    }

    public function insert($info)
    {
        $trackingLocation = TrackingLocation::create($info);

        return $this->transform($trackingLocation);
    }

    public function validateInfo($info, $action = 'insert', $id = 0)
    {
        $messages = [
            'lat.required' => "The latitude is required.",
            'lon.required' => "The longitude is required.",
            'user_id.required' => "A corresponding user is required.",            
        ];

        if ($action === 'insert') {
            $rules = [
                'lat' => 'required',
                'lon' => 'required',
                'user_id' => 'required'
            ];
        } elseif ($action === 'update' && $id > 0) {
            $rules = [
            ];
        }
        $validator = Validator::make($info, $rules, $messages);
        return $validator;
    }

    public function transformTrackedUser($info)
    {
        $user = User::find($info['user_id']);
        $task = Task::query()->where('assigned_to', '=', 1)->first();

        return [
            'id' => $user['id'],
            'name' => $user['name'],
            'status' => 'Logged in',
            'current_location' => [
                'lat' => $info['lat'],
                'lon' => $info['lon']
            ],
            'current_task' => $task,
            'attendance' => 'N/A'
        ];
    }

    public function transform($trackingLocation)
    {
        if ($trackingLocation instanceof TrackingLocation) {
            return [
                'id' => $trackingLocation->id,
                'lat' => $trackingLocation->lat,
                'lon' => $trackingLocation->lon,
                'note' => $trackingLocation->note,
                'source' => $trackingLocation->source,
                'bearing'=>$trackingLocation->bearing,
                'created_at' => $trackingLocation->created_at->format('Y-m-d H:i:s')
            ];
        }
    }
}