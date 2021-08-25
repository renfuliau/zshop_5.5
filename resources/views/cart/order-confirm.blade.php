@extends('layouts.main')

@section('title', 'Zshop - 訂單確認')

@section('main-content')
    
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="text-center my-5">
                    <h3>謝謝您！您的訂單已經成立！</h3>
                    <h5>訂單號碼： {{ $order->order_number }}</h5>
                    <h6><a href="{{ route('index') }}" style="color:blue;">繼續選購</a></h6>
                </div>
            </div>
        </div>
    </div>

@endsection

