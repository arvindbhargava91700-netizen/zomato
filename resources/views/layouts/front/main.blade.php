<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ $companyName }}">
    <meta name="keywords" content="{{ $companyName }}">
    <meta name="author" content="{{ $companyName }}">
    <link rel="icon" href="{{ asset($companyFavicon) }}" type="image/x-icon">
    <title>@yield('title', $companyName)</title>
    <link rel="apple-touch-icon" href="{{ asset($companyFavicon) }}">
    <meta name="theme-color" content="#ff8d2f">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="{{ $companyName }}">
    <meta name="msapplication-TileImage" content="{{ asset($companyFavicon) }}">
    <meta name="msapplication-TileColor" content="#FFFFFF">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!--Google font-->
    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&amp;display=swap"
        rel="stylesheet">

    <!-- bootstrap css -->
    <link rel="stylesheet" type="text/css" id="rtl-link" href="{{ asset('front/assets/css/vendors/bootstrap.css') }}">

    <!-- swiper css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('front/assets/css/vendors/swiper-bundle.min.css') }}">

    <!-- remixicon css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('front/assets/css/vendors/remixicon.css') }}">

    <!-- Theme css -->
    <link rel="stylesheet" id="change-link" type="text/css" href="{{ asset('front/assets/css/style.css') }}">

    <!-- Custom css overrides -->
    <link rel="stylesheet" type="text/css" href="{{ asset('front/assets/css/custom.css') }}?v={{ time() }}">



</head>

<body class="position-relative">


<!-- 4777 style .css  -->
    @yield('skeleton')

    @include('layouts.front.header')

    @yield('content')

    @include('layouts.front.footer')

    @include('layouts.front.scripts')

    @stack('scripts')
</body>

</html>
