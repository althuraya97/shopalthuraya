@extends('layouts.app')

@section('content')
{{-- تصحيح خاصية dir لتصبح rtl فقط، واستخدام كلاس text-end للمحاذاة --}}
<div class="container-fluid py-4" dir="rtl">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0 text-primary">
                        <i class="fas fa-plus-circle ms-2"></i> إضافة تصنيف جديد لمتجر مباهج الخد
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.categories.store') }}" method="POST">
                        @csrf

                        <div class="mb-3 text-start"> {{-- text-start في RTL تعني اليمين --}}
                            <label class="form-label fw-bold">اسم التصنيف</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}" placeholder="مثلاً: بخور، عطور، أدوات تجميل..." required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4 text-start">
                            <label class="form-label fw-bold">يتبع لتصنيف (اختياري)</label>
                            <select name="parent_id" class="form-select @error('parent_id') is-invalid @enderror">
                                <option value="">-- تصنيف رئيسي (بدون أب) --</option>
                                @foreach($parentCategories as $parent)
                                    <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
                                        {{ $parent->name }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">اختر تصنيفاً إذا كنت تريد جعل هذا التصنيف "فرعياً".</small>
                            @error('parent_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success px-5 shadow-sm">
                                <i class="fas fa-save ms-1"></i> حفظ البيانات
                            </button>
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-light border px-4">إلغاء</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
