@extends('admin.layout.main')

@section('content')
    @if(session()->has('error'))
        <p class="text-danger">{{ session('error') }}</p>
    @endif
    @if(session()->has('success'))
        <p class="text-success">{{ session('success') }}</p>
    @endif

    <form action="{{ route('users.list') }}" method="GET" class="form-inline mb-3">
        <input type="text" name="search" class="form-control mr-2" placeholder="Tìm kiếm người dùng"
            value="{{ request()->query('search') }}">

        <select name="status" class="form-control mr-2">
            <option value="">Tất cả trạng thái</option>
            <option value="0" {{ request()->query('status') == '0' ? 'selected' : '' }}>Hoạt động</option>
            <option value="1" {{ request()->query('status') == '1' ? 'selected' : '' }}>Bị khóa</option>
        </select>

        <select name="role" class="form-control mr-2">
            <option value="">Quyền</option>
            <option value="0" {{ request()->query('role') == '0' ? 'selected' : '' }}>Người dùng</option>
            <option value="1" {{ request()->query('role') == '1' ? 'selected' : '' }}>Quản trị</option>
        </select>

        <select name="address" class="form-control mr-2">
            <option value="">Tất cả địa chỉ</option>
            @foreach($addresses as $address)
                <option value="{{ $address }}" {{ request()->query('address') == $address ? 'selected' : '' }}>{{ $address }}
                </option>
            @endforeach
        </select>

        <button type="submit" class="btn btn-primary">Tìm kiếm</button>
    </form>

    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover align-middle text-center">
            <thead class="table-dark">
                <tr>
                    <th style="width: 50px;">STT</th>
                    <th>Họ và tên</th>
                    <th>Email</th>
                    <th>Số điện thoại</th>
                    <th>Địa chỉ</th>
                    <th>Quyền</th>
                    <th>Trạng thái</th>
                    <th style="width: 150px;">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $key => $u)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td class="text-start">{{ $u->full_name }}</td>
                        <td class="text-start">{{ $u->email }}</td>
                        <td>{{ $u->phone_number }}</td>
                        <td class="text-start">{{ $u->address }}</td>
                        <td>{{ $u->role == 0 ? 'Users' : 'Admin' }}</td>
                        <td>
                            <span class="{{ $u->status == 0 ? 'badge bg-success' : 'badge bg-danger' }}">
                                {{ $u->status == 0 ? 'Hoạt động' : 'Bị khóa' }}
                            </span>
                        </td>
                        <td>
                            @if($u->role == 0)
                                <a class="btn btn-info btn-sm" href="{{ route('users.show', $u->id) }}" title="Xem chi tiết"><i
                                        class="fas fa-eye"></i></a>
                                @if($u->status == 0)
                                    <button class="btn btn-info btn-sm ban-user" data-toggle="modal" data-target="#banModal"
                                        data-userid="{{ $u->id }}" title="Khóa tài khoản"><i class="fas fa-lock"></i></button>
                                @else
                                    <a class="btn btn-secondary btn-sm" href="{{ route('users.unban', $u->id) }}" title="Mở khóa"><i
                                            class="fas fa-unlock"></i></a>
                                @endif
                                <a class="btn btn-info btn-sm" href="{{ route('users.history', $u->id) }}" title="Lịch sử"><i
                                        class="fas fa-history"></i></a>
                            @else
                                <span>Không khả dụng</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal code và script giữ nguyên như cũ -->
    <div class="modal fade" id="banModal" tabindex="-1" aria-labelledby="banModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="banModalLabel">Khóa tài khoản</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
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