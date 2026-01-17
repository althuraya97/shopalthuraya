@extends('layouts.frontend')

@section('content')
<div class="container py-5" dir="rtl">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="card-header bg-primary py-4 text-center border-0">
                    <h3 class="fw-bold text-white mb-0">إنشاء حساب جديد</h3>
                    <p class="text-white-50 small mb-0 mt-2">انضم إلى متجر مباهج الخد واستمتع بتجربة تسوق فريدة</p>
                </div>

                <div class="card-body p-4 p-md-5">
                    @if ($errors->any())
                        <div class="alert alert-danger border-0 rounded-3 mb-4">
                            <ul class="mb-0 px-3">
                                @foreach ($errors->all() as $error)
                                    <li class="small">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('customer.register.post') }}" method="POST">
                        @csrf

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">الاسم الأول</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-start-0"><i class="fas fa-user text-muted"></i></span>
                                    <input type="text" name="first_name" class="form-control bg-light border-end-0" placeholder="مثلاً: محمد" value="{{ old('first_name') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">الاسم الأخير</label>
                                <input type="text" name="last_name" class="form-control bg-light" placeholder="مثلاً: العلي" value="{{ old('last_name') }}" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small">البريد الإلكتروني</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-start-0"><i class="fas fa-envelope text-muted"></i></span>
                                <input type="email" name="email" class="form-control bg-light border-end-0" placeholder="name@example.com" value="{{ old('email') }}" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small">كلمة المرور</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-start-0"><i class="fas fa-lock text-muted"></i></span>
                                <input type="password" name="password" class="form-control bg-light border-end-0" placeholder="••••••••" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold small">تأكيد كلمة المرور</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-start-0"><i class="fas fa-check-double text-muted"></i></span>
                                <input type="password" name="password_confirmation" class="form-control bg-light border-end-0" placeholder="••••••••" required>
                            </div>
                        </div>

                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill shadow-sm py-3 fw-bold transition-all">
                                إنشاء الحساب
                            </button>
                        </div>

                        <p class="text-center text-muted small mb-0">
                            لديك حساب بالفعل؟ <a href="{{ route('customer.login') }}" class="text-primary fw-bold text-decoration-none">تسجيل الدخول</a>
                        </p>
                    </form>
                </div>
            </div>

            <div class="text-center mt-4">
                <a href="{{ route('shop.index') }}" class="text-secondary text-decoration-none small">
                    <i class="fas fa-arrow-right ms-1"></i> العودة للمتجر
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    .transition-all { transition: all 0.3s ease; }
    .transition-all:hover { transform: translateY(-2px); filter: brightness(1.1); }
    .card { border: none; }
    .form-control:focus {
        box-shadow: none;
        border-color: #0d6efd;
        background-color: #fff !important;
    }
    .input-group-text { border-color: transparent; }
</style>
@endsection
