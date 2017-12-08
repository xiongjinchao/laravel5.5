<?php

namespace App\Http\Controllers;

use App\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use View,Route,Redirect;

class RoleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

        $this->middleware('auth');

        View::share('page',[
            'title' => '系统设置',
            'subTitle' => '角色管理',
            'breadcrumb' => [
                ['url' => '#','label' => '系统设置' ],
                ['url' => '#','label' => '角色管理' ]
            ]
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [
            'roles' => Role::all()
        ];
        return view('role.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $role = new Role();
        $role->name = $request->name;
        $role->display_name = $request->display_name;
        $role->description  = $request->description;
        if($role->save()){
            $request->session()->flash('success','角色创建成功');
        }else{
            $request->session()->flash('success','角色创建失败');
        }
        return Redirect::route('role.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $role = Role::find($id);
        $role->name = $request->name;
        $role->display_name = $request->display_name;
        $role->description  = $request->description;
        if($role->save()){
            $request->session()->flash('success','角色更新成功');
        }else{
            $request->session()->flash('success','角色更新失败');
        }
        return Redirect::route('role.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    //为角色分配权限
    public function permission()
    {

    }

    //保存角色权限
    public function setPermission()
    {

    }

    //检索所有权限(初始化)
    public function retrievePermission()
    {
        //系统管理员角色
        $admin = Role::where('name','=','Admin')->first();
        if($admin == null){
            $admin = new Role();
            $admin->name = 'Admin';
            $admin->display_name = '系统管理员';
            $admin->description  = '系统管理员可以执行任何操作';
            $admin->save();
        }

        //系统管理员用户
        $user = User::find(1);
        if($user!=null) {
            if (!$user->hasRole('Admin')) {
                $user->attachRole($admin);
            }
        }

        //将不存在的权限删除
        $routes = [];
        foreach (Route::getRoutes()->getRoutes() as $item){
            if(!strstr($item->getActionName(),'Auth') && strstr($item->getActionName(),'Controllers')) {
                $methods = $item->methods();
                $routes[] = '[' . $methods[0] . ']' . $item->getActionName();
            }
        }
        $permissions = Permission::all();
        foreach ($permissions as $item)
        {
            if(!in_array($item->name,$routes)){
                $item->delete();
            }
        }

        //将新路由添加都权限
        foreach (Route::getRoutes()->getRoutes() as $item){
            if(!strstr($item->getActionName(),'Auth') && strstr($item->getActionName(),'Controllers')) {
                $methods = $item->methods();
                if (!in_array('['.$methods[0].']' .$item->getActionName(),Permission::all()->pluck('name')->toArray())) {
                    $permission = new Permission();
                    $permission->name = '['.$methods[0].']' . $item->getActionName();
                    $permission->display_name = $item->getActionName();
                    $permission->description = $item->uri();
                    $permission->save();

                    $admin->attachPermission($permission);
                }
            }
        }
        request()->session()->flash('success','权限已经成功索引');
        return Redirect::route('role.index');
    }
}
