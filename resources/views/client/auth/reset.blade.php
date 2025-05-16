@extends('client.layout.main')
@section('title')
HiShoe-Store - Đặt lại mật khẩu
@endsection
@section('content')

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header text-center">
                    <h4>Đặt lại mật khẩu</h4>
                </div>
                <div class="card-body">
                    
                @if ($errors->any())
                        <div class="alert alert-danger mt-3">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('password.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="form-group">
                            <label for="email_or_phone">Email hoặc Số điện thoại</label>
                            <input type="text" name="email_or_phone" class="form-control" value="{{ old('email_or_phone') }}" required autofocus>
                        </div>

                        <div class="form-group">
                            <label for="otp">Mã OTP</label>
                            <input type="text" name="otp" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="password">Mật khẩu mới</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation">Xác nhận mật khẩu</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>

                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-primary btn-block">Đặt lại mật khẩu</button>
                        </div>
                    </form>


                    @if (session('status'))
                        <div class="alert alert-success mt-3">
                            {{ session('status') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection