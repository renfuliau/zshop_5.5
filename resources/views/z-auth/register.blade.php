@extends('layouts.main')

@section('title', 'ZShop - 註冊')

@section('main-content')
    <!-- Shop Login -->
    <section class="shop login section">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3 col-12">
                    <div class="login-form">
                        <ul class="nav nav-tabs nav-fill" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('z-login') }}">{{ __('auth.body-login') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" href="#tabs-2">{{ __('auth.body-register') }}</a>
                            </li>

                        </ul><!-- Tab panes -->
                        <div class="tab-content">
                            <div class="tab-pane" id="tabs-1" role="tabpanel">
                            </div>
                            <div class="tab-pane active" id="tabs-2" role="tabpanel">
                                <h2 class="mt-3">{{ __('auth.body-register') }}</h2>
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <!-- Form -->
                                <form class="form" method="post" action="{{ route('register-submit') }}">
                                    {{ csrf_field() }}
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <input type="text" name="email" placeholder="{{ __('auth.body-email-placeholder') }}" required="required"
                                                    value="{{ old('email') }}">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <input type="password" name="password" placeholder="{{ __('auth.body-password-placeholder') }}"
                                                    required="required" value="{{ old('password') }}">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <input type="password" name="password_confirmation" placeholder="{{ __('auth.body-password-confirm-placeholder') }}"
                                                    required="required" value="{{ old('password_confirmation') }}">
                                            </div>
                                        </div>
                                        <div class="col-12 d-flex justify-content-center">
                                            <div class="form-group login-btn">
                                                <button class="btn" type="submit">{{ __('auth.body-register') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--/ End Login -->
@endsection
