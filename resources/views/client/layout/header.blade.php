    <!-- header section strats -->
    <header class="header_section">
      <nav class="navbar navbar-expand-lg custom_nav-container ">
        <a class="navbar-brand" href="index.html">
          <span>
            HiShoe
          </span>
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class=""></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav  ">
            <li class="nav-item active">
              <a class="nav-link" href="index.html">Home <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="shop.html">
                Shop
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="why.html">
                Why Us
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="testimonial.html">
                Testimonial
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="contact.html">Contact Us</a>
            </li>
          </ul>
          <div class="user_option">

            @if (Auth::user())
            <a href="{{ route('logout') }}">
              <i class="fa fa-user" aria-hidden="true"></i>
              <span>
                {{ Auth::user()->username }}
              </span>
            </a>
            @else
            <a href="{{ route('login') }}">
              <i class="fa fa-user" aria-hidden="true"></i>
              <span>
                Login
              </span>
            </a>
            @endif


            <a href="{{ route('cart.index') }}" class="nav-link position-relative">
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