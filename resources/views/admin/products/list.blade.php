@extends('admin.layout.main')
<!-- @section('title')
    danh sách
@endsection -->
@section('content')
    <a class="btn btn-success m-2" href="{{route('products.create')}}">Thêm mới sản phẩm</a>
    <form action="search" method="GET" class="form-inline mb-3 float-right">
        <input type="text" name="search" class="form-control mr-2" placeholder="Search products" value="{{ request()->query('search') }}">
        <button type="submit" class="btn btn-success">Search</button>
    </form>
    <table class="table">
        <thead>
            <tr>
                <th>STT</th>
                <th>Tên Giày</th>
                <th>Ghi chú</th>
                <th>Giá</th>
                <th>Số lượng</th>
                <th>Loại giày</th>
                <th>Thương hiệu</th>
                <th>Hình ảnh</th>
                <th>Trang thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $key=>$u)
            <tr>
                <td>{{$key + 1}}</td>
                <td>{{$u->name}}</td>
                <td>{{$u->description}}</td>
                <td>{{$u->price}}</td>
                <td>{{$u->stock_quantity}}</td>
                <td>{{$u->category_id}}</td>
                <td>{{$u->brand_id}}</td>
                <td><img src="{{Storage::url($u->image_url)}}" width="100" alt=""></td>
                <td></td>
                <td>
                <a class="btn btn-warning m-2" href="{{route('products.edit',$u->id)}}"><i class="fas fa-pencil-alt"></i></a>
                    <form action="{{route('products.destroy',$u->id)}}" method="post">
                        @method('DELETE')
                        @csrf
                        <button class="btn btn-danger m-2" onclick="return confirm('Xác nhận xóa ?')"> <i class="fas fa-trash"></i></button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection
