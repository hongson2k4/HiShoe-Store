<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hồ Sơ Của Tôi</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background-color: #f5f5f5;
            font-family: Arial, sans-serif;
        }
        .sidebar {
            background-color: white;
            height: 100vh;
            padding: 20px 0;
            border-right: 1px solid #f0f0f0;
        }
        .sidebar-item {
            padding: 10px 20px;
            color: #333;
            display: flex;
            align-items: center;
            cursor: pointer;
        }
        .sidebar-item.active {
            color: #ee4d2d;
        }
        .sidebar-item i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        .dropdown-toggle {
            position: relative;
        }
        .dropdown-toggle::after {
            display: inline-block;
            width: 0;
            height: 0;
            margin-left: auto;
            vertical-align: middle;
            content: "";
            border-top: 0.3em solid;
            border-right: 0.3em solid transparent;
            border-bottom: 0;
            border-left: 0.3em solid transparent;
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
        }
        .main-content {
            background-color: white;
            min-height: 100vh;
            padding: 20px;
        }
        .profile-header {
            border-bottom: 1px solid #f0f0f0;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .new-badge {
            background-color: #ee4d2d;
            color: white;
            font-size: 10px;
            padding: 2px 5px;
            border-radius: 3px;
            margin-left: 5px;
        }
        .avatar-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        .avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background-color: #f5f5f5;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
        }
        .btn-orange {
            background-color: #ee4d2d;
            color: white;
            padding: 8px 30px;
        }
        .btn-orange:hover {
            background-color: #d73211;
            color: white;
        }
        .form-row {
            margin-bottom: 15px;
        }
        .form-label {
            text-align: right;
            padding-top: 8px;
        }
        .change-link {
            color: #05a;
            cursor: pointer;
            margin-left: 10px;
        }
        .dropdown-toggle::after {
            vertical-align: middle;
        }
        .username-note {
            color: #999;
            font-size: 14px;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 sidebar">
                <div class="text-center my-3">
                    <div class="d-inline-block">
                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="far fa-user fa-2x text-secondary"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <span>r92ou6jei6</span>
                        <div><small><i class="fas fa-pencil-alt text-secondary"></i> Sửa Hồ Sơ</small></div>
                    </div>
                </div>

                <!-- <div class="sidebar-item">
                    <i class="fas fa-gift text-warning"></i>
                    <span>Ngày 15 Sale Giữa Tháng</span>
                    <span class="new-badge">New</span>
                </div> -->

                <div class="sidebar-item">
                    <i class="far fa-bell text-danger"></i>
                    <span>Thông Báo</span>
                </div>

                <div class="sidebar-item dropdown-toggle" id="accountDropdown" data-toggle="collapse" data-target="#accountSubmenu" aria-expanded="true">
                    <i class="far fa-user text-primary"></i>
                    <span>Tài Khoản Của Tôi</span>
                </div>

                <div id="accountSubmenu" class="collapse show">
                    <div class="sidebar-item active ml-4">
                        <span>Hồ Sơ</span>
                    </div>

                    <div class="sidebar-item ml-4">
                        <span>Ngân Hàng</span>
                    </div>

                    <div class="sidebar-item ml-4">
                        <span>Địa Chỉ</span>
                    </div>

                    <div class="sidebar-item ml-4">
                        <span>Đổi Mật Khẩu</span>
                    </div>

                    <div class="sidebar-item ml-4">
                        <span>Cài Đặt Thông Báo</span>
                    </div>

                </div>

                <div class="sidebar-item">
                    <i class="fas fa-clipboard-list text-primary"></i>
                    <span>Đơn Mua</span>
                </div>

                <!-- <div class="sidebar-item">
                    <i class="fas fa-ticket-alt text-danger"></i>
                    <span>Kho Voucher</span>
                </div>

                <div class="sidebar-item">
                    <i class="fas fa-coins text-warning"></i>
                    <span>Shopee Xu</span>
                </div> -->
            </div>

            <!-- Main Content -->
            <div class="col-md-9 main-content">
                <div class="profile-header">
                    <h4>Hồ Sơ Của Tôi</h4>
                    <p class="text-muted">Quản lý thông tin hồ sơ để bảo mật tài khoản</p>
                </div>

                <div class="row">
                    <div class="col-md-8">
                        <form>
                            <div class="form-row">
                                <div class="col-md-3 form-label">Tên đăng nhập</div>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" value="r92ou6jei6" readonly>
                                    <div class="username-note">Tên Đăng nhập chỉ có thể thay đổi một lần.</div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-md-3 form-label">Tên</div>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" value="">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-md-3 form-label">Email</div>
                                <div class="col-md-9 d-flex align-items-center">
                                    <span>da*********@gmail.com</span>
                                    <a href="#" class="change-link">Thay Đổi</a>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-md-3 form-label">Số điện thoại</div>
                                <div class="col-md-9 d-flex align-items-center">
                                    <span>*********59</span>
                                    <a href="#" class="change-link">Thay Đổi</a>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-md-3 form-label">Giới tính</div>
                                <div class="col-md-9">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" id="male" value="male">
                                        <label class="form-check-label" for="male">Nam</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" id="female" value="female">
                                        <label class="form-check-label" for="female">Nữ</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" id="other" value="other">
                                        <label class="form-check-label" for="other">Khác</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-md-3 form-label">Ngày sinh</div>
                                <div class="col-md-9">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="dropdown">
                                                <button class="btn btn-light dropdown-toggle w-100 text-left" type="button" id="dayDropdown" data-toggle="dropdown">
                                                    Ngày
                                                </button>
                                                <div class="dropdown-menu">
                                                    <!-- Days would be populated by JavaScript -->
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="dropdown">
                                                <button class="btn btn-light dropdown-toggle w-100 text-left" type="button" id="monthDropdown" data-toggle="dropdown">
                                                    Tháng
                                                </button>
                                                <div class="dropdown-menu">
                                                    <!-- Months would be populated by JavaScript -->
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="dropdown">
                                                <button class="btn btn-light dropdown-toggle w-100 text-left" type="button" id="yearDropdown" data-toggle="dropdown">
                                                    Năm
                                                </button>
                                                <div class="dropdown-menu">
                                                    <!-- Years would be populated by JavaScript -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-row mt-4">
                                <div class="col-md-3"></div>
                                <div class="col-md-9">
                                    <button type="submit" class="btn btn-orange">Lưu</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="col-md-4">
                        <div class="avatar-container">
                            <div class="avatar">
                                <i class="far fa-user fa-3x text-secondary"></i>
                            </div>
                            <button class="btn btn-light mb-2">Chọn Ảnh</button>
                            <small class="text-muted text-center">
                                Dung lượng file tối đa 1 MB<br>
                                Định dạng:.JPEG, .PNG
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.1/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/js/bootstrap.min.js"></script>
</body>
</html>