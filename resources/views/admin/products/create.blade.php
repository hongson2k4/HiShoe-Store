@extends('admin.layout.main')
@section('content')
<form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="mb-3">
        <label class="form-label">Tên sản phẩm</label>
        <input class="form-control @error('name') is-invalid @enderror" type="text" name="name" value="{{old('name')}}">
        @error('name')
        <div class="invalid-feedback">
            {{$message}}
        </div>

        @enderror
    </div>
    <div class="mb-3">
        <label class="form-label">Ghi chú</label>   
        <textarea class="form-control @error('description') is-invalid @enderror" type="text" name="description" value="{{old('description')}}" aria-label="With textarea"></textarea>
        @error('description')
        <div class="invalid-feedback">
            {{$message}}
        </div>

        @enderror
    </div>
    <div class="mb-3">
        <label class="form-label">Giá</label>
        <input class="form-control @error('price') is-invalid @enderror" type="number" min=1 name="price" value="{{old('price')}}">
        @error('price')
        <div class="invalid-feedback">
            {{$message}}
        </div>

        @enderror
    </div>
    <div class="mb-3">
        <label class="form-label" class="form-control @error('category_id') is-invalid @enderror" type="text" name="category_id" value="{{old('category_id')}}">Danh mục</label>
            <select name="category_id" id="" class="form-control">
            <option value="">-- Chọn danh mục --</option>
                @foreach ($categories as $cate)
                    <option value="{{$cate->id}}">
                        {{$cate->name}}
                    </option>
                @endforeach
            </select>
            @error('category_id')
                <div class="invalid-feedback">
                    {{$message}}
                </div>
            @enderror
    </div>
    <div class="mb-3">
        <label class="form-label" class="form-control @error('brand_id') is-invalid @enderror" type="text" name="brand_id" value="{{old('brand_id')}}">Thương hiệu</label>
        <select name="brand_id" id="" class="form-control">
            <option value="">-- Chọn thương hiệu --</option>
            @foreach ($brands as $br)
                <option value="{{$br->id}}">
                    {{$br->name}}
                </option>
            @endforeach
        </select>
        @error('brand_id')
            <div class="invalid-feedback">
                {{$message}}
            </div>
        @enderror
    </div>
    <div class="mb-3">
        <label class="form-label">Chọn file ảnh</label>
        <input class="form-control m-2 @error('image_url') is-invalid @enderror" type="file" name="image_url" id="imageUpload" value="{{old('image_url')}}">
        <div class="image-preview" id="imagePreview">
            <img src="" alt="" class="image-preview__image" height="100">
            <span class="image-preview__default-text"></span>
        </div>
        @error('image_url')
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