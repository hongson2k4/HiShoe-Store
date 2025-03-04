@extends('admin.layout.main')
<!-- @section('title')
    danh sách
@endsection -->
@section('content')
    <a class="btn btn-success m-2" href="{{route('products.create')}}">Thêm mới biến thể sản phẩm</a>
    <form action="{{ route('products.list') }}" method="GET" class="form-inline mb-3 float-right">
    <input type="text" name="search" class="form-control mr-2" placeholder="Search products"
    value="{{ request()->query('search') }}">
        <button type="submit" class="btn btn-success">Search</button>
    </form>
    <table class="table">
        <thead>
            <tr>
                <th>STT</th>
                <th>Tên sản phẩm</th>
                <th>Kích thước</th>
                <th>Màu sắc</th>
                <th>Mã màu</th>
                <th>Giá</th>
                <th>Số lượng</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products_variant as $key=>$u)
            <tr>
                <td>{{$key + 1}}</td>
                <td>{{$u->product->name}}</td>
                <td>{{$u->size->size}}</td>
                <td>{{$u->color->name}}</td>
                <td>
                    <div class="d-flex align-items-center">
                        <span class="me-2 m-2">{{$u->color->code}}</span>
                        <div style="width: 30px; height: 30px; background-color: {{$u->color->code}}; border: 1px solid #ccc;"></div>
                    </div>
                </td>                
                <td>{{$u->price}}</td>
                <td>{{$u->stock_quantity}}</td>
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
