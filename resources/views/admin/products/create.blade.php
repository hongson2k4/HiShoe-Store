@extends('admin.layout.main')
@section('content')
<form action="{{route('products.store')}}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="mb-3">
        <label class="form-label">Username</label>
        <input class="form-control @error('username') is-invalid @enderror" type="text" name="username" value="{{old('username')}}">
        @error('username')
        <div class="invalid-feedback">
            {{$message}}
        </div>

        @enderror
    </div>
    <div class="mb-3">
        <label class="form-label">Password</label>
        <input class="form-control @error('password') is-invalid @enderror" type="text" name="password" value="{{old('password')}}">
        @error('password')
        <div class="invalid-feedback">
            {{$message}}
        </div>

        @enderror
    </div>
    <div class="mb-3">
        <label class="form-label">Full name</label>
        <input class="form-control @error('full_name') is-invalid @enderror" type="text" name="full_name" value="{{old('full_name')}}">
        @error('full_name')
        <div class="invalid-feedback">
            {{$message}}
        </div>

        @enderror
    </div>
    <div class="mb-3">
        <label class="form-label">Email</label>
        <input class="form-control @error('email') is-invalid @enderror" type="email" name="email" value="{{old('email')}}">
        @error('email')
        <div class="invalid-feedback">
            {{$message}}
        </div>

        @enderror
    </div>
    <div class="mb-3">
        <label class="form-label">Avatar</label>
        <input class="form-control @error('avatar') is-invalid @enderror" type="file" name="avatar" id="imageUpload" value="{{old('avatar')}}">
        <div class="image-preview" id="imagePreview">
            <img src="" alt="" class="image-preview__image" height="100">
            <span class="image-preview__default-text"></span>
        </div>
        @error('avatar')
        <div class="invalid-feedback">
            {{$message}}
        </div>

        @enderror
    </div>
    <div class="mb-3">
        <label class="form-label">Phone number</label>
        <input class="form-control @error('phone_number') is-invalid @enderror" type="text" name="phone_number" value="{{old('phone_number')}}">
        @error('phone_number')
        <div class="invalid-feedback">
            {{$message}}
        </div>

        @enderror
    </div>
    <div class="mb-3">
        <label class="form-label">Address</label>
        <input class="form-control @error('address') is-invalid @enderror" type="text" name="address" value="{{old('address')}}">
        @error('address')
        <div class="invalid-feedback">
            {{$message}}
        </div>

        @enderror
    </div>
    <button type="submit" class="btn btn-success">Thêm mới</button>
</form>

<script>
    const imageUpload = document.getElementById('imageUpload');
    const imagePreview = document.getElementById('imagePreview');
    const previewImage = imagePreview.querySelector('.image-preview__image');
    const previewDefaultText = imagePreview.querySelector('.image-preview__default-text');

    imageUpload.addEventListener('change', function() {
        const file = this.files[0];

        if (file) {
            const reader = new FileReader();

            previewDefaultText.style.display = "none";
            previewImage.style.display = "block";

            reader.addEventListener('load', function() {
                previewImage.setAttribute('src', this.result);
            });

            reader.readAsDataURL(file);
        } else {
            previewDefaultText.style.display = null;
            previewImage.style.display = null;
            previewImage.setAttribute('src', '');
        }
    });
</script>
@endsection