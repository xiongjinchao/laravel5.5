<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KnowledgeCategory extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'knowledge_category';

    protected $fillable = ['name','parent'];

    public function fromDateTime($value)
    {
        return strtotime(parent::fromDateTime($value));
    }

    //关联User
    public function hasOneUser()
    {
        return $this->hasOne('App\User', 'id', 'operator');
    }

    //获取深度对应的符号
    public function getSpace()
    {
        $space = '';
        $lastBrother = $this->getLastBrother();
        $space.= str_repeat('┃ ',$this->depth-1);
        $space.=$this->id == $lastBrother->id?'┗ ':'┣ ';
        return $space;
    }

    //父亲节点
    public function getParent()
    {
        if($this->parent>0){
            return self::find($this->parent);
        }else{
            return new KnowledgeCategory();
        }
    }

    //最后的兄弟节点
    public function getLastBrother()
    {
        return self::where(['parent'=>$this->parent])->where(['depth'=>$this->depth])->orderBy('rgt', 'DESC')->first();
    }

    //知识目录列表用于下拉控件
    public static function getKnowledgeCategoryOptions()
    {
        $arr = [];
        $knowledgeCategories = self::orderBy('lft', 'ASC')->get();
        foreach($knowledgeCategories as $item){
            $arr[$item->id] = $item->getSpace().$item->name;
        }
        return $arr;
    }

    //获取知识目录路径
    public static function getKnowledgeCategoryPath($category_id)
    {
        $path = '';
        $category = self::find($category_id);
        if($category == null)
        {
            return '';
        }
        $knowledgeCategories = self::where('lft','<=',$category->lft)->where('rgt','>=',$category->rgt)->orderBy('lft', 'ASC')->get();
        if($knowledgeCategories == null)
        {
            return '';
        }
        foreach($knowledgeCategories as $key => $item){
            $path.=$item->name.($key == $knowledgeCategories->count()-1?'':' \ ');
        }
        return $path;
    }
}