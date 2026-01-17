@extends('layouts.frontend')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">تفاصيل الطلب #{{ $order->id }}</h2>
        <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary rounded-pill">
            <i class="fas fa-arrow-right"></i> العودة لقائمة الطلبات
        </a>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">المنتج</th>
                                <th>السعر</th>
                                <th>الكمية</th>
                                <th>الإجمالي</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <img src="{{ asset('storage/' . $item->product->image) }}"
                                             class="rounded-3 me-3" width="70" height="70" style="object-fit: cover;">
                                        <div>
                                            <h6 class="mb-0 fw-bold">{{ $item->product->name }}</h6>
                                            <small class="text-muted">المقاس: {{ $item->size ?? 'N/A' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ number_format($item->price, 2) }} ر.ع</td>
                                <td>{{ $item->quantity }}</td>
                                <td class="fw-bold">{{ number_format($item->price * $item->quantity, 2) }} ر.ع</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer bg-white p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fs-5 fw-bold">إجمالي الطلب:</span>
                        <span class="fs-4 fw-bold text-primary">{{ number_format($order->total_price, 2) }} ر.ع</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4 text-center">
                    <h6 class="text-muted mb-2 text-uppercase small fw-bold">حالة الطلب الحالية</h6>
                    <span class="badge rounded-pill px-4 py-2
                        @if($order->status == 'قيد المعالجة') bg-warning text-dark
                        @elseif($order->status == 'shipped') bg-info
                        @elseif($order->status == 'delivered') bg-success
                        @else bg-secondary @endif">
                        {{ $order->status }}
                    </span>
                    <hr>
                    <p class="small text-muted mb-0">تاريخ الطلب: {{ $order->created_at->format('Y-m-d H:i') }}</p>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3"><i class="fas fa-truck me-2 text-primary"></i> معلومات الشحن</h5>
                    <div class="mb-2">
                        <small class="text-muted d-block">العنوان:</small>
                        <span class="fw-medium">{{ $order->address }}</span>
                        @if($order->apartment) <span class="d-block">شقة: {{ $order->apartment }}</span> @endif
                    </div>
                    <div class="mb-2">
                        <small class="text-muted d-block">المدينة والبلد:</small>
                        <span class="fw-medium">{{ $order->city }}, {{ $order->country }}</span>
                    </div>
                    <div>
                        <small class="text-muted d-block">طريقة الدفع:</small>
                        <span class="badge bg-light text-dark border">{{ $order->payment_method }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
