<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//欢迎页面
Route::get('/', function () {
    return view('welcome');
});

//登录注册
Auth::routes();

//控制面板
Route::get('/home', 'HomeController@index')->name('home');

//组织架构
Route::get('organization/move-up/{id}', 'OrganizationController@moveUp')->name('organization.move-up');
Route::get('organization/move-down/{id}', 'OrganizationController@moveDown')->name('organization.move-down');

//用户管理
Route::get('user/tab', 'UserController@tab')->name('user.tab');                                                         //列表Tap
Route::post('user/password/{id}', 'UserController@password')->name('user.password');                                    //重置密码
Route::post('user/assignment/{id}', 'UserController@assignment')->name('user.assignment');                              //保存为用户分配角色

//角色管理
Route::any('role/permission/{id}', 'RoleController@permission')->name('role.permission');                               //设置权限
Route::get('role/retrieve/{id?}', 'RoleController@retrieve')->name('role.retrieve');                                    //检索所有权限

//知识管理
Route::get('knowledge/tab', 'KnowledgeController@tab')->name('knowledge.tab');                                          //列表Tap
Route::get('knowledge/copy/{id}', 'KnowledgeController@copy')->name('knowledge.copy');                                  //复制
Route::get('knowledge/submit/{id}', 'KnowledgeController@submit')->name('knowledge.submit');                            //提交审核
Route::get('knowledge/audit/{id}/{status}', 'KnowledgeController@audit')->where(['status' => '3|4'])->name('knowledge.audit');             //审核成功 或 审核失败
Route::get('knowledge/publish/{id}/{status}', 'KnowledgeController@publish')->where(['status' => '5|6'])->name('knowledge.publish');       //上线 或 下线
Route::get('knowledge/log/{id}', 'KnowledgeController@log')->name('knowledge.log');                                     //操作日志

//知识目录
Route::get('knowledge-category/move-up/{id}', 'KnowledgeCategoryController@moveUp')->name('knowledge-category.move-up');
Route::get('knowledge-category/move-down/{id}', 'KnowledgeCategoryController@moveDown')->name('knowledge-category.move-down');

//用户管理
Route::resource('user', 'UserController', ['except' => ['show']]);

//组织架构
Route::resource('organization', 'OrganizationController', ['except' => ['show']]);

//知识目录
Route::resource('knowledge-category', 'KnowledgeCategoryController', ['except' => ['show']]);

Route::resource('knowledge', 'KnowledgeController', ['except' => ['show']]);

//FAQ管理
Route::resource('faq', 'FAQController', ['except' => ['show']]);

//公告管理
Route::resource('notice', 'NoticeController', ['except' => ['show']]);

//角色管理
Route::resource('role', 'RoleController', ['except' => ['show','create','edit']]);
