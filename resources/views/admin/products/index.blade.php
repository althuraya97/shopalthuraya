@extends('layouts.app')

@section('content')
<div class="container-fluid py-4" dir="rtl text-right">
    {{-- الرأس --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark">
            <i class="fas fa-boxes me-2 text-primary"></i> إدارة المنتجات
        </h2>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus ms-1"></i> إضافة منتج جديد
        </a>
    </div>

    {{-- رسائل التنبيه --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm text-right" role="alert">
            <i class="fas fa-check-circle ms-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="float: left;"></button>
        </div>
    @endif

    {{-- قسم البحث والتصفية --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body bg-light rounded">
            <form action="{{ route('admin.products.index') }}" method="GET" class="row g-3 align-items-end">
                {{-- البحث بالاسم --}}
                <div class="col-md-5">
                    <label class="form-label small fw-bold text-secondary">البحث عن منتج</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-start-0 text-muted">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" name="search" class="form-control border-end-0"
                               placeholder="ادخل اسم المنتج..." value="{{ request('search') }}">
                    </div>
                </div>

                {{-- التصفية بالتصنيف (شامل الأساسي والفرعي) --}}
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-secondary">تصفية حسب القسم</label>
                    <select name="category" class="form-select shadow-sm">
                        <option value="">كل الأقسام</option>
                        @foreach($categories as $cat)
                            {{-- عرض القسم الرئيسي --}}
                            <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }} class="fw-bold bg-light">
                                {{ $cat->name }} (رئيسي)
                            </option>
                            {{-- عرض الأقسام الفرعية التابعة له --}}
                            @foreach($cat->children as $child)
                                <option value="{{ $child->id }}" {{ request('category') == $child->id ? 'selected' : '' }}>
                                    &nbsp;&nbsp;&nbsp;&nbsp; ↳ {{ $child->name }}
                                </option>
                            @endforeach
                        @endforeach
                    </select>
                </div>

                {{-- أزرار التحكم --}}
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-dark px-4 flex-grow-1 shadow-sm">
                        <i class="fas fa-filter ms-1"></i> تطبيق الفلتر
                    </button>
                    @if(request()->has('search') || request()->has('category'))
                        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-danger shadow-sm" title="إعادة ضبط">
                            <i class="fas fa-undo"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- جدول المنتجات --}}
    <div class="card shadow border-0 text-right">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle text-center mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th style="width: 80px;">الصورة</th>
                            <th class="text-right">اسم المنتج</th>
                            <th>القسم الأساسي</th>
                            <th>السعر</th>
                            <th>تاريخ الإضافة</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                        <tr>
                            <td>
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}"
                                         class="rounded shadow-sm border"
                                         width="55" height="55"
                                         style="object-fit: cover;">
                                @else
                                    <div class="bg-light text-muted d-flex align-items-center justify-content-center mx-auto rounded border" style="width:55px; height:55px;">
                                        <i class="fas fa-box fa-lg opacity-25"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="text-right">
                                <div class="fw-bold text-dark">{{ $product->name }}</div>
                                {{-- عرض الوسوم (الأقسام الفرعية المرتبطة Many-to-Many) --}}
                                <div class="mt-1">
                                    @foreach($product->subCategories as $sub)
                                        <span class="badge bg-info-subtle text-info border border-info-subtle px-1" style="font-size: 10px;">
                                            {{ $sub->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>
                            <td>
                                @if($product->category)
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1">
                                        <i class="fas fa-folder ms-1 small"></i> {{ $product->category->name }}
                                    </span>
                                @else
                                    <span class="text-muted small italic">بدون قسم</span>
                                @endif
                            </td>
                            <td>
                                <span class="text-success fw-bold">{{ number_format($product->price, 2) }} $</span>
                            </td>
                            <td>
                                <div class="small text-muted">{{ $product->created_at->format('Y-m-d') }}</div>
                                <div class="text-muted" style="font-size: 10px;">{{ $product->created_at->diffForHumans() }}</div>
                            </td>
                            <td>
                                <div class="btn-group gap-2">
                                    <a href="{{ route('admin.products.edit', $product->id) }}"
                                       class="btn btn-sm btn-outline-warning rounded" title="تعديل">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('تنبيه: سيتم حذف المنتج نهائياً إلا إذا كان مرتبطاً بطلبات سابقة فسيتم أرشفته. استمرار؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded" title="حذف">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-5 text-muted bg-light">
                                <i class="fas fa-search fa-3x d-block mb-3 opacity-25"></i>
                                <span class="fs-5">لا توجد منتجات مطابقة لهذا البحث أو الفلتر.</span>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- الترقيم (Pagination) --}}
    <div class="d-flex justify-content-center mt-4">
        {{ $products->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
