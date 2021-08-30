@extends('layouts.main')

@php
$title = __('frontend.user-tab-reward-money');
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
                    <a class="nav-link active" href="#">{{ __('frontend.user-tab-reward-money') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('user-orders') }}">{{ __('frontend.user-tab-orders') }}</a>
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
        <div class="card-body">
            <div class="row d-flex justify-content-center">
                <div class="col-10">
                    <h5 class="card-title text-left my-4"><small><i class="ti-wallet"></i>
                            {{ __('frontend.user-reward-money-recent') }} ${{ $profile->reward_money }}</small>
                    </h5>
                    {{-- <h5 class="card-title text-left my-4"><small><i class="ti-view-list-alt"></i>
                        購物金紀錄：</small> --}}
                    </h5>
                </div>
            </div>
        </div>
        <table class="table shopping-summery">
            <thead>
                <tr class="main-hading">
                    <th>{{ __('frontend.user-reward-money-date') }}</th>
                    <th>{{ __('frontend.user-reward-money-item') }}</th>
                    <th class="text-center">{{ __('frontend.user-reward-money-amount') }}</th>
                    <th class="text-center">{{ __('frontend.user-reward-money-total') }}</th>
                </tr>
            </thead>
            <tbody>
                @if ($reward_money_history)
                @foreach ($reward_money_history as $key => $value)
                <tr>
                    <td class="date text-center" data-title="date"><span>{{ $value['created_at'] }}</span></td>
                    <td class="reward_item text-center" data-title="reward_item">
                        <span>{{ $value['reward_item'] }}</span>
                    </td>
                    <td class="amount text-center" data-title="amount"><span>${{ $value['amount'] }}</span></td>
                    <td class="total text-center" data-title="total"><span>${{ $value['total'] }}</span></td>
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

    i {
        font-size: 14px;
        padding-right: 8px;
    }
</style>