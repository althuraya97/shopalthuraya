<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>متجر مباهج الخد</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary-color: #0d6efd;
            --bg-light: #f4f7f6;
        }

        body {
            background-color: var(--bg-light);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding-bottom: 60px; /* مساحة للـ Mobile Bottom Nav إذا أردت إضافته */
        }

        /* تحسين الهيدر */
        .navbar {
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 0.8rem 0;
        }

        .navbar-brand {
            font-size: 1.5rem !important;
            letter-spacing: -1px;
        }

        /* تحسين شريط البحث للجوال والكمبيوتر */
        .search-container {
            position: relative;
            width: 100%;
            max-width: 500px;
        }

        .search-bar {
            border-radius: 50px !important;
            padding-right: 20px;
            padding-left: 50px; /* مساحة للزر */
            border: 1px solid #ddd;
            height: 45px;
        }

        .btn-search {
            border-radius: 50px !important;
            position: absolute;
            left: 5px;
            top: 5px;
            bottom: 5px;
            z-index: 5;
            padding: 0 20px;
        }

        /* تحسين مظهر الكروت */
        .product-card {
            transition: all 0.3s ease;
            border: none !important;
        }
        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
        }

        /* تعديلات الجوال */
        @media (max-width: 991.98px) {
            .navbar-collapse {
                background: #fff;
                margin-top: 15px;
                padding: 20px;
                border-radius: 20px;
                box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            }

            .navbar-nav .nav-link {
                color: #333 !important;
                padding: 10px 0;
                border-bottom: 1px solid #f0f0f0;
            }

            .search-container {
                margin: 15px 0;
                max-width: 100%;
            }

            .dropdown-menu {
                border: none;
                background: #f8f9fa;
                padding-right: 20px;
            }
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('shop.index') }}">
                <i class="fas fa-shopping-bag me-2 text-primary"></i> مباهج الخد
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarMain">
                <form class="search-container mx-auto" action="{{ route('shop.index') }}" method="GET">
                    <input class="form-control search-bar" type="search" name="search"
                           placeholder="ابحث عن منتج..." value="{{ request('search') }}">
                    <button class="btn btn-primary btn-search" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </form>

                <ul class="navbar-nav ms-auto align-items-lg-center">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('customer.login') }}">تسجيل الدخول</a>
                       </li>
                        <li class="nav-item mt-2 mt-lg-0">
                            <a class="btn btn-primary rounded-pill px-4 ms-lg-3 w-100" href="{{ route('customer.register') }}">إنشاء حساب</a>
                        </li>
                    @endguest

                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-white d-flex align-items-center" href="#" id="userDrop" data-bs-toggle="dropdown">
                                <div class="bg-primary rounded-circle d-flex justify-content-center align-items-center ms-2 shadow-sm" style="width: 35px; height: 35px;">
                                    <i class="fas fa-user text-white small"></i>
                                </div>
                                <span>{{ Auth::user()->first_name }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-lg-2 animate slideIn">
                                <li><a class="dropdown-item py-2" href="#"><i class="fas fa-box ms-2 text-muted"></i> طلباتي</a></li>
                                <li><a class="dropdown-item py-2" href="#"><i class="fas fa-heart ms-2 text-muted"></i> المفضلة</a></li>
                                <li><a class="dropdown-item py-2" href="#"><i class="fas fa-user-cog ms-2 text-muted"></i> الإعدادات</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('customer.logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger py-2">
                                            <i class="fas fa-sign-out-alt ms-2"></i> تسجيل الخروج
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

    <main class="container py-4 min-vh-100">
        @yield('content')
    </main>

    <footer class="bg-white py-5 mt-5 border-top">
        <div class="container text-center">
            <div class="mb-4">
                <a class="text-dark text-decoration-none fw-bold fs-4" href="#">مباهج الخد</a>
                <p class="text-muted mt-2">اختيارك الأول للأناقة والجمال</p>
            </div>
            <div class="social-links mb-4">
                <a href="#" class="text-muted mx-2 fs-5"><i class="fab fa-instagram"></i></a>
                <a href="#" class="text-muted mx-2 fs-5"><i class="fab fa-whatsapp"></i></a>
                <a href="#" class="text-muted mx-2 fs-5"><i class="fab fa-snapchat"></i></a>
            </div>
            <p class="mb-0 text-muted small"> جميع الحقوق محفوظة لمتجر مباهج الخد &copy; 2026 </p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
