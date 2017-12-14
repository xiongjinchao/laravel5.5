<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Knowledge extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */

    const STATUS_NEW = 1;           //新建
    const STATUS_WAIT_AUDIT = 2;    //待审核
    const STATUS_FAIL_AUDIT = 3;    //审核失败
    const STATUS_WAIT_PUBLISH = 4;  //待发布
    const STATUS_ONLINE = 5;        //上线
    const STATUS_OFFLINE = 6;       //下线

    protected $table = 'knowledge';

    protected $fillable = ['title','category_id','country_id','content','status'];

    public function fromDateTime($value)
    {
        return strtotime(parent::fromDateTime($value));
    }

    //关联User
    public function hasOneUser()
    {
        return $this->hasOne('App\User', 'id', 'operator');
    }

    //获取知识列表
    public static function getList($params)
    {
        $query = self::where('id', '>', '0');
        if(array_get($params, 'category_id') > 0){
            $category = KnowledgeCategory::find($params['category_id']);
            $id = KnowledgeCategory::where('lft','>=',$category->lft)->where('rgt','<=',$category->rgt)->pluck('id')->toArray();
            $query->whereIn('category_id',$id);
        }
        if(array_get($params, 'country_id') > 0){
            $query->where('country_id','=',$params['country_id']);
        }
        if(array_get($params, 'status') > 0){
            $query->where('status','=',$params['status']);
        }
        return $query->orderBy('id', 'DESC')->paginate(15);
    }

    //复制数据
    public static function copyKnowledge($id)
    {
        $current = self::find($id)->toArray();
        unset($current['id'],$current['enshrine'],$current['hit'],$current['created_at'],$current['updated_at']);
        $knowledge = new Knowledge();
        $knowledge->attributes = $current;
        $knowledge->status = self::STATUS_NEW;
        $knowledge->operator = request()->user()->id;
        $knowledge->author = request()->user()->id;
        if($knowledge->save()){
            $modelUploads  = ModelUpload::where('model','=','Knowledge')->where('model_id','=',$id)->get();
            if($modelUploads != null){
                foreach($modelUploads as $item){
                    $modelUpload = new ModelUpload();
                    $modelUpload->model = 'Knowledge';
                    $modelUpload->model_id = $knowledge->id;
                    $modelUpload->upload_id = $item->upload_id;
                    $modelUpload->save();
                }
            }
            return $knowledge;
        }else{
            return false;
        }
    }

    //知识状态
    public static function getStatusOptions($status = null)
    {
        $arr = [
            self::STATUS_NEW => '新建',
            self::STATUS_WAIT_AUDIT => '待审核',
            self::STATUS_FAIL_AUDIT => '审核失败',
            self::STATUS_WAIT_PUBLISH => '待发布',
            self::STATUS_ONLINE => '上线',
            self::STATUS_OFFLINE => '下线'
        ];
        if( $status === null ){
            return $arr;
        }else{
            return isset($arr[$status]) ? $arr[$status] : '';
        }
    }

    //国家列表
    public static function getCountryOptions($country_id = null)
    {
        $arr = Country::all()->pluck('country','id')->toArray();
        if( $country_id === null ){
            return $arr;
        }else{
            return isset($arr[$country_id]) ? $arr[$country_id] : '';
        }
    }

    //热门国家
    public static function getHotCountries()
    {
        $knowledge = (new Knowledge())->getTable();
        $country = (new Country())->getTable();
        $query = DB::select('SELECT k.country_id, c.country, COUNT(k.country_id) AS count FROM '.$knowledge.' AS k LEFT JOIN '.$country.' AS c ON( k.country_id = c.id) GROUP BY K.country_id,C.country ORDER BY count DESC,c.id ASC LIMIT 5');
        return $query;
    }
}