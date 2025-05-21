<!-- header section starts -->
<header class="header_section">
  <style>
    /* NAV LINK BASE */
    .navbar-nav .nav-item .nav-link {
      color: #333;
      padding: 8px 15px;
      position: relative;
      transition: all 0.3s ease;
    }

    /* Hover: underline mảnh */
    .navbar-nav .nav-item .nav-link:hover::after {
      content: "";
      position: absolute;
      left: 0;
      bottom: 0;
      width: 100%;
      height: 2px;
      background-color: #ff6b00;
      transition: all 0.3s ease;
    }

    /* Active: underline đậm hơn */
    .navbar-nav .nav-item.active .nav-link::after {
      content: "";
      position: absolute;
      left: 0;
      bottom: 0;
      width: 100%;
      height: 3px;
      background-color: #ff6b00;
    }

    .navbar-nav .nav-item.active .nav-link {
      color: #ff6b00;
      font-weight: bold;
    }
  </style>

  <nav class="navbar navbar-expand-lg custom_nav-container">
    <a class="navbar-brand" href="{{ route('home') }}">
      <span>HiShoe</span>
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
      aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class=""></span>
    </button>

    <div class="collapse navbar-collapse innerpage_navbar" id="navbarSupportedContent">
      <ul class="navbar-nav">
        <li class="nav-item @if(Route::currentRouteName() == 'home') active @endif">
          <a class="nav-link" href="{{ route('home') }}">Trang chủ</a>
        </li>
        <li class="nav-item @if(Route::currentRouteName() == 'shop') active @endif">
          <a class="nav-link" href="{{ route('shop') }}">Mua sắm</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="categoryDropdown" role="button" data-toggle="dropdown"
            aria-haspopup="true" aria-expanded="false">Danh mục</a>
          <div class="dropdown-menu" aria-labelledby="categoryDropdown">
            @foreach(App\Models\Category::all() as $category)
              <a class="dropdown-item" href="{{ route('category', ['category_id' => $category->id]) }}">
                {{ $category->name }}
              </a>
            @endforeach
          </div>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="brandDropdown" role="button" data-toggle="dropdown"
            aria-haspopup="true" aria-expanded="false">Nhãn hàng</a>
          <div class="dropdown-menu" aria-labelledby="brandDropdown">
            @foreach(App\Models\Brand::all() as $brand)
              <a class="dropdown-item" href="{{ route('brand', ['brand_id' => $brand->id]) }}">
                {{ $brand->name }}
              </a>
            @endforeach
          </div>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="contact.html">Liên hệ</a>
        </li>
      </ul>
<!-- User Options -->
      <div class="user_option">
        @if (Auth::user())
        <div class="dropdown">
          <a class="dropdown-toggle" id="userDropdown" data-toggle="dropdown" aria-haspopup="true"
            aria-expanded="false">
            <i class="fa fa-user" aria-hidden="true"></i>
            <span>{{ Auth::user()->username }}</span>
          </a>
          <div class="dropdown-menu" aria-labelledby="userDropdown">
            <a class="dropdown-item" href="{{ route('user.profile') }}">Thông tin cá nhân</a>
            <a class="dropdown-item" href="{{ route('password.change') }}">Đổi mật khẩu</a>
            <a class="dropdown-item" href="{{ route('logout') }}">Đăng xuất</a>
          </div>
        </div>
        @else
        <a href="{{ route('loginForm') }}">
          <i class="fa fa-user" aria-hidden="true"></i>
          <span>Đăng nhập</span>
        </a>
        @endif

        <a href="{{ route('cart') }}" class="nav-link position-relative">
          <i class="fa fa-shopping-cart fa-lg"></i>
          @if(Auth::check())
            @php $cartCount = App\Models\Cart::where('user_id', Auth::id())->sum('quantity'); @endphp
          @else
            @php $cartCount = collect(Session::get('cart', []))->sum('quantity'); @endphp
          @endif
          @if($cartCount > 0)
            <span class="position-absolute top-0 end-5 translate-middle badge rounded-pill bg-danger text-light">
              {{ $cartCount }}
            </span>
          @endif
        </a>

        <a href=""><i class="fa fa-heart" aria-hidden="true"></i></a>

        <div class="dropdown">
          <a class="dropdown-toggle" role="button" id="searchDropdown" data-toggle="dropdown"
            aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-search" aria-hidden="true"></i>
          </a>
          <div class="dropdown-menu" aria-labelledby="searchDropdown">
            <a class="dropdown-item" href="">Tìm kiếm sản phẩm</a>
            @auth
            <a class="dropdown-item" href="{{ route('order-history') }}">Đơn hàng của tôi</a>
            @endauth
            <a class="dropdown-item" href="{{ route('order.form') }}">Tình trạng đơn hàng</a>
          </div>
        </div>

      </div>
    </div>
  </nav>
</header>
<!-- end header section -->