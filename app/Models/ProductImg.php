<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImg extends Model
{
    protected $fillable = ['product_id', 'filepath', 'sort'];

    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }
}
