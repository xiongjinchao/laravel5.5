<?php

namespace App\Models;

use Zizaco\Entrust\EntrustRole;
use Config,DB;

class Role extends EntrustRole
{
    public function hasManyPermissions()
    {
        $permissionTable = config::get('entrust.permissions_table');
        $permissionRoleTable = Config::get('entrust.permission_role_table');
        return DB::table($permissionTable.' as p')->leftJoin($permissionRoleTable.' as pr','p.id', '=', 'pr.permission_id')
            ->where('pr.role_id',$this->id)
            ->get();
    }
}