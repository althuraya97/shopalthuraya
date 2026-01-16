<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>متجر Zay</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background-color: #f4f7f6; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .navbar { shadow: 0 2px 4px rgba(0,0,0,.1); }
        .product-card:hover { transform: translateY(-5px); transition: 0.3s; }
        .search-bar { border-radius: 50px; padding-right: 20px; }
        .btn-search { border-radius: 0 50px 50px 0; position: absolute; left: 0; z-index: 5; height: 100%; }
        .nav-link { font-weight: 500; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold fs-3" href="{{ route('shop.index') }}">متجر Zay</a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarMain">

                <form class="d-flex mx-auto w-50 position-relative" action="{{ route('shop.index') }}" method="GET">
                    <input class="form-control search-bar pe-3" type="search" name="search"
                           placeholder="ابحث عن منتج..." value="{{ request('search') }}" aria-label="Search">
                    <button class="btn btn-primary rounded-pill px-4 position-absolute end-0 h-100" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </form>

                <ul class="navbar-nav ms-auto align-items-center">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('customer.login') }}">تسجيل الدخول</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-outline-light rounded-pill px-4 ms-lg-3" href="{{ route('customer.register') }}">إنشاء حساب</a>
                        </li>
                    @endguest

                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-white d-flex align-items-center" href="#" id="userDrop" role="button" data-bs-toggle="dropdown">
                                <div class="bg-primary rounded-circle d-flex justify-content-center align-items-center me-2" style="width: 35px; height: 35px;">
                                    {{ substr(Auth::user()->first_name, 0, 1) }}
                                </div>
                                <span>{{ Auth::user()->first_name }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                                <li><a class="dropdown-item" href="#"><i class="fas fa-box me-2"></i> طلباتي</a></li>
                                <li><a class="dropdown-item" href="#"><i class="fas fa-user-cog me-2"></i> الإعدادات</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('customer.logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="fas fa-sign-out-alt me-2"></i> تسجيل الخروج
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <main class="container py-4">
        @yield('content')
    </main>

    <footer class="bg-white py-4 mt-5 border-top">
        <div class="container text-center text-muted">
            <p class="mb-0"> جميع الحقوق محفوظة لمتجر Zay &copy; 2026</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
