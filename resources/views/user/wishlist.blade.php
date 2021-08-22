@extends('layouts.main')

@section('title', 'ZShop - 收藏清單')

@section('main-content')
<div class="container">
    <div class="card shadow mb-4">
        <div class="row">
            <div class="col-md-12">
                @include('layouts.notification')
            </div>
        </div>
        <div class="card-header py-3">
            {{-- <h4 class=" font-weight-bold">Profile</h4> --}}



            <ul class="nav nav-tabs nav-fill">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('user-profile') }}">個人中心</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('user-reward-money') }}">購物金</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('user-orders') }}">訂單查詢</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('user-returned') }}">退貨查詢</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="#">收藏清單</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('user-qa-center') }}">問答中心</a>
                </li>
            </ul>



            <ul class="breadcrumbs">
                <li><a href="" style="color:#999">會員中心</a></li>
                <li><a href="" class="active text-primary">收藏清單</a></li>
            </ul>
        </div>
        <table class="table shopping-summery">
            <thead>
                <tr class="main-hading">
                    <th>商品圖片</th>
                    <th>商品名稱</th>
                    <th class="text-center">單價</th>
                    <th class="text-center">加入購物車</th>
                    <th class="text-center">移除項目</th>
                </tr>
            </thead>
            <tbody>
                @if ($wishlist)
                @foreach ($wishlist as $key => $wishlist)
                <tr>
                    @php
                    $photo = explode(',', $wishlist->product['photo']);
                    @endphp
                    <td class="image" data-title="No"><img src="{{ $photo[0] }}" alt="{{ $photo[0] }}">
                    </td>
                    <td class="product-des text-center" data-title="Description">
                        <p class="product-name"><a
                                href="{{ route('product-detail', $wishlist->product['slug']) }}">{{ $wishlist->product['title'] }}</a>
                        </p>
                        <p class="product-des">{!! $wishlist['summary'] !!}</p>
                    </td>
                    <td class="total-amount" data-title="Total">
                        <span>${{ $wishlist->product['special_price'] }}</span>
                    </td>
                    <td class="text-center">
                        <a class="add-to-cart" @if (!empty(Auth::user()->id)) data-user_id="{{ Auth::user()->id }}"
                            @endif
                            data-product_id="{{ $wishlist->product->id }}"><i class="ti-shopping-cart"></i></a>
                    </td>

                    <td class="text-center">
                        <a class="remove-item" @if (!empty(Auth::user()->id)) data-user_id="{{ Auth::user()->id }}"
                            @endif
                            data-product_id="{{ $wishlist->product->id }}"><i class="ti-trash"></i></a>
                    </td>

                    {{-- <td class="action" data-title="Remove"><a
                                        href="{{ route('wishlist-delete', $wishlist->id) }}"><i
                        class="ti-trash remove-icon"></i></a>
                    </td> --}}
                    {{-- <a class="btn cart add-to-cart" @if (!empty(Auth::user()->id)) data-user_id="{{ Auth::user()->id }}"
                    @endif
                    data-product_id="{{ $product->id }}">加入購物車</a> --}}
                    {{-- <a href="{{ route('add-to-wishlist', $product->slug) }}"
                    class="btn cart" data-id="{{ $product->id }}"><i class=" ti-heart "> 加入收藏</i></a> --}}
                    {{-- <a class="btn cart add-to-wishlist" @if (!empty(Auth::user()->id)) data-user_id="{{ Auth::user()->id }}"
                    @endif
                    data-product_id="{{ $product->id }}"><i class=" ti-heart ">
                        加入收藏</i></a> --}}
                </tr>
                @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>


@endsection

<style>
    .breadcrumbs {
        list-style: none;
    }

    .breadcrumbs li {
        float: left;
        margin-right: 10px;
    }

    .breadcrumbs li a:hover {
        text-decoration: none;
    }

    .breadcrumbs li .active {
        color: red;
    }

    .breadcrumbs li+li:before {
        content: "/\00a0";
    }

    i {
        font-size: 14px;
        padding-right: 8px;
    }
</style>
@push('styles')
<style>
    .add-to-wishlist {
        cursor: pointer;
    }

    .remove-item {
        cursor: pointer;
    }
</style>
@endpush
@push('scripts')
<script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>
<!-- Sweetalert JS -->
<script src="{{asset('frontend/js/sweetalert.min.js')}}"></script>
<script>
    // $('#lfm').filemanager('image');

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

        $('.remove-item').on('click', function() {
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
                url: '/zshop/user/remove-wishlist',
                data: {
                    user_id: user_id,
                    product_id: product_id
                },
                success: function(response) {
                    // document.location.reload(true);
                    // console.log(response);
                    alert(response);
                    document.location.reload(true);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert(textStatus + " " + errorThrown);
                    // console.error(textStatus + " " + errorThrown);
                }
            });
        })
</script>
@endpush