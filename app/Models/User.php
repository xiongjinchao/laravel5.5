<?php

namespace App\Models;

use Config,DB;

class User extends \App\User
{
    //未加入组织
    const STATUS_OUT_ORGANIZATION = 1;

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
    public static function getList($params, $size = 15)
    {
        if(array_get($params, 'status_out_organization') == self::STATUS_OUT_ORGANIZATION){
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
            return $query->orderBy($useTable.'.status', 'ASC')->orderBy($useTable.'.id', 'DESC')->paginate($size);
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
            return $query->orderBy('status', 'ASC')->orderBy('id', 'DESC')->paginate($size);
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

    //获取该用户下拥有的角色
    public static function getRoles($id)
    {
        $roleUserTable = Config::get('entrust.role_user_table');
        return DB::table($roleUserTable)->where('user_id','=',$id)->pluck('role_id')->toArray();
    }

    //获取各个组织的人数分布
    public static function getDistribution()
    {
        $result = [];
        $useTable = DB::getTablePrefix() . (new User())->getTable();
        $organizationTable = DB::getTablePrefix() . (new Organization())->getTable();
        $users = DB::select("SELECT count(".$useTable.".organization_id) as count, ".$organizationTable.".name, ".$organizationTable.".id FROM ".$useTable." LEFT JOIN ".$organizationTable." ON(".$useTable.".organization_id = ".$organizationTable.".id) GROUP BY ".$useTable.".organization_id Order By count DESC");
        $color = [
            '#FF6666',
            '#FF9966',
            '#CC9966',
            '#666666',
            '#FFFF66',
            '#99CC66',
            '#CC3333',
            '#003366',
            '#993333',
            '#CCCC00',
            '#663366',
            '#FFFF00',
            '#0066CC',
            '#CC0033',
            '#333333',
            '#336633',
            '#990033',
            '#003300',
            '#FF0033',
            '#333399',
            '#000000',
            '#003399',
            '#99CC00',
            '#999933',
            '#333300',
            '#99CC99',
            '#CCFF99',
            '#99CCFF',
            '#FF9900',
            '#336699',
            '#CCCC33',
            '#CC9933',
            '#996600',
            '#FFCC33',
            '#336666',
            '#99CC33',
            '#FFCC00',
            '#FF9933',
            '#CC6699',
            '#3366CC',
            '#009966',
            '#FF6600',
            '#CCFF66',
            '#CC6600',
            '#999999',
            '#999966',
            '#663300',
            '#FFFFFF',
            '#FFFF33',
            '#9933FF',
            '#66CC00',
            '#0000CC',
            '#6666CC',
            '#CC99CC',
            '#666600',
            '#006633',
            '#CCCC66',
            '#33CC33',
            '#CCFFCC',
            '#00CC00',
            '#993399',
            '#669933',
            '#333366',
            '#003333',
            '#996633',
            '#339933',
            '#9933CC',
            '#336600',
            '#CC9900',
            '#339966',
            '#6699CC',
            '#666633',
            '#66CCCC',
            '#339999',
            '#66CC99',
            '#666699',
            '#009999',
            '#FF99CC',
            '#669999',
            '#663333',
            '#3399CC',
            '#006699',
            '#9999FF',
            '#0000FF',
            '#6699FF',
            '#6666FF',
            '#66CCFF',
            '#9999CC',
            '#CC6666',
            '#000066',
            '#990066',
            '#660066',
            '#9966CC',
            '#663399',
            '#996666',
            '#330033',
            '#FF33CC',
            '#993366',
            '#669966',
            '#FFCCFF',
            '#CC3399',
            '#FF3399',
            '#999900',
            '#CCFF00',
            '#009933',
            '#CC6633',
            '#CC0066',
            '#CCCC44',
            '#33CC99',
            '#CC3366',
            '#F00000',
            '#660033',
            '#660000',
            '#006600',
            '#66CC66',
            '#660099',
            '#0099FF',
            '#ABCDEF',
            '#FFFFCC',
            '#CCFFFF',
            '#FFCCCC',
            '#99CCCC',
            '#FFCC99',
            '#FF9999',
            '#996699',
            '#CC9999',
            '#CCCC99',
            '#FFFF99',
            '#CCCCFF',
            '#0099CC',
            '#CCCCCC',
        ];
        foreach($users as $key => $item)
        {
            $result[] = [
                'label' => $item->id>0?$item->name:'未分配组织',
                'value' => $item->count,
                'color' => $color[array_rand($color,1)],
            ];
        }
        return $result;
    }
}
