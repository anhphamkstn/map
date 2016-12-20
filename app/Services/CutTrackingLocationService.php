<?php namespace App\Services;

use App\CutTrackingLocation;
use Illuminate\Support\Facades\Validator;
use Mockery\CountValidator\Exception;
use App\Helpers\StringHelper;
use App\Contact;
use App\Organization;

class CutTrackingLocationService extends SharedService
{
    protected $fillable = ['user_id','content'];

    protected $searchFields = [];
    protected $filterFields = [];
    protected $noSqlFields = [];

    public function getCutTrackingListingQueryBuilder($UserTrackingFilter)
    {
        $query = CutTrackingLocation::query();

        if (isset($UserTrackingFilter['search']) && trim($UserTrackingFilter['search'])) {
            $keyword = $UserTrackingFilter['search'];

            $query->where(function ($query) use ($keyword) {
                foreach ($this->searchFields as $key => $searchField) {
                    $query->orWhere($searchField, 'ilike', "%{$keyword}%");
                }
            });
        }

        foreach ($this->filterFields as $filterField) {
            $filterParamName = StringHelper::pascalCaseToCamelCase($filterField);

            if (isset($UserTrackingFilter[$filterParamName])) {
                $filterValue = $UserTrackingFilter[$filterParamName];
                $query->where($filterField, '=', $filterValue);
            }
        }

        $UserTrackingFilter['sortBy'] = isset($UserTrackingFilter['sortBy']) ? $UserTrackingFilter['sortBy'] : 'id';
        $UserTrackingFilter['orderDirection'] = isset($UserTrackingFilter['orderDirection']) ? $UserTrackingFilter['orderDirection'] : 'asc';

        $query->orderBy($UserTrackingFilter['sortBy'], $UserTrackingFilter['orderDirection']);

        return $query;
    }

    public function getCutTrackingFromQueryBuilder($query)
    {
        $data = [];

        $records = $query->get();

        if (!$records->isEmpty()) {
            foreach ($records as $userTracking) {
                $data[] = $this->transform($userTracking);
            }
        }

        return $data;
    }

    public function findResource($id)
    {
        return CutTrackingLocation::find($id);
    }

    public function insert($info)
    {
        $info = $this->reverse($info);

        foreach ($this->noSqlFields as $noSqlField)
            if (isset ($info[$noSqlField])) $info[$noSqlField] = json_encode($info[$noSqlField]);

        $newRecord = CutTrackingLocation::create($info);

        return $this->transform($newRecord);
    }

    public function update(CutTrackingLocation $instance, $info)
    {
        $info = $this->reverse($info);

        foreach ($this->noSqlFields as $noSqlField)
            if (isset ($info[$noSqlField])) $info[$noSqlField] = json_encode($info[$noSqlField]);

        foreach ($this->fillable as $attr) {
            if (isset($info[$attr])) {
                $instance->$attr = $info[$attr];
            }
        }
        $instance->save();

        return $this->transform($instance);
    }

    public function validateInfo($info, $action = 'insert', $id = 0)
    {
        $rules = [];
        $messages = [
        ];

        if ($action === 'insert') {
            $rules = [
            ];
        } elseif ($action === 'update' && $id > 0) {
            $rules = [
            ];
        }
        $validator = Validator::make($info, $rules, $messages);
        return $validator;
    }

    public function reverse($info)
    {
        return $info;
    }

    public function transform($record)
    {
        if ($record instanceof CutTrackingLocation) {
            return [
                'id' => $record->id,
                'user_id' => $record->user_id,
                'content' => $record->content
            ];
        }
    }
}