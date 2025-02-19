@extends('admin.layout.main')
@section('title',"nhãn hàng")
@section('content')
    <h1>Nhãn Hàng</h1>
    <div class="container mt-4">


        <form action="{{ route('category.list') }}" method="get" class="d-flex">
            <input type="text" name="search" class="form-control me-2" placeholder="Nhập Tên Danh Mục..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-danger"><i class="fa fa-search"></i></button>
        </form>




        <h2 class="mb-4">Brand List</h2>
        <a href="{{route('brands.create')}}" class="btn btn-outline-primary">Thêm mới</a>
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
                @foreach($category as $cate)
                <tr>
                    <td>{{ $cate->id }}</td>
                    <td>{{ $cate->name }}</td>
                    <td>{{ $cate->description }}</td>
                    <td>
                        <form action="{{ route('brands.delete', $cate->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                        </form> |
                        <a href="{{route('brands.edit',$cate->id)}}" class="btn btn-dark">edit</a>
                    </td>
                </tr>
                @endforeach

            </tbody>
            {{-- {{$cate->links()}} --}}
        </table>
    </div>
@endsection
