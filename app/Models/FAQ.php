<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class FAQ extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */

    const STATUS_NOT_REPLY = 1;    //未回复
    const STATUS_HAS_REPLY = 2;    //已回复
    const STATUS_DELETE = 3;       //已删除

    public $timestamps = false;

    protected $table = 'faq';

    protected $fillable = ['ask_title','category_id','answer','status','ask_user_id','assign_user_id','answer_user_id','operator'];

    //关联User
    public function hasOneUser()
    {
        return $this->hasOne('App\User', 'id', 'operator');
    }

    //关联User
    public function hasOneASK()
    {
        return $this->hasOne('App\User', 'id', 'ask_user_id');
    }

    //关联User
    public function hasOneAssign()
    {
        return $this->hasOne('App\User', 'id', 'assign_user_id');
    }

    //关联User
    public function hasOneAnswer()
    {
        return $this->hasOne('App\User', 'id', 'answer_user_id');
    }

    //获取FAQ列表
    public static function getList($params)
    {
        $query = self::where('id', '>', '0');
        if(array_get($params, 'id') > 0){
            $query->where('id','=',$params['id']);
        }
        if(array_get($params, 'category_id') > 0){
            $category = FAQCategory::find($params['category_id']);
            $id = FAQCategory::where('lft','>=',$category->lft)->where('rgt','<=',$category->rgt)->pluck('id')->toArray();
            $query->whereIn('category_id',$id);
        }
        if(array_get($params, 'status') > 0){
            $query->where('status','=',$params['status']);
        }
        if(array_get($params, 'ask_user_id') > 0){
            $query->where('ask_user_id','=',$params['ask_user_id']);
        }
        if(array_get($params, 'answer_user_id') > 0){
            $query->where('answer_user_id','=',$params['answer_user_id']);
        }
        if(array_get($params, 'ask_time_range') != ''){
            $askTimeRange = explode('~',$params['ask_time_range']);
            $query->where('ask_at','>=',strtotime($askTimeRange[0]))->where('ask_at','<=',strtotime($askTimeRange[1]));
        }
        if(array_get($params, 'answer_time_range') != ''){
            $answerTimeRange = explode('~',$params['ask_time_range']);
            $query->where('answer_at','>=',strtotime($answerTimeRange[0]))->where('answer_at','<=',strtotime($answerTimeRange[1]));
        }
        return $query->orderBy('id', 'DESC')->paginate(15);
    }

    //FAQ状态
    public static function getStatusOptions($status = null)
    {
        $arr = [
            self::STATUS_NOT_REPLY => '未回复',
            self::STATUS_HAS_REPLY => '已回复',
            self::STATUS_DELETE => '已删除'
        ];
        if( $status === null ){
            return $arr;
        }else{
            return isset($arr[$status]) ? $arr[$status] : '';
        }
    }
}