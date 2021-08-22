@extends('layouts.main')

@section('title', 'ZShop - ' . $title)

@section('main-content')

    <!-- Breadcrumbs -->
    <div class="breadcrumbs">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="bread-inner">
                        <ul class="bread-list">
                            <li><a href="{{ route('index') }}">首頁<i class="ti-arrow-right"></i></a></li>
                            @foreach ($categories as $category)
                                <li><a
                                        href="{{ route('productlist-category', ['slug' => $category->slug, 'title' => $category->title]) }}">
                            @endforeach{{ $title }}</a></li>

                            @if (isset($subtitle))
                                <li class="active"><i class="ti-arrow-right"></i><a
                                        href="javascript:void(0);">{{ $subtitle }}</a></li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Breadcrumbs -->
    <form action="" method="POST">
        {{ csrf_field() }}
        <!-- Product Style 1 -->
        <section class="product-area shop-sidebar shop-list shop section">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 col-md-4 col-12">
                        <div class="shop-sidebar">
                            <!-- Single Widget -->
                            <div class="single-widget category">
                                <h3 class="title">商品分類</h3>
                                <ul class="categor-list">
                                    <li>
                                        @foreach ($categories as $category)
                                            @if ($category->subcategory->count() > 0)
                                    <li><a
                                            href="{{ route('productlist-category', ['slug' => $category->slug, 'title' => $category->title]) }}">{{ $category->title }}</a>
                                        <ul>
                                            @foreach ($category->subcategory as $subcategory)
                                                <li><a
                                                        href="{{ route('productlist-subcategory', ['slug' => $category->slug, 'sub_slug' => $subcategory->slug, 'title' => $category->title, 'subtitle' => $subcategory->title]) }}">{{ $subcategory->title }}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </li>
                                @else
                                    <li><a
                                            href="{{ route('productlist-category', ['slug' => $category->slug, 'title' => $category->title]) }}">{{ $category->title }}</a>
                                    </li>
                                    @endif
                                    @endforeach
                                </ul>
                            </div>
                            <!--/ End Single Widget -->
                            <!-- Shop By Price -->
                            <div class="single-widget range">
                                <h3 class="title">價格區間</h3>
                                <div class="price-filter">
                                    <div class="price-filter-inner">
                                        @php
                                            $max = DB::table('products')->max('price');
                                            // dd($max);
                                        @endphp
                                        <div id="slider-range" data-min="0" data-max="{{ $max }}"
                                            data-currency="$"></div>
                                        <div class="product_filter">
                                            <button type="submit" class="filter_button">送出</button>
                                            <div class="label-input">
                                                <input style="" type="text" id="amount" readonly />
                                                <input type="hidden" name="price_range" id="price_range" value="@if (!empty($_GET['price'])) {{ $_GET['price'] }} @endif" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--/ End Shop By Price -->
                            <!-- Single Widget -->
                            <div class="single-widget recent-post">
                                <h3 class="title">特惠推薦</h3>
                                {{-- {{dd($recent_products)}} --}}
                                @foreach ($recent_products as $product)
                                    <!-- Single Post -->
                                    @php
                                        $photo = explode(',', $product->photo);
                                    @endphp
                                    <div class="single-post first">
                                        <div class="image">
                                            <img src="{{ $photo[0] }}" alt="{{ $photo[0] }}">
                                        </div>
                                        <div class="content">
                                            <h5><a
                                                    href="{{ route('product-detail', $product->slug) }}">{{ $product->title }}</a>
                                            </h5>
                                            <p class="price"><del class="text-muted">${{ $product->price }}</del>
                                                ${{ $product->special_price }} </p>
                                        </div>
                                    </div>
                                    <!-- End Single Post -->
                                @endforeach
                            </div>
                            <!--/ End Single Widget -->
                        </div>
                    </div>
                    <div class="col-lg-9 col-md-8 col-12">
                        <div class="row">
                            <div class="col-12">
                                <!-- Shop Top -->
                                <div class="shop-top">
                                    <div class="shop-shorter">
                                        <div class="single-shorter">
                                            <label>Show :</label>
                                            <select class="show" name="show" onchange="this.form.submit();">
                                                <option value="">Default</option>
                                                <option value="9" @if (!empty($_GET['show']) && $_GET['show'] == '9') selected @endif>09</option>
                                                <option value="15" @if (!empty($_GET['show']) && $_GET['show'] == '15') selected @endif>15</option>
                                                <option value="21" @if (!empty($_GET['show']) && $_GET['show'] == '21') selected @endif>21</option>
                                                <option value="30" @if (!empty($_GET['show']) && $_GET['show'] == '30') selected @endif>30</option>
                                            </select>
                                        </div>
                                        <div class="single-shorter">
                                            <label>Sort By :</label>
                                            <select class='sortBy' name='sortBy' onchange="this.form.submit();">
                                                <option value="">Default</option>
                                                <option value="title" @if (!empty($_GET['sortBy']) && $_GET['sortBy'] == 'title') selected @endif>Name</option>
                                                <option value="price" @if (!empty($_GET['sortBy']) && $_GET['sortBy'] == 'price') selected @endif>Price</option>
                                                <option value="category" @if (!empty($_GET['sortBy']) && $_GET['sortBy'] == 'category') selected @endif>Category</option>
                                                <option value="brand" @if (!empty($_GET['sortBy']) && $_GET['sortBy'] == 'brand') selected @endif>Brand</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <!--/ End Shop Top -->
                            </div>
                        </div>
                        <div class="row">
                            @if (!empty($products))
                                @foreach ($products as $product)
                                    <!-- Start Single List -->
                                    <div class="col-12">
                                        <div class="row">
                                            <div class="col-lg-4 col-md-6 col-sm-6">
                                                <div class="single-product">
                                                    <div class="product-img">
                                                        <a href="{{ route('product-detail', $product->slug) }}">
                                                            @php
                                                                $photo = explode(',', $product->photo);
                                                            @endphp
                                                            <img class="default-img" src="{{ $photo[0] }}"
                                                                alt="{{ $photo[0] }}">
                                                            <img class="hover-img" src="{{ $photo[0] }}"
                                                                alt="{{ $photo[0] }}">
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-8 col-md-6 col-12">
                                                <div class="list-content row">
                                                    <div class="product-content col-8">
                                                        <h3 class="title"><a
                                                                href="{{ route('product-detail', $product->slug) }}">{{ $product->title }}</a>
                                                        </h3>
                                                        <p class="des pt-2">{!! html_entity_decode($product->summary) !!}</p>
                                                    </div>
                                                    <div
                                                        class="product-price col-4 d-flex d-flex align-items-end flex-column">
                                                        <del>原價 ${{ $product->price }}</del>
                                                        <h5 class="text-danger">特價
                                                            ${{ $product->special_price }}</h5>
                                                        <a class="btn cart add-to-cart"
                                                            @if (!empty(Auth::user()->id))
                                                                data-user_id="{{ Auth::user()->id }}"
                                                            @endif
                                                            data-product_id="{{ $product->id }}">加入購物車</a>
                                                        <a class="btn cart add-to-wishlist"
                                                            @if (!empty(Auth::user()->id))
                                                                data-user_id="{{ Auth::user()->id }}"
                                                            @endif
                                                            data-product_id="{{ $product->id }}"><i class=" ti-heart ">
                                                                加入收藏</i></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Single List -->
                                @endforeach
                            @else
                                <h4 class="text-warning" style="margin:100px auto;">目前沒有符合的商品</h4>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--/ End Product Style 1  -->
    </form>

