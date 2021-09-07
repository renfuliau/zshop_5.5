@extends('layouts.main')

@section('title', 'ZShop - ' .  __('frontend.checkout'))

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
                        <li><a href="{{ route('cart') }}">{{ __('frontend.cart') }}<i class="ti-arrow-right"></i></a></li>
                        <li class="active"><a href="javascript:void(0)">{{ __('frontend.checkout') }}</a></li>
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
        <form class="form" method="POST" action="{{ route('checkout-store') }}">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-lg-12 col-12">
                    <div class="order-details">
                        <!-- Order Widget -->
                        <div class="single-widget">
                            <h2>{{ __('frontend.cart-order-detail') }}</h2>
                            <div class="content">
                                <ul>
                                    @foreach ($carts as $cart)
                                    <li class="order_subtotal" data-price="">{{ $cart->name }}
                                        <span>$ {{ $cart->price * $cart->quantity }}</span>
                                        <span>* {{ $cart->quantity }} = </span>
                                        <span>$ {{ $cart->price }}</span>
                                    </li>
                                    @endforeach
                                    @if (!empty($coupon) && $coupon->coupon_type == 1)
                                    <li class="coupon">{{ __('frontend.user-order-coupon') }}： {{ $coupon->name }}<span>$
                                            -{{ $coupon->coupon_amount }}</span></li>
                                    <li class="reward_money">{{ __('frontend.user-order-use-reward-money') }}： <span>$ -{{ $reward_money }}</span></li>
                                    <li class="total last" id="order_total_price">
                                        {{ __('frontend.total') }}<span>${{ $total - $coupon->coupon_amount - $reward_money }}</span>
                                    </li>
                                    @else
                                    <li class="reward_money">{{ __('frontend.user-order-use-reward-money') }}： <span>$ -{{ $reward_money }}</span></li>
                                    <li class="total last" id="order_total_price">
                                        {{ __('frontend.total') }}<span>${{ $total - $reward_money }}</span></li>
                                    @if (!empty($coupon) && $coupon->coupon_type == 2)
                                    <li class="coupon">{{ __('frontend.user-order-coupon') }}： {{ $coupon->name }}<span>$
                                            {{ $coupon->coupon_amount }}</span></li>
                                    @endif
                                    @endif
                                </ul>
                            </div>
                        </div>
                        <!--/ End Order Widget -->
                        <div class="single-widget checkout-form">
                            <h2>{{ __('frontend.user-order-received-info') }}</h2>
                            <!-- Form -->
                            <div class="row m-3">
                                <div class="col-lg-6 col-md-6 col-12">
                                    @if (!empty($coupon))
                                    <input type="hidden" name="coupon_id" value="{{ $coupon->id }}">
                                    @endif
                                    <input type="hidden" name="reward_money" value="{{ $reward_money }}">
                                    <div class="form-group">
                                        <label>{{ __('frontend.user-order-received-name') }}<span>*</span></label>
                                        <input type="text" name="name" required value="">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-12">
                                    <div class="form-group">
                                        <label>{{ __('frontend.user-order-received-phone') }}<span>*</span></label>
                                        <input type="text" name="phone" required value="">
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-12">
                                    <div class="form-group">
                                        <label>{{ __('frontend.user-order-received-post-code') }}<span>*</span></label>
                                        <input type="text" name="post_code" required value="">
                                    </div>
                                </div>
                                <div class="col-lg-9 col-md-9 col-12">
                                    <div class="form-group">
                                        <label>{{ __('frontend.user-order-received-address') }}<span>*</span></label>
                                        <input type="text" name="address" required value="">
                                    </div>
                                </div>

                            </div>
                            <!--/ End Form -->
                        </div>
                        <!-- Button Widget -->
                        <div class="get-button">
                            <div class="content">
                                <div class="button text-center">
                                    <button type="submit" class="btn">{{ __('frontend.checkout') }}</button>
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
@endpush