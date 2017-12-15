<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FAQCategory extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'faq_category';

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
            return new FAQCategory();
        }
    }

    //最后的兄弟节点
    public function getLastBrother()
    {
        return self::where(['parent'=>$this->parent])->where(['depth'=>$this->depth])->orderBy('rgt', 'DESC')->first();
    }

    //FAQ分类列表用于下拉控件
    public static function getFAQCategoryOptions()
    {
        $arr = [];
        $faqCategories = self::orderBy('lft', 'ASC')->get();
        foreach($faqCategories as $item){
            $arr[$item->id] = $item->getSpace().$item->name;
        }
        return $arr;
    }

    //获取FAQ分类路径
    public static function getFAQCategoryPath($category_id)
    {
        $path = '';
        $category = self::find($category_id);
        if($category == null)
        {
            return '';
        }
        $faqCategories = self::where('lft','<=',$category->lft)->where('rgt','>=',$category->rgt)->orderBy('lft', 'ASC')->get();
        if($faqCategories == null)
        {
            return '';
        }
        foreach($faqCategories as $key => $item){
            $path.=$item->name.($key == $faqCategories->count()-1?'':' \ ');
        }
        return $path;
    }
}