@extends('client.layout.main')
@section('title')
    HiShoe-Store - Quên mật khẩu
@endsection
@section('content')
    <div id="login">
        <h3 class="text-center pt-5">Quên mật khẩu</h3>
        <div class="container">
            <div id="login-row" class="row justify-content-center align-items-center">
                <div id="login-column" class="col-md-6">
                    <div id="login-box" class="col-md-12">
                        <form action="{{ route('password.email') }}" method="POST">
                            @csrf
                            @if ($errors->has('email'))
                                <span class="text-danger">{{ $errors->first('email') }}</span>
                            @endif
                            <div>
                                <label for="email">Địa chỉ Email</label>
                                <input type="email" id="email" name="email" required autofocus>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-success">Xác nhận</button>
                            </div>


                            @if (session('status'))
                                <div>
                                    {{ session('status') }}
                                </div>
                            @endif
                        </form>

                        <a href="{{ route('loginForm') }}">Quay về trang đăng nhập</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection