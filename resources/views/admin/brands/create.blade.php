@extends('admin.layout.main')
@section('title',"thêm nhãn hàng")
@section('content')
<div class="container mt-4">
    <h2 class="mb-4">thêm nhãn hàng</h2>

    <form action="{{ route('brands.create') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Brand Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
        </div>
        <button type="submit" class="btn btn-success">Save</button>
        <a href="{{ route('brands.list') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
