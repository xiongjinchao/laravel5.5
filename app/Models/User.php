<?php

namespace App\Models;

use DB;

class User extends \App\User
{
    //未加入组织
    const INORGANIZATION = 1;

    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 2;

    protected $fillable = [
        'name', 'email', 'password', 'organization_id', 'mobile', 'status'
    ];

    //关联User
    public function hasOneUser()
    {
        return $this->hasOne('App\User', 'id', 'operator');
    }

    //获取列表
    public static function getList($params)
    {
        if(array_get($params, 'status_inorganization') == self::INORGANIZATION){
            //未分配到组织架构的查询
            $useTable = DB::getTablePrefix() . (new User())->getTable();
            $query =  DB::table($useTable)
                ->whereNotExists(function($subQuery)use($useTable){
                    $organizationTable = DB::getTablePrefix() . (new Organization())->getTable();
                    $subQuery->select(DB::raw(1))
                        ->from($organizationTable)
                        ->whereRaw($useTable.'.organization_id = '.$organizationTable.'.id');
                });
            $query->where($useTable.'.id', '>', '1');
            if (array_get($params, 'name') != '') {
                $query->where($useTable.'.name', 'like', '%'.$params['name'].'%');
            }
            if (array_get($params, 'email') != '') {
                $query->where($useTable.'.email', 'like', '%'.$params['email'].'%');
            }
            if (array_get($params, 'mobile') != '') {
                $query->where($useTable.'.mobile', 'like', '%'.$params['mobile'].'%');
            }
            return $query->orderBy($useTable.'.status', 'ASC')->orderBy($useTable.'.id', 'DESC')->paginate(15);
        }else {
            $query = self::where('id', '>', '1');
            if (array_get($params, 'organization_id') > 0) {
                $organization = Organization::find($params['organization_id']);
                $id = Organization::where('lft', '>=', $organization->lft)->where('rgt', '<=', $organization->rgt)->pluck('id')->toArray();
                $query->whereIn('organization_id', $id);
            }
            if (array_get($params, 'name') != '') {
                $query->where('name', 'like', '%'.$params['name'].'%');
            }
            if (array_get($params, 'email') != '') {
                $query->where('email', 'like', '%'.$params['email'].'%');
            }
            if (array_get($params, 'mobile') != '') {
                $query->where('mobile', 'like', '%'.$params['mobile'].'%');
            }
            return $query->orderBy('status', 'ASC')->orderBy('id', 'DESC')->paginate(15);
        }
    }

    //获取用户
    public static function getUser($id)
    {
        return self::find($id);
    }

    //管理员状态
    public static function getStatusOptions($status = null)
    {
        $arr = [
            self::STATUS_ENABLED => '可用',
            self::STATUS_DISABLED => '禁用',
        ];
        if( $status === null ){
            return $arr;
        }else{
            return isset($arr[$status]) ? $arr[$status] : '';
        }
    }
}
