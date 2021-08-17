<header class="header shop">
    <!-- Topbar -->
    <div class="topbar">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-12 text-right">
                    <!-- Top Right -->
                    <div class="right-content">
                        <ul class="list-main">
                            {{-- <li><i class="ti-location-pin"></i> <a href="{{route('order.track')}}">繁體中文</a></li> --}}
                            {{-- <li><i class="ti-alarm-clock"></i> <a href="#">Daily deal</a></li> --}}
                            @auth
                                {{-- todo --}}
                                <li><i class="ti-location-pin"></i><a href="{{ route('login.form') }}">English</a>
                                </li>
                                <li><i class="ti-user"></i> <a href="{{ route('zshop-user-home') }}"
                                        target="_blank">會員中心</a>
                                </li>
                                <li><a href="{{ route('zshop-cart') }}" class="single-icon"><i class="ti-shopping-cart"></i>
                                        <span class="total-count">{{ Helper::cartCount() }}</span></a></li>
                                <li><i class="ti-email"></i><a href="{{ route('zshop-contact') }}">聯絡客服</a>
                                </li>

                            @else
                                <li><i class="ti-location-pin"></i><a href="{{ route('login.form') }}">English</a>
                                </li>
                                <li><i class="ti-power-off"></i><a href="{{ route('zshop-login-register') }}">登入 /
                                        註冊</a></li>
                                <li><i class="ti-email"></i><a href="{{ route('zshop-contact') }}">聯絡客服</a>
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
                            $settings = DB::table('settings')->get();
                        @endphp
                        <a href="{{ route('zshop-index') }}"><img src="@foreach ($settings
                                as $data) {{ $data->logo }} @endforeach" alt="logo"></a>
                    </div>
                    <!--/ End Logo -->
                    <!-- Search Form -->
                    <div class="search-top">
                        <div class="top-search"><a href="#0"><i class="ti-search"></i></a></div>
                        <!-- Search Form -->
                        <div class="search-top">
                            <form class="search-form">
                                <input type="text" placeholder="Search here..." name="search">
                                <button value="search" type="submit"><i class="ti-search"></i></button>
                            </form>
                        </div>
                        <!--/ End Search Form -->
                    </div>
                    <!--/ End Search Form -->
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
                                            @foreach ($category as $cat_info)
                                                <li><a href="{{ route('zshop-productlist-category',['slug'=>$cat_info->slug,'title'=>$cat_info->title]) }}">{{ $cat_info->title }}</a></li>
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
                        <form class="form-inline col-12 my-2 my-lg-0">
                            <input class="form-control col-7 mr-sm-2" type="search" placeholder="商品關鍵字"
                                aria-label="Search">
                            <button class="col-4 btn btn-outline-success my-sm-0" type="submit">搜尋</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Header Inner -->
    <div class="header-inner">
        <div class="container">
            <div class="cat-nav-head">
                <div class="row">
                    <div class="col-lg-12 col-12">
                        {{-- <div class="menu-area">
                            <!-- Main Menu -->
                            <nav class="navbar navbar-expand-lg">
                                <div class="navbar-collapse">
                                    <div class="nav-inner">
                                        <ul class="nav main-menu menu navbar-nav">
                                            <li class="{{ Request::path() == 'home' ? 'active' : '' }}"><a
                                                    href="{{ route('home') }}">Home</a></li>
                                            <li class="{{ Request::path() == 'about-us' ? 'active' : '' }}"><a
                                                    href="{{ route('about-us') }}">About Us</a></li>
                                            <li class="@if (Request::path() == 'product-grids' || Request::path() == 'product-lists') active @endif"><a
                                                    href="{{ route('product-grids') }}">Products</a><span
                                                    class="new">New</span></li>
                                            {{ Helper::getHeaderCategory() }}
                                            <li class="{{ Request::path() == 'blog' ? 'active' : '' }}"><a
                                                    href="{{ route('blog') }}">Blog</a></li>

                                            <li class="{{ Request::path() == 'contact' ? 'active' : '' }}"><a
                                                    href="{{ route('contact') }}">Contact Us</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </nav>
                            <!--/ End Main Menu -->
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--/ End Header Inner -->
</header>
