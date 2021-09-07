@extends('layouts.main')

@section('title', 'Zshop - ' . __('frontend.title-order-confirm'))

@section('main-content')
    
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="text-center my-5">
                    <h3>{{ __('frontend.cart-order-confirm') }}</h3>
                    <h5>{{ __('frontend.cart-order-number') }}： {{ $order->order_number }}</h5>
                    <h6><a href="{{ route('index') }}" style="color:blue;">{{ __('frontend.cart-keep-shopping') }}</a></h6>
                </div>
            </div>
        </div>
    </div>

@endsection

