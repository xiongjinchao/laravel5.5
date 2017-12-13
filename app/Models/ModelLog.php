<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Validator;

class ModelLog extends Model
{
    public $timestamps = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'model_log';

    //关联User
    public function hasOneUser()
    {
        return $this->hasOne('App\User', 'id', 'operator');
    }

    public static function log($params)
    {
        $validator = Validator::make($params, [
            'model' => 'required|max:255',
            'model_id' => 'required|integer|min:1',
            'content' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            request()->session()->flash('error','日志写入失败');
        }
        $log = new ModelLog();
        $log->model = $params['model'];
        $log->model_id = $params['model_id'];
        $log->content = $params['content'];
        $log->operator = request()->user()->id;;
        $log->created_at = date("Y-m-d H:i:s");

        if(!$log->save()){
            request()->session()->flash('error','日志写入失败');
        }
        return true;
    }
}