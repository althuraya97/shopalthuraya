@extends('layouts.app')

@section('content')
<div class="container-fluid py-4" dir="rtl">

    <div class="row mb-4 text-right">
        <div class="col-md-4 mb-3">
            <div class="card border-0 bg-success text-white shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-2 opacity-75">إجمالي المبيعات (المستلمة)</h6>
                            <h3 class="mb-0 fw-bold">{{ number_format(\App\Models\Order::where('status', 'delivered')->sum('total_price'), 2) }} ر.س</h3>
                        </div>
                        <i class="fas fa-money-bill-wave fa-3x opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card border-0 bg-warning text-dark shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-2 opacity-75">طلبات بانتظار التجهيز</h6>
                            <h3 class="mb-0 fw-bold">{{ \App\Models\Order::whereIn('status', ['pending', 'processing', 'قيد المعالجة'])->count() }}</h3>
                        </div>
                        <i class="fas fa-clock fa-3x opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card border-0 bg-info text-white shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-2 opacity-75">إجمالي المنتجات المتاحة</h6>
                            <h3 class="mb-0 fw-bold">{{ \App\Models\Product::count() }}</h3>
                        </div>
                        <i class="fas fa-boxes fa-3x opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body bg-light rounded">
            <form action="{{ route('admin.dashboard') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-bold small text-muted">بحث برقم الطلب</label>
                    <input type="text" name="search_id" class="form-control border-0 shadow-sm" placeholder="أدخل ID الطلب..." value="{{ request('search_id') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold small text-muted">تصفية حسب الحالة</label>
                    <select name="status" class="form-select border-0 shadow-sm">
                        <option value="">كل الحالات</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                        <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>قيد المعالجة</option>
                        <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>تم الشحن</option>
                        <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>تم التوصيل</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>ملغي</option>
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary px-4 shadow-sm">
                        <i class="fas fa-search me-1"></i> بحث
                    </button>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-white border px-4 shadow-sm">إعادة تعيين</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold text-primary"><i class="fas fa-shopping-basket me-2"></i> إدارة طلبات الزبائن</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle text-center mb-0">
                    <thead class="table-light text-muted small">
                        <tr>
                            <th class="py-3">ID</th>
                            <th>الزبون</th>
                            <th>تاريخ الطلب</th>
                            <th>الكمية</th>
                            <th>التكلفة</th>
                            <th>الحالة</th>
                            <th>تحديث الحالة</th>
                            <th>إجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <td class="fw-bold text-dark">#{{ $order->id }}</td>
                            <td class="text-right px-4">
                                <div class="d-flex align-items-center justify-content-center">
                                    <div class="ms-2">
                                        <div class="fw-bold">{{ $order->user->first_name ?? 'مستخدم' }} {{ $order->user->last_name ?? '' }}</div>
                                        <small class="text-muted">{{ $order->user->email ?? '' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="small">{{ $order->created_at->format('Y/m/d H:i') }}</td>
                            <td><span class="badge bg-light text-dark border">{{ $order->items->sum('quantity') }} قطع</span></td>
                            <td class="fw-bold text-primary">{{ number_format($order->total_price, 2) }} ر.س</td>
                            <td>
                                @php
                                    $statusClass = match($order->status) {
                                        'pending', 'قيد المعالجة', 'processing' => 'bg-warning text-dark',
                                        'shipped' => 'bg-info text-white',
                                        'delivered' => 'bg-success text-white',
                                        'cancelled' => 'bg-danger text-white',
                                        default => 'bg-secondary text-white',
                                    };
                                @endphp
                                <span class="badge {{ $statusClass }} rounded-pill px-3">{{ $order->status }}</span>
                            </td>
                            <td>
                                <form action="{{ route('admin.orders.status.update', $order->id) }}" method="POST" class="d-flex gap-1 justify-content-center">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" class="form-select form-select-sm shadow-sm" style="width: auto;">
                                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>انتظار</option>
                                        <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>شحن</option>
                                        <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>توصيل</option>
                                        <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>إلغاء</option>
                                    </select>
                                    <button type="submit" class="btn btn-sm btn-dark shadow-sm"><i class="fas fa-check"></i></button>
                                </form>
                            </td>
                            <td>
                                <div class="btn-group shadow-sm">
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">عرض</a>
                                    <a href="{{ route('admin.orders.invoice', $order->id) }}" target="_blank" class="btn btn-sm btn-outline-dark">فاتورة</a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="py-5 text-muted">لا توجد طلبات لعرضها حالياً.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white d-flex justify-content-center border-0 py-3">
            {{ $orders->appends(request()->input())->links() }}
        </div>
    </div>
</div>
@endsection
