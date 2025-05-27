@extends('admin.layout.main')
@section('content')
<form action="{{ route('vouchers.update', $voucher->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label class="form-label d-block">Trạng thái</label>
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="status" id="statusSwitch"
                value="1" {{ $voucher->status == 1 ? 'checked' : '' }}>
            <label class="form-check-label" for="statusSwitch">Kích hoạt</label>
        </div>
        @error('status')
        <div class="invalid-feedback d-block">
            {{$message}}
        </div>
        @enderror
    </div>
    <button type="submit" class="btn btn-primary">Cập nhật trạng thái</button>
</form>
@endsection
