@extends('layouts.main')

@section('title', 'ZShop - ' . $product_detail->title)

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
                        <li><a
                                href="{{ route('productlist', ['slug' => $product_detail->category->slug, 'title' => $product_detail->category->title]) }}">
                                {{ $product_detail->category->title }}<i class="ti-arrow-right"></i></a></li>
                        <li class="active"><a href="javascript:void(0);">{{ $product_detail->title }}</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Breadcrumbs -->

<!-- Shop Single -->
<section class="shop single section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <div class="col-lg-6 col-12">
                        <!-- Product Slider -->
                        <div class="product-gallery">
                            <!-- Images slider -->
                            <div class="flexslider-thumbnails">
                                <ul class="slides">
                                    @php
                                    $photo = explode(',', $product_detail->photo);
                                    // dd($photo);
                                    @endphp
                                    @foreach ($photo as $data)
                                    <li data-thumb="{{ $data }}" rel="adjustX:10, adjustY:">
                                        <img src="{{ $data }}" alt="{{ $data }}">
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                            <!-- End Images slider -->
                        </div>
                        <!-- End Product slider -->
                    </div>
                    <div class="col-lg-6 col-12">
                        <div class="product-des">
                            <!-- Description -->
                            <div class="short">
                                <h4>{{ $product_detail->title }}</h4>
                                <p class="description">{!! $product_detail->summary !!}</p>
                                <p class="price">
                                    <span class="discount">${{ $product_detail->special_price }}</span>
                                    <s>${{ $product_detail->price }}</s>
                                </p>
                            </div>

                            @if ($product_detail->size)
                            <div class="size mt-4">
                                <h4>Size</h4>
                                <ul>
                                    @php
                                    $sizes = explode(',', $product_detail->size);
                                    // dd($sizes);
                                    @endphp
                                    @foreach ($sizes as $size)
                                    <li><a href="#" class="one">{{ $size }}</a></li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                            <!--/ End Size -->
                            <!-- Product Buy -->
                            <div class="product-buy">
                                {{-- <form action="{{ route('add-to-cart') }}" method="POST"> --}}
                                {{-- {{ csrf_field() }} --}}

                                <div class="mt-4">
                                    {{-- <button type="submit" class="btn">加入購物車</button>
                                            <a href="{{ route('add-to-wishlist', $product_detail->slug) }}"
                                    class="btn min"><i class="ti-heart"></i></a> --}}
                                    <a class="btn cart add-to-cart text-white"
                                        data-product_id="{{ $product_detail->id }}">加入購物車</a>
                                    <a class="btn cart add-to-wishlist text-white" @if (!empty(Auth::user()->id))
                                        data-user_id="{{ Auth::user()->id }}"
                                        @endif
                                        data-product_id="{{ $product_detail->id }}"><i class=" ti-heart"></i></a>
                                </div>
                                {{-- </form> --}}
                                <p class="availability">庫存量 : @if ($product_detail->stock > 0)<span
                                        class="badge badge-success">{{ $product_detail->stock }}</span>@else
                                    <span class="badge badge-danger">{{ $product_detail->stock }}</span>
                                    @endif
                                </p>
                            </div>
                            <!--/ End Product Buy -->
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="product-info">
                            <div class="nav-main">
                                <!-- Tab Nav -->
                                <ul class="nav nav-tabs justify-content-center" id="myTab" role="tablist">
                                    <li class="nav-item">
                                        <h4>商品詳細資訊</h3>
                                    </li>
                                </ul>
                                <!--/ End Tab Nav -->
                            </div>
                            <div class="tab-content" id="myTabContent">
                                <!-- Description Tab -->
                                <div class="tab-pane fade show active" id="description" role="tabpanel">
                                    <div class="tab-single">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="single-des">
                                                    <p>{!! $product_detail->description !!}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--/ End Description Tab -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--/ End Shop Single -->

@endsection
@push('styles')
<style>
    /* Rating */
    .rating_box {
        display: inline-flex;
    }

    .star-rating {
        font-size: 0;
        padding-left: 10px;
        padding-right: 10px;
    }

    .star-rating__wrap {
        display: inline-block;
        font-size: 1rem;
    }

    .star-rating__wrap:after {
        content: "";
        display: table;
        clear: both;
    }

    .star-rating__ico {
        float: right;
        padding-left: 2px;
        cursor: pointer;
        color: #F7941D;
        font-size: 16px;
        margin-top: 5px;
    }

    .star-rating__ico:last-child {
        padding-left: 0;
    }

    .star-rating__input {
        display: none;
    }

    .star-rating__ico:hover:before,
    .star-rating__ico:hover~.star-rating__ico:before,
    .star-rating__input:checked~.star-rating__ico:before {
        content: "\F005";
    }
</style>
@endpush
@push('scripts')
<!-- Sweetalert JS -->
<script src="{{ asset('frontend/js/sweetalert.min.js') }}"></script>

<script>
    // $('.add-to-cart').on('click', function() {
        //     // console.log(this.getAttribute("data-productid"));
        //     var cart_qty = $('.cartTotalQuantity').text();
        //     var new_qty = parseInt(cart_qty) + 1;
        //     var user_id = this.getAttribute("data-user_id");
        //     var product_id = this.getAttribute("data-product_id");

        //     $.ajaxSetup({
        //         headers: {
        //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //         }
        //     });

        //     $.ajax({
        //         method: 'POST',
        //         url: '/zshop/add-to-cart',
        //         data: {
        //             user_id: user_id,
        //             product_id: product_id
        //         },
        //         success: function(response) {
        //             if (response['status'] == 0) {
        //                 $('.cartTotalQuantity').html(new_qty);
        //             }
        //             alert(response['message']);
        //         },
        //         error: function(jqXHR, textStatus, errorThrown) {
        //             alert(textStatus + " " + errorThrown);
        //             // console.error(textStatus + " " + errorThrown);
        //         }
        //     });
        // })

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
                    // console.log(typeof(new_qty), new_qty);
                    alert(response);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert(textStatus + " " + errorThrown);
                    // console.error(textStatus + " " + errorThrown);
                }
            });
        })

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
                url: '/zshop/ㄏ',
                data: {
                    product_id: product_id
                },
                success: function(res) {
                    $('.cartTotalQuantity').text(res);
                    alert('成功加入購物車');
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error(textStatus + " " + errorThrown);
                }
            });


        })
</script>

@endpush