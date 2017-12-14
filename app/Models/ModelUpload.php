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
                $result[$key] = $item->hasOneUpload->toArray();
                $result[$key]['key'] = $item->hasOneUpload->id;
                $result[$key]['downloadUrl'] = 'http://'.request()->server('HTTP_HOST').$item->hasOneUpload->url;
                $result[$key]['filetype'] = self::getFileType($item->hasOneUpload->url);
                $result[$key]['type'] = self::getType($item->hasOneUpload->url);
                //删除文件地址
                $result[$key]['url'] = route('upload.delete',['id'=>$item->hasOneUpload->id,'model'=>$params['model'],'model_id'=>$params['model_id']]);
                unset($result[$key]['id'],$result[$key]['operator'],$result[$key]['created_at']);
            }
        }

        return $result;
    }

    //为 BOOTSTRAP FILE INPUT 插件适配文件类型
    public static function getFileType($file)
    {
        $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        if(in_array($extension,['doc','docx','xls','xlsx','ppt','pptx','tif','ai','eps',])){
            return '';
        }else if(in_array($extension,['avi','mpg','mkv','mov','mp4','3gp','webm','wmv'])){
            return 'video/'.$extension;
        }else if(in_array($extension,['jpg','png','gif','jepg','bmp'])){
            return 'image/'.$extension;
        }else if(in_array($extension,['pdf','html','text'])){
            return '';
        }
    }

    //为 BOOTSTRAP FILE INPUT 插件适配文件类型
    public static function getType($file)
    {
        $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        if(in_array($extension,['doc','docx','xls','xlsx','ppt','pptx','tif','ai','eps'])){
            return 'office';
        }else if(in_array($extension,['avi','mpg','mkv','mov','mp4','3gp','webm','wmv'])){
            return 'video';
        }else if(in_array($extension,['jpg','png','gif','jepg','bmp'])){
            return 'image';
        }else if(in_array($extension,['pdf','html','text'])){
            return $extension;
        }
    }

}