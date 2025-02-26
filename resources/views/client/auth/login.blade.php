@extends('client.layout.main')
@section('title')
HiShoe-Store - Đăng nhập
@endsection
@section('content')
<!-- <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script> -->
<!------ Include the above in your HEAD tag ---------->

<div id="login">
    <h3 class="text-center text-white pt-5">Login form</h3>
    <div class="container">
        <div id="login-row" class="row justify-content-center align-items-center">
            <div id="login-column" class="col-md-6">
                <div id="login-box" class="col-md-12">

                    <form class="form" action="{{ route('login') }}" method="post">
                        <h3 class="text-center text-info">Đăng nhập</h3>
                        @if(session()->has('error'))
                        <p style="color: red;">{{ session('error') }}</p>
                        @endif
                        @csrf
                        <div class="form-group">
                            <label for="username" class="text-info">Tên người dùng:</label><br>
                            <input type="text" name="username" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="password" class="text-info">Mật khẩu:</label><br>
                            <input type="text" name="password" class="form-control">
                        </div>
                        <div class="form-group">
                            <!-- <label for="remember-me" class="text-info"><span>Remember me</span> <span><input id="remember-me" name="remember-me" type="checkbox"></span></label><br> -->
                            <input type="submit" name="submit" class="btn btn-info btn-md" value="Đăng nhập">
                            <a href="{{ route('password.request') }}" class="text-info">Quên mật khẩu ?</a>
                        </div>
                        <div id="register-link" class="text-right">
                            Chưa có tài khoản! <a href="{{ route('registerForm')}}" class="text-info">Đăng ký</a> ngay!
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection