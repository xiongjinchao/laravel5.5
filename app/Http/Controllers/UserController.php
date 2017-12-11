<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Organization;
use App\Models\User;
use App\Models\Role;
use Config,DB,View,Route,Redirect;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        $methods = Route::current()->methods();
        $permission = '['.$methods[0].']'. Route::current()->getActionName();
        $this->middleware('permission:'.$permission);

        View::share('page',[
            'title' => '系统设置',
            'subTitle' => '用户管理',
            'breadcrumb' => [
                ['url' => '#','label' => '系统设置' ],
                ['url' => route('user.index'),'label' => '用户管理' ]
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
            'organizations' => Organization::getOrganizationOptions()
        ];
        return view('user.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [
            'breadcrumb' => [['url' => '#','label' => '创建' ]],
            'organizations' => Organization::getOrganizationOptions()
        ];
        return view('user.create',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = new User();
        $user->fill($request->all());
        $user->operator = $request->user()->id;
        $user->password = bcrypt($request->password);
        if($user->save()){
            $request->session()->flash('success','用户创建成功');
        }else{
            $request->session()->flash('error','用户创建失败');
        }
        return Redirect::route('user.index');
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
        $data = [
            'breadcrumb' => [['url' => '#','label' => '更新' ]],
            'organizations' => Organization::getOrganizationOptions(),
            'user' => User::find($id),
        ];
        return view('user.edit',$data);
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
        $user = User::find($id);
        $user->fill($request->all());
        $user->operator = $request->user()->id;
        if($user->save()){
            $request->session()->flash('success','用户编辑成功');
        }else{
            $request->session()->flash('error','用户编辑失败');
        }
        return Redirect::route('user.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(User::find($id)->delete()){
            request()->session()->flash('success','用户已成功删除');
        }else{
            request()->session()->flash('error','用户删除失败');
        }
        return Redirect::route('user.index');
    }

    //用户列表数据
    public function listing()
    {
        $data = [
            'users' => User::getlist(request()->all()),
            'roles' => Role::orderBy('name','DESC')->get()
        ];
        $view = view('user.listing', $data)->render();
        return ['status' => 'success', 'html' => $view];
    }

    //保存为用户分配角色
    public function assignment($id){
        $user = User::find($id);
        $user->operator = request()->user()->id;
        if($user->save()){
            $roleUserTable = Config::get('entrust.role_user_table');
            DB::table($roleUserTable)->where('user_id','=',$id)->delete();
            if(!empty(request()->roles)){
                foreach (request()->roles as $item){
                    $role = Role::find($item);
                    if(!$user->hasRole($role->name)){
                        $user->attachRole($role);
                    }
                }
            }
            request()->session()->flash('success','分配角色成功');
        }else{
            request()->session()->flash('error','分配角色失败');
        }
        return Redirect::route('user.index');
    }

    //修改密码
    public function password($id)
    {
        $user = User::find($id);
        $user->operator = request()->user()->id;
        $user->password = bcrypt(request()->password);
        if($user->save()){
            request()->session()->flash('success','重置密码成功');
        }else{
            request()->session()->flash('error','重置密码失败');
        }
        return Redirect::route('user.index');
    }
}
