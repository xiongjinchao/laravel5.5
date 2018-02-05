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

//登录注册
Auth::routes();

//控制面板
Route::get('/', 'HomeController@index')->name('home');
Route::get('/home/notice', 'HomeController@notice')->name('home.notice');                                               //头部提醒信息

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

//上传
Route::any('upload', 'UploadController@index')->name('upload');                                                         //上传地址
Route::any('upload/delete/{id}/{model?}/{model_id?}', 'UploadController@destroy')->name('upload.delete');               //删除文件

//知识目录
Route::get('knowledge-category/move-up/{id}', 'KnowledgeCategoryController@moveUp')->name('knowledge-category.move-up');
Route::get('knowledge-category/move-down/{id}', 'KnowledgeCategoryController@moveDown')->name('knowledge-category.move-down');

//FAQ分类管理
Route::get('faq-category/move-up/{id}', 'FAQCategoryController@moveUp')->name('faq-category.move-up');
Route::get('faq-category/move-down/{id}', 'FAQCategoryController@moveDown')->name('faq-category.move-down');

//FAQ管理
Route::any('faq/change/{id}', 'FAQController@change')->name('faq.change');                                              //转换为知识
Route::get('faq/log/{id}', 'FAQController@log')->name('faq.log');                                                       //操作日志

//公告管理
Route::any('notice/change/{id}', 'NoticeController@change')->name('notice.change');                                     //转换为知识
Route::get('notice/log/{id}', 'NoticeController@log')->name('notice.log');                                              //操作日志

//用户管理
Route::resource('user', 'UserController', ['except' => ['show']]);

//组织架构
Route::resource('organization', 'OrganizationController', ['except' => ['show']]);

//知识目录
Route::resource('knowledge-category', 'KnowledgeCategoryController', ['except' => ['show']]);

//知识管理
Route::resource('knowledge', 'KnowledgeController', ['except' => ['show']]);

//FAQ分类管理
Route::resource('faq-category', 'FAQCategoryController', ['except' => ['show']]);

//FAQ管理
Route::resource('faq', 'FAQController', ['except' => ['show']]);

//公告管理
Route::resource('notice', 'NoticeController', ['except' => ['show']]);

//角色管理
Route::resource('role', 'RoleController', ['except' => ['show','create','edit']]);

//澳大利亚
Route::get('aussie-index', 'AussieController@index')->name('aussie.index'); //继续
Route::get('aussie-logout', 'AussieController@logout')->name('aussie.logout'); //退出登录
Route::get('aussie-login', 'AussieController@login')->name('aussie.login'); //登录
Route::get('aussie-continuation', 'AussieController@continuation')->name('aussie.continuation'); //继续
Route::get('aussie-account', 'AussieController@account')->name('aussie.account'); //账号信息
Route::get('aussie-tourist-visa', 'AussieController@touristVisa')->name('aussie.tourist-visa'); //旅游签证
