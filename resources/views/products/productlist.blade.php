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
                        <li><a href="{{ route('index') }}">{{ __('frontend.index') }}<i class="ti-arrow-right"></i></a>
                        </li>
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
                            <h3 class="title">{{ __('frontend.product-category') }}</h3>
                            <ul class="categor-list">
                                @foreach ($categories as $category)
                                @if ($category->subcategory->count() > 0)
                                @if (App::getLocale() == 'zh-tw')
                                    <li>
                                        <a
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
                                    <li>
                                        <a
                                            href="{{ route('productlist-category', ['slug' => $category->slug, 'title' => $category->title]) }}">{{ $category->slug }}</a>
                                        <ul>
                                            @foreach ($category->subcategory as $subcategory)
                                            <li><a
                                                    href="{{ route('productlist-subcategory', ['slug' => $category->slug, 'sub_slug' => $subcategory->slug, 'title' => $category->title, 'subtitle' => $subcategory->title]) }}">{{ $subcategory->slug }}</a>
                                            </li>
                                            @endforeach
                                        </ul>
                                    </li>
                                @endif
                                @else
                                @if (App::getLocale() == 'zh-tw')
                                    <li><a
                                            href="{{ route('productlist-category', ['slug' => $category->slug, 'title' => $category->title]) }}">{{ $category->title }}</a>
                                    </li>
                                @else
                                    <li><a
                                            href="{{ route('productlist-category', ['slug' => $category->slug, 'title' => $category->title]) }}">{{ $category->slug }}</a>
                                    </li>
                                @endif
                                @endif
                                @endforeach
                            </ul>
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
                                    {{-- <div class="single-shorter">
                                        <label>Show :</label>
                                        <select class="show" name="show" onchange="this.form.submit();">
                                            <option value="">Default</option>
                                            <option value="9" @if (!empty($_GET['show']) && $_GET['show']=='9' )
                                                selected @endif>09</option>
                                            <option value="15" @if (!empty($_GET['show']) && $_GET['show']=='15' )
                                                selected @endif>15</option>
                                            <option value="21" @if (!empty($_GET['show']) && $_GET['show']=='21' )
                                                selected @endif>21</option>
                                            <option value="30" @if (!empty($_GET['show']) && $_GET['show']=='30' )
                                                selected @endif>30</option>
                                        </select>
                                    </div>
                                    <div class="single-shorter">
                                        <label>Sort By :</label>
                                        <select class='sortBy' name='sortBy' onchange="this.form.submit();">
                                            <option value="">Default</option>
                                            <option value="title" @if (!empty($_GET['sortBy']) &&
                                                $_GET['sortBy']=='title' ) selected @endif>Name</option>
                                            <option value="price" @if (!empty($_GET['sortBy']) &&
                                                $_GET['sortBy']=='price' ) selected @endif>Price</option>
                                            <option value="category" @if (!empty($_GET['sortBy']) &&
                                                $_GET['sortBy']=='category' ) selected @endif>Category</option>
                                            <option value="brand" @if (!empty($_GET['sortBy']) &&
                                                $_GET['sortBy']=='brand' ) selected @endif>Brand</option>
                                        </select>
                                    </div> --}}
                                </div>
                            </div>
                            <!--/ End Shop Top -->
                        </div>
                    </div>
                    <div class="row">
                        @if (! $products->isEmpty())
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
                                                <img class="default-img" src="{{ $photo[0] }}" alt="{{ $photo[0] }}">
                                                <img class="hover-img" src="{{ $photo[0] }}" alt="{{ $photo[0] }}">
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
                                        <div class="product-price col-4 d-flex d-flex align-items-end flex-column">
                                            <del>{{ __('frontend.product-price') }} $ {{ $product->price }}</del>
                                            <h5 class="text-danger">{{ __('frontend.product-special-price') }}
                                                $ {{ $product->special_price }}</h5>
                                            <a class="btn cart add-to-cart" @if (!empty(Auth::user()->id))
                                                data-user_id="{{ Auth::user()->id }}"
                                                @endif
                                                data-product_id="{{ $product->id }}">{{ __('frontend.add-to-cart') }}</a>
                                            <a class="btn cart add-to-wishlist" @if (!empty(Auth::user()->id))
                                                data-user_id="{{ Auth::user()->id }}"
                                                @endif
                                                data-product_id="{{ $product->id }}"><i class=" ti-heart ">
                                                    </i>{{ __('frontend.add-to-wishlist') }}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Single List -->
                        @endforeach
                        @else
                        <h4 class="text-warning" style="margin:100px auto;">{{ __('frontend.product-no-products') }}</h4>
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
<script src="{{ asset('frontend/js/sweetalert.min.js') }}"></script>

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
        
        $('.add-to-cart').click(function() {

            var product_id = $(this).data("product_id");
            console.log(product_id);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                method: 'POST',
                url: '/zshop/add-to-cart',
                data: {
                    product_id: product_id
                },
                success: function(res) {
                    console.log(res);
                    $('.cartTotalQuantity').text(res['qty']);
                    alert(res['message']);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error(textStatus + " " + errorThrown);
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