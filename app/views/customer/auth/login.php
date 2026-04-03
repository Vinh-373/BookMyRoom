<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập - Nationwide</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Sử dụng lại CSS Split-screen đã cung cấp ở trên */
        :root { --primary-blue: #003580; --accent-blue: #006ce4; }
        body { font-family: 'Inter', sans-serif; margin: 0; display: flex; height: 100vh; }
        .auth-banner { flex: 1; background: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)), url('https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=1000&q=80'); background-size: cover; padding: 60px; color: white; display: flex; flex-direction: column; justify-content: flex-end; }
        .auth-form-section { width: 500px; background: #f5f7f9; display: flex; justify-content: center; align-items: center; }
        .form-card { background: white; padding: 40px; border-radius: 12px; width: 400px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); }
        .input-group { margin-bottom: 20px; }
        .input-group label { display: block; margin-bottom: 8px; font-weight: 600; }
        .input-group input { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; }
        .btn-submit { width: 100%; padding: 14px; background: var(--primary-blue); color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: bold; }
        .auth-link { display: block; text-align: center; margin-top: 20px; color: var(--accent-blue); text-decoration: none; font-size: 14px; }
    </style>
</head>
<body>
    <div class="auth-banner">
        <h1>Welcome Back.</h1>
        <p>Your next extraordinary escape awaits.</p>
    </div>
    <div class="auth-form-section">
        <div class="form-card">
            <h2 style="margin-bottom: 25px; text-align: center;">Sign In</h2>
            <form action="/auth/loginAction" method="POST">
                <div class="input-group">
                    <label>Email</label>
                    <input type="email" name="email" required placeholder="example@gmail.com">
                </div>
                <div class="input-group">
                    <label>Password</label>
                    <input type="password" name="password" required placeholder="••••••••">
                </div>
                <button type="submit" class="btn-submit">Login</button>
            </form>
            <a href="/auth/register" class="auth-link">Don't have an account? Sign Up</a>
            <a href="/auth/forgot" class="auth-link" style="margin-top: 10px;">Forgot Password?</a>
        </div>
    </div>
</body>
</html>