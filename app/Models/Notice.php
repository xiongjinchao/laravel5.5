<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Notice extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */

    const STATUS_WAIT_PUBLISH = 1;    //未发布
    const STATUS_PUBLISHED = 2;       //已发布

    protected $table = 'notice';

    protected $fillable = ['title','content','status','operator'];

    public function fromDateTime($value)
    {
        return strtotime(parent::fromDateTime($value));
    }

    //关联User
    public function hasOneUser()
    {
        return $this->hasOne('App\User', 'id', 'operator');
    }

    //关联User
    public function hasOneAuthor()
    {
        return $this->hasOne('App\User', 'id', 'author');
    }

    //获取Notice列表
    public static function getList($params)
    {
        $query = self::where('id', '>', '0');
        if(array_get($params, 'id') > 0){
            $query->where('id','=',$params['id']);
        }
        if(array_get($params, 'title') !=''){
            $query->where('title','like','%'.$params['title'].'%');
        }
        if(array_get($params, 'status') > 0){
            $query->where('status','=',$params['status']);
        }
        if(array_get($params, 'author') > 0){
            $query->where('author','=',$params['author']);
        }
        if(array_get($params, 'created_time_range') != ''){
            $createdTimeRange = explode('~',$params['created_time_range']);
            $query->where('created_at','>=',strtotime($createdTimeRange[0]))->where('created_at','<=',strtotime($createdTimeRange[1])+3600*24);
        }
        return $query->orderBy('id', 'DESC')->paginate(15);
    }

    //公告状态
    public static function getStatusOptions($status = null)
    {
        $arr = [
            self::STATUS_WAIT_PUBLISH => '未发布',
            self::STATUS_PUBLISHED => '已发布'
        ];
        if( $status === null ){
            return $arr;
        }else{
            return isset($arr[$status]) ? $arr[$status] : '';
        }
    }
}