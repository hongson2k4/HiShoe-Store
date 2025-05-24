@extends('client.layout.main')

@section('title', 'HiShoe-Store - Đổi mật khẩu')

@section('content')
    <style>
        /* Định kiểu cho container chính */
        .change-password-container {
            max-width: 500px;
            margin: 4rem auto;
            padding: 0 15px;
        }

        /* Định kiểu cho khung đổi mật khẩu */
        .change-password-box {
            background: linear-gradient(145deg, #ffffff, #f8f9fa);
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.15);
            padding: 2.5rem;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        /* Hiệu ứng khi di chuột vào khung */
        .change-password-box:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 32px rgba(0,0,0,0.2);
        }

        /* Định kiểu cho tiêu đề */
        .change-password-box h2 {
            font-size: 2rem;
            font-weight: 700;
            color: #2c3e50;
            text-align: center;
            margin-bottom: 2rem;
            position: relative;
        }

        /* Gạch chân trang trí cho tiêu đề */
        .change-password-box h2::after {
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
            .change-password-box {
                padding: 1.75rem;
            }

            .change-password-box h2 {
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

    <div class="change-password-container">
        <div class="change-password-box">
            <h2>Đổi mật khẩu</h2>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            <form action="{{ route('password.changeForm') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="old_password">Mật khẩu cũ</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="old_password" name="old_password" value="{{ old('old_password') }}" required>
                        <div class="input-group-append">
                            <button type="button" class="btn btn-outline-secondary" id="toggleOldPassword">
                                <i class="fa fa-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="new_password">Mật khẩu mới</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="new_password" name="new_password" value="{{ old('new_password') }}" required>
                        <div class="input-group-append">
                            <button type="button" class="btn btn-outline-secondary" id="toggleNewPassword">
                                <i class="fa fa-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Xác nhận mật khẩu mới</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" value="{{ old('confirm_password') }}" required>
                        <div class="input-group-append">
                            <button type="button" class="btn btn-outline-secondary" id="toggleConfirmPassword">
                                <i class="fa fa-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Đổi mật khẩu</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Bao gồm jQuery và Bootstrap 5 JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>

    <script>
        // Xử lý nút hiển thị/ẩn mật khẩu cũ
        const toggleOldPassword = document.getElementById('toggleOldPassword');
        const oldPasswordInput = document.getElementById('old_password');
        toggleOldPassword.addEventListener('click', function () {
            const type = oldPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            oldPasswordInput.setAttribute('type', type);
            this.querySelector('i').classList.toggle('fa-eye');
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });

        // Xử lý nút hiển thị/ẩn mật khẩu mới
        const toggleNewPassword = document.getElementById('toggleNewPassword');
        const newPasswordInput = document.getElementById('new_password');
        toggleNewPassword.addEventListener('click', function () {
            const type = newPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            newPasswordInput.setAttribute('type', type);
            this.querySelector('i').classList.toggle('fa-eye');
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });

        // Xử lý nút hiển thị/ẩn xác nhận mật khẩu
        const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
        const confirmPasswordInput = document.getElementById('confirm_password');
        toggleConfirmPassword.addEventListener('click', function () {
            const type = confirmPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            confirmPasswordInput.setAttribute('type', type);
            this.querySelector('i').classList.toggle('fa-eye');
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });
    </script>
@endsection
