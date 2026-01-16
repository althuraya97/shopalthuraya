@extends('layouts.frontend')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-lg rounded-4">
                <div class="card-body p-5">
                    <h3 class="fw-bold mb-4 text-center">تفاصيل الشحن والدفع</h3>
                    <hr class="mb-5">

                    @if ($errors->any())
                        <div class="alert alert-danger rounded-3">
                            <ul class="mb-0 small">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('orders.store') }}" method="POST">
                        @csrf

                        <div class="row g-4">
                            <div class="col-md-8">
                                <label class="form-label fw-bold small">عنوان المنزل والشارع *</label>
                                <input type="text" name="address" class="form-control bg-light py-2"
                                       placeholder="مثال: حي النخيل - شارع الملك فهد" value="{{ old('address') }}" required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold small">رقم الشقة / الطابق</label>
                                <input type="text" name="apartment" class="form-control bg-light py-2"
                                       placeholder="شقة 12" value="{{ old('apartment') }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold small">المدينة *</label>
                                <input type="text" name="city" class="form-control bg-light py-2"
                                       placeholder="الرياض" value="{{ old('city') }}" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold small">الولاية / المنطقة</label>
                                <input type="text" name="state" class="form-control bg-light py-2"
                                       placeholder="منطقة الرياض" value="{{ old('state') }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold small">البلد *</label>
                                <select name="country" class="form-select bg-light py-2" required>
                                    <option value="" selected disabled>اختر البلد...</option>
                                    <option value="Saudi Arabia">المملكة العربية السعودية</option>
                                    <option value="UAE">الإمارات العربية المتحدة</option>
                                    <option value="Oman">سلطنة عمان</option>
                                    <option value="Kuwait">الكويت</option>
                                    <option value="Egypt">مصر</option>
                                    </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold small">الرمز البريدي *</label>
                                <input type="text" name="zip_code" class="form-control bg-light py-2"
                                       placeholder="12345" value="{{ old('zip_code') }}" required>
                            </div>
                        </div>

                        <hr class="my-5">

                        <div class="mb-4">
                            <h5 class="fw-bold mb-3">طريقة الدفع</h5>
                            <div class="p-3 border border-primary rounded-3 bg-light d-flex align-items-center">
                                <input class="form-check-input me-3" type="radio" name="payment_method"
                                       id="cod" value="COD" checked>
                                <label class="form-check-label d-flex align-items-center" for="cod">
                                    <i class="fas fa-money-bill-wave text-success fs-4 me-2"></i>
                                    <span>الدفع عند الاستلام</span>
                                </label>
                            </div>
                        </div>

                        <div class="d-grid mt-5">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill fw-bold shadow">
                                تأكيد وتقديم الطلب <i class="fas fa-check-circle ms-2"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
