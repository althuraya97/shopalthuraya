@extends('layouts.app')

@section('content')
<div class="container-fluid py-4" dir="rtl text-right">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow border-0">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0 text-primary">
                        <i class="fas fa-edit me-2"></i> تعديل القسم: {{ $category->name }}
                    </h5>
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-sm btn-light border">
                        <i class="fas fa-arrow-right"></i> عودة للقمائمة
                    </a>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="name" class="form-label fw-bold">اسم القسم الأساسي</label>
                            <input type="text" name="name" id="name"
                                   class="form-control form-control-lg @error('name') is-invalid @enderror"
                                   value="{{ old('name', $category->name) }}"
                                   placeholder="أدخل اسم القسم الجديد" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- ملاحظة للآدمن --}}
                        <div class="alert alert-info border-0 shadow-sm">
                            <i class="fas fa-info-circle me-2"></i>
                            تعديل اسم القسم الأساسي سيؤثر على جميع الأقسام الفرعية والمنتجات المرتبطة به.
                        </div>

                        <div class="d-grid gap-2 mt-5">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save me-2"></i> حفظ التغييرات
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- قسم إضافي اختياري: عرض الأقسام الفرعية المرتبطة --}}
            <div class="card mt-4 shadow border-0">
                <div class="card-header bg-light">
                    <h6 class="mb-0 fw-bold">الأقسام الفرعية التابعة لهذا القسم</h6>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @forelse($category->subCategories as $sub)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $sub->name }}
                                <span class="badge bg-secondary rounded-pill">فرعي</span>
                            </li>
                        @empty
                            <li class="list-group-item text-muted text-center">لا توجد أقسام فرعية حالياً</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
