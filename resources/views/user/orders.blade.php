@extends('layouts.main')

@php
$title = __('frontend.user-tab-orders');
@endphp
@section('title', 'ZShop -' . $title)

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
                    <a class="nav-link" href="{{ route('user-profile') }}">{{ __('frontend.user-tab-profile') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link"
                        href="{{ route('user-reward-money') }}">{{ __('frontend.user-tab-reward-money') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="#">{{ __('frontend.user-tab-orders') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('user-returned') }}">{{ __('frontend.user-tab-return') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('user-wishlist') }}">{{ __('frontend.user-tab-wishlist') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('user-qa-center') }}">{{ __('frontend.user-tab-qacenter') }}</a>
                </li>
            </ul>
        </div>
        <table class="table shopping-summery">
            <thead>
                <tr class="main-hading">
                    <th>{{ __('frontend.user-order-number') }}</th>
                    <th>{{ __('frontend.user-order-date') }}</th>
                    <th class="text-center">{{ __('frontend.user-order-total') }}</th>
                    <th class="text-center">{{ __('frontend.user-order-status') }}</th>
                    <th class="text-center">{{ __('frontend.user-order-detail') }}</th>
                </tr>
            </thead>
            <tbody>
                @if($orders)
                @foreach($orders as $key => $value)
                <tr>
                    <td class="text-center date" data-title="date"><span>{{$value['order_number']}}</span></td>
                    <td class="text-center reward_item" data-title="reward_item"><span>{{$value['created_at']}}</span>
                    </td>
                    <td class="text-center amount" data-title="amount"><span>$ {{$value['total']}}</span></td>
                    @if (App::getLocale() == 'zh-tw')
                        <td class="text-center total" data-title="total"><span>{{$order_status[$value['status']]}}</span>
                        </td>
                    @else
                        <td class="text-center total" data-title="total"><span>{{$order_status_en[$value['status']]}}</span>
                        </td>
                    @endif
                    <td class="text-center text-center"><a
                            href="{{route('user-order-detail', $value['order_number'])}}"><i
                                class="ti-layout-media-overlay-alt-2"></i></a></td>
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