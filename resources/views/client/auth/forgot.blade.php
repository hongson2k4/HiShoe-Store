@extends('client.layout.main')

@section('title', 'HiShoe-Store - Quên mật khẩu')

@section('content')
    <style>
        /* Định kiểu cho container chính */
        .forgot-container {
            max-width: 500px;
            margin: 4rem auto;
            padding: 0 15px;
        }

        /* Định kiểu cho khung quên mật khẩu */
        .forgot-box {
            background: linear-gradient(145deg, #ffffff, #f8f9fa);
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.15);
            padding: 2.5rem;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        /* Hiệu ứng khi di chuột vào khung */
        .forgot-box:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 32px rgba(0,0,0,0.2);
        }

        /* Định kiểu cho tiêu đề */
        .forgot-box h3 {
            font-size: 2rem;
            font-weight: 700;
            color: #2c3e50;
            text-align: center;
            margin-bottom: 2rem;
            position: relative;
        }

        /* Gạch chân trang trí cho tiêu đề */
        .forgot-box h3::after {
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
            width: 100%;
        }

        /* Hiệu ứng khi ô nhập liệu được focus */
        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 8px rgba(0,123,255,0.4);
            background: #f8fbff;
        }

        /* Định kiểu cho thông báo lỗi */
        .text-danger {
            font-size: 0.85rem;
            margin-top: 0.3rem;
            display: block;
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
            padding: 0.75rem;
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

        /* Định kiểu cho liên kết quay lại */
        .text-link {
            display: block;
            text-align: center;
            margin-top: 1.5rem;
            color: #007bff;
            text-decoration: none;
            font-weight: 500;
            font-size: 1rem;
        }

        /* Hiệu ứng khi di chuột vào liên kết */
        .text-link:hover {
            text-decoration: underline;
            color: #0056b3;
        }

        /* Điều chỉnh cho màn hình nhỏ */
        @media (max-width: 576px) {
            .forgot-box {
                padding: 1.75rem;
            }

            .forgot-box h3 {
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

    <div class="forgot-container">
        <div class="forgot-box">
            <h3>Quên mật khẩu</h3>
            @if ($errors->has('identifier'))
                <div class="text-danger">{{ $errors->first('identifier') }}</div>
            @endif
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            <form action="{{ route('password.sendResetLink') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="identifier">Email hoặc Số điện thoại</label>
                    <input type="text" id="identifier" name="identifier" class="form-control" value="{{ old('identifier') }}" required autofocus>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Xác nhận</button>
                </div>
            </form>
            <a href="{{ route('loginForm') }}" class="text-link">Quay về trang đăng nhập</a>
        </div>
    </div>

    <!-- Bao gồm Bootstrap 5 JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
@endsection
