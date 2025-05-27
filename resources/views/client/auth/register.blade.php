@extends('client.layout.main')

@section('title', 'HiShoe-Store - Đăng ký')

@section('content')
    <style>
        /* Định kiểu cho container chính */
        .register-container {
            max-width: 500px;
            margin: 4rem auto;
            padding: 0 15px;
        }

        /* Định kiểu cho khung đăng ký */
        .register-box {
            background: linear-gradient(145deg, #ffffff, #f8f9fa);
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.15);
            padding: 2.5rem;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        /* Hiệu ứng khi di chuột vào khung đăng ký */
        .register-box:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 32px rgba(0,0,0,0.2);
        }

        /* Định kiểu cho tiêu đề */
        .register-box h3 {
            font-size: 2rem;
            font-weight: 700;
            color: #2c3e50;
            text-align: center;
            margin-bottom: 2rem;
            position: relative;
        }

        /* Gạch chân trang trí cho tiêu đề */
        .register-box h3::after {
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
        .text-danger {
            font-size: 0.85rem;
            margin-top: 0.3rem;
            display: block;
            color: #e63946;
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
            .register-box {
                padding: 1.75rem;
            }

            .register-box h3 {
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

    <div class="register-container">
        <div class="register-box">
            <h3>Đăng ký tài khoản</h3>
            <form class="form" action="{{ route('register') }}" method="post">
                @csrf
                <div class="form-group">
                    <label for="full_name">Họ và tên</label>
                    <input type="text" name="full_name" id="full_name" class="form-control" value="{{ old('full_name') }}" required>
                    @if ($errors->has('full_name'))
                        <span class="text-danger">{{ $errors->first('full_name') }}</span>
                    @endif
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required>
                    @if ($errors->has('email'))
                        <span class="text-danger">{{ $errors->first('email') }}</span>
                    @endif
                </div>
                <div class="form-group">
                    <label for="phone_number">Số điện thoại</label>
                    <input type="text" name="phone_number" id="phone_number" class="form-control" value="{{ old('phone_number') }}" required>
                    @if ($errors->has('phone_number'))
                        <span class="text-danger">{{ $errors->first('phone_number') }}</span>
                    @endif
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
                    @if ($errors->has('password'))
                        <span class="text-danger">{{ $errors->first('password') }}</span>
                    @endif
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
                    @if ($errors->has('password_confirmation'))
                        <span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
                    @endif
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Đăng ký</button>
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
