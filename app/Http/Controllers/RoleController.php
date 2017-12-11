<?php

namespace App\Http\Controllers;

use App\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Config,DB,View,Route,Redirect;

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
        if($role = Role::find($id)->delete()){
        	request()->session()->flash('success','角色已成功删除');
        }else{
        	request()->session()->flash('error','角色删除失败');
        }
        return Redirect::route('role.index');
    }

    //为角色分配权限
    public function permission($id)
    {
    	$role = Role::find($id);
		$data = [
			'breadcrumb' => [
				['url' => '#','label' => $role->display_name ],
				['url' => '#','label' => '分配' ]
			],
            'role' => $role,
            'permissions' => Permission::orderBy('name','ASC')->get()
            
        ];
        return view('role.permission', $data);
    }

    //保存角色权限
    public function setPermission()
    {

    }

    //检索所有权限(初始化)
    public function retrievePermission($id)
    {
        //系统管理员角色
        $admin = Role::where('name','=','Admin')->first();
        if($admin == null){
            $admin = new Role();
            $admin->name = 'Admin';
            $admin->display_name = '系统管理员';
            $admin->description  = '系统';
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
        foreach ($permissions as $item){
            if(!in_array($item->name,$routes)){
                $item->delete();
            }
        }
        
        //删除没有任何权限的系统角色
        $roleTable = config::get('entrust.roles_table');
        $query = DB::table($roleTable)
        	->whereNotExists(function($subQuery)use($roleTable){
            	$permissionRoleTable = config::get('entrust.permission_role_table');
                $subQuery->select(DB::raw(1))
                	->from($permissionRoleTable)
                    ->whereRaw($roleTable.'.id = '.$permissionRoleTable.'.role_id');
            })->where($roleTable.'.description', '=', '系统')->delete();

        //将Controller加入到系统角色 & 将新路由添加到权限
        foreach (Route::getRoutes()->getRoutes() as $item){
            if(!strstr($item->getActionName(),'Auth') && strstr($item->getActionName(),'Controllers')) {
            	$controller = explode('@',$item->getActionName())[0];
                $methods = $item->methods();
                $role = Role::where('name','=',$controller)->first();
                if($role == null) {
                	$role = new Role();
                	$role->name = $controller;
            		$role->display_name = $controller;
            		$role->description  = '系统';
            		$role->save();
                }
                $permission = Permission::where('name','=','['.$methods[0].']' . $item->getActionName())->first();
                if($permission == null){
                	$permission = new Permission();
                	$permission->name = '['.$methods[0].']' . $item->getActionName();
                    $permission->display_name = $item->getActionName();
                    $permission->description = $item->uri();
                    $permission->save();
                }
                if(DB::table(config::get('entrust.permission_role_table'))->where('role_id','=',$admin->id)->where('permission_id','=',$permission->id)->count()==0){
					$admin->attachPermission($permission);                
                }
                
                if(DB::table(config::get('entrust.permission_role_table'))->where('role_id','=',$role->id)->where('permission_id','=',$permission->id)->count()==0){
					$role->attachPermission($permission);                
                }
            }
        }
        
        request()->session()->flash('success','权限已经成功索引');
        return Redirect::route('role.permission',['id' => $id]);
    }
}
