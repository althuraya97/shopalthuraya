@extends('layouts.frontend')

@section('content')
<div class="container py-5" dir="rtl">
    <h2 class="fw-bold mb-4 text-right"><i class="fas fa-shopping-cart ms-2"></i> عربة التسوق</h2>

    @if(session('cart') && count(session('cart')) > 0)
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 p-3">
                    <div class="table-responsive">
                        <table class="table align-middle text-right">
                            <thead>
                                <tr class="text-muted small border-bottom">
                                    <th>المنتج</th>
                                    <th>المقاس</th>
                                    <th>الكمية</th>
                                    <th>السعر</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $total = 0 @endphp
                                @foreach(session('cart') as $id => $details)
                                    @php $total += $details['price'] * $details['quantity'] @endphp
                                    <tr class="border-bottom">
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ asset('storage/' . $details['image']) }}"
                                                     class="rounded-3 ms-3"
                                                     style="width: 70px; height: 70px; object-fit: cover;">
                                                <div>
                                                    <h6 class="mb-0 fw-bold">{{ $details['name'] }}</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-light text-dark border">{{ $details['size'] ?? '-' }}</span></td>
                                        <td>
                                            <span class="fw-bold">{{ $details['quantity'] }}</span>
                                        </td>
                                        <td class="fw-bold text-primary">{{ number_format($details['price'] * $details['quantity'], 2) }} ر.س</td>
                                        <td>
                                            <form action="{{ route('cart.remove', $id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm text-danger border-0">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3 text-right">
                        <a href="{{ route('shop.index') }}" class="btn btn-link text-decoration-none text-secondary p-0">
                            <i class="fas fa-arrow-right ms-1"></i> العودة للتسوق
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-light">
                    <h5 class="fw-bold mb-4 border-bottom pb-2">ملخص الحساب</h5>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">المجموع الفرعي:</span>
                        <span class="fw-bold">{{ number_format($total, 2) }} ر.س</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3 text-success">
                        <span>الشحن:</span>
                        <span>مجاني</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-4 mt-2">
                        <span class="fs-5 fw-bold text-dark">الإجمالي:</span>
                        <span class="fs-4 fw-bold text-primary">{{ number_format($total, 2) }} ر.س</span>
                    </div>

                    @auth
                        <a href="{{ route('orders.store') }}"
                           onclick="event.preventDefault(); document.getElementById('checkout-form').submit();"
                           class="btn btn-primary btn-lg w-100 rounded-pill shadow-sm py-3">
                            <i class="fas fa-check-circle ms-2"></i> إتمام عملية الشراء
                        </a>
                        <form id="checkout-form" action="{{ route('orders.store') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    @else
                        <div class="alert alert-warning text-center small rounded-4 border-0">
                            <i class="fas fa-exclamation-triangle ms-1"></i> يرجى تسجيل الدخول لإتمام الطلب
                        </div>
                        <a href="{{ route('customer.login') }}" class="btn btn-dark btn-lg w-100 rounded-pill py-3">
                            تسجيل الدخول
                        </a>
                    @endauth

                    <div class="mt-4 d-flex justify-content-center gap-3 grayscale opacity-50">
                        <i class="fab fa-cc-visa fa-2x"></i>
                        <i class="fab fa-cc-mastercard fa-2x"></i>
                        <i class="fas fa-money-bill-wave fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-5">
            <div class="mb-4">
                <i class="fas fa-shopping-basket fa-5x text-light"></i>
            </div>
            <h4 class="text-muted mb-4">سلة التسوق فارغة حالياً</h4>
            <a href="{{ route('shop.index') }}" class="btn btn-primary btn-lg rounded-pill px-5 shadow">
                ابدأ التسوق الآن
            </a>
        </div>
    @endif
</div>

<style>
    .grayscale { filter: grayscale(1); }
</style>
@endsection
