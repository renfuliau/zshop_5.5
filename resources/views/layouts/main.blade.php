<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    @include('layouts.head')
</head>

<body class="js">
    @include('layouts.notification')
    <!-- Header -->
    @include('layouts.header')
    <!--/ End Header -->
    @yield('main-content')

    @include('layouts.footer') 

</body>

</html>
