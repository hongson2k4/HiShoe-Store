@extends('client.layout.main')
@section('content')

<div class="container">
    <h2>Đánh giá cho sản phẩm: {{ $product->name }}</h2>
    
    @if($reviews->isNotEmpty())
        <div class="reviews">
            @foreach($reviews as $review)
                <div class="review-item border p-3 mb-2">
                    <strong>{{ $review->user->name }}</strong>
                    <p>⭐ {{ $review->rating }}/5</p>
                    <p>{{ $review->comment }}</p>
                </div>
            @endforeach
        </div>
    @else
        <p>Chưa có đánh giá nào.</p>
    @endif

    <a href="{{ route('product.detail', $product->id) }}" class="btn btn-primary">Quay lại sản phẩm</a>
</div>
@endsection