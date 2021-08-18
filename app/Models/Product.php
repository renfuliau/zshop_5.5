<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';

    protected $fillable = ['title', 'slug', 'summary', 'description', 'photo', 'stock', 'size', 'state', 'status', 'price', 'special_price', 'is_featured', 'category_id', 'subcategory_id'];

    public function category(){
        return $this->hasOne('App\Models\Category','id','category_id');
    }

    public function subcategory(){
        return $this->hasOne('App\Models\Category','id','subcategory_id');
    }
}
