@extends('client.layout.main')
@section('title')
HiShoe-Store - Đăng ký
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
                        <form id="login-form" class="form" action="{{ route('register') }}" method="post">
                            @csrf
                            <h3 class="text-center text-info">Đăng ký tài khoản</h3>
                            <div class="form-group">
                                <label for="username" class="text-info">Họ và tên:</label><br>
                                <input type="text" name="full_name" class="form-control" value="{{old('full_name')}}">
                            </div>
                            <div class="form-group">
                                <label for="username" class="text-info">Tên tài khoản:</label><br>
                                <input type="text" name="username" class="form-control" value="{{old('username')}}">
                            </div>
                            <div class="form-group">
                                <label for="password" class="text-info">Email:</label><br>
                                <input type="text" name="email" class="form-control" value="{{old('email')}}">
                            </div>
                            <div class="form-group">
                                <label for="password" class="text-info">Số điện thoại:</label><br>
                                <input type="text" name="phone_number" class="form-control" value="{{old('phone_number')}}">
                            </div>
                            <div class="form-group">
                                <label for="username" class="text-info">Ảnh đại diện:</label><br>
                                <input type="file" name="avatar" class="form-control" value="{{old('avatar')}}">
                            </div>
                            <div class="form-group">
                                <label for="password" class="text-info">Mật khẩu:</label><br>
                                <input type="password" name="password" class="form-control" value="{{old('password')}}">
                            </div>
                            <div class="form-group">
                                <label for="password" class="text-info">Tỉnh / Thành phố:</label><br>
                                <select name="province" id="province" class="form-control" value="{{old('province')}}">
                                    <option value="" disabled selected>-- Chọn tỉnh/thành phố --</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="password" class="text-info">Quận / Huyện / Thị xã:</label><br>
                                <select name="district" id="district" class="form-control" value="{{old('district')}}">
                                    <option value="" disabled selected>-- Chọn quận/huyện/thị xã --</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="password" class="text-info">Xã / Phường / Thị trấn:</label><br>
                                <select name="ward" id="ward" class="form-control" value="{{old('ward')}}">
                                    <option value="" disabled selected>-- Chọn xã/phường/thị trấn --</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <input type="submit" name="submit" class="btn btn-info btn-md" value="submit">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
<script src="{{ asset('client/js/jquery-3.6.0.min.js') }}"></script>
<script>
    $(document).ready(function() {
        let provinces = [];
        let districts = [];
        let wards = [];

        $.getJSON('/hanhchinhvn/tinh_tp.json', function(data) {
            provinces = data;
            $.each(provinces, function(index, province) {
                $('#province').append(
                    `<option value="${province.code}">${province.name_with_type}</option>`
                );
            });
        });

        $.getJSON('/hanhchinhvn/quan_huyen.json', function(data) {
            districts = data;
        });

        $.getJSON('/hanhchinhvn/xa_phuong.json', function(data) {
            wards = data;
        });

        $('#province').on('change', function() {
            const selectedProvince = $(this).val();

            $('#district').html('<option value="">-- Vui lòng chọn 1 --</option>');
            $('#ward').html('<option value="">-- Chọn xã/phường/thị trấn --</option>');

            if (selectedProvince) {
                $.each(districts, function(index, district) {
                    if (district.parent_code === selectedProvince) {
                        $('#district').append(
                            `<option value="${index}">${district.name_with_type}</option>`
                        );
                    }
                });
            }
        });

        $('#district').on('change', function() {
            const selectedDistrict = $(this).val();

            $('#ward').html('<option value="">-- Vui lòng chọn 1 --</option>');

            if (selectedDistrict) {
                $.each(wards, function(index, ward) {
                    if (ward.parent_code === selectedDistrict) {
                        $('#ward').append(
                            `<option value="${index}">${ward.name_with_type}</option>`
                        );
                    }
                });
            }
        });
    });
</script>
@endsection