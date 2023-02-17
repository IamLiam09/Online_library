<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubContent extends Model
{

    public function content_id(){
        return $this->hasMany('App\Models\Content', 'subcontent', 'id');
    }
}
