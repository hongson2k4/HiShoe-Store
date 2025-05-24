@extends('client.layout.main')

@section('title', 'HiShoe-Store - Đặt lại mật khẩu')

@section('content')
    <style>
        /* Định kiểu cho container chính */
        .reset-container {
            max-width: 500px;
            margin: 4rem auto;
            padding: 0 15px;
        }

        /* Định kiểu cho khung đặt lại mật khẩu */
        .reset-box {
            background: linear-gradient(145deg, #ffffff, #f8f9fa);
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.15);
            padding: 2.5rem;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        /* Hiệu ứng khi di chuột vào khung */
        .reset-box:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 32px rgba(0,0,0,0.2);
        }

        /* Định kiểu cho tiêu đề */
        .reset-box h4 {
            font-size: 2rem;
            font-weight: 700;
            color: #2c3e50;
            text-align: center;
            margin-bottom: 2rem;
            position: relative;
        }

        /* Gạch chân trang trí cho tiêu đề */
        .reset-box h4::after {
            content: '';
            width: 80px;
            height: 4px;
            background: linear-gradient(to right, #007bff, #00c4ff);
            position: absolute;
            bottom: -12px;
            left: 50%;
            transform: translateX(-50%);
            border-radius: 2px;
        }

        /* Định kiểu cho nhãn của form */
        .form-group label {
            font-weight: 600;
            color: #34495e;
            margin-bottom: 0.75rem;
            font-size: 1rem;
        }

        /* Định kiểu cho ô nhập liệu */
        .form-control {
            border-radius: 8px;
            border: 1px solid #d1d8e0;
            padding: 0.85rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #fff;
        }

        /* Hiệu ứng khi ô nhập liệu được focus */
        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 8px rgba(0,123,255,0.4);
            background: #f8fbff;
        }

        /* Định kiểu cho nút hiển thị/ẩn mật khẩu */
        .input-group .btn-outline-secondary {
            border-radius: 0 8px 8px 0;
            border: 1px solid #d1d8e0;
            background: #f8f9fa;
            transition: background 0.3s ease;
        }

        /* Hiệu ứng khi di chuột vào nút hiển thị/ẩn mật khẩu */
        .input-group .btn-outline-secondary:hover {
            background: #e9ecef;
        }

        /* Định kiểu cho thông báo lỗi */
        .alert-danger {
            font-size: 0.85rem;
            margin-bottom: 1.5rem;
            border-radius: 8px;
            background: #fce9eb;
            border: 1px solid #e63946;
            color: #e63946;
        }

        /* Định kiểu cho thông báo thành công */
        .alert-success {
            font-size: 0.85rem;
            margin-bottom: 1.5rem;
            border-radius: 8px;
            background: #e6f4ea;
            border: 1px solid #2ecc71;
            color: #2ecc71;
        }

        /* Định kiểu cho nút chính */
        .btn-primary {
            width: 100%;
            padding: 0.85rem;
            border-radius: 8px;
            background: linear-gradient(to right, #007bff, #00c4ff);
            border: none;
            font-weight: 600;
            font-size: 1.1rem;
            color: #fff;
            transition: background 0.3s ease, transform 0.2s ease;
        }

        /* Hiệu ứng khi di chuột vào nút chính */
        .btn-primary:hover {
            background: linear-gradient(to right, #0056b3, #0096cc);
            transform: translateY(-2px);
        }

        /* Hiệu ứng khi nhấn nút */
        .btn-primary:active {
            transform: translateY(0);
        }

        /* Điều chỉnh cho màn hình nhỏ */
        @media (max-width: 576px) {
            .reset-box {
                padding: 1.75rem;
            }

            .reset-box h4 {
                font-size: 1.6rem;
            }

            .form-control {
                padding: 0.7rem;
                font-size: 0.95rem;
            }

            .btn-primary {
                padding: 0.75rem;
                font-size: 1rem;
            }
        }
    </style>

    <div class="reset-container">
        <div class="reset-box">
            <h4>Đặt lại mật khẩu</h4>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            <form action="{{ route('password.update') }}" method="POST">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="form-group">
                    <label for="email_or_phone">Email hoặc Số điện thoại</label>
                    <input type="text" name="email_or_phone" id="email_or_phone" class="form-control" value="{{ old('email_or_phone') }}" required autofocus>
                </div>

                <div class="form-group">
                    <label for="otp">Mã OTP</label>
                    <input type="text" name="otp" id="otp" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="password">Mật khẩu mới</label>
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
                    <label for="password_confirmation">Xác nhận mật khẩu</label>
                    <div class="input-group">
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                        <div class="input-group-append">
                            <button type="button" class="btn btn-outline-secondary" id="toggleConfirmPassword">
                                <i class="fa fa-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Đặt lại mật khẩu</button>
                </div>
            </form>
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
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.querySelector('i').classList.toggle('fa-eye');
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });

        // Xử lý nút hiển thị/ẩn xác nhận mật khẩu
        const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
        const confirmPasswordInput = document.getElementById('password_confirmation');
        toggleConfirmPassword.addEventListener('click', function () {
            const type = confirmPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            confirmPasswordInput.setAttribute('type', type);
            this.querySelector('i').classList.toggle('fa-eye');
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });
    </script>
@endsection
