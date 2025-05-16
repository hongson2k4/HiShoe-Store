@extends('admin.layout.main')
<!-- @section('title')
    danh sách
@endsection -->
@section('content')
    <!-- Display error and success messages -->
    @if(session()->has('error'))
        <p style="color: red;">{{ session('error') }}</p>
    @endif
    @if(session()->has('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    <!-- Search Form -->
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

    <!-- Users Table -->
    <table class="table container-fluid">
        <thead>
            <tr>
                <th>STT</th>
                <th>Họ và tên</th>
                <th>Email</th>
                <th>Số điện thoại</th>
                <th>Địa chỉ</th>
                <th>Quyền</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $key => $u)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $u->full_name }}</td>
                    <td>{{ $u->email }}</td>
                    <td>{{ $u->phone_number }}</td>
                    <td>{{ $u->address }}</td>
                    <td>{{ $u->role == 0 ? 'Users' : 'Admin' }}</td>
                    <td>
                        <span class="{{ $u->status == 0 ? 'badge bg-success text-white px-2 rounded' : 'badge bg-danger text-white px-2 rounded' }}">
                            {{ $u->status == 0 ? 'Hoạt động' : 'Bị khóa' }}
                        </span>
                    </td>
                    <td>
                        @if($u->role == 0)

                        <a class="btn btn-info" href="{{ route('users.show', $u->id) }}"><i class="fas fa-eye"></i></a>
                        @if($u->status == 0)
                            <button class="btn btn-info ban-user" data-toggle="modal" data-target="#banModal" data-userid="{{ $u->id }}"><i class="fas fa-lock"></i></button>
                        @else
                            <a class="btn btn-secondary" href="{{ route('users.unban', $u->id) }}"><i class="fas fa-unlock"></i></a>
                        @endif
                        <a class="btn btn-info" href="{{ route('users.history', $u->id) }}"><i class="fas fa-history"></i></a>
                        @else
                        Không khả dụng
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Ban User Modal -->
    <div class="modal fade" id="banModal" tabindex="-1" aria-labelledby="banModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="banModalLabel">Khóa tài khoản</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('users.ban', $u->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="user_id" id="user_id">
                        <div class="form-group">
                            <label for="ban_reason" class="col-form-label">Lí do khóa:</label>
                            <textarea class="form-control" name="ban_reason" id="ban_reason" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Xác nhận</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- JavaScript to Handle Modal User ID -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const banButtons = document.querySelectorAll('.ban-user');
            const userIdInput = document.getElementById('user_id');

            banButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const userId = button.getAttribute('data-userid');
                    userIdInput.value = userId;
                });
            });
        });
    </script>
@endsection