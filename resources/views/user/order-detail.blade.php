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
                            <li><a href="{{ route('user-orders') }}">訂單查詢<i class="ti-arrow-right"></i></a></li>
                            <li class="active"><a href="javascript:void(0)">{{ $order->order_number }}</a></li>
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
            <div class="col-12">
                <!-- Shopping Summery -->
                @if (!empty($carts))
                    <table class="table shopping-summery">
                        <thead>
                            <tr class="main-hading">
                                <th>商品圖片</th>
                                <th class="col-4">商品名稱</th>
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
                                        $photo = explode(',', $value->attributes['photos']);
                                    @endphp
                                    <td class="image" data-title="No"><img src="{{ $photo[0] }}"
                                            alt="{{ $photo[0] }}"></td>
                                    <td class="product-des" data-title="Description">
                                        <p class="product-name"><a
                                                href="{{ route('product-detail', $value->product['slug']) }}"
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
                                                <button type="button" class="btn btn-primary btn-number" disabled="disabled"
                                                    data-type="minus" data-field="quant{{ $value->id }}">
                                                    <i class="ti-minus"></i>
                                                </button>
                                            </div>
                                            <input type="text" name="quant{{ $value->id }}" class="input-number"
                                                autocomplete="off" data-min="1"
                                                data-max="{{ $value->attributes['stock'] }}"
                                                data-product_id="{{ $value->id }}" data-price="{{ $value->price }}"
                                                value="{{ $value->quantity }}">
                                            <input type="hidden" name="qty_id[]" value="{{ $value->id }}">
                                            <div class="button plus">
                                                <button type="button" class="btn btn-primary btn-number" data-type="plus"
                                                    data-field="quant{{ $value->id }}">
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
            
                @endif
                <!--/ End Shopping Summery -->
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
