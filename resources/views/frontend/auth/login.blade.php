@extends('layouts.frontend')

@section('content')
<div class="container py-5" dir="rtl">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="card-header bg-primary py-4 text-center border-0">
                    <h3 class="fw-bold text-white mb-0">تسجيل الدخول</h3>
                    <p class="text-white-50 small mb-0 mt-2"> مرحباً بك مجدداً في متجر مباهج الخد ٍ</p>
                </div>

                <div class="card-body p-4 p-md-5">
                    @if ($errors->any())
                        <div class="alert alert-danger border-0 rounded-3 mb-4 small text-center">
                            <i class="fas fa-exclamation-circle me-1"></i> {{ $errors->first() }}
                        </div>
                    @endif

                    <form action="{{ route('customer.login.post') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-bold small">البريد الإلكتروني</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="fas fa-envelope text-muted"></i></span>
                                <input type="email" name="email" class="form-control bg-light border-0" placeholder="email@example.com" value="{{ old('email') }}" required autofocus>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold small">كلمة المرور</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="fas fa-lock text-muted"></i></span>
                                <input type="password" name="password" id="passwordField" class="form-control bg-light border-0" placeholder="••••••••" required>
                                <button class="btn btn-light border-0" type="button" id="togglePassword">
                                    <i class="fas fa-eye text-muted" id="eyeIcon"></i>
                                </button>
                            </div>
                        </div>

                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill shadow-sm py-3 fw-bold transition-hover">
                                دخول <i class="fas fa-sign-in-alt ms-2"></i>
                            </button>
                        </div>

                        <div class="text-center">
                            <p class="text-muted small mb-0">
                                ليس لديك حساب؟ <a href="{{ route('customer.register') }}" class="text-primary fw-bold text-decoration-none">إنشاء حساب جديد</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>

            <div class="text-center mt-4">
                <a href="{{ route('shop.index') }}" class="text-secondary text-decoration-none small">
                    <i class="fas fa-home ms-1"></i> العودة للرئيسية
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('togglePassword').addEventListener('click', function (e) {
        const passwordField = document.getElementById('passwordField');
        const eyeIcon = document.getElementById('eyeIcon');

        // التبديل بين نوع الإدخال password و text
        const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordField.setAttribute('type', type);

        // تغيير شكل الأيقونة
        eyeIcon.classList.toggle('fa-eye');
        eyeIcon.classList.toggle('fa-eye-slash');
    });
</script>

<style>
    .form-control:focus {
        box-shadow: none;
        background-color: #fff !important;
        border: 1px solid #0d6efd !important;
    }
    .input-group-text, .btn-light {
        background-color: #f8f9fa !important;
    }
    .transition-hover {
        transition: all 0.3s ease;
    }
    .transition-hover:hover {
        transform: translateY(-2px);
        filter: brightness(1.1);
    }
</style>
@endsection
