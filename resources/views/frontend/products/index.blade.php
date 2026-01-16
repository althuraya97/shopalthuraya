@extends('layouts.frontend')

@section('content')
<div class="row text-right" dir="rtl">
    <div class="col-lg-3 mb-4">
        <form action="{{ route('shop.index') }}" method="GET" class="filter-sidebar shadow-sm p-3 bg-white rounded-4">
            <h5 class="fw-bold mb-3 border-bottom pb-2">تصفية المنتجات</h5>

            <div class="mb-3">
                <label class="form-label fw-bold small">التصنيف الرئيسي</label>
                <select name="category" id="main_category" class="form-select shadow-sm">
                    <option value="">كل التصنيفات</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold small">التصنيف الفرعي</label>
                <select name="sub_category" id="sub_category" class="form-select shadow-sm">
                    <option value="">اختر القسم الرئيسي أولاً</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold small">نطاق السعر</label>
                <div class="d-flex gap-2">
                    <input type="number" name="min_price" class="form-control" placeholder="أدنى" value="{{ request('min_price') }}">
                    <input type="number" name="max_price" class="form-control" placeholder="أعلى" value="{{ request('max_price') }}">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold small">المقاس</label>
                <select name="size" class="form-select shadow-sm">
                    <option value="">كل المقاسات</option>
                    @foreach(['S', 'M', 'L', 'XL', 'XXL'] as $size)
                        <option value="{{ $size }}" {{ request('size') == $size ? 'selected' : '' }}>{{ $size }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary w-100 mt-3 rounded-pill shadow">تطبيق الفلاتر</button>
            <a href="{{ route('shop.index') }}" class="btn btn-link w-100 text-secondary mt-2 text-decoration-none small">إعادة تعيين</a>
        </form>
    </div>

    <div class="col-lg-9">
        <div class="d-flex justify-content-between align-items-center mb-4 bg-white p-3 rounded-4 shadow-sm">
            <div class="text-muted small">
                عرض <strong>{{ $products->count() }}</strong> من أصل <strong>{{ $products->total() }}</strong> منتج
            </div>

            <div class="d-flex align-items-center gap-2">
                <label class="text-nowrap mb-0 small fw-bold">ترتيب حسب:</label>
                <select class="form-select form-select-sm w-auto shadow-sm" onchange="location = this.value;">
                    <option value="{{ request()->fullUrlWithQuery(['sort' => 'latest']) }}" {{ request('sort') == 'latest' ? 'selected' : '' }}>الأحدث</option>
                    <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_low']) }}" {{ request('sort') == 'price_low' ? 'selected' : '' }}>السعر: من الأقل</option>
                    <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_high']) }}" {{ request('sort') == 'price_high' ? 'selected' : '' }}>السعر: من الأعلى</option>
                    <option value="{{ request()->fullUrlWithQuery(['sort' => 'oldest']) }}" {{ request('sort') == 'oldest' ? 'selected' : '' }}>الأقدم</option>
                </select>
            </div>
        </div>

        @if(request('search'))
            <p class="mb-4">نتائج البحث عن: <strong class="text-primary">"{{ request('search') }}"</strong></p>
        @endif

        <div class="row g-4">
            @forelse($products as $product)
                <div class="col-md-4 col-sm-6">
                    <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden product-card">
                        <div class="position-relative">
                            <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" style="height: 220px; object-fit: cover;" alt="{{ $product->name }}">
                        </div>
                        <div class="card-body d-flex flex-column">
                            <h6 class="text-primary small mb-1">{{ $product->category->name ?? 'تصنيف عام' }}</h6>
                            <h5 class="card-title h6 fw-bold text-dark">{{ $product->name }}</h5>
                            <p class="text-dark fw-bold mb-3 fs-5">{{ number_format($product->price) }} ر.س</p>

                            <div class="mt-auto">
                                <a href="{{ route('shop.show', $product->id) }}" class="btn btn-outline-primary w-100 rounded-pill">
                                    <i class="fas fa-eye ms-1"></i> عرض التفاصيل
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <div class="bg-white p-5 rounded-4 shadow-sm">
                        <i class="fas fa-search-minus fa-3x text-muted mb-3"></i>
                        <h4 class="text-muted">عذراً، لا توجد نتائج تطابق بحثك أو تصفيتك.</h4>
                        <p class="text-secondary">جرب تغيير الكلمات المفتاحية أو معايير التصفية.</p>
                        <a href="{{ route('shop.index') }}" class="btn btn-primary rounded-pill px-4">العودة للمتجر</a>
                    </div>
                </div>
            @endforelse
        </div>

        <div class="d-flex justify-content-center mt-5">
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const mainCat = document.getElementById('main_category');
        const subCat = document.getElementById('sub_category');
        const categories = @json($categories);

        mainCat.addEventListener('change', function () {
            const selectedId = this.value;
            subCat.innerHTML = '<option value="">كل الأقسام الفرعية</option>';

            if (selectedId) {
                const category = categories.find(c => c.id == selectedId);
                if (category && category.children && category.children.length > 0) {
                    category.children.forEach(sub => {
                        const option = document.createElement('option');
                        option.value = sub.id;
                        option.text = sub.name;
                        if (sub.id == "{{ request('sub_category') }}") {
                            option.selected = true;
                        }
                        subCat.appendChild(option);
                    });
                } else {
                    subCat.innerHTML = '<option value="">لا توجد أقسام فرعية</option>';
                }
            } else {
                subCat.innerHTML = '<option value="">اختر القسم الرئيسي أولاً</option>';
            }
        });

        if (mainCat.value) {
            mainCat.dispatchEvent(new Event('change'));
        }
    });
</script>
@endsection