@endsection
@push('styles')
    <style>
        .pagination {
            display: inline-flex;
        }

        .filter_button {
            /* height:20px; */
            text-align: center;
            background: #F7941D;
            padding: 8px 16px;
            margin-top: 10px;
            color: white;
        }

        .add-to-wishlist {
            cursor: pointer;
        }

        .add-to-cart {
            cursor: pointer;
        }
    </style>
@endpush
@push('scripts')
    <!-- Sweetalert JS -->
    <script src="{{asset('frontend/js/sweetalert.min.js')}}"></script>

    <script>
        $(document).ready(function() {
            /*----------------------------------------------------*/
            /*  Jquery Ui slider js
            /*----------------------------------------------------*/
            if ($("#slider-range").length > 0) {
                const max_value = parseInt($("#slider-range").data('max')) || 500;
                const min_value = parseInt($("#slider-range").data('min')) || 0;
                const currency = $("#slider-range").data('currency') || '';
                let price_range = min_value + '-' + max_value;
                if ($("#price_range").length > 0 && $("#price_range").val()) {
                    price_range = $("#price_range").val().trim();
                }

                let price = price_range.split('-');
                $("#slider-range").slider({
                    range: true,
                    min: min_value,
                    max: max_value,
                    values: price,
                    slide: function(event, ui) {
                        $("#amount").val(currency + ui.values[0] + " -  " + currency + ui.values[1]);
                        $("#price_range").val(ui.values[0] + "-" + ui.values[1]);
                    }
                });
            }
            if ($("#amount").length > 0) {
                const m_currency = $("#slider-range").data('currency') || '';
                $("#amount").val(m_currency + $("#slider-range").slider("values", 0) +
                    "  -  " + m_currency + $("#slider-range").slider("values", 1));
            }
        })

        $(document).ready(function() {
            $('.minus').click(function() {
                var $input = $(this).parent().find('input');
                var count = parseInt($input.val()) - 1;
                count = count < 1 ? 1 : count;
                $input.val(count);
                $input.change();
                return false;
            });
            $('.plus').click(function() {
                var $input = $(this).parent().find('input');
                $input.val(parseInt($input.val()) + 1);
                $input.change();
                return false;
            });
        });

        $('.add-to-cart').on('click', function() {
            // console.log(this.getAttribute("data-productid"));
            var user_id = this.getAttribute("data-user_id");
            var product_id = this.getAttribute("data-product_id");

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                method: 'POST',
                url: '/zshop/add-to-cart',
                data: {
                    user_id: user_id,
                    product_id: product_id
                },
                success: function(response) {
                    // document.location.reload(true);
                    // console.log(response);
                    alert(response);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert(textStatus + " " + errorThrown);
                    // console.error(textStatus + " " + errorThrown);
                }
            });
        })

        $('.add-to-wishlist').on('click', function() {
            // console.log(this.getAttribute("data-productid"));
            var user_id = this.getAttribute("data-user_id");
            var product_id = this.getAttribute("data-product_id");

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                method: 'POST',
                url: '/zshop/user/add-to-wishlist',
                data: {
                    user_id: user_id,
                    product_id: product_id
                },
                success: function(response) {
                    // document.location.reload(true);
                    // console.log(response);
                    alert(response);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert(textStatus + " " + errorThrown);
                    // console.error(textStatus + " " + errorThrown);
                }
            });
        })
    </script>

@endpush
