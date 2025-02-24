<!DOCTYPE html>
<html>

<head>
    <!-- Basic -->
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- Mobile Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <!-- Site Metas -->
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <link rel="shortcut icon" href="images/favicon.png" type="image/x-icon">

    <title>
    @yield('title')
    </title>

    @include('client.asset.style')

</head>

<body>
    <div class="hero_area">
        @include('client.layout.header')
    </div>
    <!-- end hero area -->

    <!-- slider section -->
    @if (Request::routeIs('home'))
    @include('client.layout.slider')
    @endif

    <!-- end slider section -->

    <!-- shop section -->
    <section class="shop_section layout_padding">
        @yield('content')
        @include('client.layout.footer')
    </section>
    <!-- end info section -->

</body>
@include('client.asset.script')

</html>