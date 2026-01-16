@extends('layouts.app')

@section('content')
<div class="container-fluid py-4" dir="rtl text-right">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow border-0">
                <div class="card-header bg-white">
                    <h5 class="fw-bold mb-0">إضافة تصنيف جديد (رئيسي أو فرعي)</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.categories.store') }}" method="POST">
                        @csrf

                        <div class="mb-3 text-right">
                            <label class="form-label fw-bold">اسم التصنيف</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4 text-right">
                            <label class="form-label fw-bold">يتبع لتصنيف (اختياري)</label>
                            <select name="parent_id" class="form-select">
                                <option value="">-- تصنيف رئيسي (بدون أب) --</option>
                                @foreach($parentCategories as $parent)
                                    <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted">اختر تصنيفاً إذا كنت تريد جعل هذا التصنيف "فرعياً".</small>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success px-5">حفظ البيانات</button>
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-light border">رجوع</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
