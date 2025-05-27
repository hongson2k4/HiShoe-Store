@extends('admin.layout.main')
@section('title',"Sửa Danh Mục")
@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Sửa Danh Mục</h2>

    <form action="{{ route('category.update',$category->id) }}" method="POST">
        @method('PUT')
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Danh mục</label>
            <input type="text" class="form-control" value="{{ $category->name }}" id="name" name="name" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Mô tả</label>
            <textarea class="form-control"  id="description"  name="description" rows="3">{{ $category->description }}"</textarea>
        </div>
        <button type="submit" class="btn btn-success">Lưu</button>
        <a href="{{ route('category.list') }}" class="btn btn-secondary">Trở lại</a>
    </form>
</div>
@endsection
