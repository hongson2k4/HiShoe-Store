@extends('admin.layout.main')
<!-- @section('title')
    danh sách
@endsection -->
@section('content')
    <form action="{{ route('users.list') }}" method="GET" class="form-inline mb-3">
        <input type="text" name="search" class="form-control mr-2" placeholder="Search users" value="{{ request()->query('search') }}">
        
        <select name="status" class="form-control mr-2">
            <option value="">All Statuses</option>
            <option value="0" {{ request()->query('status') == '0' ? 'selected' : '' }}>Active</option>
            <option value="1" {{ request()->query('status') == '1' ? 'selected' : '' }}>Locked</option>
        </select>
        
        <select name="role" class="form-control mr-2">
            <option value="">All Roles</option>
            <option value="0" {{ request()->query('role') == '0' ? 'selected' : '' }}>Users</option>
            <option value="1" {{ request()->query('role') == '1' ? 'selected' : '' }}>Admin</option>
        </select>
        
        <select name="address" class="form-control mr-2">
            <option value="">All Addresses</option>
            @foreach($addresses as $address)
                <option value="{{ $address }}" {{ request()->query('address') == $address ? 'selected' : '' }}>{{ $address }}</option>
            @endforeach
        </select>
        
        <button type="submit" class="btn btn-primary">Search</button>
    </form>
    <table class="table container-fluid ">
        <thead>
            <tr>
                <th>STT</th>
                <th>Username</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Number</th>
                <th>Address</th>
                <th>Role</th>
                <th>Status</th>
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
                    <span class="{{ $u->status == 0 ? 'bg-success text-white px-2 rounded' : 'bg-danger text-white px-2 rounded' }}">
                    {{ $u->status == 0 ? 'Active' : 'Locked' }}
                </span>
                </td>
                <td>
                <a class="btn btn-warning" href="{{route('users.edit',$u->id)}}"><i class="fas fa-pencil-alt"></i></a>
                @if($u->status == 0)
                <a class="btn btn-info" href="{{route('users.ban',$u->id)}}"><i class="fas fa-lock"></i></a>
                @else
                <a class="btn btn-secondary" href="{{route('users.ban',$u->id)}}"><i class="fas fa-unlock"></i></a>
                @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection