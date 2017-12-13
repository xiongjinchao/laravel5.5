<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Validator;

class ModelUpload extends Model
{
    public $timestamps = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'model_upload';

    //关联Upload
    public function hasOneUpload()
    {
        return $this->hasOne('App\Models\Upload', 'id', 'upload_id');
    }

    public static function getUploads($params)
    {
        $validator = Validator::make($params, [
            'model' => 'required|max:255',
            'model_id' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return [];
        }
        $modelUploads = self::with('hasOneUpload')->where('model','=',$params['model'])->where('model_id','=',$params['model_id'])->get();
        $result = [];
        if($modelUploads != null){
            foreach($modelUploads as $key => $item){
                $result[$key] = $item->hasOneUpload;
                $result[$key]['key'] = $item->hasOneUpload->id;
                $result[$key]['type'] = explode('/',$item->hasOneUpload->type)[0];
                $result[$key]['url'] = $item->hasOneUpload->url;
                $result[$key]['url'] = 'http://admin.laravel5.5.com'.$item->hasOneUpload->url;
                $result[$key]['downloadUrl'] = $item->hasOneUpload->url;
                //delete url
                //$result[$key]['url'] = route('upload.delete',['id'=>$item->hasOneUpload->id,'model'=>$params['model'],'model_id'=>$params['model_id']]);
                unset($result[$key]['id'],$result[$key]['operator'],$result[$key]['created_at']);
            }
        }

        return $result;
    }

}