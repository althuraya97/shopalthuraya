@extends('layouts.frontend')

@section('content')
<div class="container py-5 d-flex justify-content-center">
    <div class="col-md-6 bg-white p-4 shadow-sm rounded-4">
        <h3 class="text-center mb-4 fw-bold">إنشاء حساب جديد</h3>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('customer.register.post') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">الاسم الأول</label>
                    <input type="text" name="first_name" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">اسم العائلة</label>
                    <input type="text" name="last_name" class="form-control" required>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">البريد الإلكتروني</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">كلمة السر</label>
                <input type="password" name="password" class="form-control" placeholder="8 خانات + رقم + رمز" required>
            </div>
            <div class="mb-3">
                <label class="form-label">تأكيد كلمة السر</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 fw-bold">تسجيل الحساب</button>
        </form>
    </div>
</div>
@endsection
