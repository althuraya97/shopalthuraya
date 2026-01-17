<div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark shadow" style="width: 250px; min-height: 100vh; position: sticky; top: 0;">
    <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
        <span class="fs-4 fw-bold">لوحة التحكم</span>
    </a>
    <hr>
    <ul class="nav nav-pills flex-column mb-auto px-0">
        <li class="nav-item mb-2">
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : 'text-white' }} d-flex justify-content-between align-items-center">
                <span><i class="fas fa-shopping-cart ms-2"></i> إدارة الطلبات</span>
                @if(isset($pendingOrdersCount) && $pendingOrdersCount > 0)
                    <span class="badge rounded-pill bg-danger shadow-sm anim-pulse">{{ $pendingOrdersCount }}</span>
                @endif
            </a>
        </li>
        <li class="mb-2">
            <a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : 'text-white' }}">
                <i class="fas fa-box-open ms-2"></i> إدارة المنتجات
            </a>
        </li>
        <li class="mb-2">
            <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : 'text-white' }}">
                <i class="fas fa-tags ms-2"></i> التصنيفات
            </a>
        </li>
    </ul>
    <hr>
    <form action="{{ route('admin.logout') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-danger w-100 btn-sm">
            <i class="fas fa-sign-out-alt ms-1"></i> تسجيل الخروج
        </button>
    </form>
</div>
