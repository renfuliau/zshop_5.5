@extends('layouts.main')

@section('title', 'ZShop - 個人中心')

@section('main-content')
    <div class="container">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                {{-- <h4 class=" font-weight-bold">Profile</h4> --}}
                <ul class="nav nav-tabs nav-fill">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">個人中心</a>
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
                        <a class="nav-link" href="{{ route('user-qa-center') }}">問答中心</a>
                    </li>
                </ul>



                <ul class="breadcrumbs">
                    <li><a href="" style="color:#999">會員中心</a></li>
                    <li><a href="" class="active text-primary">個人中心</a></li>
                </ul>
            </div>
            <div class="card-body">
                <div class="row d-flex justify-content-center">
                    <div class="col-10">
                        <h5 class="card-title text-left my-4"><small><i class="ti-crown"></i>
                                {{ $user_level->name }}</small></h5>
                        <div class="card">
                            <div class="card-body mt-4">
                                <form class="border px-4 pt-2 pb-3" method="POST"
                                    action="{{ route('user-profile-update', $profile->id) }}">
                                    {{ csrf_field() }}
                                    <div class="row d-flex justify-content-center">
                                        <div class="form-group col-lg-6 col-12">
                                            <label for="inputTitle" class="col-form-label">姓名</label>
                                            <input id="inputTitle" type="text" name="name" placeholder="輸入姓名"
                                                value="{{ $profile->name }}" class="form-control">
                                            {{-- @error('name')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror --}}
                                        </div>

                                        <div class="form-group col-lg-6 col-12">
                                            <label for="inputEmail" class="col-form-label">Email</label>
                                            <input id="inputEmail" type="email" name="email" placeholder="輸入 Email"
                                                value="{{ $profile->email }}" class="form-control">
                                            {{-- @error('email')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror --}}
                                        </div>
                                        <div class="form-group col-lg-6 col-12">
                                            <label for="inputPhone" class="col-form-label">手機</label>
                                            <input id="inputPhone" type="phone" name="phone" placeholder="輸入手機"
                                                value="{{ $profile->phone }}" class="form-control">
                                            {{-- @error('phone')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror --}}
                                        </div>
                                        <div class="form-group col-lg-6 col-12">
                                            <label for="inputAddress" class="col-form-label">地址</label>
                                            <input id="inputAddress" type="address" name="address" placeholder="輸入地址"
                                                value="{{ $profile->address }}" class="form-control">
                                            {{-- @error('address')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror --}}
                                        </div>
                                        <button type="submit" class="btn btn-success btn-sm">儲存變更</button>
                                    </div>
                                </form>
                                <div class="text-center">
                                    <i class="ti-lock"></i><a href="{{ route('user-change-password') }}">變更密碼</a>
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
