@extends('layouts.frontend')

@section('content')
<div class="container-fluid py-4">
    <div class="d-lg-none mb-3">
        <button class="btn btn-dark w-100 rounded-pill shadow-sm py-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#filterSidebar">
            <i class="fas fa-filter ms-2"></i> تصفية المنتجات بحثاً عن الأناقة
        </button>
    </div>

    <div class="row text-right" dir="rtl">
        <div class="col-lg-3 d-none d-lg-block">
            <aside class="filter-sidebar shadow-sm p-4 bg-white rounded-4 sticky-top" style="top: 100px;">
                @include('shop.partials.filters')
            </aside>
        </div>

        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-4 bg-white p-3 rounded-4 shadow-sm">
                <div class="text-muted small">
                    عرض <strong>{{ $products->count() }}</strong> منتج
                </div>
                <select class="form-select form-select-sm w-auto shadow-sm border-0 bg-light" onchange="location = this.value;">
                    <option value="{{ request()->fullUrlWithQuery(['sort' => 'latest']) }}" {{ request('sort') == 'latest' ? 'selected' : '' }}>الأحدث</option>
                    <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_low']) }}" {{ request('sort') == 'price_low' ? 'selected' : '' }}>السعر: من الأقل</option>
                    <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_high']) }}" {{ request('sort') == 'price_high' ? 'selected' : '' }}>السعر: من الأعلى</option>
                </select>
            </div>

            <div class="row g-3">
                @forelse($products as $product)
                    <div class="col-6 col-md-4 col-lg-4">
                        <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden product-card">
                            <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" style="height: 200px; object-fit: cover;" alt="{{ $product->name }}" onerror="this.src='https://via.placeholder.com/200x200?text=No+Image'">
                            <div class="card-body p-3">
                                <h5 class="card-title h6 fw-bold text-dark">{{ $product->name }}</h5>
                                <p class="text-primary fw-bold mb-2">{{ number_format($product->price, 2) }} ر.ع</p>
                                <a href="{{ route('shop.show', $product->id) }}" class="btn btn-sm btn-outline-dark w-100 rounded-pill small">التفاصيل</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <p class="text-muted">لا توجد منتجات تطابق خياراتك حالياً.</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-4">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>

<div class="offcanvas offcanvas-start" tabindex="-1" id="filterSidebar" aria-labelledby="filterSidebarLabel">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title fw-bold" id="filterSidebarLabel">تصفية المنتجات</h5>
        <button type="button" class="btn-close ms-0 me-auto" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        @include('shop.partials.filters')
    </div>
</div>
@endsection

<script>
document.addEventListener('DOMContentLoaded', function () {
    // تحديد النموذج (الفورم) داخل السايدبار المنزلق
    const filterForm = document.querySelector('#filterSidebar form');

    if (filterForm) {
        filterForm.addEventListener('submit', function(e) {
            // 1. العثور على عنصر السايدبار
            const filterCanvas = document.getElementById('filterSidebar');

            // 2. الحصول على نسخة البوتستراب للتحكم به
            const instance = bootstrap.Offcanvas.getInstance(filterCanvas);

            // 3. إغلاق السايدبار فوراً
            if (instance) {
                instance.hide();
            }

            // سيستمر النموذج في الإرسال وتحديث الصفحة والسايدبار مغلق
        });
    }
});
</script>
