@extends('admin.layout.main')
@section('title',"sửa nhãn hàng")
@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Sửa nhãn hàng</h2>

    <form action="{{ route('brands.update',$brands->id) }}" method="POST">
        @method('PUT')
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Brand Name</label>
            <input type="text" class="form-control" value="{{ $brands->name }}" id="name" name="name" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control"  id="description"  name="description" rows="3">{{ $brands->description }}"</textarea>
        </div>
        <button type="submit" class="btn btn-success">Save</button>
        <a href="{{ route('brands.list') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
