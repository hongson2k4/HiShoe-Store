@extends('admin.layout.main')
<!-- @section('title')
    danh sách
@endsection -->
@section('content')
<div class="container mt-4">
    <h2>Edit Brand</h2>
    <form action="{{ route('brands.update', $brand) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">Brand Name</label>
            <input type="text" name="name" class="form-control" value="{{ $brand->name }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Description</label>
            <input type="text" name="description" class="form-control" value="{{ $brand->description }}">
        </div>
        <button type="submit" class="btn btn-success">Update</button>
        <a href="{{ route('brands.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
