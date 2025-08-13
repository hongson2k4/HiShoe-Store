@extends('client.layout.main')

@section('title', 'HiShoe-Store - Đăng nhập')

@section('content')
    <style>
        /* Định kiểu cho container chính */
        .login-container {
            max-width: 500px;
            margin: 3rem auto;
            padding: 0 15px;
        }

        /* Định kiểu cho khung đăng nhập */
        .login-box {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
            padding: 2rem;
            transition: transform 0.3s ease;
        }

        /* Hiệu ứng khi di chuột vào khung đăng nhập */
        .login-box:hover {
            transform: translateY(-5px);
        }

        /* Định kiểu cho tiêu đề */
        .login-box h3 {
            font-size: 1.8rem;
            font-weight: 600;
            color: #333;
            text-align: center;
            margin-bottom: 1.5rem;
            position: relative;
        }

        /* Gạch chân trang trí cho tiêu đề */
        .login-box h3::after {
            content: '';
            width: 60px;
            height: 3px;
            background: #007bff;
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
        }

        /* Định kiểu cho nhãn của form */
        .form-group label {
            font-weight: 500;
            color: #444;
            margin-bottom: 0.5rem;
        }

        /* Định kiểu cho ô nhập liệu */
        .form-control {
            border-radius: 6px;
            border: 1px solid #ced4da;
            padding: 0.75rem;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        /* Hiệu ứng khi ô nhập liệu được focus */
        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0,123,255,0.3);
        }

        /* Định kiểu cho nút hiển thị/ẩn mật khẩu */
        .input-group .btn-outline-secondary {
            border-radius: 0 6px 6px 0;
            border: 1px solid #ced4da;
            background: #f8f9fa;
        }

        /* Định kiểu cho nút chính */
        .btn-primary {
            width: 100%;
            padding: 0.75rem;
            border-radius: 6px;
            background: #007bff;
            border: none;
            font-weight: 500;
            transition: background 0.3s ease;
        }

        /* Hiệu ứng khi di chuột vào nút chính */
        .btn-primary:hover {
            background: #0056b3;
        }

        /* Định kiểu cho thông báo lỗi */
        .error-message {
            color: #dc3545;
            font-size: 0.9rem;
            margin-bottom: 1rem;
            text-align: center;
        }

        /* Định kiểu cho các liên kết văn bản */
        .text-links {
            margin-top: 1rem;
            text-align: center;
        }

        /* Định kiểu cho các liên kết */
        .text-links a {
            color: #007bff;
            text-decoration: none;
            font-weight: 500;
        }

        /* Hiệu ứng khi di chuột vào liên kết */
        .text-links a:hover {
            text-decoration: underline;
        }

        /* Định kiểu cho modal */
        .modal-content {
            border-radius: 10px;
            border: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        /* Định kiểu cho tiêu đề modal */
        .modal-header {
            border-bottom: none;
            padding-bottom: 0;
        }

        /* Định kiểu cho tiêu đề modal */
        .modal-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #333;
        }

        /* Định kiểu cho nội dung modal */
        .modal-body p {
            margin-bottom: 0.5rem;
            color: #444;
        }

        /* Định kiểu cho văn bản in đậm trong modal */
        .modal-body strong {
            color: #333;
        }

        /* Định kiểu cho chân modal */
        .modal-footer {
            border-top: none;
        }

        /* Điều chỉnh cho màn hình nhỏ */
        @media (max-width: 576px) {
            .login-box {
                padding: 1.5rem;
            }

            .login-box h3 {
                font-size: 1.5rem;
            }

            .form-control {
                padding: 0.6rem;
            }
        }
    </style>

    <div class="login-container">
        <div class="login-box">
            <h3>Đăng nhập</h3>
            @if(session()->has('error'))
                <p class="error-message">{{ session('error') }}</p>
            @endif
            <form class="form" action="{{ route('login') }}" method="post">
                @csrf
                <div class="form-group">
                    <label for="username">Email hoặc tên người dùng</label>
                    <input type="text" name="username" id="username" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="password">Mật khẩu</label>
                    <div class="input-group">
                        <input type="password" name="password" id="password" class="form-control" required>
                        <div class="input-group-append">
                            <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                                <i class="fa fa-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Đăng nhập</button>
                </div>
                <div class="form-group">
                    <a href="{{ route('login.google') }}" class="btn btn-danger" style="width:100%">
                        <i class="fa fa-google"></i> Đăng nhập bằng Google
                    </a>
                </div>
                <div class="text-links">
                    <a href="{{ route('password.request') }}">Quên mật khẩu?</a>
                </div>
                <div class="text-links">
                    Chưa có tài khoản? <a href="{{ route('registerForm') }}">Đăng ký ngay!</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal cho tài khoản bị khóa -->
    <div class="modal fade" id="lockedAccountModal" tabindex="-1" aria-labelledby="lockedAccountModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="lockedAccountModalLabel">Tài khoản bị khóa</h5>
                    <button type="button" class="btn btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Lý do:</strong> <span id="banReason"></span></p>
                    <p><strong>Thời gian khóa:</strong> <span id="bannedAt"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bao gồm jQuery và Bootstrap 5 JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>

    <script>
        // Xử lý nút hiển thị/ẩn mật khẩu
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');

        togglePassword.addEventListener('click', function () {
            // Chuyển đổi kiểu của ô nhập mật khẩu
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            // Chuyển đổi biểu tượng mắt
            this.querySelector('i').classList.toggle('fa-eye');
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });

        // Hiển thị modal nếu tài khoản bị khóa
        @if(session()->has('locked'))
            const lockedData = @json(session('locked'));
            document.getElementById('banReason').textContent = lockedData.ban_reason;
            document.getElementById('bannedAt').textContent = lockedData.banned_at;
            $('#lockedAccountModal').modal('show');
        @endif
    </script>
@endsection
