@extends('layouts.app')

@section('content')
<div class="container-fluid py-4" dir="rtl text-right">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold text-primary">
                        {{ isset($category) ? 'تعديل التصنيف: ' . $category->name : 'إضافة تصنيف جديد' }}
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ isset($category) ? route('admin.categories.update', $category->id) : route('admin.categories.store') }}" method="POST">
                        @csrf
                        @if(isset($category)) @method('PUT') @endif

                        <div class="mb-4">
                            <label for="name" class="form-label fw-bold">اسم التصنيف باللغة العربية</label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $category->name ?? '') }}" placeholder="مثلاً: ملابس رجالية، أحذية رياضية..." required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save ml-1"></i> {{ isset($category) ? 'تحديث البيانات' : 'حفظ التصنيف' }}
                            </button>
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-light border">إلغاء</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
