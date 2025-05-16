@extends('admin.layout.main')
@section('title', "thêm danh muc")
@section('content')
    <div class="container mt-4">
        <h2 class="mb-4">thêm danh muc </h2>

        <form action="{{ route('category.create') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Tên danh mục</label>
                <input type="text" class="form-control" id="name" name="name" >
            </div>
            @error('name')
                <span class="text-danger">{{$message}}</span>
            @enderror
            <div class="mb-3">
                <label for="description" class="form-label">Mô tả</label>
                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
            </div>
            @error('description')
                <span class="text-danger">{{$message}}</span>
            @enderror
            <button type="submit" class="btn btn-success">Lưu</button>
            <a href="{{ route('category.list') }}" class="btn btn-secondary">Trở lại</a>
        </form>
    </div>
@endsection
