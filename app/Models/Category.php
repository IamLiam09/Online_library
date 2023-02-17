<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'store_id',
        'name',
        'category_image',
        'status',
        'created_by',
    ];
}
