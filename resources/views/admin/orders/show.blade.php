@extends('layouts.app')

@section('content')
<div class="container py-5 text-right" dir="rtl">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold">تفاصيل الطلب <span class="text-primary">#{{ $order->id }}</span></h2>
            <p class="text-muted small">تاريخ الطلب: {{ $order->created_at->format('Y-m-d H:i') }}</p>
        </div>

        <div class="d-flex gap-2 align-items-center">
            <a href="{{ route('admin.orders.invoice', $order->id) }}" target="_blank" class="btn btn-dark shadow-sm">
                <i class="fa fa-print"></i> طباعة الفاتورة
            </a>

            <form action="{{ route('admin.orders.status.update', $order->id) }}" method="POST" class="d-flex align-items-center border p-2 rounded bg-white shadow-sm">
                @csrf
                @method('PATCH')
                <label class="ms-2 mb-0 fw-bold small">حالة الطلب:</label>
                <select name="status" class="form-select form-select-sm mx-2" style="width: auto;">
                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                    <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>جاري التجهيز</option>
                    <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>تم الشحن</option>
                    <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>تم التوصيل</option>
                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>ملغي</option>
                </select>
                <button type="submit" class="btn btn-primary btn-sm px-3">تحديث</button>
            </form>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-4 mb-4 rounded-4">
                <h5 class="fw-bold border-bottom pb-2 mb-3"><i class="fas fa-user-circle me-1 text-primary"></i> بيانات الزبون</h5>
                <p class="mb-1"><strong>الاسم:</strong> {{ $order->user->first_name }} {{ $order->user->last_name }}</p>
                <p class="mb-1"><strong>الإيميل:</strong> {{ $order->user->email }}</p>
            </div>

            <div class="card border-0 shadow-sm p-4 rounded-4">
                <h5 class="fw-bold border-bottom pb-2 mb-3"><i class="fas fa-truck me-1 text-primary"></i> عنوان الشحن</h5>
                <p class="mb-1"><strong>البلد:</strong> {{ $order->country }}</p>
                <p class="mb-1"><strong>المدينة:</strong> {{ $order->city }}</p>
                <p class="mb-1"><strong>الشارع/المنزل:</strong> {{ $order->address }}</p>
                @if($order->apartment)
                    <p class="mb-1"><strong>الشقة/الدور:</strong> {{ $order->apartment }}</p>
                @endif
                <p class="mb-1"><strong>الرمز البريدي:</strong> {{ $order->zip_code }}</p>
                <p class="mb-0 mt-3 small text-muted"><strong>طريقة الدفع:</strong> {{ $order->payment_method }}</p>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-shopping-cart me-1 text-primary"></i> المنتجات المطلوبة</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">المنتج</th>
                                    <th>السعر الوحدة</th>
                                    <th>الكمية</th>
                                    <th class="text-center">الإجمالي</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ asset('storage/' . $item->product->image) }}" class="rounded me-3" width="50" height="50" style="object-fit: cover;">
                                            <div>
                                                <div class="fw-bold">{{ $item->product->name }}</div>
                                                <small class="text-muted">المقاس: {{ $item->size ?? 'غير محدد' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ number_format($item->price, 2) }} ر.ع</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td class="text-center fw-bold">{{ number_format($item->price * $item->quantity, 2) }} ر.ع</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="3" class="text-end fw-bold py-3 fs-5">إجمالي الطلب:</td>
                                    <td class="text-center fw-bold text-success py-3 fs-5">{{ number_format($order->total_price, 2) }} ر.ع</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
