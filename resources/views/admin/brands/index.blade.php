@extends('admin.layout.main')
@section('title',"nhãn hàng")
@section('content')
    <h1>Nhãn Hàng</h1>
    <div class="container mt-4">
        <a href="{{route('brands.create')}}" class="btn btn-outline-primary">Thêm mới</a>
        <h2 class="mb-4">Brand List</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($brands as $brand)
                <tr>
                    <td>{{ $brand->id }}</td>
                    <td>{{ $brand->name }}</td>
                    <td>{{ $brand->description }}</td>
                    <td>
                        <form action="{{ route('brands.delete', $brand->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                        </form> |
                        <a href="{{route('brands.edit',$brand->id)}}" class="btn btn-dark">edit</a>
                    </td>
                </tr>
                @endforeach

            </tbody>
        </table>
    </div>
@endsection
