@extends('admin.layout.main')
<!-- @section('title')
    danh sách
@endsection -->
@section('content')
<h2 class="text-center m-4">Danh sách và quản lý biến thể sản phẩm</h2>
<div class="text-center m-4">
    <a class="btn btn-success m-2" href="#">Quản lý danh mục</a>
    <a class="btn btn-success m-2" href="#">Quản lý thương hiệu</a>
    <a class="btn btn-success m-2" href="{{route('colors.index')}}">Quản lý màu sắc</a>
    <a class="btn btn-success m-2" href="{{route('sizes.index')}}">Quản lý kích thước</a>
</div>
<a class="btn btn-primary m-2" href="{{route('products_variant.create')}}">Thêm id sản phẩm đã có</a>
    <table class="table">
        <thead>
            <tr>
                <th>STT</th>
                <th>Tên sản phẩm</th>
                <th>Danh mục</th>
                <th>Thương hiệu</th>
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
                <td>{{$u->category->name}}</td>
                <td>{{$u->brand->name}}</td>
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
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection
