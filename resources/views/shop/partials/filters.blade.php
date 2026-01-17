{{-- resources/views/shop/partials/filters.blade.php --}}
<form action="{{ route('shop.index') }}" method="GET" id="filterForm">
    <h5 class="fw-bold mb-3 d-none d-lg-block">تصفية المنتجات</h5>

    <div class="mb-3">
        <label class="form-label small fw-bold">التصنيف الرئيسي</label>
        <select name="category" id="main_category" class="form-select shadow-sm border-0 bg-light">
            <option value="">كل التصنيفات</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                    {{ $cat->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label small fw-bold">التصنيف الفرعي</label>
        <select name="sub_category" id="sub_category" class="form-select shadow-sm border-0 bg-light">
            <option value="">اختر القسم الرئيسي أولاً</option>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label small fw-bold">المقاس</label>
        <select name="size" class="form-select shadow-sm border-0 bg-light">
            <option value="">كل المقاسات</option>
            @foreach(['S', 'M', 'L', 'XL', 'XXL', '3XL'] as $size)
                <option value="{{ $size }}" {{ request('size') == $size ? 'selected' : '' }}>{{ $size }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label small fw-bold">نطاق السعر</label>
        <div class="d-flex gap-2">
            <input type="number" name="min_price" class="form-control form-control-sm border-0 bg-light" placeholder="أدنى" value="{{ request('min_price') }}">
            <input type="number" name="max_price" class="form-control form-control-sm border-0 bg-light" placeholder="أعلى" value="{{ request('max_price') }}">
        </div>
    </div>

    <button type="submit" class="btn btn-primary w-100 rounded-pill mt-3 shadow-sm">
    تطبيق الفلاتر
</button>

    <a href="{{ route('shop.index') }}" class="btn btn-link w-100 text-secondary mt-2 text-decoration-none small">إعادة تعيين</a>
</form>
