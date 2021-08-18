<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $categories;

    public function __construct()
    {
        $this->categories = Category::getAllParentWithChild();
    }

    public function productlist()
    {
        $products = Product::query();

        if (!empty($_GET['category'])) {
            $slug = explode(',', $_GET['category']);
            // dd($slug);
            $cat_ids = Category::select('id')->whereIn('slug', $slug)->pluck('id')->toArray();
            // dd($cat_ids);
            $products->whereIn('cat_id', $cat_ids);
            // return $products;
        }

        if (!empty($_GET['sortBy'])) {
            if ($_GET['sortBy'] == 'title') {
                $products = $products->where('status', 'active')->orderBy('title', 'ASC');
            }
            if ($_GET['sortBy'] == 'price') {
                $products = $products->orderBy('price', 'ASC');
            }
        }

        if (!empty($_GET['price'])) {
            $price = explode('-', $_GET['price']);
            $products->whereBetween('price', $price);
        }

        $recent_products = Product::where('status', 'active')->orderBy('id', 'DESC')->limit(3)->get();
        // Sort by number
        if (!empty($_GET['show'])) {
            $products = $products->where('status', 'active')->paginate($_GET['show']);
        } else {
            $products = $products->where('status', 'active')->paginate(9);
        }
        return view('products.productlist')
            ->with('products', $products)
            ->with('recent_products', $recent_products)
            ->with('categories', $this->categories);
    }

    public function productlistByCategory(Request $request)
    {
        // dd($request);
        $title = $request->title;
        $products = Category::getProductByCategory($request->slug);
        // dd($products);
        $recent_products = Product::where('status', 'active')->orderBy('id', 'DESC')->limit(3)->get();
        return view('products.productlist')
            ->with('products', $products->products)
            ->with('recent_products', $recent_products)
            ->with('categories', $this->categories)
            ->with('title', $title);
    }
}
