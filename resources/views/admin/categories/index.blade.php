@extends('layouts.app')

@section('content')
<div class="container-fluid py-4" dir="rtl">
    {{-- رأس الصفحة --}}
    <div class="d-flex justify-content-between align-items-center mb-4 text-right">
        <h3 class="fw-bold text-dark">
            <i class="fas fa-sitemap ms-2 text-primary"></i> إدارة التصنيفات (أساسي وفرعي)
        </h3>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary shadow-sm px-4">
            <i class="fas fa-plus ms-1"></i> إضافة تصنيف جديد
        </a>
    </div>

    {{-- رسائل التنبيه --}}
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm alert-dismissible fade show text-right" role="alert">
            <i class="fas fa-check-circle ms-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" style="float: left;"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm alert-dismissible fade show text-right" role="alert">
            <i class="fas fa-exclamation-triangle ms-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" style="float: left;"></button>
        </div>
    @endif

    <div class="card shadow border-0 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle text-center mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th style="width: 8%">ID</th>
                            <th style="width: 22%" class="text-right px-4">القسم الأساسي</th>
                            <th style="width: 40%">الأقسام الفرعية التابعة</th>
                            <th style="width: 15%">تاريخ الإنشاء</th>
                            <th style="width: 15%">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                        <tr>
                            <td class="text-muted fw-bold">#{{ $category->id }}</td>
                            <td class="text-right px-4">
                                <div class="fw-bold text-primary fs-5">{{ $category->name }}</div>
                                <small class="badge bg-secondary-subtle text-secondary border">
                                    {{ $category->products_count ?? 0 }} منتج إجمالي
                                </small>
                            </td>
                            <td>
                                <div class="d-flex flex-wrap justify-content-center gap-2">
                                    {{-- استخدام العلاقة children بدلاً من subCategories --}}
                                    @forelse($category->children as $sub)
                                        <div class="btn-group border rounded-pill bg-light overflow-hidden p-0 mb-1 shadow-sm">
                                            {{-- زر تعديل القسم الفرعي --}}
                                            <button class="btn btn-sm btn-light border-end px-2" data-bs-toggle="modal" data-bs-target="#editSubCategoryModal{{ $sub->id }}">
                                                <i class="fas fa-pencil-alt text-warning small"></i>
                                            </button>

                                            <span class="badge text-dark px-2 py-2 border-0">
                                                {{ $sub->name }}
                                            </span>

                                            {{-- فورم حذف القسم الفرعي المدمج --}}
                                            <form action="{{ route('admin.categories.destroy', $sub->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-light text-danger border-start px-2 py-1"
                                                        onclick="return confirm('هل أنت متأكد من حذف القسم الفرعي ({{ $sub->name }})؟')">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        </div>
                                    @empty
                                        <span class="text-muted small italic">لا توجد أقسام فرعية</span>
                                    @endforelse
                                </div>
                            </td>
                            <td>
                                <div class="text-secondary small">{{ $category->created_at->format('Y-m-d') }}</div>
                            </td>
                            <td>
                                <div class="btn-group gap-2">
                                    {{-- زر تعديل القسم الأساسي --}}
                                    <button type="button" class="btn btn-sm btn-outline-warning rounded-2" data-bs-toggle="modal" data-bs-target="#editCategoryModal{{ $category->id }}">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    {{-- فورم حذف القسم الأساسي المدمج --}}
                                    <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-2"
                                                onclick="return confirm('هل أنت متأكد؟ سيتم حذف القسم ({{ $category->name }}) والأقسام الفرعية التابعة له إذا كانت فارغة من المنتجات.')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-muted py-5 bg-light">
                                <i class="fas fa-folder-open fa-3x mb-3 d-block opacity-25"></i>
                                <span class="fs-5">لا توجد تصنيفات مضافة حالياً.</span>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- مودالات التعديل --}}
@foreach($categories as $category)
    {{-- مودال القسم الأساسي --}}
    <div class="modal fade" id="editCategoryModal{{ $category->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title fw-bold">تعديل القسم الأساسي: {{ $category->name }}</h5>
                    <button type="button" class="btn-close ms-0 me-auto" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body text-right">
                        <div class="mb-3">
                            <label class="form-label fw-bold">اسم القسم</label>
                            <input type="text" name="name" class="form-control" value="{{ $category->name }}" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-warning fw-bold">حفظ التغييرات</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @foreach($category->children as $sub)
    {{-- مودال القسم الفرعي --}}
    <div class="modal fade" id="editSubCategoryModal{{ $sub->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-info">
                <div class="modal-header bg-info text-white">
                    <h6 class="modal-title">تعديل القسم الفرعي</h6>
                    <button type="button" class="btn-close ms-0 me-auto" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.categories.update', $sub->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body text-right">
                        <label class="small fw-bold mb-1">الاسم الجديد:</label>
                        <input type="text" name="name" class="form-control form-control-sm" value="{{ $sub->name }}" required>
                    </div>
                    <div class="modal-footer p-1">
                        <button type="submit" class="btn btn-info btn-sm text-white w-100 fw-bold">تحديث</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach
@endforeach
@endsection
