<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    protected $user;
    protected $categories;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            // dd($this->user);
            if (!empty($this->user)) {
                $this->cart_total_qty = Cart::getTotalQty(Auth::user()->id);
            }
            return $next($request);
        });
        $this->categories = Category::getAllParentCategory();
    }

    public function productlistByCategory(Request $request)
    {
        $title = $request->title;
        // dd($request->slug);
        $products = Category::getProductByCategory($request->slug);
        return view('products.productlist')
            ->with('categories', $this->categories)
            ->with('products', $products->products)
            // ->with('recent_products', $recent_products)
            ->with('title', $title);
    }

    public function productSubcategory(Request $request)
    {
        $products = Category::getProductBySubcategory($request->sub_slug);
        $title = $request->title;
        $subtitle = $request->subtitle;
        dd($products);
        return view('products.productlist')
            ->with('categories', $this->categories)
            ->with('products', $products->subcategoryProducts)
            ->with('recent_products', $recent_products)
            ->with('title', $title)
            ->with('subtitle', $subtitle);
    }

    public function productDetail($id)
    {
        // dd($slug);
        $product_detail = Product::getProductById($id);
        // dd($product_detail);
        return view('products.product-detail', compact('product_detail'))
            ->with('categories', $this->categories);
    }

    public function productSearch(Request $request)
    {
        $keyword = $request->keyword;
        // dd($keyword);
        $products = Product::getSearchProducts($keyword);
        // dd($products);
        $recent_products = Product::where('status', 'active')->orderBy('id', 'DESC')->limit(3)->get();

        return view('products.productlist-keyword', compact('keyword', 'products', 'recent_products'))
            ->with('categories', $this->categories);
    }

    // public function productSearch($keyword)
    // {
    //     // $keyword = $request->keyword;
    //     // dd($keyword);
    //     $products = Product::getSearchProducts($keyword);
    //     // dd($products);
    //     $recent_products = Product::where('status', 'active')->orderBy('id', 'DESC')->limit(3)->get();

    //     return view('products.productlist-keyword', compact('keyword', 'products', 'recent_products'))
    //         ->with('categories', $this->categories);
    // }
}
