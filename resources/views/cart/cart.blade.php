@extends('layouts.main')
@section('title', 'Zshop - ' . __('frontend.cart'))
@section('main-content')
    <!-- Breadcrumbs -->
    <div class="breadcrumbs">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="bread-inner">
                        <ul class="bread-list">
                            <li><a href="{{ route('index') }}">{{ __('frontend.index') }}<i
                                        class="ti-arrow-right"></i></a>
                            </li>
                            <li class="active"><a href="javascript:void(0);">{{ __('frontend.cart') }}</a></li>
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
                                    <th>{{ __('frontend.cart-product-img') }}</th>
                                    <th class="col-4">{{ __('frontend.cart-product-title') }}</th>
                                    <th class="text-center">{{ __('frontend.cart-product-price') }}</th>
                                    <th class="text-center">{{ __('frontend.quantity') }}</th>
                                    <th class="text-center">{{ __('frontend.subtotal') }}</th>
                                    <th class="text-center"><i class="ti-trash remove-icon"></i></th>
                                </tr>
                            </thead>
                            <tbody id="cart_item_list">
                                {{ csrf_field() }}
                                @foreach ($carts as $key => $value)
                                    <tr>
                                        <td class="image" data-title="No"><img
                                                src="{{ $value->attributes['photo'] }}"
                                                alt="{{ $value->attributes['photo'] }}"></td>
                                        <td class="product-des" data-title="Description">
                                            <p class="product-name"><a
                                                    href="{{ route('product-detail', $value->product['id']) }}"
                                                    target="_blank">{{ $value->name }}</a></p>
                                        </td>
                                        <td class="price" data-title="Price">
                                            <input class="price_input{{ $value->id }}" type="number"
                                                value="{{ $value->price }}" style="display:none;">
                                            <span>$ {{ $value->price }}</span>
                                        </td>
                                        <td class="qty" data-title="Qty">
                                            <!-- Input Order -->
                                            <div class="input-group">
                                                <div class="button minus">
                                                    <button type="button" class="btn btn-primary btn-number"
                                                        disabled="disabled" data-type="minus"
                                                        data-field="quant{{ $value->id }}">
                                                        <i class="ti-minus"></i>
                                                    </button>
                                                </div>
                                                <input type="text" name="quant{{ $value->id }}" class="input-number"
                                                    autocomplete="off" data-min="1"
                                                    data-max="{{ $value->attributes['stock'] }}"
                                                    data-product_id="{{ $value->id }}"
                                                    data-price="{{ $value->price }}" value="{{ $value->quantity }}">
                                                <input type="hidden" name="qty_id[]" value="{{ $value->id }}">
                                                <div class="button plus">
                                                    <button type="button" class="btn btn-primary btn-number"
                                                        data-type="plus" data-field="quant{{ $value->id }}">
                                                        <i class="ti-plus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <!--/ End Input Order -->
                                        </td>
                                        <td class="total-amount{{ $value->id }} cart_single_price" data-title="Total">
                                            <span class="money">$ {{ $value->price * $value->quantity }}</span>
                                        </td>
                                        <td class="text-center">
                                            <a class="remove_item" style="cursor: pointer;" @if (!empty(Auth::user()->id))
                                                data-user_id="{{ Auth::user()->id }}"
                                @endif
                                data-product_id="{{ $value->id }}"><i class="ti-trash"></i></a>
                                </td>
                                </tr>
                    @endforeach
                    </tbody>
                    </table>
                @else
                    <div class="text-center">
                        {{ __('frontend.cart-is-empty') }} <a href="{{ route('index') }}" style="color:blue;">{{ __('frontend.cart-continue-shopping') }}</a>
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
                                        <h6 class="mb-3">{{ __('frontend.cart-choose-coupon') }}</h6>
                                        <div class="col">
                                            <div class="form-check">
                                                <span id="coupon{{ $coupon1->coupon_type }}_value"
                                                    style="display:none">{{ $coupon1->coupon_amount }}</span>
                                                <input class="form-check-input coupon_type{{ $coupon1->coupon_type }}"
                                                    type="radio" name="coupon-option"
                                                    id="coupon_type{{ $coupon1->coupon_type }}" autocomplete="off"
                                                    value="{{ $coupon1->coupon_amount }}"
                                                    data-subtotal="{{ $total }}"
                                                    data-coupon1_amount={{ $coupon1->coupon_amount }}
                                                    data-coupon1_id="{{ $coupon1->id }}">
                                                <label class="form-check-label coupon-label1" for="exampleRadios1">
                                                    {{ $coupon1->name }}
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input coupon_type{{ $coupon2->coupon_type }}"
                                                    type="radio" name="coupon-option"
                                                    id="coupon_type{{ $coupon2->coupon_type }}" autocomplete="off"
                                                    value="{{ $coupon2->coupon_amount }}"
                                                    data-subtotal="{{ $total }}"
                                                    data-coupon2_amount={{ $coupon2->coupon_amount }}
                                                    data-coupon2_id="{{ $coupon2->id }}">
                                                <label class="form-check-label coupon-label2" for="exampleRadios2">
                                                    {{ $coupon2->name }}
                                                </label>
                                            </div>
                                        </div>
                                    @endif
                                    @if (!empty($coupon1) && empty($coupon2))
                                        <div class="form-check">
                                            <span id="coupon{{ $coupon1->coupon_type }}_value"
                                                style="display:none">{{ $coupon1->coupon_amount }}</span>
                                            <input class="form-check-input coupon_type{{ $coupon1->coupon_type }}"
                                                type="checkbox" name="coupon-option"
                                                id="coupon_type{{ $coupon1->coupon_type }}" autocomplete="off"
                                                value="{{ $coupon1->coupon_amount }}"
                                                data-subtotal="{{ $total }}"
                                                data-coupon1_amount={{ $coupon1->coupon_amount }}
                                                data-coupon1_id="{{ $coupon1->id }}">
                                            <label class="form-check-label coupon-label1" for="exampleRadios1">
                                                {{ $coupon1->name }}
                                            </label>
                                        </div>
                                    @endif
                                    @if (empty($coupon1) && !empty($coupon2))
                                        <div class="form-check">
                                            <input class="form-check-input coupon_type{{ $coupon2->coupon_type }}"
                                                type="checkbox" name="coupon-option"
                                                id="coupon_type{{ $coupon2->coupon_type }}" autocomplete="off"
                                                value="{{ $coupon2->coupon_amount }}"
                                                data-subtotal="{{ $total }}"
                                                data-coupon2_amount={{ $coupon2->coupon_amount }}
                                                data-coupon2_id="{{ $coupon2->id }}">
                                            <label class="form-check-label coupon-label2" for="exampleRadios2">
                                                {{ $coupon2->name }}
                                            </label>
                                        </div>
                                    @endif
                                    @if (!empty($carts))
                                        <h6>{{ __('frontend.cart-use-reward-money') }}</h6>
                                        <h6><small>({{ __('frontend.cart-present-reward-money') }}: ${{ $user_info->reward_money }})</small></h6>
                                        <input id="reward_money" type="number" min="0"
                                            max="{{ $user_info->reward_money }}" oninput="validity.valid||(value='');"
                                            onkeypress="return event.charCode <= {{ $user_info->reward_money }}"
                                            value="0" autocomplete="off">
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-7 col-12">
                                <div class="right">
                                    <ul>
                                        @if (!empty($total))
                                            <li class="subtotal" data-subtotal="{{ $total }}">{{ __('frontend.subtotal') }}
                                                <span class="subtotal_span">$ {{ $total }}</span>
                                            </li>
                                        @endif

                                        <li class="reward_money">
                                            {{ __('frontend.cart-use-reward-money') }}<span>$ 0</span>
                                        </li>
                                        @if (!empty($coupon1))
                                            <li class="coupon1_price" data-coupon1="{{ $coupon1['coupon_amount'] }}">
                                                {{ __('frontend.coupon') }}<span>$ 0</span>
                                            </li>
                                        @endif
                                        @if (!empty($carts))
                                            <li class="total" style="border-style: solid hidden hidden hidden"
                                                data-total="{{ $total }}">
                                                {{ __('frontend.total') }}<span>$ {{ $total }}</span>
                                            </li>
                                        @endif
                                        @if (!empty($coupon2))
                                            <li class="coupon2_price" data-coupon2="{{ $coupon1['coupon_amount'] }}">
                                                {{ __('frontend.cart-coupon-reward') }}<span>$ 0</span>
                                            </li>
                                        @endif
                                    </ul>
                                    {{-- @if (!empty($carts))
                                        <div class="button5">
                                            <a href="{{ route('checkout') }}" class="btn go-to-checkout"
                                data-total="{{ $total }}" data-reward_money_amount="0">前往結帳</a>
                            </div>
                            @endif --}}
                                    @if (!empty($carts))
                                        <form class="border px-4 pt-2 pb-3" method="POST"
                                            action="{{ route('checkout') }}">
                                            {{ csrf_field() }}
                                            <div class="row d-flex justify-content-center">
                                                <input id="input_coupon_id" type="hidden" name="coupon_id" value=""
                                                    class="form-control">
                                                <input id="input_reward_money" type="hidden" name="reward_money" value="0"
                                                    class="form-control">
                                                <button type="submit" class="btn btn-success btn-sm">{{ __('frontend.checkout') }}</button>
                                            </div>
                                        </form>
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
            var product_id = this.getAttribute("data-product_id");
            var price_value = parseInt($(this).attr('data-price'));
            var coupon1_id = $('.coupon_type1').attr('data-coupon1_id');
            var coupon2_id = $('.coupon_type2').attr('data-coupon2_id');

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                method: 'POST',
                url: '/zshop/change-product-qty',
                data: {
                    product_id: product_id,
                    new_qty: new_qty,
                    coupon1_id: coupon1_id,
                    coupon2_id: coupon2_id
                },
                success: function(res) {
                    var current_qty = res['qty'];
                    var total_class = '.total-amount' + product_id;

                    if (res['coupon1']) {
                        $('.coupon_type1').attr('data-coupon1_id', res['coupon1']['coupon_id']);
                        $('.coupon_type1').attr('data-coupon1_amount', res['coupon1']['coupon_amount']);
                        $('.coupon-label1').html(res['coupon1']['coupon_title']);
                    }
                    if (res['coupon2']) {
                        $('.coupon_type2').attr('data-coupon2_id', res['coupon2']['coupon_id']);
                        $('.coupon_type2').attr('data-coupon2_amount', res['coupon2']['coupon_amount']);
                        $('.coupon-label2').html(res['coupon2']['coupon_title']);
                    }

                    $('.cartTotalQuantity').text(res['total_qty']);
                    $('.subtotal').html('小計<span>$ ' + res['total'] + '</span>');
                    $('.subtotal').attr('data-subtotal', res['total']);
                    $('.total').html('{{ __('frontend.total') }}<span>$ ' + res['total'] + '</span>');
                    $('.total').attr('data-total', res['total']);
                    $(total_class).html('<span>$ ' + res['qty'] * price_value + '</span>');

                    var coupon_value = $(".coupon_type1").attr("data-coupon1_amount");
                    var subtotal = $('.subtotal').attr("data-subtotal");
                    var reward_money = $("#reward_money").val();
                    var total = parseInt(subtotal) - parseInt(coupon_value) - parseInt(reward_money);
                    if ($(".coupon_type1").prop("checked")) {
                        $(".coupon1_price").html("{{ __('frontend.coupon') }}<span>$ -" + coupon_value + "</span>");
                        $(".coupon2_price").html("{{ __('frontend.cart-coupon-reward') }}<span>$ 0</span>");
                        $(".total").html("{{ __('frontend.total') }}<span>$ " + total.toString() + "</span>")
                        $('.total').attr('data-total', total);
                        $('#input_coupon_id').attr('value', $('.coupon_type1').attr('data-coupon1_id'));
                    }
                    if ($(".coupon_type2").prop("checked")) {
                        $(".coupon1_price").html("{{ __('frontend.coupon') }}<span>$ -" + coupon_value + "</span>");
                        $(".coupon2_price").html("{{ __('frontend.cart-coupon-reward') }}<span>$ 0</span>");
                        $(".total").html("{{ __('frontend.total') }}<span>$ " + total + "</span>")
                        $('.total').attr('data-total', total);
                        $('#input_coupon_id').attr('value', $('.coupon_type2').attr('data-coupon2_id'));
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error(textStatus + " " + errorThrown);
                }
            });
        });

        $('.remove_item').on('click', function() {
            // console.log(this.getAttribute("data-productid"));
            var product_id = this.getAttribute("data-product_id");

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
                    alert(res)
                    document.location.reload(true);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error(textStatus + " " + errorThrown);
                }
            });
        })

        $(".coupon_type2").on("change", function() {
                var subtotal = $('.subtotal').attr("data-subtotal");
                var coupon_value = this.getAttribute("data-coupon2_amount");
                var reward_money = $("#reward_money").val();
                var total = parseInt(subtotal) - parseInt(reward_money);
            if ($(".coupon_type2").prop("checked")) {
                // var coupon_id = this.getAttribute("data-coupon2_id");
                $(".coupon1_price").html("{{ __('frontend.coupon') }}<span>$ 0</span>");
                $(".coupon2_price").html("{{ __('frontend.cart-coupon-reward') }}<span>$ " + coupon_value + "</span>");
                $(".total").html("{{ __('frontend.total') }}<span>$ " + total + "</span>");
                $('.total').attr('data-total', total);
                $('#input_coupon_id').attr('value', $('.coupon_type2').attr('data-coupon2_id'));
            } else {
                // var coupon_id = null;
                // $(".coupon1_price").html("{{ __('frontend.coupon') }}<span>$ 0</span>");
                $(".coupon2_price").html("{{ __('frontend.cart-coupon-reward') }}<span>$ 0</span>");
                $(".total").html("{{ __('frontend.total') }}<span>$ " + total + "</span>");
                $('.total').attr('data-total', total);
                $('#input_coupon_id').attr('value', $('.coupon_type2').attr('data-coupon2_id'));
            }
        });

        $(".coupon_type1").on("change", function() {
            var subtotal = $('.subtotal').attr("data-subtotal");
            var coupon_value = this.getAttribute("data-coupon1_amount");
            var reward_money = $("#reward_money").val();
            if ($(".coupon_type1").prop("checked")) {
                var total = parseInt(subtotal) - parseInt(coupon_value) - parseInt(reward_money);
                // var coupon_id = this.getAttribute("data-coupon1_id");
                $(".coupon1_price").html("{{ __('frontend.coupon') }}<span>$ -" + coupon_value + "</span>");
                $(".coupon2_price").html("{{ __('frontend.cart-coupon-reward') }}<span>$ 0</span>");
                $(".total").html("{{ __('frontend.total') }}<span>$ " + total + "</span>")
                $('.total').attr('data-total', total);
                $('#input_coupon_id').attr('value', $('.coupon_type1').attr('data-coupon1_id'));
            } else {
                var total = parseInt(subtotal) - parseInt(reward_money);
                // var coupon_id = null;
                $(".coupon1_price").html("{{ __('frontend.coupon') }}<span>$ 0</span>");
                $(".coupon2_price").html("{{ __('frontend.cart-coupon-reward') }}<span>$ 0</span>");
                $(".total").html("{{ __('frontend.total') }}<span>$ " + total + "</span>")
                $('.total').attr('data-total', total);
                $('#input_coupon_id').attr('value', $('.coupon_type1').attr('data-coupon1_id'));
            }
        });

        $("#reward_money").on("change", function() {
            var reward_money = $("#reward_money").val();
            if (!reward_money) {
                var reward_money = 0;
            }
            var subtotal = $(".subtotal").attr('data-subtotal');
            var total = parseInt(subtotal) - parseInt(reward_money);
            if ($(".coupon_type1").prop("checked")) {
                var coupon1_value = $(".coupon_type1").attr('data-coupon1_amount');
                var total = parseInt(subtotal) - parseInt(coupon1_value) - parseInt(reward_money);
            }
            $('.reward_money').html("{{ __('frontend.cart-use-reward-money') }}<span>$ -" + $("#reward_money").val() + "</span>");
            if ($("#reward_money").val() == 0) {
                $('.reward_money').html("{{ __('frontend.cart-use-reward-money') }}<span>$ 0</span>");
            }
            $(".total").html("{{ __('frontend.total') }}<span>$ " + total + "</span>");
            $('.total').attr('data-total', total);
            $('#input_reward_money').attr('value', reward_money);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                method: 'POST',
                url: '/zshop/change-reward-money',
                data: {
                    reward_money: reward_money,
                },
                success: function(res) {
                    // alert(res);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error(textStatus + " " + errorThrown);
                }
            });
        });
    </script>

@endpush
