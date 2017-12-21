<?php

namespace App\Models;

use Zizaco\Entrust\EntrustPermission;

class Permission extends EntrustPermission
{
    public static function getPermission($permission = null)
    {
        $arr = [
            'index' => '入口',
            'create' => '创建',
            'update' => '编辑',
            'show' => '详情',
            'destroy' => '删除',
            'tab' => '标签',
            'copy' => '复制',
            'submit' => '提交',
            'audit' => '审核',
            'publish' => '发布',
            'moveUp' => '上移',
            'moveDown' => '下移',
            'password' => '修改密码',
            'permission' => '分配权限',
            'retrieve' => '检索权限',
            'assignment' => '分配角色',
            'log' => '查看日志',
            'change' => '转换知识',
        ];

        return isset($arr[$permission]) ? $arr[$permission] : '';
    }
}