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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    {{-- link jquery sắp xếp tăng dần, giảm dần --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    {{-- link bootstrap 5.3.0 --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

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
        </section>
        <!-- end shop section -->
    
        @include('client.layout.footer')
    
        {{-- Scripts của từng trang --}}
        @include('client.asset.script')
    
        {{-- Chèn script từ các file con --}}
        @stack('scripts')

        <!-- Scripts modal -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
</body>
</html>
