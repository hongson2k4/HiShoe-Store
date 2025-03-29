@extends('admin.layout.main')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h2>Create New Brand</h2>
        </div>
        <div class="card-body">
            <form action="{{ route('brands.store') }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label class="form-label">Brand Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" 
                           class="form-control @error('name') is-invalid @enderror" 
                           value="{{ old('name') }}" 
                           required 
                           maxlength="255"
                           placeholder="Enter brand name">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" 
                              class="form-control" 
                              maxlength="1000" 
                              rows="4"
                              placeholder="Optional brand description">{{ old('description') }}</textarea>
                </div>
                
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary">
                        Create Brand
                    </button>
                    <a href="{{ route('brands.index') }}" class="btn btn-secondary">
                        Back to List
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection