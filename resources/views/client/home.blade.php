@extends('client.layout.main')
@section('title')
HiShoe-Store - Trang chủ
@endsection
@section('content')

<div class="container">
    @if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif
    <div class="heading_container heading_center">
        <h2>
            Sản phẩm mới nhất
        </h2>
    </div>

    <div class="row">
        @foreach($products as $product)
        <div class="col-sm-6 col-md-4 col-lg-3">
            <div class="box position-relative" style="height: 380px">
                <button class="btn btn-none like-btn {{ auth()->guard('web')->check() && $product->likes()->where('product_id', $product->id)->where('user_id', auth()->id())->exists() ? 'liked' : '' }}" 
                    data-id="{{ $product->id }}">
                    <span class="heart">{{ auth()->guard('web')->check() && $product->likes()->where('product_id', $product->id)->where('user_id', auth()->id())->exists() ? '❤️' : '🤍' }}</span> 
                    <span class="text">{{ auth()->guard('web')->check() && $product->likes()->where('product_id', $product->id)->where('user_id', auth()->id())->exists() ? 'Đã thích' : 'Thích' }}</span>
                </button>
    
                <a href="{{ route('detail', $product->id) }}">
                    <div class="img-box">
                        <img src="{{ $product->image_url ? Storage::url($product->image_url) : asset('images/default-product.jpg') }}" alt="{{ $product->name }}">
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
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll(".like-btn").forEach(button => {
                button.addEventListener("click", function () {
                    let productId = this.dataset.id;
                    let heart = this.querySelector(".heart");
                    let text = this.querySelector(".text");
                    let isLoggedIn = {{ auth()->check() ? 'true' : 'false' }}; // Kiểm tra trạng thái đăng nhập

                    if (!isLoggedIn) {
                        // Hiển thị modal đăng nhập
                        $('#loginModal').modal('show');
                        return;
                    }

                    fetch(`/products/${productId}/like`, {  
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"  
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error("Lỗi khi gửi yêu cầu.");
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.liked !== undefined) {
                            // Cập nhật biểu tượng và văn bản dựa trên phản hồi từ server
                            heart.textContent = data.liked ? '❤️' : '🤍';
                            text.innerText = data.liked ? "Đã thích" : "Thích";
                        }
                    })
                    .catch(error => console.error("Lỗi:", error));
                });
            });
        });
    </script>
    <!-- Modal Đăng nhập -->
    <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginModalLabel">Thông báo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    Bạn cần đăng nhập để thích sản phẩm!
                </div>
                <div class="modal-footer">
                    <a href="{{ route('loginForm') }}" class="btn btn-primary">Đăng nhập</a>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>
    <div class="btn-box">
        <a href="">
            Xem tất cả sản phẩm
        </a>
    </div>
</div>
</section>

