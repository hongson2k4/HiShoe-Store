@extends('client.layout.main')

@section('content')
     <style>
        .form-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .form-title {
            color: #007bff;
            margin-bottom: 20px;
            text-align: center;
        }
        .required-field::after {
            content: "*";
            color: red;
            margin-left: 4px;
        }
    </style>

    <div class="container mt-4">
        <div class="form-container">
            <h2 class="form-title">Thêm Địa Chỉ Người Nhận</h2>
            <form id="recipientAddressForm">
                <div class="row">
                    <!-- Họ tên người nhận -->
                    <div class="col-md-6 mb-3">
                        <label for="recipientName" class="required-field">Họ tên người nhận</label>
                        <input type="text" class="form-control" id="recipientName" required>
                    </div>
                    
                    <!-- Số điện thoại -->
                    <div class="col-md-6 mb-3">
                        <label for="recipientPhone" class="required-field">Số điện thoại</label>
                        <input type="tel" class="form-control" id="recipientPhone" required>
                    </div>
                </div>
                
                <div class="row">
                    <!-- Tỉnh/Thành phố -->
                    <div class="col-md-6 mb-3">
                        <label for="province" class="required-field">Tỉnh/Thành phố</label>
                        <select class="form-control" id="province" required>
                            <option value="">Chọn Tỉnh/Thành phố</option>
                            <option value="hanoi">Hà Nội</option>
                            <option value="hochiminh">TP. Hồ Chí Minh</option>
                            <option value="danang">Đà Nẵng</option>
                            <option value="haiphong">Hải Phòng</option>
                            <!-- Có thể thêm các tỉnh thành khác -->
                        </select>
                    </div>
                    
                    <!-- Quận/Huyện -->
                    <div class="col-md-6 mb-3">
                        <label for="district" class="required-field">Quận/Huyện</label>
                        <select class="form-control" id="district" required>
                            <option value="">Chọn Quận/Huyện</option>
                            <!-- Sẽ được cập nhật dựa trên tỉnh/thành phố -->
                        </select>
                    </div>
                </div>
                
                <div class="row">
                    <!-- Phường/Xã -->
                    <div class="col-md-6 mb-3">
                        <label for="ward" class="required-field">Phường/Xã</label>
                        <select class="form-control" id="ward" required>
                            <option value="">Chọn Phường/Xã</option>
                            <!-- Sẽ được cập nhật dựa trên quận/huyện -->
                        </select>
                    </div>
                    
                    <!-- Đường -->
                    <div class="col-md-6 mb-3">
                        <label for="street" class="required-field">Đường</label>
                        <input type="text" class="form-control" id="street" required>
                    </div>
                </div>
                
                <!-- Số nhà, thông tin khác -->
                <div class="mb-3">
                    <label for="houseNumber" class="required-field">Số nhà, tòa nhà, thông tin khác</label>
                    <textarea class="form-control" id="houseNumber" rows="2" required></textarea>
                </div>
                
                <!-- Địa chỉ mặc định -->
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="defaultAddress">
                    <label class="form-check-label" for="defaultAddress">
                        Đặt làm địa chỉ mặc định
                    </label>
                </div>
                
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary px-4">Lưu địa chỉ</button>
                    <button type="button" class="btn btn-outline-secondary px-4 ml-2">Hủy</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/js/bootstrap.bundle.min.js"></script>
    <script>
        // Sample data for demonstration
        const locationData = {
            hanoi: {
                name: "Hà Nội",
                districts: {
                    "hoankiem": {
                        name: "Hoàn Kiếm",
                        wards: ["Hàng Bạc", "Hàng Bồ", "Hàng Đào"]
                    },
                    "caugiay": {
                        name: "Cầu Giấy",
                        wards: ["Dịch Vọng", "Mai Dịch", "Yên Hòa"]
                    }
                }
            },
            hochiminh: {
                name: "TP. Hồ Chí Minh",
                districts: {
                    "district1": {
                        name: "Quận 1",
                        wards: ["Bến Nghé", "Bến Thành", "Đa Kao"]
                    },
                    "district3": {
                        name: "Quận 3",
                        wards: ["Phường 1", "Phường 2", "Phường 3"]
                    }
                }
            }
        };

        // Populate districts when province changes
        $("#province").change(function() {
            const provinceId = $(this).val();
            const districtSelect = $("#district");
            districtSelect.empty().append('<option value="">Chọn Quận/Huyện</option>');
            $("#ward").empty().append('<option value="">Chọn Phường/Xã</option>');
            
            if (provinceId && locationData[provinceId]) {
                const districts = locationData[provinceId].districts;
                for (const [id, district] of Object.entries(districts)) {
                    districtSelect.append(`<option value="${id}">${district.name}</option>`);
                }
            }
        });

        // Populate wards when district changes
        $("#district").change(function() {
            const provinceId = $("#province").val();
            const districtId = $(this).val();
            const wardSelect = $("#ward");
            wardSelect.empty().append('<option value="">Chọn Phường/Xã</option>');
            
            if (provinceId && districtId && 
                locationData[provinceId] && 
                locationData[provinceId].districts[districtId]) {
                const wards = locationData[provinceId].districts[districtId].wards;
                wards.forEach(ward => {
                    wardSelect.append(`<option value="${ward}">${ward}</option>`);
                });
            }
        });

        // Form validation
        $("#recipientAddressForm").submit(function(e) {
            e.preventDefault();
            
            // Basic validation
            let isValid = true;
            $(this).find('[required]').each(function() {
                if (!$(this).val()) {
                    $(this).addClass('is-invalid');
                    isValid = false;
                } else {
                    $(this).removeClass('is-invalid');
                }
            });
            
            if (isValid) {
                alert("Địa chỉ đã được lưu thành công!");
                // Here you would typically submit the form data via AJAX
            } else {
                alert("Vui lòng điền đầy đủ thông tin bắt buộc!");
            }
        });
    </script>

@endsection