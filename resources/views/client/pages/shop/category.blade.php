@extends('client.layout.main')

@section('title', 'Danh mục: ' . $category->name)

@section('content')
<div class="container">
    <h2>Danh mục: {{ $category->name }}</h2>
    <div class="row">
        @foreach($products as $product)
        <div class="col-md-3">
            <div class="product-card">
                <a href="{{ route('detail', ['product_id' => $product->id]) }}">
                    <img src="{{ Storage::url($product->image_url) }}" alt="{{ $product->name }}" class="img-fluid">
                    <h5>{{ $product->name }}</h5>
                    <p>{{ number_format($product->price, 0, ',', '.') }} VND</p>
                </a>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection