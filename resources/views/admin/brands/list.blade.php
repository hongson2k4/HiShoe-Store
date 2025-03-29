@extends('admin.layout.main')

@section('content')
<div class="container-fluid mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2 class="mb-0">Brand Management</h2>
            <a class="btn btn-primary" href="{{ route('brands.create') }}">
                <i class="fas fa-plus-circle me-1"></i> Add New Brand
            </a>
        </div>
        <div class="card-body">
            {{-- Search and Filter Form --}}
            <form action="{{ route('brands.index') }}" method="GET" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-4">
                        <input type="text" 
                               name="search" 
                               class="form-control" 
                               placeholder="Search by brand name" 
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">All Statuses</option>
                            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>
                                Active Brands
                            </option>
                            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>
                                Inactive Brands
                            </option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-outline-primary">
                            <i class="fas fa-filter me-1"></i> Apply Filter
                        </button>
                    </div>
                    <div class="col-md-3 text-end">
                        <a href="{{ route('brands.index') }}" class="btn btn-secondary">
                            <i class="fas fa-refresh me-1"></i> Reset Filters
                        </a>
                    </div>
                </div>
            </form>

            {{-- Brands Table --}}
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($brands as $brand)
                        <tr>
                            <td>{{ $brand->id }}</td>
                            <td>{{ $brand->name }}</td>
                            <td>{{ Str::limit($brand->description, 50) }}</td>
                            <td>
                                <span class="badge {{ $brand->status ? 'bg-success text-dark' : 'bg-secondary text-light' }}">
                                    {{ $brand->status ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('brands.edit', $brand) }}" 
                                       class="btn btn-sm btn-warning me-1" 
                                       title="Edit Brand">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('brands.toggle', $brand) }}" 
                                          method="POST" 
                                          class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" 
                                                class="btn btn-sm text-light {{ $brand->status ? 'btn-secondary text-danger' : 'btn-success ' }}"
                                                onclick="return confirm('{{ $brand->status ? 'Deactivate' : 'Activate' }} this brand?')"
                                                title="{{ $brand->status ? 'Deactivate' : 'Activate' }} Brand">
                                            <i class="fas fa-{{ $brand->status ? 'ban' : 'check' }}"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    No brands found. Create your first brand!
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted">
                    Showing {{ $brands->firstItem() }} to {{ $brands->lastItem() }} 
                    of {{ $brands->total() }} entries
                </div>
                <div>
                    {{ $brands->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection