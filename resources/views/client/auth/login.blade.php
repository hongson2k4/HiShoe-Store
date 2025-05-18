@extends('client.layout.main')
@section('title')
HiShoe-Store - Đăng nhập
@endsection
@section('content')
<!-- <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script> -->
<!------ Include the above in your HEAD tag ---------->

<div id="login">
    <h3 class="text-center text-white pt-5">Login form</h3>
    <div class="container">
        <div id="login-row" class="row justify-content-center align-items-center">
            <div id="login-column" class="col-md-6">
                <div id="login-box" class="col-md-12">

                    <form class="form" action="{{ route('login') }}" method="post">
                        <h3 class="text-center text-info">Đăng nhập</h3>
                        @if(session()->has('error'))
                        <p style="color: red;">{{ session('error') }}</p>
                        @endif
                        @csrf
                        <div class="form-group">
                            <label for="username" class="text-info">Email hoặc tên người dùng:</label><br>
                            <input type="text" name="username" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="password" class="text-info">Mật khẩu:</label><br>
                            <div class="input-group">
                                <input type="password" name="password" id="password" class="form-control">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <!-- <label for="remember-me" class="text-info"><span>Remember me</span> <span><input id="remember-me" name="remember-me" type="checkbox"></span></label><br> -->
                            <input type="submit" name="submit" class="btn btn-info btn-md" value="Đăng nhập">
                            <a href="{{ route('password.request') }}" class="text-info">Quên mật khẩu ?</a>
                        </div>
                        <div id="register-link" class="text-right">
                            Chưa có tài khoản! <a href="{{ route('registerForm')}}" class="text-info">Đăng ký</a> ngay!
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include jQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<!-- Include Bootstrap JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.2/js/bootstrap.bundle.min.js"></script>

<!-- Modal for locked account -->
<div class="modal fade" id="lockedAccountModal" tabindex="-1" role="dialog" aria-labelledby="lockedAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="lockedAccountModalLabel">Tài khoản bị khóa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><strong>Lý do:</strong> <span id="banReason"></span></p>
                <p><strong>Thời gian khóa:</strong> <span id="bannedAt"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

<script>
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');

    togglePassword.addEventListener('click', function () {
        // Toggle the type attribute
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);

        // Toggle the icon
        this.querySelector('i').classList.toggle('fa-eye');
        this.querySelector('i').classList.toggle('fa-eye-slash');
    });

    // Show modal if account is locked
    @if(session()->has('locked'))
        const lockedData = @json(session('locked'));
        document.getElementById('banReason').textContent = lockedData.ban_reason;
        document.getElementById('bannedAt').textContent = lockedData.banned_at;
        $('#lockedAccountModal').modal('show');
    @endif
</script>
@endsection