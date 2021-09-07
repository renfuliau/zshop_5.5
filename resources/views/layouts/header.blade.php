<header class="header shop">
    <!-- Topbar -->
    <div class="topbar">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-12 text-right">
                    <!-- Top Right -->
                    <div class="right-content">
                        <ul class="list-main">
                            @auth
                                {{-- todo --}}
                                <li><i class="ti-location-pin"></i><a
                                        href="{{ route('locale-change') }}">{{ __('auth.header-language') }}</a>
                                </li>
                                <li><i class="ti-user"></i> <a
                                        href="{{ route('user-profile') }}">{{ __('auth.header-user-center') }}</a>
                                </li>
                                <li><a href="{{ route('cart') }}" class="single-icon"><i class="ti-shopping-cart"></i>
                                        <span
                                            class="cartTotalQuantity">{{ \Cart::session(Auth::user()->id)->getTotalQuantity() }}</span>
                                    </a></li>
                                <li><i class="ti-email"></i><a
                                        href="{{ route('contact') }}">{{ __('auth.header-contact') }}</a>
                                </li>
                                <li><i class="ti-shift-right-alt"></i><a
                                        href="{{ route('z-logout') }}">{{ __('auth.header-logout') }}</a></li>

                            @else
                                <li><i class="ti-location-pin"></i><a
                                        href="{{ route('locale-change') }}">{{ __('auth.header-language') }}</a>
                                </li>
                                <li><i class="ti-power-off"></i><a
                                        href="{{ route('z-login') }}">{{ __('auth.header-login-register') }}</a></li>
                                <li><i class="ti-email"></i><a
                                        href="{{ route('contact') }}">{{ __('auth.header-contact') }}</a>
                                </li>
                            @endauth
                        </ul>
                    </div>
                    <!-- End Top Right -->
                </div>
            </div>
        </div>
    </div>
    <!-- End Topbar -->
    <div class="middle-inner bg-dark">
        <div class="container">
            <div class="row">
                <div class="col-lg-2 col-md-2 col-12">
                    <!-- Logo -->
                    <div class="logo">
                        @php
                            // dd($cart_total_qty);
                            $settings = DB::table('settings')->get();
                        @endphp
                        <a href="{{ route('index') }}"><img src="@foreach ($settings as $data) {{ $data->logo }} @endforeach" alt="logo"></a>
                    </div>
                    <!--/ End Logo -->
                    <div class="mobile-nav"></div>
                </div>
                <div class="col-lg-7 col-md-6 col-12">
                    <div class="search-bar-top">
                        <div class="menu-area">
                            <!-- Main Menu -->
                            <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
                                <div class="navbar-collapse col-12">
                                    <div class="nav-inner col-12">
                                        <ul class="nav main-menu menu navbar-nav d-flex justify-content-between">
                                            @foreach ($categories as $category)
                                                @if (App::getLocale() == 'zh-tw')
                                                    <li><a
                                                            href="{{ route('productlist-category', ['slug' => $category->slug, 'title' => $category->title]) }}">{{ $category->title }}</a>
                                                    </li>
                                                @else
                                                    <li><a
                                                            href="{{ route('productlist-category', ['slug' => $category->slug, 'title' => $category->title]) }}">{{ $category->slug }}</a>
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </nav>
                            <!--/ End Main Menu -->
                        </div>

                    </div>
                </div>
                <div class="col-lg-3 col-md-4 col-12">
                    <div class="right-bar col-12">
                        <!-- Search Form -->
                        <form class="form-inline col-12 my-2 my-lg-0" method="POST"
                            action="{{ route('product-search') }}">
                            {{ csrf_field() }}

                            @if (!empty($keyword))
                                <input id="keyword" name="keyword" class="form-control col-7 mr-sm-2" type="search"
                                    value="{{ $keyword }}" aria-label="Search">
                            @else
                                <input id="keyword" name="keyword" class="form-control col-7 mr-sm-2" type="search"
                                    placeholder="{{ __('auth.header-search-placeholder') }}" aria-label="Search">
                            @endif
                            <button id="search-btn" class="col-4 btn btn-outline-success my-sm-0"
                                type="submit">{{ __('auth.header-search-btn') }}</button>
                        </form>
                        {{-- @if (!empty($keyword))
                            <input id="keyword" name="keyword" class="form-control col-7 mr-sm-2" type="text"
                                value="{{ $keyword }}" aria-label="Search">
                            <a class="col-4 search-btn" href="{{ route('product-search', ['keyword' => $keyword]) }}">搜尋</a>
                        @else
                            <input id="keyword" name="keyword" class="form-control col-7 mr-sm-2" type="text"
                                placeholder="{{ __('auth.header-search-placeholder') }}" aria-label="Search">
                            <a class="col-4 search-btn" href="{{ route('product-search', ['keyword' => '']) }}">搜尋</a>
                        @endif --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
