@extends('admin.layout.main')

@section('content')
<div class="container mt-4">
    <h2 class="mb-3">Brand List</h2>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Form tìm kiếm và lọc --}}
    <form action="{{ route('brands.index') }}" method="GET" class="mb-3">
        <div class="row">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search by name" value="{{ request('search') }}">
            </div>
            <div class="col-md-4">
                <select name="status" class="form-control">
                    <option value="">-- Filter by Status --</option>
                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Hidden</option>
                </select>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
        </div>
    </form>

    {{-- Form thêm brand --}}
    <form action="{{ route('brands.store') }}" method="POST" class="mb-4">
        @csrf
        <div class="row">
            <div class="col-md-4">
                <input type="text" name="name" class="form-control" placeholder="Brand Name" required>
            </div>
            <div class="col-md-4">
                <input type="text" name="description" class="form-control" placeholder="Description">
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary">Add Brand</button>
            </div>
        </div>
    </form>

    {{-- Bảng danh sách brand --}}
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($brands as $brand)
            <tr>
                <td>{{ $brand->id }}</td>
                <td>{{ $brand->name }}</td>
                <td>{{ $brand->description }}</td>
                <td>
                    <a href="{{ route('brands.edit', $brand) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('brands.toggle', $brand) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PUT')
                        @if($brand->status)
                        <button type="submit" class="btn btn-secondary btn-sm" onclick="return confirm('Are you sure you want to hide this brand?')">
                            Deactive
                        </button>
                        @else
                        <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Are you sure you want to show this brand?')">
                            Active
                        </button>
                        @endif
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="d-flex justify-content-end">
        {{ $brands->links() }}
    </div>
</div>
@endsection