@extends('layouts.main')

@section('title', 'Checkout page')

@section('main-content')

    <!-- Breadcrumbs -->
    <div class="breadcrumbs">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="bread-inner">
                        <ul class="bread-list">
                            <li><a href="{{ route('index') }}">首頁<i class="ti-arrow-right"></i></a></li>
                            <li><a href="{{ route('cart') }}">購物車<i class="ti-arrow-right"></i></a></li>
                            <li class="active"><a href="javascript:void(0)">填寫資料</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Breadcrumbs -->

    <!-- Start Checkout -->
    <section class="shop checkout section">
        <div class="container">
            <form class="form" method="POST" action="{{ route('checkoutStore') }}">
                {{ csrf_field() }}
                <div class="row">

                    <div class="col-lg-8 col-12">
                        <div class="checkout-form">
                            <h2>填寫收件人資訊</h2>
                            <!-- Form -->
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-12">
                                    <div class="form-group">
                                        <label>收件人姓名<span>*</span></label>
                                        <input type="text" name="first_name" placeholder=""
                                            value="{{ old('first_name') }}" value="{{ old('first_name') }}">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-12">
                                    <div class="form-group">
                                        <label>收件人電話<span>*</span></label>
                                        <input type="number" name="phone" placeholder="" required
                                            value="{{ old('phone') }}"
                                            style="-webkit-appearance: none; -moz-appearance: textfield;">
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-12">
                                    <div class="form-group">
                                        <label>郵遞區號</label>
                                        <input type="text" name="post_code" placeholder="" value="{{ old('post_code') }}">
                                    </div>
                                </div>
                                <div class="col-lg-9 col-md-9 col-12">
                                    <div class="form-group">
                                        <label>收件人地址<span>*</span></label>
                                        <input type="text" name="address1" placeholder="" value="{{ old('address1') }}">
                                    </div>
                                </div>

                            </div>
                            <!--/ End Form -->
                        </div>
                    </div>
                    <div class="col-lg-4 col-12">
                        <div class="order-details">
                            <!-- Order Widget -->
                            <div class="single-widget">
                                <h2>訂單明細</h2>
                                <div class="content">
                                    <ul>
                                        <li class="order_subtotal" data-price="">訂單金額
                                            <span>$</span>
                                        </li>
                                        {{-- <li class="shipping">
                                        Shipping Cost
                                        @if (count(Helper::shipping()) > 0 && Helper::cartCount() > 0)
                                            <select name="shipping" class="nice-select">
                                                <option value="">Select your address</option>
                                                @foreach (Helper::shipping() as $shipping)
                                                    <option value="{{ $shipping->id }}" class="shippingOption"
                                                        data-price="{{ $shipping->price }}">{{ $shipping->type }}:
                                                        ${{ $shipping->price }}</option>
                                                @endforeach
                                            </select>
                                        @else
                                            <span>Free</span>
                                        @endif
                                    </li> --}}

                                        @if (session('coupon'))
                                            <li class="coupon_price" data-price="{{ session('coupon')['value'] }}">You
                                                Save<span>${{ number_format(session('coupon')['value'], 2) }}</span></li>
                                        @endif
                                        @php
                                            // $total = Helper::totalCartPrice();
                                            if (session('coupon')) {
                                                $total = $total - session('coupon')['value'];
                                            }
                                        @endphp
                                        @if (session('coupon'))
                                            <li class="last" id="order_total_price">
                                                Total<span>${{ number_format($total, 2) }}</span></li>
                                        @else
                                            <li class="last" id="order_total_price">
                                                Total<span>${{ number_format($total, 2) }}</span></li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                            <!--/ End Order Widget -->
                            <!-- Order Widget -->
                            <div class="single-widget">
                                <h2>Payments</h2>
                                <div class="content">
                                    <div class="checkbox">
                                        {{-- <label class="checkbox-inline" for="1"><input name="updates" id="1" type="checkbox"> Check Payments</label> --}}
                                        <form-group>
                                            <input name="payment_method" type="radio" value="cod"> <label> Cash On
                                                Delivery</label><br>
                                            <input name="payment_method" type="radio" value="paypal"> <label> PayPal</label>
                                        </form-group>

                                    </div>
                                </div>
                            </div>
                            <!--/ End Order Widget -->
                            <!-- Payment Method Widget -->
                            <div class="single-widget payement">
                                <div class="content">
                                    <img src="{{ 'backend/img/payment-method.png' }}" alt="#">
                                </div>
                            </div>
                            <!--/ End Payment Method Widget -->
                            <!-- Button Widget -->
                            <div class="single-widget get-button">
                                <div class="content">
                                    <div class="button">
                                        <button type="submit" class="btn">確認結帳</button>
                                    </div>
                                </div>
                            </div>
                            <!--/ End Button Widget -->
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
    <!--/ End Checkout -->
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
        function showMe(box) {
            var checkbox = document.getElementById('shipping').style.display;
            // alert(checkbox);
            var vis = 'none';
            if (checkbox == "none") {
                vis = 'block';
            }
            if (checkbox == "block") {
                vis = "none";
            }
            document.getElementById(box).style.display = vis;
        }
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

        });
    </script>

@endpush
