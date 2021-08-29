@extends('layouts.main')

@section('title', 'ZShop - 訂單明細')

@section('main-content')

<!-- Breadcrumbs -->
<div class="breadcrumbs">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="bread-inner">
                    <ul class="bread-list">
                        <li><a href="{{ route('user-orders') }}">{{ __('frontend.user-tab-orders') }}<i
                                    class="ti-arrow-right"></i></a></li>
                        <li class="active"><a href="javascript:void(0)">{{ $order->order_number }}</a></li>
                        <input type="hidden" name="order-id" id="order-id" value="{{ $order->id }}">
                        <input type="hidden" name="order-number" id="order-number" value="{{ $order->order_number }}">
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
        <div class="row">

            <div class="col-12">
                <!-- Shopping Summery -->
                <h5 class="text-center my-3">{{ __('frontend.order-detail') }}</h5 class="text-center my-3">
                @if (!empty($order))
                <table class="table shopping-summery" style="height: auto;">
                    <thead>
                        <tr class="main-hading">
                            <th>{{ __('frontend.user-wishlist-img') }}</th>
                            <th class="col-4">{{ __('frontend.user-wishlist-title') }}</th>
                            <th class="text-center">{{ __('frontend.user-wishlist-price') }}</th>
                            <th class="text-center">{{ __('frontend.quantity') }}</th>
                            <th class="text-center">{{ __('frontend.subtotal') }}</th>
                        </tr>
                    </thead>
                    <tbody id="cart_item_list">
                        {{ csrf_field() }}
                        @foreach ($order->orderItems as $value)
                        <tr>
                            @php
                            $photo = explode(',', $value->product['photo']);
                            @endphp
                            <td class="image text-center"><img src="{{ $photo[0] }}" alt="{{ $photo[0] }}"></td>
                            <td class="product-des text-center" data-title="Description">
                                <p class="product-name"><a href="{{ route('product-detail', $value->product['slug']) }}"
                                        target="_blank">{{ $value->product['title'] }}</a></p>
                            </td>
                            <td class="price text-center" data-title="Price">
                                <span>$ {{ $value->price }}</span>
                            </td>
                            <td class="qty text-center" data-title="Qty">
                                <span>{{ $value->quantity }}</span>
                            </td>
                            <td class="cart_single_price text-right" data-title="Total">
                                <span class="money pr-4">$ {{ $value->price * $value->quantity }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>


                @endif
                <!--/ End Shopping Summery -->
                <div class="single-widget col-12 mb-5 pb-5">
                    <div class="content">
                        <ul>
                            <li class="reward_money last">{{ __('frontend.user-order-use-reward-money') }}：
                                @if (!$order->reward_money)
                                <span>$ 0</span>
                                @else
                                <span>$ -{{ $order->reward_money }}</span>
                                @endif
                            </li>
                            @if (!empty($order->coupon) && $order->coupon['coupon_type'] == 1)
                            <li class="coupon">{{ __('frontend.user-order-coupon') }}： {{ $order->coupon['name'] }}<span>$
                                    -{{ $order->coupon['coupon_amount'] }}</span></li>
                            {{-- <li class="reward_money">使用購物金： <span>$ -{{ $order->reward_money }}</span></li> --}}
                            <li class="total last" id="order_total_price">
                                {{ __('frontend.total') }}<span>$ {{ $order->total }}</span>
                            </li>
                            @else
                            {{-- <li class="reward_money">使用購物金： <span>$ -{{ $order->reward_money }}</span></li> --}}
                            <li class="total last" id="order_total_price">
                                {{ __('frontend.total') }}<span>$ {{ $order->total }}</span></li>
                            @if (!empty($order->coupon) && $order->coupon['coupon_type'] == 2)
                            <li class="coupon">{{ __('frontend.coupon') }}： {{ $order->coupon['name'] }}<span>$
                                    {{ $order->coupon['coupon_amount'] }}</span></li>
                            @endif
                            @endif
                        </ul>
                    </div>
                </div>
                <div class="row mb-5 pb-5">
                    <div class="col-lg-6 col-12 border p-4">
                        <h5 class="text-center mb-4">{{ __('frontend.user-order-received-info') }}</h5>
                        <p class="pl-5">{{ __('frontend.user-order-received-name') }}：
                            <span class="order-name">{{ $order->name }}</span>
                        </p>
                        <p class="pl-5">{{ __('frontend.user-order-received-phone') }}：
                            <span class="order-phone">{{ $order->phone }}</span>
                        </p>
                        <p class="pl-5">{{ __('frontend.user-order-received-address') }}：
                            <span class="order-address">{{ $order->address }}</span>
                        </p>
                    </div>
                    <div class="col-lg-6 col-12 border p-4">
                        <h5 class="text-center mb-4">{{ __('frontend.user-order-qa') }}</h5>
                        @if (!empty($messages))
                        @foreach ($messages as $message)
                        @switch($message['subject'])
                        @case(1)
                        <div class="text-right border p-2 m-2">
                            <h6>{{ $message['message'] }} :<i class="ti-user"></i></h6>
                        </div>
                        @break

                        @case(2)
                        <div class="text-left border p-2 m-2">
                            <h6><i class="ti-headphone-alt"></i>: {{ $message['message'] }}</h6>
                        </div>
                        @break
                        @endswitch
                        @endforeach
                        @endif
                        <input class="w-100" type="text" name="question" id="question" placeholder="{{ __('frontend.user-order-qa-placeholder') }}">
                        <div class="text-center mt-2">
                            <button class="btn qa-button">{{ __('frontend.submit') }}</button>
                        </div>
                    </div>
                    <div class="col-12 text-center mt-5">
                        @if ($order->status && $order->status < 4) <button class="btn mx-5 received-button">
                            {{ __('frontend.user-order-received-btn') }}</button>
                            <button class="btn mx-5 cancel-button">{{ __('frontend.user-order-cancel-btn') }}</button>
                            @elseif ($order->status == 4)
                            {{-- <button class="btn return-button">我要退貨</button> --}}
                            <form class="border px-4 pt-2 pb-3" method="POST"
                                action="{{ route('order-return', $order->order_number) }}">
                                {{ csrf_field() }}
                                <div class="row d-flex justify-content-center">
                                    <input id="input_order_id" type="hidden" name="order_id" value="{{ $order->id }}">
                                    {{-- <input id="input_order_number" type="hidden" name="order_number" value="{{ $order->order_number }}"
                                    class="form-control"> --}}
                                    <button type="submit" class="btn btn-success btn-sm">{{ __('frontend.user-order-return-btn') }}</button>
                                </div>
                            </form>
                            @else
                            <h5 class="text-danger">{{ $order_status[$order->status] }}</h5>
                            @endif
                    </div>
                </div>
            </div>
        </div>
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

        $('.qa-button').click(function() {
            var order_name = $('.order-name').text();
            var order_phone = $('.order-phone').text();
            var order_id = $('#order-id').val();
            var message = $('#question').val();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                method: 'POST',
                url: '/zshop/user/order-message-store',
                data: {
                    name: order_name,
                    phone: order_phone,
                    order_id: order_id,
                    message: message,
                },
                success: function(res) {
                    alert(res['message']);
                    document.location.reload(true);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error(textStatus + " " + errorThrown);
                }
            });
        })

        $('.received-button').click(function() {
            var order_id = $('#order-id').val();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                method: 'POST',
                url: '/zshop/user/order-received',
                data: {
                    order_id: order_id,
                },
                success: function(res) {
                    // alert(res);
                    console.log(res);
                    console.log(res['message']);
                    alert(res['message']);
                    if (res['level_up']) {
                        alert(res['level_up']);
                    }
                    if (res['reward_money']) {
                        alert(res['reward_money']);
                    }
                    document.location.reload(true);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error(textStatus + " " + errorThrown);
                }
            });
        })

        $('.cancel-button').click(function() {
            var r = confirm("你確定要取消此筆訂單嗎?");
            if (r == true) {
                var order_id = $('#order-id').val();

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    method: 'POST',
                    url: '/zshop/user/order-cancel',
                    data: {
                        order_id: order_id,
                    },
                    success: function(res) {
                        alert(res);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error(textStatus + " " + errorThrown);
                    }
                });
            }
        })

        // $('.return-button').click(function() {
        //     var order_id = $('#order-id').val();
        //     var order_number = $('#order-number').val();

        //     $.ajaxSetup({
        //         headers: {
        //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //         }
        //     });

        //     $.ajax({
        //         method: 'POST',
        //         url: '/zshop/user/order-return/' + order_number,
        //         data: {
        //             order_id: order_id,
        //             order_number: order_number,
        //         },
        //         success: function(res) {
        //             window.location = res.url;
        //         },
        //         error: function(jqXHR, textStatus, errorThrown) {
        //             console.error(textStatus + " " + errorThrown);
        //         }
        //     });
        // })
</script>

@endpush