@extends('layouts.main')

@section('title', 'ZShop - 訂單查詢')

@section('main-content')
    <div class="container">
        <div class="card shadow mb-4">
            <div class="row">
                <div class="col-md-12">
                    @include('layouts.notification')
                </div>
            </div>
            <div class="card-header py-3">
                {{-- <h4 class=" font-weight-bold">Profile</h4> --}}



                <ul class="nav nav-tabs nav-fill">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('user-profile') }}">個人中心</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('user-reward-money') }}">購物金</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="#">訂單查詢</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('user-returned') }}">退貨查詢</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('user-wishlist') }}">收藏清單</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('user-qa-center') }}">問答中心</a>
                    </li>
                </ul>



                <ul class="breadcrumbs">
                    <li><a href="" style="color:#999">會員中心</a></li>
                    <li><a href="" class="active text-primary">訂單查詢</a></li>
                </ul>
            </div>
            <table class="table shopping-summery">
                <thead>
                    <tr class="main-hading">
                        <th>訂單編號</th>
                        <th>訂單日期</th>
                        <th class="text-center">合計</th>
                        <th class="text-center">訂單狀態</th>
                        <th class="text-center">明細</th>
                    </tr>
                </thead>
                <tbody>
                    @if($orders)
                        @foreach($orders as $key => $value)
                            <tr>
                                <td class="date" data-title="date"><span>{{$value['order_number']}}</span></td>
                                <td class="reward_item" data-title="reward_item"><span>{{$value['created_at']}}</span></td>
                                <td class="amount" data-title="amount"><span>${{$value['total_amount']}}</span></td>
                                <td class="total" data-title="total"><span>{{$value['order_status']}}</span></td>
                                <td class="text-center"><a href="{{route('zshop-index')}}"><i class="ti-layout-media-overlay-alt-2"></i></a></td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
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

@push('scripts')
    <script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>
    <script>
        $('#lfm').filemanager('image');
    </script>
@endpush
