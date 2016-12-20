<?php
/**
 * Created by PhpStorm.
 * User: btgiang
 * Date: 24/08/2016
 * Time: 11:11
 */

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\TeamSaleMembers;
use App\TeamSale;
use App\User;

class SaleTeamService extends SharedService
{
    protected $fillable = ['id', 'code', 'name', 'active', 'created_at', 'updated_at'];
    protected $fillMember = ['team_id', 'user_id', 'team_leader', 'created_at', 'updated_at'];
    protected $searchFields = ['code', 'name'];

    public function insert($info)
    {
        $newSaleTeam = TeamSale::create($info);
        //return $newSaleTeam;
        return $this->transformSaleTeam($newSaleTeam);
    }

    public function update(TeamSale $instance, $info)
    {
        foreach ($this->fillable as $attr) {
            if (isset($info[$attr])) {
                $instance->$attr = $info[$attr];
            }
        }
        $instance->save();
        return $instance;
    }

    public function setAddMemberBuilder($teamSaleInfo)
    {
        //xoa tat ca teamsale
        $dataReturn = [];
        foreach ($teamSaleInfo as $teamSale) {
            $teamSaleId = $teamSale['id'];
            $deleteAllMembers = TeamSaleMembers::where('team_id', '=', $teamSaleId)->delete();

            foreach ($teamSale['member'] as $members) {
                $isleader = $members['leader'];
                $user_id = $members['user']['id'];
                $dataReturn[] = DB::table('team_sale_members')->insertGetId([
                    'team_id' => $teamSaleId,
                    'team_leader' => $isleader,
                    'user_id' => $user_id,
                    'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
                    'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
                ]);
            }
        }
        $data=[];
        $teamSales = TeamSale::where ('id','=',$teamSaleId)->get();
        foreach($teamSales as $teamSale)
        {
            $data = $this->transform($teamSale);
        }
       // return $teamSales;

        //$rdata=$this-> transform($data) ;
        return $data;

    }

    public function getAllMemberUnasign()
    {
        $dataReturn = User::whereNotExists(function ($query) {
            $query->select(DB::raw(1))
                ->from('team_sale_members')
                ->whereRaw('team_sale_members.user_id = users.id');
        })->get();
        return $dataReturn;
    }

    public function findResource($id)
    {
        return TeamSale::find($id);
    }

    public function delete($id)
    {
        return TeamSale::destroy($id);
    }

    public function transformSaleTeam($saleTeam)
    {
        if ($saleTeam instanceof TeamSale) {
            return [
                'id' => $saleTeam->id,
                'code' => $saleTeam->code,
                'name' => $saleTeam->name,
                'active' => $saleTeam->active,
                'created_at' => $saleTeam->created_at,
                'updated_at' => $saleTeam->created_at
            ];
        }
    }

    public function getTeamSaleListingQueryBuilder($TeamSaleFilter)
    {
        $query = TeamSale::query();
        if (isset($TeamSaleFilter['search']) && trim($TeamSaleFilter['search'])) {
            $keyword = $TeamSaleFilter['search'];
            $query->where('active', '=', 'true')
                ->where(function ($query) use ($keyword) {
                    foreach ($this->searchFields as $key => $searchField) {
                        $query->orWhere($searchField, 'like', "%{$keyword}%");
                    }
                });
        }else
            $query->where('active', '=', 'true');

        // $query->orderBy($productFilter['sortBy'], $productFilter['orderDirection']);

        return $query;
    }

    public function getSaleTeamsFromQueryBuilder($query)
    {
        $data = [];

        $teamSales = $query->get();

        if (!$teamSales->isEmpty()) {
            foreach ($teamSales as $key => $teamSale) {
                $data[] = $this->transform($teamSale);
            }
        }

        return $data;
    }

    public function validateInfo($info, $action = 'insert', $id = 0)
    {
        $messages = [
            'code.required' => "The code is required.",
            'name.required' => "The name is required."
        ];
        if ($action === 'insert') {
            $rules = [
                'code' => 'required',
                'name' => 'required'
            ];
        } elseif ($action === 'update' && $id > 0) {
            $rules = [
                'name' => 'required',
                'code' => 'required'
            ];
        }
        $validator = Validator::make($info, $rules, $messages);
        return $validator;
    }

    private function transform($saleTeam)
    {
        $memberOfTeam = [];
        $members = TeamSaleMembers::where('team_id', '=', $saleTeam['id'])->get();
        foreach ($members as $member) {
            $users = User::where('id', '=', $member['user_id'])->get();
            if ($users->isEmpty()) {
                $Data = [
                    'id' => $saleTeam->id,
                    'code' => $saleTeam->code,
                    'name' => $saleTeam->name,
                    'active' => $saleTeam->active,
                    'members' => []
                ];
                return $Data;
            }

            foreach ($users as $user) {
                $memberOfTeam[] = ['leader' => $member->team_leader,
                    'created_at' => $member['created_at'],
                    'updated-at' => $member->updated_at,
                    'user' => ['id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'phone_1' => $user->phone_1]];
            }
        }
        return [
            'id' => $saleTeam->id,
            'code' => $saleTeam->code,
            'name' => $saleTeam->name,
            'active' => $saleTeam->active,
            'member' => $memberOfTeam
        ];


    }

}