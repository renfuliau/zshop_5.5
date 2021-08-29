@extends('layouts.main')

@section('title', 'Zshop - 購物車')

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
                        <li class="active"><a href="javascript:void(0);">購物車</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Breadcrumbs -->

<!-- Shopping Cart -->
<div class="shopping-cart section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <!-- Shopping Summery -->
                @if (!empty($carts))
                <table class="table shopping-summery">
                    <thead>
                        <tr class="main-hading">
                            <th>商品圖片</th>
                            <th>商品名稱</th>
                            <th class="text-center">單價</th>
                            <th class="text-center">數量</th>
                            <th class="text-center">小計</th>
                            <th class="text-center"><i class="ti-trash remove-icon"></i></th>
                        </tr>
                    </thead>
                    <tbody id="cart_item_list">
                        {{ csrf_field() }}
                        @foreach ($carts as $key => $value)
                        <tr>
                            @php
                            $photo = explode(',', $value->product['photo']);
                            @endphp
                            <td class="image" data-title="No"><img src="{{ $photo[0] }}" alt="{{ $photo[0] }}"></td>
                            <td class="product-des" data-title="Description">
                                <p class="product-name"><a href="{{ route('product-detail', $value->product['slug']) }}"
                                        target="_blank">{{ $value->product['title'] }}</a></p>
                                <p class="product-des">{!! $value['summary'] !!}</p>
                            </td>
                            <td class="price" data-title="Price">
                                <input class="price_key" type="number" value="{{ $key }}" style="display:none;">

                                <input class="price_input[{{ $key }}]" type="number"
                                    value="{{ $value->product['special_price'] }}" style="display:none;">
                                <span>${{ $value->product['special_price'] }}</span>
                            </td>
                            <td class="qty" data-title="Qty">
                                <!-- Input Order -->
                                <div class="input-group">
                                    <div class="button minus">
                                        <button type="button" class="btn btn-primary btn-number" disabled="disabled"
                                            data-type="minus" data-field="quant[{{ $key }}]">
                                            <i class="ti-minus"></i>
                                        </button>
                                    </div>
                                    <input type="text" name="quant[{{ $key }}]" class="input-number" autocomplete="off"
                                        data-min="1" data-max="{{ $value->product->stock }}" data-product_id=""
                                        data-price="{{ $value->product['special_price'] }}"
                                        value="{{ $value->quantity }}">
                                    <input type="hidden" name="qty_id[]" value="{{ $value->id }}">
                                    <div class="button plus">
                                        <button type="button" class="btn btn-primary btn-number" data-type="plus"
                                            data-field="quant[{{ $key }}]">
                                            <i class="ti-plus"></i>
                                        </button>
                                    </div>
                                </div>
                                <!--/ End Input Order -->
                            </td>
                            <td class="total-amount cart_single_price" data-title="Total"><span
                                    class="money">${{ $value->product['special_price'] * $value->quantity }}</span>
                            </td>
                            <td class="text-center">
                                <a class="remove-item" @if (!empty(Auth::user()->id))
                                    data-user_id="{{ Auth::user()->id }}"
                                    @endif
                                    data-product_id="{{ $value->product['id'] }}"><i class="ti-trash"></i></a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="text-center">
                    購物車是空的 <a href="{{ route('index') }}" style="color:blue;">繼續選購</a>
                </div>
                @endif
                <!--/ End Shopping Summery -->
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <!-- Total Amount -->
                <div class="total-amount">
                    <div class="row">
                        <div class="col-lg-8 col-md-5 col-12">
                            <div class="left">
                                @if (!empty($coupon1) && !empty($coupon2))
                                <h6 class="mb-3">選擇優惠:</h6>
                                <div class="col">
                                    <div class="form-check">
                                        <span id="coupon{{ $coupon1->coupon_type }}_value"
                                            style="display:none">{{ $coupon1->coupon_amount }}</span>
                                        <input class="form-check-input coupon_type{{ $coupon1->coupon_type }}"
                                            type="radio" name="coupon-option"
                                            id="coupon_type{{ $coupon1->coupon_type }}" autocomplete="off"
                                            value="{{ $coupon1->coupon_amount }}" data-subtotal="{{ $total }}"
                                            data-coupon1_amount={{ $coupon1->coupon_amount }}>
                                        <label class="form-check-label" for="exampleRadios1">
                                            {{ $coupon1->name }}
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input coupon_type{{ $coupon2->coupon_type }}"
                                            type="radio" name="coupon-option"
                                            id="coupon_type{{ $coupon2->coupon_type }}" autocomplete="off"
                                            value="{{ $coupon2->coupon_amount }}" data-subtotal="{{ $total }}"
                                            data-coupon2_amount={{ $coupon2->coupon_amount }}>
                                        <label class="form-check-label" for="exampleRadios2">
                                            {{ $coupon2->name }}
                                        </label>
                                    </div>
                                </div>
                                @endif
                                @if (!empty($coupon1) && empty($coupon2))
                                <div class="coupon">
                                    <h6>選擇優惠:</h6>
                                    <select id="coupon_name" class="custom-select">
                                        <option selected value="1">{{ $coupon1->name }}</option>
                                        <option value="2">{{ $coupon2->name }}</option>
                                    </select>
                                </div>
                                @endif
                                @if (empty($coupon1) && !empty($coupon2))
                                <div class="coupon">
                                    <h6>選擇優惠:</h6>
                                    <select id="coupon_name" class="custom-select">
                                        <option selected value="1">{{ $coupon1->name }}</option>
                                        <option value="2">{{ $coupon2->name }}</option>
                                    </select>
                                </div>
                                @endif
                                @if (!empty($carts))
                                <h6>折抵購物金:</h6>
                                <h6><small>(目前購物金:{{ $user_info->reward_money }})</small></h6>
                                <input id="reward_money" type="number" min="0" max="{{ $user_info->reward_money }}"
                                    oninput="validity.valid||(value='');"
                                    onkeypress="return event.charCode <= {{ $user_info->reward_money }}" value="0"
                                    autocomplete="off">
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-7 col-12">
                            <div class="right">
                                <ul>
                                    @if (!empty($total))
                                    <li class="subtotal" data-subtotal="{{ $total }}">小計
                                        <span class="subtotal_span">$ {{ $total }}</span>
                                    </li>
                                    @endif

                                    @if (!empty($coupon1))
                                    <li class="coupon1_price" data-coupon1="{{ $coupon1['coupon_amount'] }}">
                                        折扣<span>0</span>
                                    </li>
                                    <li class="reward_money">
                                        使用購物金<span>0</span>
                                    </li>
                                    @endif
                                    @if (!empty($carts))
                                    <li class="total" style="border-style: solid hidden hidden hidden"
                                        data-total="{{ $total }}">
                                        總計<span>$ {{ $total }}</span>
                                    </li>
                                    @endif
                                    @if (!empty($coupon2))
                                    <li class="coupon2_price" data-coupon2="{{ $coupon1['coupon_amount'] }}">
                                        贈送購物金<span>0</span>
                                    </li>
                                    @endif
                                    <span class="usd"></span>
                                </ul>
                                @if (!empty($carts))
                                <div class="button5">
                                    <a href="{{ route('checkout') }}" class="btn go-to-checkout"
                                        data-total="{{ $total }}" data-reward_money_amount="0">前往結帳</a>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <!--/ End Total Amount -->
            </div>
        </div>
    </div>
