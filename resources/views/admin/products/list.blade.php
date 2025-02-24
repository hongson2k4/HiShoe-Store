@extends('admin.layout.main')
<!-- @section('title')
    danh sách
@endsection -->
@section('content')
    <a class="btn btn-success m-2" href="{{route('products.create')}}">Thêm mới sản phẩm</a>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Stock_quantity</th>
                <th>Category_id</th>
                <th>Brand_id</th>
                <th>Image_url</th>
                <th>Action</th>
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
                <td>{{$u->image_url}}</td>
                <td>
                <a class="btn btn-warning m-2" href="{{route('products.edit',$u->id)}}"><i class="fas fa-pencil-alt"></i></a>
                    <form action="{{route('products.destroy',[$u->id])}}" method="post">
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
