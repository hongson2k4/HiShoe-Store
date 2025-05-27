@extends('admin.layout.main')
@section('content')
    <form enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
        <label class="form-label">Tên tài khoản</label>
        <input class="form-control @error('username') is-invalid @enderror"  type="text" name="username" value="{{old('username', $user['username'])}}" readonly>
        @error('username')
        <div class="invalid-feedback">
            {{$message}}
        </div>
            
        @enderror
    </div>
    <div class="mb-3">
        <label class="form-label">Tên người dùng</label>
        <input class="form-control @error('full_name') is-invalid @enderror"  type="text" name="full_name" value="{{old('full_name', $user['full_name'])}}" readonly>
        @error('full_name')
        <div class="invalid-feedback">
            {{$message}}
        </div>
            
        @enderror
    </div>
    <div class="mb-3">
        <label class="form-label">Email</label>
        <input class="form-control @error('email') is-invalid @enderror"  type="email" name="email" value="{{old('email', $user['email'])}}" readonly>
        @error('email')
        <div class="invalid-feedback">
            {{$message}}
        </div>
            
        @enderror
    </div>
    <div class="mb-3">
        <label class="form-label">Số điện thoại</label>
        <input class="form-control @error('phone_number') is-invalid @enderror"  type="text" name="phone_number" value="{{old('phone_number', $user['phone_number'])}}" readonly>
        @error('phone_number')
        <div class="invalid-feedback">
            {{$message}}
        </div>
            
        @enderror
    </div>
    <div class="mb-3">
        <label class="form-label">Địa chỉ</label>
        <input class="form-control @error('address') is-invalid @enderror"  type="text" name="address" value="{{old('address', $user['address'])}}" readonly>
        @error('address')
        <div class="invalid-feedback">
            {{$message}}
        </div>
            
        @enderror
    </div>
    <a href="{{ route('users.list') }}" class="btn btn-white">Trở lại</a>
    </form>
@endsection