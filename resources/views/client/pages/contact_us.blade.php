@extends('client.layout.main')

@section('title', 'Liên hệ - HiShoe Store')

@push('styles')
<!-- Bootstrap 4 CDN -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
@endpush

@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <h1>🏃‍♂️ HiShoe Store</h1>
        <p class="lead">Giày thể thao chất lượng cao - Phù hợp mọi nhu cầu của bạn</p>
    </div>
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="h5 mb-4">Thông Tin Liên Hệ</h2>
                    <ul class="list-unstyled">
                        <li class="mb-3">
                            <strong>📍 Địa chỉ cửa hàng:</strong><br>
                            123 Phố Thể Thao, Quận Hoàn Kiếm<br>Hà Nội, Việt Nam
                        </li>
                        <li class="mb-3">
                            <strong>📞 Số điện thoại:</strong><br>
                            Hotline: 0123 456 789<br>Zalo: 0987 654 321
                        </li>
                        <li class="mb-3">
                            <strong>✉️ Email:</strong><br>
                            info@hishoestore.com<br>support@hishoestore.com
                        </li>
                        <li class="mb-3">
                            <strong>🌐 Website & Mạng xã hội:</strong><br>
                            www.hishoestore.com
                            <div class="mt-2">
                                <a href="#" class="btn btn-outline-primary btn-sm rounded-circle mr-2">📘</a>
                                <a href="#" class="btn btn-outline-danger btn-sm rounded-circle mr-2">📷</a>
                                <a href="#" class="btn btn-outline-info btn-sm rounded-circle mr-2">🐦</a>
                                <a href="#" class="btn btn-outline-dark btn-sm rounded-circle">📺</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="h5 mb-4">Gửi Tin Nhắn</h2>
                    <form id="contactForm">
                        <div class="form-group">
                            <label for="name">Họ và tên *</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email *</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Số điện thoại</label>
                            <input type="tel" class="form-control" id="phone" name="phone">
                        </div>
                        <div class="form-group">
                            <label for="interest">Bạn quan tâm đến</label>
                            <select class="form-control" id="interest" name="interest">
                                <option value="">Chọn loại giày</option>
                                <option value="running">Giày chạy bộ</option>
                                <option value="basketball">Giày bóng rổ</option>
                                <option value="football">Giày bóng đá</option>
                                <option value="casual">Giày thường ngày</option>
                                <option value="hiking">Giày leo núi</option>
                                <option value="other">Khác</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="message">Tin nhắn *</label>
                            <textarea class="form-control" id="message" name="message" rows="4" required placeholder="Mô tả nhu cầu của bạn, kích cỡ mong muốn, màu sắc yêu thích..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Gửi tin nhắn</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="h5 mb-4">Giờ Mở Cửa</h2>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between">
                            <span><strong>Thứ 2 - Thứ 6</strong></span>
                            <span>8:00 - 22:00</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span><strong>Thứ 7 - Chủ nhật</strong></span>
                            <span>8:00 - 23:00</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span><strong>Ngày lễ</strong></span>
                            <span>9:00 - 21:00</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span><strong>Tư vấn online</strong></span>
                            <span>24/7</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="h5 mb-4">Chuyên Môn Của Chúng Tôi</h2>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <div class="text-center">
                                <div style="font-size:2rem;">🏃‍♂️</div>
                                <h6 class="mt-2">Giày Chạy Bộ</h6>
                                <small>Tư vấn giày chạy phù hợp với kiểu chạy và địa hình</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="text-center">
                                <div style="font-size:2rem;">⚽</div>
                                <h6 class="mt-2">Giày Thể Thao</h6>
                                <small>Đa dạng các môn: bóng đá, bóng rổ, tennis, cầu lông</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="text-center">
                                <div style="font-size:2rem;">🥾</div>
                                <h6 class="mt-2">Giày Outdoor</h6>
                                <small>Leo núi, trekking, dã ngoại với độ bền cao</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="text-center">
                                <div style="font-size:2rem;">👟</div>
                                <h6 class="mt-2">Giày Lifestyle</h6>
                                <small>Phong cách thời trang cho mọi hoạt động hàng ngày</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>  
</div>
@endsection

@push('scripts')
<!-- Bootstrap 4 JS (optional, for dropdowns, etc.) -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('contactForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const name = formData.get('name');
        const email = formData.get('email');
        if (!name || !email) {
            alert('Vui lòng điền đầy đủ thông tin bắt buộc!');
            return;
        }
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;
        submitBtn.textContent = 'Đang gửi...';
        submitBtn.disabled = true;
        setTimeout(() => {
            alert(`Cảm ơn ${name}! Chúng tôi đã nhận được tin nhắn của bạn và sẽ phản hồi sớm nhất có thể.`);
            this.reset();
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
        }, 2000);
    });
</script>
@endpush