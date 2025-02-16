@extends('admin.layout.main')
@section('content')
<div class="container mt-4">
    <h2 class="mb-3">Danh Sách Màu</h2>
    <a href="{{ route('colors.create') }}" class="btn btn-primary mb-3">Thêm Màu</a>

    <table class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Tên Màu</th>
                <th>Mã Màu</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($colors as $color)
            <tr>
                <td>{{ $color->id }}</td>
                <td>{{ $color->name }}</td>
                <td>
                    <span class="badge" style="background-color: {{ $color->code }}; padding: 10px;">{{ $color->code }}</span>
                </td>
                <td>
                    <a href="{{ route('colors.edit', $color->id) }}" class="btn btn-warning btn-sm">Sửa</a>
                    <form action="{{ route('colors.destroy', $color->id) }}" method="POST" style="display:inline;">
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
