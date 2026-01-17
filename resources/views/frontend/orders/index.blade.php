@extends('layouts.frontend')

@section('content')
<div class="container py-5" dir="rtl">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold"><i class="fas fa-box ms-2 text-primary"></i> طلباتي السابقة</h3>
                <a href="{{ route('shop.index') }}" class="btn btn-outline-primary rounded-pill px-4">
                    العودة للتسوق
                </a>
            </div>

            @forelse($orders as $order)
                <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden transition-hover">
                    <div class="card-header bg-white border-bottom py-3">
                        <div class="row align-items-center">
                            <div class="col-md-3">
                                <span class="text-muted small">رقم الطلب:</span>
                                <div class="fw-bold">#{{ $order->id }}</div>
                            </div>
                            <div class="col-md-3">
                                <span class="text-muted small">تاريخ الطلب:</span>
                                <div>{{ $order->created_at->format('Y-m-d') }}</div>
                            </div>
                            <div class="col-md-3">
                                <span class="text-muted small">إجمالي المبلغ:</span>
                                <div class="text-primary fw-bold">{{ number_format($order->total_amount, 2) }} ر.ع</div>
                            </div>
                            <div class="col-md-3 text-md-start">
                                @php
                                    $statusClasses = [
                                        'pending' => 'bg-warning text-dark',
                                        'completed' => 'bg-success',
                                        'cancelled' => 'bg-danger',
                                        'processing' => 'bg-info text-white'
                                    ];
                                    $statusLabels = [
                                        'pending' => 'قيد الانتظار',
                                        'completed' => 'مكتمل',
                                        'cancelled' => 'ملغي',
                                        'processing' => 'قيد التنفيذ'
                                    ];
                                @endphp
                                <span class="badge {{ $statusClasses[$order->status] ?? 'bg-secondary' }} rounded-pill px-3 py-2">
                                    {{ $statusLabels[$order->status] ?? $order->status }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 align-middle">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="border-0 px-4 py-3 small">المنتج</th>
                                        <th class="border-0 py-3 small text-center">الكمية</th>
                                        <th class="border-0 py-3 small text-center">السعر</th>
                                        <th class="border-0 py-3 small text-center">المقاس</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->items as $item)
                                    <tr>
                                        <td class="px-4 border-0">
                                            <div class="d-flex align-items-center">
                                                <img src="{{ asset('storage/' . $item->product->image) }}"
                                                     class="rounded-3 shadow-sm me-3"
                                                     style="width: 50px; height: 50px; object-fit: cover;">
                                                <span class="fw-bold ms-2">{{ $item->product->name }}</span>
                                            </div>
                                        </td>
                                        <td class="text-center border-0">{{ $item->quantity }}</td>
                                        <td class="text-center border-0 fw-bold">{{ number_format($item->price, 2) }} ر.ع</td>
                                        <td class="text-center border-0 text-muted">{{ $item->size ?? 'N/A' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card-footer bg-light border-0 py-3 px-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="small text-muted">
                                <i class="fas fa-map-marker-alt ms-1"></i> عنوان التوصيل: {{ $order->address }}, {{ $order->city }}
                            </div>
                            <button class="btn btn-sm btn-link text-decoration-none">تفاصيل الفاتورة <i class="fas fa-chevron-left me-1"></i></button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-5 bg-white rounded-4 shadow-sm">
                    <img src="https://cdn-icons-png.flaticon.com/512/4076/4076432.png" style="width: 120px;" class="mb-4 opacity-50">
                    <h4 class="text-muted">ليس لديك أي طلبات حالياً</h4>
                    <p class="text-secondary mb-4">ابدأ التسوق الآن واكتشف أفضل العروض!</p>
                    <a href="{{ route('shop.index') }}" class="btn btn-primary btn-lg rounded-pill px-5 shadow">اذهب للمتجر</a>
                </div>
            @endforelse

            <div class="d-flex justify-content-center mt-4">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</div>

<style>
    .transition-hover {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .transition-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.05) !important;
    }
    .table th { font-weight: 600; text-transform: uppercase; }
</style>
@endsection
