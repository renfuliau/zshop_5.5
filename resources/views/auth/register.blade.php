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
                                <a class="nav-link" href="{{ route('login') }}">登入</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" href="#tabs-2">註冊</a>
                            </li>

                        </ul><!-- Tab panes -->
                        <div class="tab-content">
                            <div class="tab-pane" id="tabs-1" role="tabpanel">
                                <h2 class="mt-3">登入</h2>
                                <!-- Form -->
                                <form class="form" method="post" action="{{ route('register-submit') }}">
                                    {{ csrf_field() }}
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <input type="email" name="email" placeholder="請輸入 Email" required="required"
                                                    value="{{ old('email') }}">
                                                {{-- @error('email')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror --}}
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <input type="password" name="password" placeholder="請輸入密碼"
                                                    required="required" value="{{ old('password') }}">
                                                {{-- @error('password')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror --}}
                                            </div>
                                        </div>
                                        <div class="col-12 d-flex justify-content-center">
                                            <div class="form-group login-btn">
                                                <button class="btn" type="submit">登入</button>
                                            </div>
                                        </div>
                                        <div class="col-12 d-flex justify-content-center align-item-center">
                                            <a class="lost-pass my-auto" href="">
                                                忘記密碼?
                                            </a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane active" id="tabs-2" role="tabpanel">
                                <h2 class="mt-3">註冊</h2>
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
                                                <input type="text" name="email" placeholder="請輸入 Email" required="required"
                                                    value="{{ old('email') }}">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <input type="password" name="password" placeholder="請輸入密碼（至少6個字元）"
                                                    required="required" value="{{ old('password') }}">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <input type="password" name="password_confirmation" placeholder="請再輸入一次密碼"
                                                    required="required" value="{{ old('password_confirmation') }}">
                                            </div>
                                        </div>
                                        <div class="col-12 d-flex justify-content-center">
                                            <div class="form-group login-btn">
                                                <button class="btn" type="submit">立即註冊</button>
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
