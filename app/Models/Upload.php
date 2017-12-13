<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Upload extends Model
{
    public $timestamps = false;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'uploads';

    //关联User
    public function hasOneUpload()
    {
        return $this->hasOne('App\User', 'id', 'operator');
    }
}