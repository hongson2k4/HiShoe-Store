@extends('admin.layout.main')
@section('content')
<div class="container mt-4">
    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

    <h2 class="mb-3">Danh sách Sizes</h2>
    <a href="{{ route('sizes.create') }}" class="btn btn-primary mb-3">Thêm Size</a>

    <table class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Size</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sizes as $size)
            <tr>
                <td>{{ $size->id }}</td>
                <td>{{ $size->size }}</td>
                <td>
                    <a href="{{ route('sizes.edit', $size->id) }}" class="btn btn-warning btn-sm">Sửa</a>
                    <form action="{{ route('sizes.destroy', $size->id) }}" method="POST" style="display:inline;">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa?')">Xóa</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
