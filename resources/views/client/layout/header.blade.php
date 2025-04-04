<!-- header section strats -->
<header class="header_section fixed-top bg-white">
  <nav class="navbar navbar-expand-lg custom_nav-container">
    <a class="navbar-brand" href="index.html">
      <span>
        HiShoe
      </span>
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
      aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class=""></span>
    </button>

    <div class="collapse navbar-collapse innerpage_navbar" id="navbarSupportedContent">
      <ul class="navbar-nav @if(Route::currentRouteName() == 'home') active @endif">
        <li class="nav-item ">
          <a class="nav-link" href="{{ route('home') }}">Trang chủ</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{ route('shop') }}">
            Mua sắm
          </a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="categoryDropdown" role="button" data-toggle="dropdown"
            aria-haspopup="true" aria-expanded="false">
            Danh mục
          </a>
          <div class="dropdown-menu" aria-labelledby="categoryDropdown">
            @foreach(App\Models\Category::all() as $category)
        <a class="dropdown-item" href="{{ route('shop', ['category_id' => $category->id]) }}">
          {{ $category->name }}
        </a>
        <input type="checkbox" name="category_id[]" value="{{ $category->id }}" 
       {{ in_array($category->id, (array) request('category_id', [])) ? 'checked' : '' }}>
      @endforeach
          </div>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="brandDropdown" role="button" data-toggle="dropdown"
            aria-haspopup="true" aria-expanded="false">
            Nhãn hàng
          </a>
          <div class="dropdown-menu" aria-labelledby="brandDropdown">
            @foreach(App\Models\Brand::all() as $brand)
        <a class="dropdown-item" href="{{ route('shop', ['brand_id' => $brand->id]) }}">
          {{ $brand->name }}
        </a>
        <input type="checkbox" name="brand_id[]" value="{{ $brand->id }}" 
       {{ in_array($brand->id, (array) request('brand_id', [])) ? 'checked' : '' }}>
      @endforeach
          </div>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="contact.html">Contact Us</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{ route('order.form') }}">Tình trạng đơn hàng</a>
        </li>
        @if (auth()->check())
            <li class="nav-item">
              <a class="nav-link" href="{{ route('order-history') }}">LỊCH SỬ ĐƠN HÀNG</a>
            </li>
        @endif
        @if (auth()->check())
        <li class="nav-item">
          <a class="nav-link" href="{{ route('liked.products') }}">Sản phẩm đã thích</a>
        
        </li>
        @endif
      </ul>
      <div class="user_option">

        @if (Auth::user())
      <div class="dropdown">
        <a class="dropdown-toggle" href="#" role="button" id="userDropdown" data-toggle="dropdown"
        aria-haspopup="true" aria-expanded="false">
        <img src="https://i.pinimg.com/736x/d3/9f/65/d39f65eadc2dc28cf859e77680f3fe42.jpg" alt="" style="width: 35px; height: auto; border-radius: 50%;">
        <span>
          {{ Auth::user()->username }}
        </span>
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
      <span>
      Login
      </span>
    </a>
  @endif
        <a href="" class="nav-link position-relative">
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
        <a href="">
          <i class="fa fa-heart" aria-hidden="true"></i>
        </a>
        <form class="form-inline ">
          <button class="btn nav_search-btn" type="submit">
            <i class="fa fa-search" aria-hidden="true"></i>
          </button>
        </form>

      </div>
    </div>
  </nav>
</header>
<!-- end header section -->