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

    public function productlist()
    {
        $products = Product::query();
        dd($products);
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
            ->with('categories', $this->categories)
            ->with('products', $products)
            ->with('recent_products', $recent_products);
    }

    public function productlistByCategory(Request $request)
    {
        // dd($request);
        $title = $request->title;
        $products = Category::getProductByCategory($request->slug);
        // dd($products->products);
        // $recent_products = Product::where('status', 'active')->orderBy('id', 'DESC')->limit(3)->get();
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
        // return $products;
        // dd($this->categories[1]->subcategory->count());
        // dd($products);
        $recent_products = Product::where('status', 'active')->orderBy('id', 'DESC')->limit(3)->get();

        // if (request()->is('e-shop.loc/product-grids')) {
        //     return view('frontend.pages.product-grids')->with('products', $products->sub_products)->with('recent_products', $recent_products);
        // } else {
        return view('products.productlist')
            ->with('categories', $this->categories)
            ->with('products', $products->subcategoryProducts)
            ->with('recent_products', $recent_products)
            ->with('title', $title)
            ->with('subtitle', $subtitle);
        // }
    }

    public function productDetail($slug)
    {
        // dd($slug);
        $product_detail = Product::getProductBySlug($slug);
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
}
