<style>
  .modal {
    z-index: 1055 !important; /* Đảm bảo modal luôn ở trên cùng */
  }
  .modal-backdrop {
    z-index: 1050 !important; /* Đảm bảo backdrop nằm dưới modal */
  }
  body {
  padding-top: 70px; /* Điều chỉnh theo chiều cao của header */
}
  .header_section {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%; /* Đảm bảo header chiếm toàn bộ chiều ngang */
  z-index: 1020;
}
</style>
<header class="header_section fixed-top bg-white">
  <nav class="navbar navbar-expand-lg custom_nav-container ">
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
        <a class="dropdown-item" href="{{ route('category', ['category_id' => $category->id]) }}">
          {{ $category->name }}
        </a>
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
        <a class="dropdown-item" href="{{ route('brand', ['brand_id' => $brand->id]) }}">
          {{ $brand->name }}
        </a>
      @endforeach
          </div>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="contact.html">Contact Us</a>
        </li>
      </ul>
      <div class="user_option">

        @if (Auth::guard('web')->user())
      <div class="dropdown">
        <a class="dropdown-toggle" href="#" role="button" id="userDropdown" data-toggle="dropdown"
        aria-haspopup="true" aria-expanded="false">
        <i class="fa fa-user" aria-hidden="true"></i>
        <span>
          {{ Auth::guard('web')->user()->username }}
        </span>
        </a>
        <div class="dropdown-menu" aria-labelledby="userDropdown">
        <a class="dropdown-item" href="{{ route('user.profile') }}">Thông tin cá nhân</a>
        <a class="dropdown-item" href="{{ route('order-history') }}">Đơn hàng của tôi</a>
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
        <a href="{{ route('cart') }}" class="nav-link position-relative">
          <i class="fa fa-shopping-cart fa-lg"></i>
          @if(Auth::guard('web')->check())
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
        <a href="{{ route('liked.products') }}" id="likedProductsLink">
          <i class="fa fa-heart" aria-hidden="true"></i>
        </a>
        <form class="form-inline ">
          <a href="#" class="nav-link" data-toggle="modal" data-target="#searchModal">
            <i class="fa fa-search" aria-hidden="true"></i>
          </a>
        </form>

      </div>
    </div>
  </nav>
</header>

<!-- Modal for login prompt -->
<div class="modal fade" id="loginPromptModal" tabindex="-1" role="dialog" aria-labelledby="loginPromptModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="loginPromptModalLabel">Thông báo</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Bạn cần đăng nhập để xem sản phẩm yêu thích.
      </div>
      <div class="modal-footer">
        <a href="{{ route('loginForm') }}" class="btn btn-primary">Đăng nhập</a>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const likedProductsLink = document.getElementById('likedProductsLink');
    likedProductsLink.addEventListener('click', function (event) {
      @if (!Auth::guard('web')->check())
        event.preventDefault();
        $('#loginPromptModal').modal('show');
      @endif
    });
  });
</script>

<!-- end header section -->