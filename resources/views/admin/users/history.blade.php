@extends('admin.layout.main')
@section('content')
    <div class="container-fluid">
        <h2>Lịch sử cập nhật tài khoản của {{ $user->full_name }}</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Trường thay đổi</th>
                    <th>Thay đổi bởi</th>
                    <th>Nội dung</th>
                    <th>Thời gian</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($history as $key => $change)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $change->field_name }}</td>
                        <td>{{ $change->changed_by->username }}</td>
                        <td>{{ $change->content }}</td>
                        <td>{{ $change->updated_at }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection