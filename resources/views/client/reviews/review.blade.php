@extends('client.layout.main')
@section('content')

<div class="container"  style="margin-top: 100px;">
    <h2 class="text-center mb-5">Đánh giá sản phẩm</h2>

    <!-- Hiển thị thông tin sản phẩm -->
    <div class="card mb-4">
        <div class="row g-0">
            <div class="col-md-4">
                <img src="{{ asset('storage/' . $product->image_url) }}" class="img-fluid rounded-start" alt="{{ $product->name }}">
            </div>
            <div class="col-md-8">
                <div class="card-body">
                    <h4 class="card-title">{{ $product->name }}</h4>
                    <p class="card-text">{{ $product->description }}</p>
                    <p class="card-text"><strong>Giá:</strong> {{ number_format($product->price, 0, ',', '.') }} VNĐ</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Hiển thị thông báo -->
    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

    <!-- Form đánh giá -->
    <div class="card mt-4">
        <div class="card-body">
            <h5>Viết đánh giá</h5>
    
            <form action="{{ route('orders.review.store', [$order->id, $product->id]) }}" method="POST">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="order_id" value="{{ $order->id }}">

    
                <div class="mb-3">
                    <label for="rating" class="form-label">Chọn số sao</label>
                    <select name="rating" id="rating" class="form-control" required>
                        <option value="5">⭐ 5 - Tuyệt vời</option>
                        <option value="4">⭐ 4 - Tốt</option>
                        <option value="3">⭐ 3 - Trung bình</option>
                        <option value="2">⭐ 2 - Kém</option>
                        <option value="1">⭐ 1 - Rất tệ</option>
                    </select>
                </div>
    
                <div class="mb-3">
                    <label for="comment" class="form-label">Nhận xét</label>
                    <textarea name="comment" id="comment" class="form-control" rows="3" required></textarea>
                </div>
    
                <button type="submit" class="btn btn-primary">Gửi đánh giá</button>
            </form>
        </div>
    </div>
    
    <div class="review-container">
        <div class="review-header">⭐ Đánh giá sản phẩm</div>
    
        @if ($totalReviews > 0)
            <p>⭐ Trung bình: {{ $averageRating }} / 5</p>
            <p>📢 Tổng số đánh giá: {{ $totalReviews }}</p>
    
            @foreach($reviews as $review)
                <div class="review-item">
                    <strong>{{ $review->user->full_name }}</strong> - 
                    <span class="review-stars">⭐ {{ $review->rating }}/5</span>
                    <p class="review-text">{{ $review->comment }}</p>
                </div>
            @endforeach
        @else
            <p class="no-reviews">Chưa có đánh giá nào cho sản phẩm này. Hãy là người đầu tiên đánh giá! ⭐</p>
        @endif
    </div>

    <a href="#" class="btn btn-secondary mt-3">Quay lại sản phẩm</a>
</div>
@endsection

