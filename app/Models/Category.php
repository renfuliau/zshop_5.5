<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';

    protected $fillable = ['title', 'slug', 'is_parent', 'parent_id', 'status'];

    public function parentCategory()
    {
        return $this->hasOne('App\Models\Category', 'id', 'parent_id')->where('status', 'active');
    }

    public function subcategory()
    {
        return $this->hasMany('App\Models\Category', 'parent_id', 'id')->where('status', 'active');
    }

    public function products()
    {
        return $this->hasMany('App\Models\Product', 'category_id', 'id')->where('status', 'active');
    }

    public function subcategoryProducts(){
        return $this->hasMany('App\Models\Product','subcategory_id','id')->where('status','active');
    }

    public static function getAllParentCategory()
    {
        return Category::where('is_parent', 1)->where('status', 'active')->limit(6)->get();
    }

    public static function getAllParentWithChild()
    {
        return Category::with('subcategory')->where('is_parent', 1)->where('status', 'active')->orderBy('title', 'ASC')->limit(4)->get();
    }

    public static function getProductByCategory($slug){
        return Category::with('products')->where('slug',$slug)->first();
    }

    public static function getProductBySubcategory($slug){
        // return $slug;
        return Category::with('subcategoryProducts')->where('slug',$slug)->first();
    }

    public static function countActiveCategory(){
        $data=Category::where('status','active')->count();
        if($data){
            return $data;
        }
        return 0;
    }
}
