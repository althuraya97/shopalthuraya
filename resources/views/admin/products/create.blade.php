@extends('layouts.app')

@section('content')
<div class="container-fluid py-4" dir="rtl text-right">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-box-open me-2"></i> إضافة منتج جديد
                    </h5>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-light btn-sm shadow-sm">
                        <i class="fas fa-arrow-right"></i> عودة للقائمة
                    </a>
                </div>

                <div class="card-body p-4 text-right">
                    {{-- عرض رسائل الخطأ العامة --}}
                    @if($errors->any())
                        <div class="alert alert-danger border-0 shadow-sm mb-4">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-md-8">
                                <div class="row">
                                    {{-- اسم المنتج --}}
                                    <div class="col-md-6 mb-4">
                                        <label class="form-label fw-bold">اسم المنتج <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="أدخل اسم المنتج" required>
                                    </div>

                                    {{-- السعر --}}
                                    <div class="col-md-6 mb-4">
                                        <label class="form-label fw-bold">السعر <span class="text-danger">*</span></label>
                                        <div class="input-group" dir="ltr">
                                            <span class="input-group-text">USD</span>
                                            <input type="number" step="0.01" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price') }}" placeholder="0.00" required>
                                        </div>
                                    </div>
                                </div>

                                {{-- اختيار القسم الأساسي (العمود الجديد category_id) --}}
                                <div class="mb-4 text-right">
                                    <label class="form-label fw-bold text-primary">القسم المرجعي (أساسي) <span class="text-danger">*</span></label>
                                    <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                        <option value="">-- اختر القسم الذي سينتمي إليه المنتج --</option>
                                        @foreach($categories as $parent)
                                            <option value="{{ $parent->id }}" {{ old('category_id') == $parent->id ? 'selected' : '' }} class="fw-bold">
                                                {{ $parent->name }} (رئيسي)
                                            </option>
                                            @foreach($parent->children as $child)
                                                <option value="{{ $child->id }}" {{ old('category_id') == $child->id ? 'selected' : '' }}>
                                                    &nbsp;&nbsp;&nbsp;&nbsp; ↳ {{ $child->name }}
                                                </option>
                                            @endforeach
                                        @endforeach
                                    </select>
                                    <small class="text-muted">هذا هو القسم الذي سيظهر المنتج بداخله عند التصفح.</small>
                                </div>

                                {{-- اختيار الوسوم/التصنيفات الفرعية المرتبطة (Many-to-Many) --}}
                                <div class="mb-4 text-right">
                                    <label class="form-label fw-bold">التصنيفات الإضافية (اختياري)</label>
                                    <select name="sub_categories[]" class="form-select @error('sub_categories') is-invalid @enderror" multiple style="height: 100px;">
                                        @foreach($subCategories as $sub)
                                            <option value="{{ $sub->id }}" {{ (is_array(old('sub_categories')) && in_array($sub->id, old('sub_categories'))) ? 'selected' : '' }}>
                                                {{ $sub->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">يمكنك اختيار أكثر من تصنيف فرعي لربط المنتج به.</small>
                                </div>
                            </div>

                            {{-- رفع الصورة مع المعاينة --}}
                            <div class="col-md-4 mb-4 text-center border-start">
                                <label class="form-label fw-bold d-block">صورة المنتج <span class="text-danger">*</span></label>
                                <div class="image-preview-container shadow-sm rounded p-2 border bg-light mb-3">
                                    <img src="{{ asset('assets/images/placeholder-product.png') }}" id="preview-img" class="img-fluid rounded" style="max-height: 200px; object-fit: contain;">
                                </div>
                                <input type="file" name="image" id="image-input" class="form-control @error('image') is-invalid @enderror" accept="image/*" required>
                                @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        {{-- الأحجام والوصف --}}
                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <div class="p-3 border rounded bg-light">
                                    <label class="form-label fw-bold d-block mb-3">الأحجام المتوفرة</label>
                                    <div class="d-flex flex-wrap gap-4 justify-content-start">
                                        @foreach(['S', 'M', 'L', 'XL', 'XXL'] as $size)
                                            <div class="form-check m-0">
                                                <input class="form-check-input" type="checkbox" name="sizes[]" value="{{ $size }}" id="size{{ $size }}" {{ (is_array(old('sizes')) && in_array($size, old('sizes'))) ? 'checked' : '' }}>
                                                <label class="form-check-label fw-bold" for="size{{ $size }}">{{ $size }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold">وصف المنتج <span class="text-danger">*</span></label>
                                <textarea name="description" class="form-control" rows="4" required>{{ old('description') }}</textarea>
                            </div>

                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold">سياسة الإرجاع</label>
                                <textarea name="return_policy" class="form-control" rows="4">{{ old('return_policy') }}</textarea>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4 border-top pt-4">
                            <a href="{{ route('admin.products.index') }}" class="btn btn-light border px-4">إلغاء</a>
                            <button type="submit" class="btn btn-success px-5 shadow">
                                <i class="fas fa-save me-2"></i> حفظ المنتج
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
    // سكريبت معاينة الصورة فور اختيارها
    document.getElementById('image-input').onchange = evt => {
        const [file] = evt.target.files;
        if (file) {
            document.getElementById('preview-img').src = URL.createObjectURL(file);
        }
    }
</script>
@endpush

@endsection
