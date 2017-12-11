<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use View,Entrust,Route,Request,Response;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function checkEntrustAuth()
    {
        //检查权限
        $methods = Route::current()->methods();
        $permission = '['.$methods[0].']'. Route::current()->getActionName();
        if(Entrust::can($permission)){
            return true;
        }else{
            if(Request::ajax()){
                return Response::json(['status' => 'error','message'=>'（#403）抱歉，您没有权限访问']);
            }else{
                return abort(403, '（#403）抱歉，您没有权限访问');
            }
        }
    }
}