{{-- Block hiển thị voucher của cửa hàng --}}
@if(isset($vouchers) && count($vouchers))
<div class="container my-5">
    <div class="card shadow-sm">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="fa fa-gift"></i> Mã giảm giá của cửa hàng</h5>
        </div>
        <div class="card-body">
            <div class="row">
                @foreach($vouchers as $voucher)
                <div class="col-md-6 col-lg-4 mb-3">
                    <div class="border rounded p-3 h-100 bg-light">
                        <h6 class="text-primary font-weight-bold">{{ $voucher->code }}</h6>
                        <p class="mb-1">
                            <strong>Giảm:</strong>
                            @if($voucher->discount_type == 0)
                                {{ $voucher->discount_value }}%
                                @if($voucher->max_discount_value)
                                    (Tối đa {{ number_format($voucher->max_discount_value, 0, ',', '.') }}đ)
                                @endif
                            @else
                                {{ number_format($voucher->discount_value, 0, ',', '.') }}đ
                            @endif
                        </p>
                        <p class="mb-1"><strong>Đơn tối thiểu:</strong> {{ number_format($voucher->min_order_value, 0, ',', '.') }}đ</p>
                        <p class="mb-1"><strong>HSD:</strong> {{ \Carbon\Carbon::parse($voucher->end_date)->format('d/m/Y') }}</p>
                        <span class="badge badge-{{ $voucher->status == 1 ? 'success' : 'secondary' }}">
                            {{ $voucher->status == 1 ? 'Còn hiệu lực' : 'Không hiệu lực' }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endif

<!-- Phần khuyến mãi -->
<section class="saving_section ">
    <div class="box">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6">
                    <div class="img-box">
                        <img src="{{ asset('client/images/saving-img.png') }}" alt="">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="detail-box">
                        <div class="heading_container">
                            <h2>
                                Tiết kiệm tốt nhất với <br>
                                sản phẩm mới
                            </h2>
                        </div>
                        <p>
                            Chúng tôi mang đến những ưu đãi tuyệt vời cho các sản phẩm mới nhất. Hãy khám phá ngay để không bỏ lỡ cơ hội sở hữu những sản phẩm chất lượng với giá ưu đãi!
                        </p>
                        <div class="btn-box">
                            <a href="#" class="btn1">
                                Mua ngay
                            </a>
                            <a href="#" class="btn2">
                                Xem thêm
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Kết thúc phần khuyến mãi -->

<!-- Phần lý do mua sắm -->
<section class="why_section layout_padding">
    <div class="container">
        <div class="heading_container heading_center">
            <h2>
                Tại sao nên mua sắm với chúng tôi
            </h2>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="box ">
                    <div class="img-box">
                        <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
                            <!-- SVG cho Giao hàng nhanh -->
                        </svg>
                    </div>
                    <div class="detail-box">
                        <h5>
                            Giao hàng nhanh
                        </h5>
                        <p>
                            Chúng tôi đảm bảo giao hàng nhanh chóng đến tay bạn.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="box ">
                    <div class="img-box">
                        <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 490.667 490.667" style="enable-background:new 0 0 490.667 490.667;" xml:space="preserve">
                            <!-- SVG cho Miễn phí vận chuyển -->
                        </svg>
                    </div>
                    <div class="detail-box">
                        <h5>
                            Miễn phí vận chuyển
                        </h5>
                        <p>
                            Tận hưởng dịch vụ vận chuyển miễn phí cho mọi đơn hàng.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="box ">
                    <div class="img-box">
                        <svg id="_30_Premium" height="512" viewBox="0 0 512 512" width="512" xmlns="http://www.w3.org/2000/svg" data-name="30_Premium">
                            <!-- SVG cho Chất lượng tốt nhất -->
                        </svg>
                    </div>
                    <div class="detail-box">
                        <h5>
                            Chất lượng tốt nhất
                        </h5>
                        <p>
                            Sản phẩm của chúng tôi luôn đảm bảo chất lượng hàng đầu.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Kết thúc phần lý do mua sắm -->

<!-- Phần quà tặng -->
<section class="gift_section layout_padding-bottom">
    <div class="box ">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-5">
                    <div class="img_container">
                        <div class="img-box">
                            <img src="{{ asset('client/images/gifts.png') }}" alt="">
                        </div>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="detail-box">
                        <div class="heading_container">
                            <h2>
                                Quà tặng cho <br>
                                người thân yêu
                            </h2>
                        </div>
                        <p>
                            Tặng những món quà ý nghĩa cho người thân yêu của bạn với những sản phẩm được chọn lọc kỹ lưỡng, đảm bảo mang lại niềm vui và hạnh phúc.
                        </p>
                        <div class="btn-box">
                            <a href="#" class="btn1">
                                Mua ngay
                            </a>
                            <a href="#" class="btn2">
                                Xem thêm
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Kết thúc phần quà tặng -->

<!-- Phần liên hệ -->
<section class="contact_section ">
    <div class="container px-0">
        <div class="heading_container ">
            <h2 class="">
                Liên hệ với chúng tôi
            </h2>
        </div>
    </div>
    <div class="container container-bg">
        <div class="row">
            <div class="col-lg-7 col-md-6 px-0">
                <div class="map_container">
                    <div class="map-responsive">
                        <iframe src="https://www.google.com/maps/embed/v1/place?key=AIzaSyA0s1a7phLN0iaD6-UE7m4qP-z21pH0eSc&q=Eiffel+Tower+Paris+France" width="600" height="300" frameborder="0" style="border:0; width: 100%; height:100%" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-5 px-0">
                <form action="#">
                    <div>
                        <input type="text" placeholder="Họ và tên" />
                    </div>
                    <div>
                        <input type="email" placeholder="Email" />
                    </div>
                    <div>
                        <input type="text" placeholder="Số điện thoại" />
                    </div>
                    <div>
                        <input type="text" class="message-box" placeholder="Tin nhắn" />
                    </div>
                    <div class="d-flex ">
                        <button>
                            GỬI
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
<!-- Kết thúc phần liên hệ -->

<!-- Phần đánh giá khách hàng -->
<section class="client_section layout_padding">
    <div class="container">
        <div class="heading_container heading_center">
            <h2>
                Đánh giá khách hàng
            </h2>
        </div>
    </div>
    <div class="container px-0">
        <div id="customCarousel2" class="carousel carousel-fade" data-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <div class="box">
                        <div class="client_info">
                            <div class="client_name">
                                <h5>
                                    Morijorch
                                </h5>
                                <h6>
                                    Khách hàng
                                </h6>
                            </div>
                            <i class="fa fa-quote-left" aria-hidden="true"></i>
                        </div>
                        <p>
                            Tôi rất hài lòng với chất lượng sản phẩm và dịch vụ của HiShoe-Store. Giao hàng nhanh chóng và sản phẩm đúng như mô tả!
                        </p>
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="box">
                        <div class="client_info">
                            <div class="client_name">
                                <h5>
                                    Rochak
                                </h5>
                                <h6>
                                    Khách hàng
                                </h6>
                            </div>
                            <i class="fa fa-quote-left" aria-hidden="true"></i>
                        </div>
                        <p>
                            Dịch vụ tuyệt vời, sản phẩm chất lượng cao và giá cả hợp lý. Tôi sẽ tiếp tục ủng hộ HiShoe-Store!
                        </p>
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="box">
                        <div class="client_info">
                            <div class="client_name">
                                <h5>
                                    Brad Johns
                                </h5>
                                <h6>
                                    Khách hàng
                                </h6>
                            </div>
                            <i class="fa fa-quote-left" aria-hidden="true"></i>
                        </div>
                        <p>
                            HiShoe-Store là lựa chọn hàng đầu của tôi khi mua sắm trực tuyến. Sản phẩm đẹp, giao hàng nhanh và dịch vụ chăm sóc khách hàng tuyệt vời!
                        </p>
                    </div>
                </div>
            </div>
            <div class="carousel_btn-box">
                <a class="carousel-control-prev" href="#customCarousel2" role="button" data-slide="prev">
                    <i class="fa fa-angle-left" aria-hidden="true"></i>
                    <span class="sr-only">Trước</span>
                </a>
                <a class="carousel-control-next" href="#customCarousel2" role="button" data-slide="next">
                    <i class="fa fa-angle-right" aria-hidden="true"></i>
                    <span class="sr-only">Tiếp</span>
                </a>
            </div>
        </div>
    </div>
</section>
<!-- Kết thúc phần đánh giá khách hàng -->

<!-- Phần thông tin chân trang -->
<section class="info_section layout_padding2-top">
    <div class="social_container">
        <div class="social_box">
            <a href="">
                <i class="fa fa-facebook" aria-hidden="true"></i>
            </a>
            <a href="">
                <i class="fa fa-twitter" aria-hidden="true"></i>
            </a>
            <a href="">
                <i class="fa fa-instagram" aria-hidden="true"></i>
            </a>
            <a href="">
                <i class="fa fa-youtube" aria-hidden="true"></i>
            </a>
        </div>
    </div>
    <div class="info_container ">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-lg-3">
                    <h6>
                        VỀ CHÚNG TÔI
                    </h6>
                    <p>
                        HiShoe-Store là cửa hàng trực tuyến chuyên cung cấp các sản phẩm chất lượng cao, giá cả hợp lý, và dịch vụ tận tâm.
                    </p>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="info_form ">
                        <h5>
                            Đăng ký nhận tin
                        </h5>
                        <form action="#">
                            <input type="email" placeholder="Nhập email của bạn">
                            <button>
                                Đăng ký
                            </button>
                        </form>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <h6>
                        HỖ TRỢ
                    </h6>
                    <p>
                        Nếu bạn cần hỗ trợ, vui lòng liên hệ với chúng tôi qua email hoặc số điện thoại. Chúng tôi luôn sẵn sàng giúp bạn!
                    </p>
                </div>
                <div class="col-md-6 col-lg-3">
                    <h6>
                        LIÊN HỆ
                    </h6>
                    <div class="info_link-box">
                        <a href="">
                            <i class="fa fa-map-marker" aria-hidden="true"></i>
                            <span> 123 Đường GB, London, UK </span>
                        </a>
                        <a href="">
                            <i class="fa fa-phone" aria-hidden="true"></i>
                            <span>+01 12345678901</span>
                        </a>
                        <a href="">
                            <i class="fa fa-envelope" aria-hidden="true"></i>
                            <span> demo@gmail.com</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Kết thúc phần thông tin chân trang -->

@endsection