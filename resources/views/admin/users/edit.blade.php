@extends('admin.layout.main')
@section('content')
    <form action="{{route('users.update',$user->id)}}" method="POST" enctype="multipart/form-data">
        @method('PUT')
        @csrf
        <div class="mb-3">
        <label class="form-label">Username</label>
        <input class="form-control @error('username') is-invalid @enderror"  type="text" name="username" value="{{old('username', $user['username'])}}">
        @error('username')
        <div class="invalid-feedback">
            {{$message}}
        </div>
            
        @enderror
    </div>
    <div class="mb-3">
        <label class="form-label">Password</label>
        <input class="form-control @error('password') is-invalid @enderror"  type="text" name="password" value="{{old('password', $user['password'])}}">
        @error('password')
        <div class="invalid-feedback">
            {{$message}}
        </div>
            
        @enderror
    </div>
    <div class="mb-3">
        <label class="form-label">Full name</label>
        <input class="form-control @error('full_name') is-invalid @enderror"  type="text" name="full_name" value="{{old('full_name', $user['full_name'])}}">
        @error('full_name')
        <div class="invalid-feedback">
            {{$message}}
        </div>
            
        @enderror
    </div>
    <div class="mb-3">
        <label class="form-label">Email</label>
        <input class="form-control @error('email') is-invalid @enderror"  type="email" name="email" value="{{old('email', $user['email'])}}">
        @error('email')
        <div class="invalid-feedback">
            {{$message}}
        </div>
            
        @enderror
    </div>
    <div class="mb-3">
        <label class="form-label">Avatar</label>
        <input class="form-control @error('avatar') is-invalid @enderror"  type="file" name="avatar" value="{{old('avatar', $user['avatar'])}}">
        @error('avatar')
        <div class="invalid-feedback">
            {{$message}}
        </div>
            
        @enderror
    </div>
    <div class="mb-3">
        <label class="form-label">Phone number</label>
        <input class="form-control @error('phone_number') is-invalid @enderror"  type="text" name="phone_number" value="{{old('phone_number', $user['phone_number'])}}">
        @error('phone_number')
        <div class="invalid-feedback">
            {{$message}}
        </div>
            
        @enderror
    </div>
    <div class="mb-3">
        <label class="form-label">Address</label>
        <input class="form-control @error('address') is-invalid @enderror"  type="text" name="address" value="{{old('address', $user['address'])}}">
        @error('address')
        <div class="invalid-feedback">
            {{$message}}
        </div>
            
        @enderror
    </div>
    <button type="submit" class="btn btn-success">Sửa</button>
    </form>
@endsection