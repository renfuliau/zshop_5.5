@extends('layouts.main')

@section('title', 'ZShop - 登入')

@section('main-content')
    <!-- Shop Login -->
    <section class="shop login section">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3 col-12">
                    <div class="login-form">

                        <ul class="nav nav-tabs nav-fill" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" href="#tabs-1">{{ __('auth.body-login') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('z-register') }}">{{ __('auth.body-register') }}</a>
                            </li>

                        </ul><!-- Tab panes -->
                        <div class="tab-content">
                            <div class="tab-pane active" id="tabs-1" role="tabpanel">
                                <h2 class="mt-3">{{ __('auth.body-login') }}</h2>
                                <!-- Form -->
                                <form class="form" method="post" action="{{ route('login-submit') }}">
                                    {{ csrf_field() }}
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <input type="email" name="email" placeholder="{{ __('auth.body-email-placeholder') }}" required="required"
                                                    value="{{ old('email') }}">
                                                {{-- @error('email')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror --}}
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <input type="password" name="password" placeholder="{{ __('auth.body-password-placeholder') }}"
                                                    required="required" value="{{ old('password') }}">
                                                {{-- @error('password')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror --}}
                                            </div>
                                        </div>
                                        <div class="col-12 d-flex justify-content-center">
                                            <div class="form-group login-btn">
                                                <button class="btn" type="submit">{{ __('auth.body-login') }}</button>
                                            </div>
                                        </div>
                                        <div class="col-12 d-flex justify-content-center align-item-center">
                                            <a class="lost-pass my-auto" href="{{ route('forget-password') }}">
                                                {{ __('auth.body-forget-password') }}
                                            </a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane" id="tabs-2" role="tabpanel">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--/ End Login -->
@endsection
