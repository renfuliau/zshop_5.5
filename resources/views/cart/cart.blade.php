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
                        <li><a href="{{ route('index') }}">首頁<i class="ti-arrow-right"></i></a></li>
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
                        <form action="{{ route('cart.update') }}" method="POST">
                            {{ csrf_field() }}
                            @if ($carts)
                                @foreach ($carts as $key => $value)
                                    <tr>
                                        @php
                                            $photo = explode(',', $value->product['photo']);
                                        @endphp
                                        <td class="image" data-title="No"><img src="{{ $photo[0] }}"
                                                alt="{{ $photo[0] }}"></td>
                                        <td class="product-des" data-title="Description">
                                            <p class="product-name"><a
                                                    href="{{ route('product-detail', $value->product['slug']) }}"
                                                    target="_blank">{{ $value->product['title'] }}</a></p>
                                            <p class="product-des">{!! $value['summary'] !!}</p>
                                        </td>
                                        <td class="price" data-title="Price">
                                            <span>${{ $value->product['special_price'] }}</span>
                                        </td>
                                        <td class="qty" data-title="Qty">
                                            <!-- Input Order -->
                                            <div class="input-group">
                                                <div class="button minus">
                                                    <button type="button" class="btn btn-primary btn-number"
                                                        disabled="disabled" data-type="minus"
                                                        data-field="quant[{{ $key }}]">
                                                        <i class="ti-minus"></i>
                                                    </button>
                                                </div>
                                                <input type="text" name="quant[{{ $key }}]"
                                                    class="input-number" data-min="1" data-max="{{ $value->product->stock }}"
                                                    value="{{ $value->quantity }}">
                                                <input type="hidden" name="qty_id[]" value="{{ $value->id }}">
                                                <div class="button plus">
                                                    <button type="button" class="btn btn-primary btn-number"
                                                        data-type="plus" data-field="quant[{{ $key }}]">
                                                        <i class="ti-plus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <!--/ End Input Order -->
                                        </td>
                                        <td class="total-amount cart_single_price" data-title="Total"><span
                                                class="money">${{ $value->product['special_price'] * $value->quantity }}</span></td>

                                        <td class="action" data-title="Remove"><a
                                                href="{{ route('cart-delete', $value->id) }}"><i
                                                    class="ti-trash remove-icon"></i></a></td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td class="text-center">
                                        購物車是空的<a href="{{ route('product-grids') }}" style="color:blue;">繼續選購</a>

                                    </td>
                                </tr>
                            @endif

                        </form>
                    </tbody>
                </table>
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
                                            <input class="form-check-input" type="radio" name="receipt_option"
                                                id="receipt_option" value="二聯式發票" checked>
                                            <label class="form-check-label" for="exampleRadios1">
                                                {{ $coupon1->name }}
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="receipt_option"
                                                id="receipt_option" value="三聯式發票">
                                            <label class="form-check-label" for="exampleRadios2">
                                                {{ $coupon2->name }}
                                            </label>
                                        </div>
                                    </div>
                                    {{-- <div class="coupon">
                                        <h6>選擇優惠:</h6>
                                        <input type="hidden" name="coupon_value" value="" />
                                        <select id="coupon_name" class="custom-select">
                                            <option selected value="1">{{ $coupon1->name }}</option>
                                            <option value="2">{{ $coupon2->name }}</option>
                                        </select>
                                    </div> --}}
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
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-7 col-12">
                            <div class="right">
                                <ul>
                                    <li class="order_subtotal" data-price="{{ $total }}">小計
                                        <span>${{ $total }}</span>
                                    </li>
                                    @if ($coupon1)
                                        <li class="coupon_price" data-price="{{ $coupon1['value'] }}">
                                            折扣<span id="coupon1_value">$ {{ $coupon1['value'] * -1 }}</span>
                                        </li>
                                    @endif
                                    @if ($coupon2)
                                        <li class="coupon_price" data-price="{{ $coupon1['value'] }}">
                                            贈送購物金<span id="coupon2_value">$ {{ $coupon1['value'] * -1 }}</span>
                                        </li>
                                    @endif
                                    {{-- @php
                                        $total_amount = {{ $total }};
                                        if (session()->has('coupon')) {
                                            $total_amount = $total_amount - Session::get('coupon')['value'];
                                        }
                                    @endphp
                                    @if (session()->has('coupon'))
                                        <li class="last" id="order_total_price">You
                                            Pay<span>${{ number_format($total_amount, 2) }}</span></li>
                                    @else
                                        <li class="last" id="order_total_price">You
                                            Pay<span>${{ number_format($total_amount, 2) }}</span></li>
                                    @endif --}}
                                </ul>
                                <div class="button5">
                                    <a href="{{ route('checkout') }}" class="btn">前往結帳</a>
                                </div>
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

</style>
@endpush
@push('scripts')
<script src="{{ asset('frontend/js/nice-select/js/jquery.nice-select.min.js') }}"></script>
<script src="{{ asset('frontend/js/select2/js/select2.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $("select.select2").select2();
    });
    $('select.nice-select').niceSelect();
</script>
<script>
    $(document).ready(function() {
        $('.shipping select[name=shipping]').change(function() {
            let cost = parseFloat($(this).find('option:selected').data('price')) || 0;
            let subtotal = parseFloat($('.order_subtotal').data('price'));
            let coupon = parseFloat($('.coupon_price').data('price')) || 0;
            // alert(coupon);
            $('#order_total_price span').text('$' + (subtotal + cost - coupon).toFixed(2));
        });

        $("#coupon_name").change(function() {
            // 宣告product_num 為選取元素的value
            var product_num = $(this).val();
            // 宣告class_num 等於字串轉數字 +1
            // var class_num = parseInt(product_num) + 1;
            // 透過name選取product_id,並寫入新的值
            $("input[name='coupon_value']").val(product_num);

            // 透過name選取product_class_id,並寫入product_num+1
            // $("input[name='product_class_id']").val(class_num);

            // 透過id選取product_id,並寫入新的值
            // $("#price_2").val("3456");

            alert("after change product_id value = " + $("input[name='coupon_value']").val());
            // alert("after change price_2 value = " + $("#price_2").val());
        });
    });
</script>

@endpush
