@extends('layouts.app')

@section('content')
<div class="container-fluid py-4" dir="rtl text-right">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm border-0">
                {{-- رأس البطاقة --}}
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-edit ms-2"></i> تعديل المنتج: {{ $product->name }}
                    </h5>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-light btn-sm shadow-sm">
                        <i class="fas fa-arrow-right"></i> عودة للقائمة
                    </a>
                </div>

                <div class="card-body p-4 text-right">
                    {{-- عرض الأخطاء إن وجدت --}}
                    @if($errors->any())
                        <div class="alert alert-danger border-0 shadow-sm mb-4">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- تم دمج كل شيء في هذا النموذج لضمان الحفظ الصحيح --}}
                    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-8">
                                {{-- الصف الأول: رقم المنتج واسم المنتج --}}
                                <div class="row">
                                    {{-- رقم المنتج (ID) --}}
                                    <div class="col-md-4 mb-4">
                                        <label class="form-label fw-bold text-secondary">رقم المنتج (ID)</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light"><i class="fas fa-fingerprint"></i></span>
                                            <input type="text" class="form-control bg-light" value="#{{ $product->id }}" readonly>
                                        </div>
                                        <small class="text-muted">مرجع تلقائي غير قابل للتعديل.</small>
                                    </div>

                                    {{-- اسم المنتج --}}
                                    <div class="col-md-8 mb-4">
                                        <label class="form-label fw-bold">اسم المنتج <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                               value="{{ old('name', $product->name) }}" required>
                                    </div>
                                </div>

                                {{-- الصف الثاني: السعر والمخزون --}}
                                <div class="row">
                                    {{-- السعر --}}
                                    <div class="col-md-6 mb-4">
                                        <label class="form-label fw-bold">السعر الحالي</label>
                                        <div class="input-group" dir="ltr">
                                            <span class="input-group-text bg-light">USD</span>
                                            <input type="number" step="0.01" name="price" class="form-control"
                                                   value="{{ old('price', $product->price) }}" required>
                                        </div>
                                    </div>

                                    {{-- كمية المخزون (Stock) --}}
                                    <div class="col-md-6 mb-4">
                                        <label class="form-label fw-bold text-success">كمية المخزون المتوفرة <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-success text-white"><i class="fas fa-cubes"></i></span>
                                            <input type="number" name="stock" class="form-control @error('stock') is-invalid @enderror"
                                                   value="{{ old('stock', $product->stock) }}" placeholder="مثال: 50" required min="0">
                                        </div>
                                        <small class="text-muted">عند وصولها لـ 0، سيظهر المنتج كـ "نافد".</small>
                                    </div>
                                </div>

                                {{-- القسم الأساسي المرجعي --}}
                                <div class="mb-4">
                                    <label class="form-label fw-bold text-primary">القسم المرجعي (الأساسي) <span class="text-danger">*</span></label>
                                    <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                        @foreach($categories as $parent)
                                            <option value="{{ $parent->id }}" {{ old('category_id', $product->category_id) == $parent->id ? 'selected' : '' }} class="fw-bold bg-light">
                                                {{ $parent->name }} (رئيسي)
                                            </option>
                                            @foreach($parent->children as $child)
                                                <option value="{{ $child->id }}" {{ old('category_id', $product->category_id) == $child->id ? 'selected' : '' }}>
                                                    &nbsp;&nbsp;&nbsp;&nbsp; ↳ {{ $child->name }}
                                                </option>
                                            @endforeach
                                        @endforeach
                                    </select>
                                </div>

                                {{-- الأحجام --}}
                                <div class="mb-4">
                                    <label class="form-label fw-bold d-block">الأحجام المتوفرة</label>
                                    <div class="p-3 border rounded bg-light d-flex flex-wrap gap-4">
                                        @foreach(['S', 'M', 'L', 'XL', 'XXL'] as $size)
                                            <div class="form-check m-0">
                                                <input class="form-check-input" type="checkbox" name="sizes[]"
                                                       value="{{ $size }}" id="size{{ $size }}"
                                                       {{ (is_array($product->sizes) && in_array($size, $product->sizes)) ? 'checked' : '' }}>
                                                <label class="form-check-label fw-bold" for="size{{ $size }}">{{ $size }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            {{-- الصورة الحالية والمعاينة --}}
                            <div class="col-md-4 mb-4 text-center border-start">
                                <label class="form-label fw-bold d-block">صورة المنتج</label>
                                <div class="image-preview-container shadow-sm rounded p-2 border bg-white mb-3">
                                    <img src="{{ asset('storage/' . $product->image) }}" id="preview-img"
                                         class="img-fluid rounded" style="max-height: 200px; object-fit: contain;">
                                </div>
                                <input type="file" name="image" id="image-input" class="form-control shadow-sm" accept="image/*">
                                <small class="text-secondary mt-2 d-block small italic">اتركه فارغاً للحفاظ على الصورة القديمة.</small>
                            </div>
                        </div>

                        <hr class="my-4">

                        {{-- الوصف وسياسة الإرجاع --}}
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold">وصف المنتج</label>
                                <textarea name="description" class="form-control" rows="4" required>{{ old('description', $product->description) }}</textarea>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold">سياسة الإرجاع</label>
                                <textarea name="return_policy" class="form-control" rows="4">{{ old('return_policy', $product->return_policy) }}</textarea>
                            </div>
                        </div>

                        {{-- أزرار التحكم --}}
                        <div class="d-flex justify-content-end gap-2 mt-4 border-top pt-4">
                            <a href="{{ route('admin.products.index') }}" class="btn btn-light border px-4">إلغاء</a>
                            <button type="submit" class="btn btn-primary px-5 shadow">
                                <i class="fas fa-save ms-2"></i> حفظ كافة التغييرات
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('image-input').onchange = evt => {
        const [file] = evt.target.files;
        if (file) {
            document.getElementById('preview-img').src = URL.createObjectURL(file);
        }
    }
</script>
@endpush
@endsection
