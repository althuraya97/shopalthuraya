@extends('layouts.app')

@section('content')
<div class="container-fluid py-4" dir="rtl text-right">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm border-0">
                {{-- رأس البطاقة --}}
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-plus-circle ms-2"></i> إضافة منتج جديد إلى المخزن
                    </h5>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-light btn-sm shadow-sm">
                        <i class="fas fa-arrow-right"></i> عودة للقائمة
                    </a>
                </div>

                <div class="card-body p-4 text-right">
                    {{-- عرض رسائل الخطأ --}}
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
                                {{-- الصف الأول: رقم المنتج والاسم --}}
                                <div class="row">
                                    <div class="col-md-4 mb-4">
                                        <label class="form-label fw-bold text-secondary">رقم المنتج (ID)</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light"><i class="fas fa-fingerprint"></i></span>
                                            <input type="text" class="form-control bg-light" value="توليد تلقائي" readonly>
                                        </div>
                                        <small class="text-muted">سيتم تحديد الرقم بعد الحفظ.</small>
                                    </div>

                                    <div class="col-md-8 mb-4">
                                        <label class="form-label fw-bold">اسم المنتج <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                               value="{{ old('name') }}" placeholder="أدخل اسم المنتج بالكامل" required>
                                    </div>
                                </div>

                                {{-- الصف الثاني: السعر والمخزون --}}
                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <label class="form-label fw-bold">السعر <span class="text-danger">*</span></label>
                                        <div class="input-group" dir="ltr">
                                            <span class="input-group-text bg-light text-dark fw-bold">USD</span>
                                            <input type="number" step="0.01" name="price" class="form-control @error('price') is-invalid @enderror"
                                                   value="{{ old('price') }}" placeholder="0.00" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-4">
                                        <label class="form-label fw-bold text-success">كمية المخزون (الكمية المتوفرة) <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-success text-white"><i class="fas fa-cubes"></i></span>
                                            <input type="number" name="stock" class="form-control @error('stock') is-invalid @enderror"
                                                   value="{{ old('stock', 0) }}" placeholder="مثال: 50" required min="0">
                                        </div>
                                        <small class="text-muted">أدخل الكمية المتاحة حالياً للبيع.</small>
                                    </div>
                                </div>

                                {{-- القسم الأساسي --}}
                                <div class="mb-4">
                                    <label class="form-label fw-bold text-primary">القسم المرجعي (الأساسي) <span class="text-danger">*</span></label>
                                    <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                        <option value="">-- اختر القسم الأساسي --</option>
                                        @foreach($categories as $parent)
                                            <option value="{{ $parent->id }}" {{ old('category_id') == $parent->id ? 'selected' : '' }} class="fw-bold bg-light">
                                                {{ $parent->name }} (رئيسي)
                                            </option>
                                            @foreach($parent->children as $child)
                                                <option value="{{ $child->id }}" {{ old('category_id') == $child->id ? 'selected' : '' }}>
                                                    &nbsp;&nbsp;&nbsp;&nbsp; ↳ {{ $child->name }}
                                                </option>
                                            @endforeach
                                        @endforeach
                                    </select>
                                </div>

                                {{-- التصنيفات الإضافية --}}
                                <div class="mb-4">
                                    <label class="form-label fw-bold">وسوم المنتج (تصنيفات فرعية إضافية)</label>
                                    <select name="sub_categories[]" class="form-select @error('sub_categories') is-invalid @enderror" multiple style="height: 120px;">
                                        @foreach($subCategories as $sub)
                                            <option value="{{ $sub->id }}" {{ (is_array(old('sub_categories')) && in_array($sub->id, old('sub_categories'))) ? 'selected' : '' }}>
                                                {{ $sub->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">اضغط CTRL للاختيار المتعدد.</small>
                                </div>
                            </div>

                            {{-- رفع الصورة --}}
                            <div class="col-md-4 mb-4 text-center border-start">
                                <label class="form-label fw-bold d-block">صورة المنتج <span class="text-danger">*</span></label>
                                <div class="image-preview-container shadow-sm rounded p-2 border bg-white mb-3">
                                    <img src="{{ asset('assets/images/placeholder-product.png') }}" id="preview-img"
                                         class="img-fluid rounded" style="max-height: 250px; object-fit: contain;">
                                </div>
                                <input type="file" name="image" id="image-input" class="form-control @error('image') is-invalid @enderror" accept="image/*" required>
                            </div>
                        </div>

                        <hr class="my-4">

                        {{-- الأحجام والوصف --}}
                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <label class="form-label fw-bold">الأحجام المتوفرة</label>
                                <div class="p-3 border rounded bg-light d-flex flex-wrap gap-4">
                                    @foreach(['S', 'M', 'L', 'XL', 'XXL'] as $size)
                                        <div class="form-check m-0">
                                            <input class="form-check-input" type="checkbox" name="sizes[]" value="{{ $size }}" id="size{{ $size }}" {{ (is_array(old('sizes')) && in_array($size, old('sizes'))) ? 'checked' : '' }}>
                                            <label class="form-check-label fw-bold" for="size{{ $size }}">{{ $size }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold">وصف المنتج <span class="text-danger">*</span></label>
                                <textarea name="description" class="form-control" rows="5" placeholder="أدخل تفاصيل المنتج..." required>{{ old('description') }}</textarea>
                            </div>

                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold">سياسة الإرجاع</label>
                                <textarea name="return_policy" class="form-control" rows="5" placeholder="مثال: يمكن الإرجاع خلال 14 يوم من تاريخ الاستلام...">{{ old('return_policy') }}</textarea>
                            </div>
                        </div>

                        {{-- الأزرار --}}
                        <div class="d-flex justify-content-end gap-3 mt-4 border-top pt-4">
                            <a href="{{ route('admin.products.index') }}" class="btn btn-light border px-4">إلغاء</a>
                            <button type="submit" class="btn btn-success px-5 shadow fw-bold">
                                <i class="fas fa-save ms-2"></i> تأفيذ الإضافة والحفظ
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
