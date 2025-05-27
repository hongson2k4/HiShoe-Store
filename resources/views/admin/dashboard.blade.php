@extends('admin.layout.main')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Bảng điều khiển</h1>
        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                class="fas fa-download fa-sm text-white-50"></i> Tạo báo cáo</a>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Sản phẩm</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{$pro}}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Khách hàng</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $user }}</div>
                        </div>
                        <div class="col-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 2rem; height: 2rem; color: #6c757d;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Đơn hàng
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $order }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-box fa-2x text-muted"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Doanh thu</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($doanhthu, 0, ',', '.') }} đ</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Requests Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Bình luận</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{$comment}}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-comments fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Nhãn hàng</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{$brand}}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-building fs-2 text-primary"></i> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Danh mục</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{$cat}}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-list-ul fs-2 text-secondary"></i>   
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Mã giảm giá</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{$vou}}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-box-seam fs-2 text-muted"></i>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <div class="container-fluid">
            <form method="GET" action="{{ route('admin.dashboard') }}" class="mb-4">
                <div class="form-row align-items-end">
                    <div class="col-auto">
                        <label for="from_date">Từ ngày</label>
                        <input type="date" class="form-control" id="from_date" name="from_date" value="{{ $fromDate ?? '' }}">
                    </div>
                    <div class="col-auto">
                        <label for="to_date">Đến ngày</label>
                        <input type="date" class="form-control" id="to_date" name="to_date" value="{{ $toDate ?? '' }}">
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary">Xem thống kê</button>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Xóa lọc</a>
                    </div>
                </div>
            </form>
            <form method="GET" action="{{ route('admin.dashboard') }}" class="mb-4">
                <label for="timeframe">Chọn khoảng thời gian:</label>
                <select id="timeframe" name="type" class="form-control" onchange="this.form.submit()">
                    <option value="day" {{ request('type') == 'day' ? 'selected' : '' }}>Ngày</option>
                    <option value="week" {{ request('type') == 'week' ? 'selected' : '' }}>Tuần</option>
                    <option value="month" {{ request('type') == 'month' ? 'selected' : '' }}>Tháng</option>
                    <option value="year" {{ request('type') == 'year' ? 'selected' : '' }}>Năm</option>
                </select>
            </form>
            <canvas id="myChart"></canvas>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const ctx = document.getElementById('myChart');
            const type = "{{ request('type', 'day') }}"; // Lấy type từ URL, mặc định là 'day'
            
            // Dữ liệu từ controller
            const data = {
                labels: @json($labels),
                datasets: [{
                    label: `Doanh thu theo ${type === 'day' ? 'ngày' : type === 'week' ? 'tuần' : type === 'month' ? 'tháng' : 'năm'}`,
                    data: @json($chartData),
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            };

            new Chart(ctx, {
                type: 'bar',
                data: data,
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return value.toLocaleString() + ' VNĐ';
                                }
                            }
                        }
                    }
                }
            });
        </script>
    </div>
@endsection