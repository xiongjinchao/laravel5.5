<?php

namespace App\Models;

use Zizaco\Entrust\EntrustPermission;

class Permission extends EntrustPermission
{
    public static function getPermission($permission = null)
    {
        $arr = [
            'index' => '入口页面',
            'create' => '创建页面',
            'store' => '创建保存',
            'edit' => '编辑页面',
            'update' => '编辑保存',
            'show' => '查看详情',
            'destroy' => '删除',
            'listing' => '嵌套列表页',
            'copy' => '复制',
            'submit' => '提交',
            'audit' => '审核',
            'publish' => '发布',
            'moveUp' => '上移',
            'moveDown' => '下移',
            'password' => '修改密码',
            'permission' => '设置角色权限',
            'setPermission' => '保存角色权限',
            'retrievePermission' => '检索权限',
        ];

        return isset($arr[$permission]) ? $arr[$permission] : '';
    }
}