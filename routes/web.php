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
Route::get('user/listing', 'UserController@listing')->name('user.listing');                                             //列表
Route::post('user/password/{id}', 'UserController@password')->name('user.password');                                    //重置密码

//角色管理
Route::get('role/permission/{id}', 'RoleController@permission')->name('role.permission');                               //设置权限
Route::post('role/set-permission/{id}', 'RoleController@setPermission')->name('role.set-permission');                   //保存设置权限
Route::get('role/retrieve-permission', 'RoleController@retrievePermission')->name('role.retrieve-permission');          //检索所有权限

//知识管理
Route::get('knowledge/listing', 'KnowledgeController@listing')->name('knowledge.listing');                              //列表
Route::get('knowledge/copy/{id}', 'KnowledgeController@copy')->name('knowledge.copy');                                  //复制
Route::get('knowledge/submit/{id}', 'KnowledgeController@submit')->name('knowledge.submit');                            //提交审核
Route::get('knowledge/audit/{id}', 'KnowledgeController@audit')->name('knowledge.audit');                               //审核成功 或 审核失败
Route::get('knowledge/publish/{id}', 'KnowledgeController@publish')->name('knowledge.publish');                         //上线 或 下线

//知识目录
Route::get('knowledge-category/move-up/{id}', 'KnowledgeCategoryController@moveUp')->name('knowledge-category.move-up');
Route::get('knowledge-category/move-down/{id}', 'KnowledgeCategoryController@moveDown')->name('knowledge-category.move-down');

Route::resources([
    //用户管理
    'user' => 'UserController',
    //组织架构
    'organization' => 'OrganizationController',
    //角色管理
    'role' => 'RoleController',
    //知识目录
    'knowledge-category' => 'KnowledgeCategoryController',
    //知识管理
    'knowledge' => 'KnowledgeController',
    //FAQ管理
    'faq' => 'FAQController',
    //公告管理
    'notice' => 'NoticeController',
]);
