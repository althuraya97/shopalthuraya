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

                    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-8">
                                <div class="row">
                                    {{-- اسم المنتج --}}
                                    <div class="col-md-6 mb-4">
                                        <label class="form-label fw-bold">اسم المنتج <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                               value="{{ old('name', $product->name) }}" required>
                                    </div>

                                    {{-- السعر --}}
                                    <div class="col-md-6 mb-4">
                                        <label class="form-label fw-bold">السعر الحالي</label>
                                        <div class="input-group" dir="ltr">
                                            <span class="input-group-text bg-light">USD</span>
                                            <input type="number" step="0.01" name="price" class="form-control"
                                                   value="{{ old('price', $product->price) }}" required>
                                        </div>
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
                                    <small class="text-muted">هذا هو القسم الرئيسي الذي يظهر فيه المنتج للمتسوقين.</small>
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

                        {{-- قسم إدارة التصنيفات الإضافية --}}
                        <div class="card shadow-none border mb-4">
                            <div class="card-header bg-dark text-white d-flex align-items-center py-2">
                                <i class="fas fa-tags ms-2"></i> <h6 class="mb-0">التصنيفات الإضافية (وسوم المنتج)</h6>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive" style="max-height: 250px;">
                                    <table class="table table-hover align-middle text-center mb-0">
                                        <thead class="table-light sticky-top">
                                            <tr>
                                                <th class="text-right pr-4">اسم التصنيف</th>
                                                <th>الحالة</th>
                                                <th>الإجراء</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($subCategories as $subCat)
                                                @php $isAttached = $product->subCategories->contains($subCat->id); @endphp
                                                <tr>
                                                    <td class="text-right pr-4 fw-semibold">{{ $subCat->name }}</td>
                                                    <td>
                                                        @if($isAttached)
                                                            <span class="badge rounded-pill bg-success-subtle text-success border border-success px-3">مرتبط</span>
                                                        @else
                                                            <span class="badge rounded-pill bg-secondary-subtle text-secondary border px-3">غير مرتبط</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <form action="{{ route('admin.products.toggleCategory', $product->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <input type="hidden" name="sub_category_id" value="{{ $subCat->id }}">
                                                            <input type="hidden" name="action" value="{{ $isAttached ? 'detach' : 'attach' }}">
                                                            <button type="submit" class="btn btn-sm {{ $isAttached ? 'btn-outline-danger' : 'btn-outline-success' }} px-3 rounded-pill">
                                                                <i class="fas {{ $isAttached ? 'fa-minus-circle' : 'fa-plus-circle' }} ms-1"></i>
                                                                {{ $isAttached ? 'إزالة' : 'ربط' }}
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

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
                                <i class="fas fa-save ms-2"></i> حفظ التغييرات
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
    // سكريبت معاينة الصورة فور تغييرها
    document.getElementById('image-input').onchange = evt => {
        const [file] = evt.target.files;
        if (file) {
            document.getElementById('preview-img').src = URL.createObjectURL(file);
        }
    }
</script>
@endpush
@endsection
