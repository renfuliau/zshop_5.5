@extends('layouts.main')

@section('title', 'ZShop - 聯絡客服')

@section('main-content')
<!-- readcrumbs -->
<div class="breadcrumbs">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="bread-inner">
                    <ul class="bread-list">
                        <li><a href="{{ route('index') }}">首頁<i class="ti-arrow-right"></i></a></li>
                        <li class="active"><a href="javascript:void(0);">聯絡客服</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row d-flex justify-content-center">
            <div class="col-8">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-12 my-4">
                        <div class="card">
                            <div class="card-body">
                                <form class="form-contact form contact_form" method="post"
                                    action="{{ route('contact-store') }}" id="contactForm" novalidate="novalidate">
                                    {{ csrf_field() }}
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label>姓名<span>*</span></label>
                                                <input name="name" id="name" type="text" placeholder="輸入您的姓名">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label>Email<span>*</span></label>
                                                <input name="email" type="email" id="email"
                                                    placeholder="輸入您的 Email">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group message">
                                                <label>留言<span>*</span></label>
                                                <textarea name="message" id="message" cols="30" rows="4"
                                                    placeholder="輸入您的寶貴意見"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group button">
                                                <button type="submit" class="btn ">送出</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-12 my-4">
                        <div class="card">
                            <img class="card-img-top" src="{{ $photo_path }}" alt="{{ $photo_path }}">
                            <div class="card-body text-center">
                                <h5 class="card-title">加入 ZShop 與客服線上互動</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- @include('frontend.layouts.newsletter') --}}
<!-- End Shop Newsletter -->
<!--================Contact Success  =================-->


@endsection
@push('styles')
<style>
	.modal-dialog .modal-content .modal-header{
		position:initial;
		padding: 10px 20px;
		border-bottom: 1px solid #e9ecef;
	}
	.modal-dialog .modal-content .modal-body{
		height:100px;
		padding:10px 20px;
	}
	.modal-dialog .modal-content {
		width: 50%;
		border-radius: 0;
		margin: auto;
	}
</style>
@endpush
@push('scripts')
<script src="{{ asset('frontend/js/jquery.form.js') }}"></script>
<script src="{{ asset('frontend/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('frontend/js/contact.js') }}"></script>
@endpush
