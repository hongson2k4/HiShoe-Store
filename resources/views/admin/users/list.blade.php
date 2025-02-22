@extends('admin.layout.main')
<!-- @section('title')
    danh sách
@endsection -->
@section('content')
    <a class="btn btn-secondary" href="{{route('users.create')}}">Thêm mới</a>
    <form action="{{ route('users.list') }}" method="GET" class="form-inline mb-3">
        <input type="text" name="search" class="form-control mr-2" placeholder="Search users" value="{{ request()->query('search') }}">
        <button type="submit" class="btn btn-primary">Search</button>
    </form>
    <table class="table">
        <thead>
            <tr>
                <th>STT</th>
                <th>Username</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Number</th>
                <th>Address</th>
                <th>Role</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $key=>$u)
            <tr>
                <td>{{$key + 1}}</td>
                <td>{{$u->username}}</td>
                <td>{{$u->full_name}}</td>
                <td>{{$u->email}}</td>
                <td>{{$u->phone_number}}</td>
                <td>{{$u->address}}</td>
                <td>{{$u->role == 0 ? 'Users' : 'Admin'}}</td>
                <td>
                <a class="btn btn-warning" href="{{route('users.edit',$u->id)}}"><i class="fas fa-pencil-alt"></i></a>
                    <form action="{{route('users.destroy',[$u->id])}}" method="post">
                        @method('DELETE')
                        @csrf
                        <button class="btn btn-danger" onclick="return confirm('Xác nhận xóa ?')"> <i class="fas fa-trash"></i></button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection
