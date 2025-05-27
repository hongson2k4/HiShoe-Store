@extends('client.layout.main')

@section('content')
<div class="container">
    <h2 class="text-center">Sản phẩm đã thích</h2>
    <div class="row">
        @foreach($likedProducts as $product)
            <div class="col-sm-6 col-md-4 col-lg-3">
                <div class="box position-relative">
          
                    <button class=" btn btn-none dislike-btn"  data-product-id="{{ $product->id }}"  >
                        <span class="like-icon" 
                        style="cursor: pointer; font-size: 20px; color: red;">
                        &#10084;
                          
                        </span>
                         <span class="like-text">Đã thích</span>
                 </button>
           
                  

                    <a href="">
                        <div class="img-box">
                            <a href="{{ route('detail', $product->id) }}">    <img src="{{ $product->image_url ? Storage::url($product->image_url) : asset('images/default-product.jpg') }}" alt="{{ $product->name }}">
                           
                        </div>
                        <div class="detail-box">
                        <a class="card-title" href="{{ route('detail', $product->id) }}">{{ $product->name }}</a>
                        <h6>Giá: <span>{{ number_format($product->price) }} VND</span></h6>
                        </div>
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection

<script>
 document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".dislike-btn").forEach(function (heartIcon) {
        heartIcon.addEventListener("click", function () {
            let productId = this.getAttribute("data-product-id");

            // Hiển thị hộp thoại xác nhận
            let confirmUnLike = confirm("Bạn có chắc muốn bỏ thích sản phẩm này?");
            if (!confirmUnLike) {
                return; // Nếu chọn "Không", dừng lại
            }

            // Gửi yêu cầu tới server để bỏ thích
            fetch(`/products/${productId}/like`, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Content-Type": "application/json"
                }
            })
            .then(response => response.json())
            .then(data => {
                // Kiểm tra phản hồi từ server
                if (data.status === "unliked") {
                    // Xóa sản phẩm khỏi danh sách ngay lập tức
                    this.closest(".col-sm-6").remove(); // Xóa sản phẩm khỏi DOM
                } else if (data.error) {
                    alert(data.error); // Thông báo lỗi nếu có
                }
            })
            .catch(error => {
                console.error("Lỗi:", error);
                alert("Có lỗi xảy ra. Vui lòng thử lại!");
            });
        });
    });
});

</script>