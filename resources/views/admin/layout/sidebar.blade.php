<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">SB Admin <sup>2</sup></div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="{{'dashboard'}}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Bảng điều khiển</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#user" aria-expanded="true"
            aria-controls="user">
            <i class="fas fa-fw fa-user"></i>
            <span>Quản lý người dùng</span>
        </a>
        <div id="user" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{route('users.list')}}">Danh sách người dùng</a>
                <a class="collapse-item" href="cards.html">Cards</a>
            </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#s_attribute" aria-expanded="true"
            aria-controls="s_attribute">
            <i class="fas fa-solid fa-cart-plus"></i>
            <span>Quản lý Shop Attribute</span>
        </a>
        <div id="s_attribute" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                {{-- Nút nhấn danh sách sản phẩm --}}
                <a class="collapse-item" href="{{route('brands.index')}}">Nhãn hàng</a>
                {{-- Nút nhấn danh sách biến thể sản phẩm --}}
                <a class="collapse-item" href="{{route('category.list')}}">Danh Mục</a>
            </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#products" aria-expanded="true"
            aria-controls="products">
            <i class="fas fa-solid fa-cart-plus"></i>
            <span>Quản lý sản phẩm</span>
        </a>
        <div id="products" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                {{-- Nút nhấn danh sách sản phẩm --}}
                <a class="collapse-item" href="{{route('products.list')}}">Danh sách sản phẩm</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Orders -->
    <li class="nav-item">
        <a class="nav-link" href="{{route('orders.index')}}">
            <i class="fas fa-fw fa-table"></i>
            <span>Quản lý dơn hàng</span></a>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#attribute" aria-expanded="true"
            aria-controls="attribute">
            <i class="fas fa-solid fa-cart-plus"></i>
            <span>Quản lý thuộc tính</span>
        </a>
        <div id="attribute" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                {{-- Nút nhấn danh sách sản phẩm --}}
                <a class="collapse-item" href="{{route('sizes.index')}}">Kích thước</a>
                {{-- Nút nhấn danh sách biến thể sản phẩm --}}
                <a class="collapse-item" href="{{route('colors.index')}}">Màu sắc</a>
            </div>
        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#vouchers" aria-expanded="true"
            aria-controls="vouchers">
            <i class="fas fa-solid fa-cart-plus"></i>
            <span>Quản lý mã giảm giá</span>
        </a>
        <div id="vouchers" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">

                <a class="collapse-item" href="{{route('vouchers.list')}}">Danh sách mã giảm giá</a>
            </div>
        </div>
    </li>

</ul>
<!-- End of Sidebar -->
