@extends('layouts.main')
@section('title', 'Zshop - 我要退貨')
@section('main-content')
    <!-- Breadcrumbs -->
    <div class="breadcrumbs">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="bread-inner">
                        <ul class="bread-list">
                            <li><a href="{{ route('user-orders') }}">訂單查詢<i class="ti-arrow-right"></i></a></li>
                            <li class="active"><a href="javascript:void(0);">{{ $order->order_number }} 退貨</a></li>
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
                    @if (!empty($order->orderItems))
                        <table class="table shopping-summery">
                            <thead>
                                <tr class="main-hading">
                                    <th>選取</th>
                                    <th>商品圖片</th>
                                    <th class="col-4">商品名稱</th>
                                    <th class="text-center">單價</th>
                                    <th class="text-center">數量</th>
                                    <th class="text-center">小計</th>
                                </tr>
                            </thead>
                            <tbody id="cart_item_list">
                                {{ csrf_field() }}
                                @foreach ($order->orderItems as $key => $order_item)
                                    <tr>
                                        <td>
                                            <p>
                                                {{-- <input type="hidden" name="order_item_id" id="order_item_id" value="{{ $order_item->id }}"> --}}
                                                <input type="checkbox" class="custom-check" id="order_item{{ $order_item->id }}"
                                                    value="1" autocomplete="off"
                                                    data-order_item_id="{{ $order_item->id }}" checked="true">
                                                <label for="custom-check{{ $order_item->order_id }}"></label>
                                            </p>
                                        </td>
                                        @php
                                            $photo = explode(',', $order_item->Product['photo']);
                                        @endphp
                                        <td class="image" data-title="No"><img src="{{ $photo[0] }}"
                                                alt="{{ $photo[0] }}"></td>
                                        <td class="product-des" data-title="Description">
                                            <p class="product-name"><a
                                                    href="{{ route('product-detail', $order_item->Product['slug']) }}"
                                                    target="_blank">{{ $order_item->Product['title'] }}</a></p>
                                        </td>
                                        <td class="price" data-title="Price">
                                            <input class="price_input{{ $order_item->id }}" type="number"
                                                order_item="{{ $order_item->price }}" style="display:none;">
                                            <span>$ {{ $order_item->price }}</span>
                                        </td>
                                        <td class="qty" data-title="Qty">
                                            <!-- Input Order -->
                                            <div class="input-group">
                                                <div class="button minus">
                                                    <button type="button" class="btn btn-primary btn-number" disabled="disabled" data-type="minus" data-field="quant{{ $order_item->id }}"><i class="ti-minus"></i>
                                                    </button>
                                                </div>
                                                <input type="text" name="quant{{ $order_item->id }}" class="input-number"
                                                    autocomplete="off" data-min="0"
                                                    data-max="{{ $order_item->quantity }}"
                                                    data-order_item_id="{{ $order_item->id }}"
                                                    data-price="{{ $order_item->price }}"
                                                    order_item="{{ $order_item->quantity }}"
                                                    value="{{ $order_item->quantity }}">
                                                <input type="hidden" name="qty_id[]" order_item="{{ $order_item->id }}">
                                                <div class="button plus">
                                                    <button type="button" class="btn btn-primary btn-number" data-type="plus" data-field="quant{{ $order_item->id }}">      <i class="ti-plus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <!--/ End Input Order -->
                                        </td>
                                        <td class="total-amount{{ $order_item->id }} cart_single_price"
                                            data-title="Total">
                                            <span class="money">$ {{ $order_item->price * $order_item->quantity }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{-- @else
                    <div class="text-center">
                        購物車是空的 <a href="{{ route('index') }}" style="color:blue;">繼續選購</a>
                    </div> --}}
                    @endif
                    <!--/ End Shopping Summery -->
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
            var order_item_id = this.getAttribute("data-order_item_id");
            var price_value = parseInt($(this).attr('data-price'));
            var total_amount_class = '.total-amount' + order_item_id;
            var total = new_qty * price_value;
            $(total_amount_class).html('<span class="money">$ ' + total + '</span>')

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

                    $('.cartTotalQuantity').text(res['total_qty']);
                    $('.subtotal').html('小計<span>$ ' + res['total'] + '</span>');
                    $('.subtotal').attr('data-subtotal', res['total']);
                    $('.total').html('總計<span>$ ' + res['total'] + '</span>');
                    $('.total').attr('data-total', res['total']);
                    $(total_class).html('<span>$ ' + res['qty'] * price_value + '</span>'); 
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error(textStatus + " " + errorThrown);
                }
            });
        });

        $('.custom-check').on('change', function() {
            var order_item_id = this.getAttribute('data-order_item_id');
                console.log(order_item_id);
            array.forEach(element => {
                
            });
            if ($(".custom-check").prop("checked")) {
                console.log("checked");
                $(this).attr('value', 1);
                
            } else {
                console.log("unchecked");
                $(this).attr('value', 0);
            }
            // if ($(".custom-check").prop("checked")) {
            //     var order_item_id = this.getAttribute('data-order-item-id');
            //     console.log('666', order_item_id);
            // }
        })
    </script>

@endpush
