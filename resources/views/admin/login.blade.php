<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>دخول الإدارة - مباهج الخد</title>
            <link rel="icon" type="image/png" href="https://mabahijalkhad.com/wp-content/uploads/2024/09/%D9%85%D8%A8%D8%A7%D9%87%D8%AC-%D8%A7%D9%84%D8%AE%D8%AF.png">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background-color: #f4f7f6; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; }
        .login-container { width: 100%; max-width: 400px; padding: 30px; background: #fff; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
        .btn-success { background-color: #59ab6e; border: none; padding: 12px; font-weight: bold; border-radius: 30px; }
        .btn-success:hover { background-color: #489358; }
    </style>
</head>
<body>

<div class="login-container">
    <h3 class="text-center text-success mb-4 fw-bold">mabahij_alkahd Admin</h3>

    @if($errors->any())
        <div class="alert alert-danger small py-2">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.login.submit') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label fw-bold small">البريد الإلكتروني</label>
            <input type="email" name="email" class="form-control bg-light border-0" required placeholder="admin@edraakmc.com" value="{{ old('email') }}">
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold small">كلمة المرور</label>
            <div class="input-group">
                <input type="password" name="password" id="adminPass" class="form-control bg-light border-0" required placeholder="••••••••">
                <button class="btn btn-light border-0" type="button" onclick="toggleAdminPass()">
                    <i class="fas fa-eye text-muted" id="adminEyeIcon"></i>
                </button>
            </div>
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox" name="remember" class="form-check-input" id="remember">
            <label class="form-check-label small" for="remember">تذكرني</label>
        </div>

        <button type="submit" class="btn btn-success w-100 shadow-sm">دخول لوحة التحكم</button>
    </form>
</div>

<script>
    function toggleAdminPass() {
        const pass = document.getElementById('adminPass');
        const icon = document.getElementById('adminEyeIcon');
        if (pass.type === 'password') {
            pass.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            pass.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }
</script>
</body>
</html>
