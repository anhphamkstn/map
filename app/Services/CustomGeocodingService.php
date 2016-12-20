<?php namespace App\Services;

use App\CustomGeocoding;
use Illuminate\Support\Facades\Validator;
use Mockery\CountValidator\Exception;
use App\Helpers\StringHelper;
use App\Contact;
use App\Organization;

class CustomGeocodingService extends SharedService
{
    protected $fillable = ['tag_name', 'country', 'state', 'city', 'area', 'postal_code', 'building_name', 'lat', 'lon'];

    protected $searchFields = ['tag_name', 'country', 'state', 'city', 'area', 'postal_code', 'building_name'];
    protected $filterFields = ['country', 'postal_code'];
    protected $noSqlFields = [];

    public function getOpportuneListingQueryBuilder($opportuneFilter)
    {
        $query = CustomGeocoding::query();

        if (isset($opportuneFilter['search']) && trim($opportuneFilter['search'])) {
            $keyword = $opportuneFilter['search'];

            $query->where(function ($query) use ($keyword) {
                foreach ($this->searchFields as $key => $searchField) {
                    $query->orWhere($searchField, 'ilike', "%{$keyword}%");
                }
            });
        }

        foreach ($this->filterFields as $filterField) {
            $filterParamName = StringHelper::pascalCaseToCamelCase($filterField);

            if (isset($opportuneFilter[$filterParamName])) {
                $filterValue = $opportuneFilter[$filterParamName];
                $query->where($filterField, '=', $filterValue);
            }
        }

        $opportuneFilter['sortBy'] = isset($opportuneFilter['sortBy']) ? $opportuneFilter['sortBy'] : 'id';
        $opportuneFilter['orderDirection'] = isset($opportuneFilter['orderDirection']) ? $opportuneFilter['orderDirection'] : 'asc';

        $query->orderBy($opportuneFilter['sortBy'], $opportuneFilter['orderDirection']);

        return $query;
    }

    public function getOpportunitiesFromQueryBuilder($query)
    {
        $data = [];

        $records = $query->get();

        if (!$records->isEmpty()) {
            foreach ($records as $opportune) {
                $data[] = $this->transform($opportune);
            }
        }

        return $data;
    }

    public function findResource($id)
    {
        return CustomGeocoding::find($id);
    }

    public function insert($info)
    {
        $info = $this->reverse($info);

        foreach ($this->noSqlFields as $noSqlField)
            if (isset ($info[$noSqlField])) $info[$noSqlField] = json_encode($info[$noSqlField]);

        $newRecord = CustomGeocoding::create($info);

        return $this->transform($newRecord);
    }

    public function update(CustomGeocoding $instance, $info)
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
        if ($record instanceof CustomGeocoding) {

            return [
                'id' => $record->id,
                'tag_name' => $record->tag_name,
                'country' => $record->country,
                'state' => $record->state,
                'city' => $record->city,
                'area' => $record->area,
                'postal_code' => $record->postal_code,
                'building_name' => $record->building_name,
                'lat' => doubleval($record->lat),
                'lon' => doubleval($record->lon)
            ];
        }
    }

    public function delete($CustId)
    {
        return CustomGeocoding::destroy($CustId);
    }
}