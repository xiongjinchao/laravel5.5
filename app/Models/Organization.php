<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'organization';

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

    public function hasManyUsers()
    {
        return $this->hasMany('App\User', 'organization_id', 'id');
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
            return new Organization();
        }
    }

    //最后的兄弟节点
    public function getLastBrother()
    {
        return self::where(['parent'=>$this->parent])->where(['depth'=>$this->depth])->orderBy('rgt', 'DESC')->first();
    }

    //组织架构列表用于下拉控件
    public static function getOrganizationOptions()
    {
        $arr = [];
        $organizations = self::orderBy('lft', 'ASC')->get();
        foreach($organizations as $item){
            $arr[$item->id] = $item->getSpace().$item->name;
        }
        return $arr;
    }

    //获取组织架构的路径
    public static function getOrganizationPath($organization_id)
    {
        $path = '';
        $organization = self::find($organization_id);
        if($organization == null)
        {
            return '';
        }
        $organizations = self::where('lft','<=',$organization->lft)->where('rgt','>=',$organization->rgt)->orderBy('lft', 'ASC')->get();
        if($organizations == null)
        {
            return '';
        }
        foreach($organizations as $key => $item){
            $path.=$item->name.($key == $organizations->count()-1?'':' \ ');
        }
        return $path;
    }
}
