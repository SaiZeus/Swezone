<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', sans-serif;
        }
        .login-card {
            width: 100%;
            max-width: 420px;
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            padding: 40px 30px;
        }
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .login-header .icon-box {
            width: 60px;
            height: 60px;
            background: #eef2ff;
            color: #4f46e5;
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 12px;
        }
        .form-control {
            height: 46px;
            border-radius: 10px;
            border: 1px solid #cbd5e1;
            padding-left: 14px;
            font-size: 0.9rem;
        }
        .form-control:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.15);
        }
        .btn-primary {
            height: 46px;
            border-radius: 10px;
            background: #4f46e5;
            border: 0;
            font-weight: 700;
            font-size: 0.9rem;
            transition: all 0.2s ease;
        }
        .btn-primary:hover {
            background: #4338ca;
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="login-header">
        <div class="icon-box">
            <i class="fa-solid fa-user-shield"></i>
        </div>
        <h4 class="fw-bold text-dark">Admin Access</h4>
        <p class="text-muted small">Enter your credentials to manage system</p>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger py-2 px-3 small rounded-3 mb-3">
            <i class="fa-solid fa-triangle-exclamation me-1"></i>
            {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('admin.login.post') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label text-secondary small fw-bold">Email Address</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus placeholder="admin@example.com">
        </div>

        <div class="mb-3">
            <label class="form-label text-secondary small fw-bold">Password</label>
            <input type="password" name="password" class="form-control" required placeholder="••••••••">
        </div>

        <div class="d-flex justify-between align-items-center mb-4">
            <div class="form-check">
                <input type="checkbox" name="remember" class="form-check-input" id="remember">
                <label class="form-check-label text-muted small" for="remember">Remember me</label>
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100">
            Sign In <i class="fa-solid fa-arrow-right-to-bracket ms-1"></i>
        </button>
    </form>
</div>

</body>
</html>