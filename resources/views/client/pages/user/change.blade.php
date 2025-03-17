@extends('client.layout.main')

@section('title', 'Change Password')

@section('content')
<div class="container">
    <h2>Đổi mật khẩu</h2>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('password.changeForm') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="old_password">Mật khẩu cũ</label>
            <input type="password" class="form-control" id="old_password" name="old_password" value="{{old('old_password')}}" required>
        </div>
        <div class="form-group">
            <label for="new_password">Mật khẩu mới</label>
            <input type="password" class="form-control" id="new_password" name="new_password" value="{{old('new_password')}}" required>
        </div>
        <div class="form-group">
            <label for="confirm_password">Xác nhận mật khẩu mới</label>
            <input type="password" class="form-control" id="confirm_password" name="confirm_password" value="{{old('confirm_password')}}" required>
        </div>
        <button type="submit" class="btn btn-primary">Đổi mật khẩu</button>
    </form>
</div>
@endsection