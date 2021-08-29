@extends('layouts.main')

@php
    $title = __('frontend.user-profile-reset-password');
@endphp
@section('title', 'ZShop - ' . $title)

@section('main-content')
<div class="container">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            {{-- <h4 class=" font-weight-bold">Profile</h4> --}}
            <ul class="nav nav-tabs nav-fill">
                <li class="nav-item">
                    <a class="nav-link active" href="#">{{ __('frontend.user-tab-profile') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link"
                        href="{{ route('user-reward-money') }}">{{ __('frontend.user-tab-reward-money') }}</a>
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



            <ul class="breadcrumbs">
                <li><a href="{{ route('user-profile') }}" style="color:#999">{{ __('frontend.user-tab-profile') }}</a>
                </li>
                <li><a href="#" class="active text-primary">{{ __('frontend.user-profile-reset-password') }}</a></li>
            </ul>
        </div>
        <div class="card-body">
            <div class="row d-flex justify-content-center">
                <div class="col-10">
                    <div class="card">
                        <div class="card-header">{{ __('frontend.user-profile-reset-password') }}</div>

                        <div class="card-body">
                            <form method="POST" action="{{ route('user-change-password-update') }}">
                                {{ csrf_field() }}

                                @foreach ($errors->all() as $error)
                                <p class="text-danger">{{ $error }}</p>
                                @endforeach
                                <input type="password" style="display:none;">
                                <div class="form-group row">
                                    <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('frontend.user-profile-old-password') }}</label>

                                    <div class="col-md-6">
                                        <input id="password" type="password" class="form-control"
                                            name="current_password">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('frontend.user-profile-new-password') }}</label>

                                    <div class="col-md-6">
                                        <input id="new_password" type="password" class="form-control"
                                            name="new_password">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('frontend.user-profile-confirm-new-password') }}</label>

                                    <div class="col-md-6">
                                        <input id="new_confirm_password" type="password" class="form-control"
                                            name="new_confirm_password">
                                    </div>
                                </div>

                                <div class="form-group row mb-0">
                                    <div class="col-md-12 text-center">
                                        <button type="submit" class="btn btn-primary">{{ __('auth.body-submit') }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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