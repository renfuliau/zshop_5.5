@extends('layouts.main')

@php
$title = __('frontend.user-tab-profile');
@endphp
@section('title', 'ZShop -' . $title)

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
                        <a class="nav-link"
                            href="{{ route('user-orders') }}">{{ __('frontend.user-tab-orders') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"
                            href="{{ route('user-returned') }}">{{ __('frontend.user-tab-return') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"
                            href="{{ route('user-wishlist') }}">{{ __('frontend.user-tab-wishlist') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"
                            href="{{ route('user-qa-center') }}">{{ __('frontend.user-tab-qacenter') }}</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="row d-flex justify-content-center">
                    <div class="col-10">
                        @if (App::getLocale() == 'zh-tw')
                            <h5 class="card-title text-left my-4"><small><i class="ti-crown"></i>
                                    {{ $user_level->name }}</small></h5>
                        @else
                            <h5 class="card-title text-left my-4"><small><i class="ti-crown"></i>
                                    {{ $user_level->name_en }}</small></h5>
                        @endif
                        <h5 class="card-title text-left mt-4"><small><i class="ti-medall"></i>
                                {{ __('frontend.user-profile-total-shopping-amount') . ': $ ' . $profile->total_shopping_amount }}</small>
                        </h5>
                        @if (!empty($next_user_level))
                            <h6 class="text-left"><small>
                                    ({{ '$ ' . $amount_to_level_up . ' ' . __('frontend.user-profile-to-next-level') . ' ' . $next_user_level['name'] }})</small>
                            </h6>
                        @endif
                        <div class="card">
                            <div class="card-body mt-4">
                                <form class="border px-4 pt-2 pb-3" method="POST"
                                    action="{{ route('user-profile-update', $profile->id) }}">
                                    {{ csrf_field() }}
                                    <div class="row d-flex justify-content-center">
                                        <div class="form-group col-lg-6 col-12">
                                            <label for="inputTitle"
                                                class="col-form-label">{{ __('frontend.user-profile-name') }}</label>
                                            <input id="inputTitle" type="text" name="name"
                                                placeholder="{{ __('frontend.user-profile-placeholder-name') }}"
                                                value="{{ $profile->name }}" class="form-control">
                                        </div>

                                        <div class="form-group col-lg-6 col-12">
                                            <label for="inputEmail" class="col-form-label">Email</label>
                                            <input id="inputEmail" type="email" name="email"
                                                placeholder="{{ __('frontend.user-profile-placeholder-email') }}"
                                                value="{{ $profile->email }}" class="form-control">
                                        </div>
                                        <div class="form-group col-lg-6 col-12">
                                            <label for="inputPhone"
                                                class="col-form-label">{{ __('frontend.user-profile-phone') }}</label>
                                            <input id="inputPhone" type="phone" name="phone"
                                                placeholder="{{ __('frontend.user-profile-placeholder-phone') }}"
                                                value="{{ $profile->phone }}" class="form-control">
                                        </div>
                                        <div class="form-group col-lg-6 col-12">
                                            <label for="inputAddress"
                                                class="col-form-label">{{ __('frontend.user-profile-address') }}</label>
                                            <input id="inputAddress" type="address" name="address"
                                                placeholder="{{ __('frontend.user-profile-placeholder-address') }}"
                                                value="{{ $profile->address }}" class="form-control">
                                        </div>
                                        <button type="submit"
                                            class="btn btn-success btn-sm">{{ __('frontend.user-profile-update-btn') }}</button>
                                    </div>
                                </form>
                                <div class="text-center">
                                    <i class="ti-lock"></i><a
                                        href="{{ route('user-change-password') }}">{{ __('frontend.user-profile-reset-password') }}</a>
                                </div>
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
