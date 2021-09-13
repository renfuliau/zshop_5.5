@extends('layouts.main')

@section('title', 'ZShop - ' . __('frontend.user-order-detail'))

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
                    <h5 class="text-center my-3">{{ __('frontend.user-order-detail') }}</h5 class="text-center my-3">
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
                                @foreach ($order->orderItems as $orderItem)
                                    <tr>
                                        @if ($orderItem['is_return'] == 0)
                                            <td class="image text-center"><img
                                                    src="{{ $orderItem->product->productImg[0]->filepath }}"
                                                    alt="{{ $orderItem->product->productImg[0]->filepath }}"></td>
                                            <td class="product-des text-center" data-title="Description">
                                                <p class="product-name"><a
                                                        href="{{ route('product-detail', $orderItem->product['id']) }}"
                                                        target="_blank">{{ $orderItem->product['title'] }}</a></p>
                                            </td>
                                            <td class="price text-center" data-title="Price">
                                                <span>$ {{ $orderItem->price }}</span>
                                            </td>
                                            <td class="qty text-center" data-title="Qty">
                                                <span>{{ $orderItem->quantity }}</span>
                                            </td>
                                            <td class="cart_single_price text-right" data-title="Total">
                                                <span class="money pr-4">$
                                                    {{ $orderItem->price * $orderItem->quantity }}</span>
                                            </td>
                                        @endif
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
                                    <li class="coupon">{{ __('frontend.user-order-coupon') }}：
                                        {{ $order->coupon['name'] }}<span>$
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
                                        <li class="coupon">{{ __('frontend.user-order-coupon') }}：
                                            {{ $order->coupon['name'] }}<span>$
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
                            <input class="w-100" type="text" name="question" id="question"
                                placeholder="{{ __('frontend.user-order-qa-placeholder') }}">
                            <div class="text-center mt-2">
                                <button class="btn qa-button">{{ __('frontend.submit') }}</button>
                            </div>
                        </div>
                        <div class="col-12 text-center mt-5">
                            @if ($order->status && $order->status < 4) <button
                                    class="btn mx-5 received-button">
                                    {{ __('frontend.user-order-received-btn') }}</button>
                                <button
                                    class="btn mx-5 cancel-button">{{ __('frontend.user-order-cancel-btn') }}</button>
                            @elseif ($order->status >= 4 && $order->subtotal > $return_total)
                                <a class="btn text-white"
                                    href="{{ route('order-return', ['order_number' => $order->order_number, 'order_id' => $order->id]) }}">{{ __('frontend.user-order-return-btn') }}</a>
                            @else
                                @if (App::getLocale() == 'zh-tw')
                                    <h5 class="text-danger">{{ $order_status[$order->status] }}</h5>
                                @else
                                    <h5 class="text-danger">{{ $order_status_en[$order->status] }}</h5>
                                @endif
                            @endif
                        </div>
                    </div>
                    @if ($order['status'] > 4)
                        {{-- <h5 class="py-3 text-center">退貨</h5> --}}

                        <table class="table shopping-summery bg-danger" style="height: auto;">
                            <thead>
                                <tr class="main-hading">
                                    <h5 class="py-3 text-center">{{ __('frontend.return') }}</h5>

                                    <th>{{ __('frontend.user-wishlist-img') }}</th>
                                    <th class="col-4">{{ __('frontend.user-wishlist-title') }}</th>
                                    <th class="text-center">{{ __('frontend.user-wishlist-price') }}</th>
                                    <th class="text-center">{{ __('frontend.quantity') }}</th>
                                    <th class="text-center">{{ __('frontend.subtotal') }}</th>
                                </tr>
                            </thead>
                            <tbody id="cart_item_list">
                                @foreach ($order->orderItems as $orderItem)
                                    @if ($orderItem['is_return'] == 1)
                                        <tr>
                                            <td class="image text-center"><img
                                                    src="{{ $orderItem->product->productImg[0]->filepath }}"
                                                    alt="{{ $orderItem->product->productImg[0]->filepath }}"></td>
                                            <td class="product-des text-center" data-title="Description">
                                                <p class="product-name"><a
                                                        href="{{ route('product-detail', $orderItem->product['id']) }}"
                                                        target="_blank">{{ $orderItem->product['title'] }}</a>
                                                </p>
                                            </td>
                                            <td class="price text-center" data-title="Price">
                                                <span>$ {{ $orderItem->price }}</span>
                                            </td>
                                            <td class="qty text-center" data-title="Qty">
                                                <span>{{ $orderItem->quantity }}</span>
                                            </td>
                                            <td class="cart_single_price text-right" data-title="Total">
                                                <span class="money pr-4">$
                                                    {{ $orderItem->price * $orderItem->quantity }}</span>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                        <div class="single-widget col-12 mb-5 pb-5">
                            <div class="content">
                                <ul>
                                    @if ($order['subtotal'] == $return_total)
                                        @if (!empty($order->coupon) && $order->coupon['coupon_type'] == 1)
                                            <li class="coupon">{{ __('frontend.user-order-coupon1-cancel') }}：
                                                {{ $order->coupon['name'] }}<span>$
                                                    -{{ $order->coupon['coupon_amount'] }}</span></li>
                                            {{-- <li class="reward_money">{{ __('frontend.user-order-return-reward-money') }}： <span>$ {{ $order->reward_money }}</span>
                                            </li> --}}
                                            <li class="total last" id="order_total_price">
                                                {{ __('frontend.user-order-return-total') }}<span>$
                                                    {{ $order['subtotal'] - $order->coupon['coupon_amount'] }}</span>
                                            </li>
                                        @else
                                            {{-- <li class="reward_money">{{ __('frontend.user-order-return-reward-money') }}： <span>$ {{ $order->reward_money }}</span>
                                            </li> --}}
                                            @if (!empty($order->coupon) && $order->coupon['coupon_type'] == 2)
                                                <li class="coupon">
                                                    {{ __('frontend.user-order-coupon2-cancel') }}：
                                                    {{ $order->coupon['name'] }}<span>$
                                                        -{{ $order->coupon['coupon_amount'] }}</span></li>
                                                <li class="total last" id="order_total_price">
                                                    {{ __('frontend.user-order-return-total') }}<span>$
                                                        {{ $order->subtotal }}</span>
                                                </li>
                                            @else
                                                <li class="total last" id="order_total_price">
                                                    {{ __('frontend.user-order-return-total') }}<span>$
                                                        {{ $order->subtotal }}</span></li>
                                            @endif

                                        @endif

                                    @elseif (!empty($order->coupon) && $order->coupon['coupon_type'] == 1 &&
                                        $order['subtotal'] - $return_total < $order->coupon['coupon_line'])
                                            <li class="coupon">{{ __('frontend.user-order-coupon1-cancel') }}：
                                                {{ $order->coupon['name'] }}<span>$
                                                    -{{ $order->coupon['coupon_amount'] }}</span></li>
                                            {{-- <li class="reward_money">使用購物金： <span>$ -{{ $order->reward_money }}</span></li> --}}
                                            <li class="total last" id="order_total_price">
                                                {{ __('frontend.user-order-return-total') }}<span>$
                                                    {{ $return_total - $order->coupon['coupon_amount'] }}</span>
                                            </li>
                                        @else
                                            {{-- <li class="reward_money">使用購物金： <span>$ -{{ $order->reward_money }}</span></li> --}}
                                            <li class="total last" id="order_total_price">
                                                {{ __('frontend.user-order-return-total') }}<span>$
                                                    {{ $return_total }}</span></li>
                                            @if (!empty($order->coupon) && $order->coupon['coupon_type'] == 2)
                                                <li class="coupon">
                                                    {{ __('frontend.user-order-coupon2-cancel') }}：
                                                    {{ $order->coupon['name'] }}<span>$
                                                        -{{ $order->coupon['coupon_amount'] }}</span></li>
                                            @endif
                                    @endif
                                </ul>
                            </div>
                        </div>
                    @endif
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
    </script>

@endpush
