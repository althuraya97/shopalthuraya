<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login - EdraakMC</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body { background-color: #f4f7f6; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; }
        .login-container { width: 100%; max-width: 400px; padding: 30px; background: #fff; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
        .btn-success { background-color: #59ab6e; border: none; padding: 10px; font-weight: bold; }
        .btn-success:hover { background-color: #489358; }
        .form-control:focus { border-color: #59ab6e; box-shadow: 0 0 0 0.2rem rgba(89, 171, 110, 0.25); }
    </style>
</head>
<body>

<div class="login-container">
    <h3 class="text-center text-success mb-4 font-weight-bold">EdraakMC Admin</h3>

    @if($errors->any())
        <div class="alert alert-danger shadow-sm">
            <ul class="mb-0 px-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('login.submit') }}" method="POST">
        @csrf <div class="mb-3">
            <label class="form-label fw-bold">Email address</label>
            <input type="email" name="email" class="form-control" required
                   placeholder="admin@edraakmc.com" value="{{ old('email') }}">
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Password</label>
            <input type="password" name="password" class="form-control" required
                   placeholder="••••••••••••">
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox" name="remember" class="form-check-input" id="remember">
            <label class="form-check-label" for="remember">Remember me</label>
        </div>

        <button type="submit" class="btn btn-success w-100 shadow-sm">Login</button>
    </form>
</div>

</body>
</html>