</div>
<!--/ End Shopping Cart -->

@endsection
@push('styles')
<style>
    li.shipping {
        display: inline-flex;
        width: 100%;
        font-size: 14px;
    }

    li.shipping .input-group-icon {
        width: 100%;
        margin-left: 10px;
    }

    .input-group-icon .icon {
        position: absolute;
        left: 20px;
        top: 0;
        line-height: 40px;
        z-index: 3;
    }

    .form-select {
        height: 30px;
        width: 100%;
    }

    .form-select .nice-select {
        border: none;
        border-radius: 0px;
        height: 40px;
        background: #f6f6f6 !important;
        padding-left: 45px;
        padding-right: 40px;
        width: 100%;
    }

    .list li {
        margin-bottom: 0 !important;
    }

    .list li:hover {
        background: #F7941D !important;
        color: white !important;
    }

    .form-select .nice-select::after {
        top: 14px;
    }

    .remove-item {
        cursor: pointer;
    }
</style>
@endpush
@push('scripts')
<script src="{{ asset('frontend/js/nice-select/js/jquery.nice-select.min.js') }}"></script>
<script src="{{ asset('frontend/js/select2/js/select2.min.js') }}"></script>
<!-- Sweetalert JS -->
<script src="{{ asset('frontend/js/sweetalert.min.js') }}"></script>
<script>
    $(document).ready(function() {
            $("select.select2").select2();
        });
        $('select.nice-select').niceSelect();
</script>
<script>
    $('.input-number').on('change', function() {
            // console.log("onchangeValue:",this.value);
            // console.log("onchangeProductID:",this.getAttribute("data-productid"));
            var new_qty = this.value;
            var product_id = this.getAttribute("data-productid");

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                method: 'POST',
                url: '/zshop/changeProductQty',
                data: {
                    product_id: product_id,
                    new_qty: new_qty
                },
                success: function(res) {
                    document.location.reload(true);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error(textStatus + " " + errorThrown);
                }
            });
        });

        $('.remove_item').on('click', function() {
            // console.log(this.getAttribute("data-productid"));
            var product_id = this.getAttribute("data-productid");

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                method: 'POST',
                url: '/zshop/remove_item',
                data: {
                    product_id: product_id
                },
                success: function(res) {
                    document.location.reload(true);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error(textStatus + " " + errorThrown);
                }
            });
        })
</script>

@endpush