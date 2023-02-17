<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model
{
    protected $fillable = [
        'store_id',
        'name',
        'category',
        'created_by',
    ];

    public function category_id()
    {
        return $this->hasOne('App\Models\Category', 'id', 'category');
    }
}
