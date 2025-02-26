@extends('client.layout.main')
@section('title')
HiShoe-Store - Đặt lại mật khẩu
@endsection
@section('content')

<form action="{{ route('password.update') }}" method="POST">
    @csrf
    <input type="hidden" name="token" value="{{ $token }}">

    <div>
        <label for="email">Email</label>
        <input type="email" name="email" value="{{ old('email', $email) }}" required autofocus>
    </div>

    <div>
        <label for="password">New Password</label>
        <input type="password" name="password" required>
    </div>

    <div>
        <label for="password_confirmation">Confirm Password</label>
        <input type="password" name="password_confirmation" required>
    </div>

    <div>
        <button type="submit">Reset Password</button>
    </div>
</form>

@if ($errors->any())
    <div>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (session('status'))
    <div>
        {{ session('status') }}
    </div>
@endif

@endsection