@extends('layouts.main')

@section('title', 'ZShop - 問答中心')

@section('main-content')
    <div class="container">
        @if (!empty($messages))
            <div class="card shadow mb-4">
                <div class="row">
                    <div class="col-md-12">
                        @include('layouts.notification')
                    </div>
                </div>
                <div class="card-header py-3">
                    <ul class="nav nav-tabs nav-fill">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('user-profile') }}">個人中心</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('user-reward-money') }}">購物金</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('user-orders') }}">訂單查詢</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('user-returned') }}">退貨查詢</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('user-wishlist') }}">收藏清單</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="#">問答中心</a>
                        </li>
                    </ul>
                </div>
                <table class="table shopping-summery">
                    <thead>
                        <tr class="main-hading">
                            <th>問答時間</th>
                            <th>訂單號碼</th>
                            {{-- <th class="text-center">最新內容</th> --}}
                            <th class="text-center">查閱</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($messages as $message)
                            <tr>
                                <td class="text-center date" data-title="date"><span>{{ $message['created_at'] }}</span>
                                </td>
                                <td class="text-center order_number" data-title="order_number">
                                    <span>{{ $message->order['order_number'] }}</span>
                                </td>
                                <td class="text-center text-center"><a
                                        href="{{ route('user-order-detail', $message->order['order_number']) }}"><i
                                            class="ti-layout-media-overlay-alt-2"></i></a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center">
                購物車是空的 <a href="{{ route('index') }}" style="color:blue;">繼續選購</a>
            </div>
        @endif
    </div>


@endsection

<style>
    .breadcrumbs {
        list-style: none;
    }

    .breadcrumbs li {
        float: left;
        margin-right: 10px;
    }

    .breadcrumbs li a:hover {
        text-decoration: none;
    }

    .breadcrumbs li .active {
        color: red;
    }

    .breadcrumbs li+li:before {
        content: "/\00a0";
    }

    .image {
        background: url('{{ asset('backend/img/background.jpg') }}');
        height: 150px;
        background-position: center;
        background-attachment: cover;
        position: relative;
    }

    .image img {
        position: absolute;
        top: 55%;
        left: 35%;
        margin-top: 30%;
    }

    i {
        font-size: 14px;
        padding-right: 8px;
    }

</style>
