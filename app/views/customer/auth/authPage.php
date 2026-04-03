<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Sign Up - BookMyRoom</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script src="https://accounts.google.com/gsi/client" async defer></script>

    <style>
        /* ==================== GLOBAL STYLE ==================== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f2f5;
        }

        .main-login {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .container-login {
            position: relative;
            width: 100%;
            max-width: 920px;
            background: #ffffff;
            border-radius: 28px;
            overflow: hidden;
            box-shadow: 0 25px 70px rgba(0, 0, 0, 0.15);
            display: flex;
            min-height: 550px;
        }

        /* ==================== LEFT PANEL (IMAGE) ==================== */
        .left-panel {
            width: 50%;
            background: #1A2B48 url('http://localhost/BookMyRoom/public/images/login.png') no-repeat center;
            background-size: cover;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 40px;
            color: #ffffff;
            transition: 0.6s ease-in-out;
        }

        /* ==================== RIGHT PANEL & FORMS ==================== */
        .right-panel {
            width: 50%;
            padding: 40px;
            display: flex;
            align-items: center;
            background: #ffffff;
        }

        .form-area {
            position: relative;
            width: 100%;
            height: 480px;
            /* Chiều cao cố định để tránh giật lag khi chuyển form */
        }

        .sign-up-form,
        .sign-in-form {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            transition: all 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }

        /* Trạng thái mặc định: Hiện Sign In */
        .sign-in-form {
            opacity: 1;
            transform: translateX(0);
            z-index: 2;
        }

        .sign-up-form {
            opacity: 0;
            transform: translateX(100px);
            z-index: 1;
            pointer-events: none;
        }

        /* Khi ở chế độ Sign Up */
        .container-login.sign-up-mode .sign-in-form {
            opacity: 0;
            transform: translateX(-100px);
            pointer-events: none;
        }

        .container-login.sign-up-mode .sign-up-form {
            opacity: 1;
            transform: translateX(0);
            pointer-events: auto;
        }

        /* ==================== FORM ELEMENTS ==================== */
        h2 {
            font-size: 1.8rem;
            text-align: center;
            margin-bottom: 25px;
            color: #1A2B48;
        }

        .input-group {
            margin-bottom: 15px;
        }

        .input-group label {
            display: block;
            margin-bottom: 6px;
            font-size: 0.85rem;
            color: #44474d;
            font-weight: 600;
        }

        .input-group input {
            width: 100%;
            padding: 12px 18px;
            border: 1px solid #c5c6ce;
            border-radius: 12px;
            background: #f9fafb;
            font-size: 0.95rem;
            transition: 0.3s;
        }

        .input-group input:focus {
            outline: none;
            border-color: #1A2B48;
            box-shadow: 0 0 0 4px rgba(26, 43, 72, 0.1);
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            font-size: 0.8rem;
        }

        .checkbox-group label {
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        /* Google Icon Section */
        .social-login {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 20px;
        }

        .social-login p {
            font-size: 0.8rem;
            color: #888;
            margin-bottom: 10px;
        }

        .icon-gg i {
            font-size: 1.4rem;
            cursor: pointer;
            transition: 0.3s;
            border: 1px solid #ddd;
            padding: 12px;
            border-radius: 50%;
            color: #EA4335;
            /* Google Red */
        }

        .icon-gg i:hover {
            background: #f1f1f1;
            transform: scale(1.1);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .forgot-link {
            color: #375ca8;
            text-decoration: none;
            font-weight: 500;
        }

        .actions {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .btn {
            width: 100%;
            padding: 12px;
            font-size: 0.95rem;
            font-weight: 600;
            border-radius: 12px;
            cursor: pointer;
            transition: 0.3s;
            border: none;
        }

        .btn-primary {
            background: #1A2B48;
            color: #fff;
        }

        .btn-primary:hover {
            background: #2c4269;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: #f1f5f9;
            color: #1A2B48;
        }

        .btn-secondary:hover {
            background: #e2e8f0;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .container-login {
                flex-direction: column;
            }

            .left-panel {
                display: none;
            }

            .right-panel {
                width: 100%;
            }
        }
    </style>
</head>

<body>

    <div class="main-login">
        <div class="container-login">

            <div class="left-panel">
                <h3>Welcome Back!</h3>
                <p>To keep connected with us please login with your personal info</p>
            </div>

            <div class="right-panel">
                <div class="form-area">

                    <div class="sign-up-form">
                        <h2>Create Account</h2>
                        <form id="signUpForm">
                            <div class="input-group">
                                <label>Full Name</label>
                                <input type="text" name="fullname" placeholder="John Doe" required>
                            </div>
                            <div class="input-group">
                                <label>Email Address</label>
                                <input type="email" name="email" placeholder="example@mail.com" required>
                            </div>
                            <div class="input-group">
                                <label>Password</label>
                                <input type="password" name="password" placeholder="••••••••" required>
                            </div>
                            <div class="checkbox-group">
                                <label><input type="checkbox" id="terms" required> I agree to Terms & Conditions</label>
                            </div>
                            <div class="actions">
                                <button type="submit" class="btn btn-primary">Sign Up</button>
                                <button type="button" class="btn btn-secondary btn-to-signin">Already have an account? Sign In</button>
                            </div>
                        </form>
                    </div>

                    <div class="sign-in-form">
                        <h2>Sign In</h2>

                        <!-- Thay thế phần social-login -->
                        <div class="social-login">
                            <p>Sign in with</p>
                            <div id="googleBtn" style="display: flex; justify-content: center;"></div>
                        </div>

                        <form id="signInForm">
                            <div class="input-group">
                                <label>Email Address</label>
                                <input type="email" name="email" placeholder="example@mail.com" required>
                            </div>
                            <div class="input-group">
                                <label>Password</label>
                                <input type="password" name="password" placeholder="••••••••" required>
                            </div>
                            <div class="checkbox-group">
                                <label><input type="checkbox" name="remember"> Remember me</label>
                                <a href="forgot" class="forgot-link">Forgot Password?</a>
                            </div>
                            <div class="actions">
                                <button type="submit" class="btn btn-primary">Sign In Now</button>
                                <button type="button" class="btn btn-secondary btn-to-signup">New here? Create Account</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <script>
        const container = document.querySelector('.container-login');
        const baseUrl = "<?= BASE_URL ?>";

        // ==================== CHUYỂN ĐỔI FORM ====================
        document.querySelector('.btn-to-signup').addEventListener('click', () => container.classList.add('sign-up-mode'));
        document.querySelector('.btn-to-signin').addEventListener('click', () => container.classList.remove('sign-up-mode'));

        // ==================== GOOGLE LOGIN ====================
        window.onload = function() {
            google.accounts.id.initialize({
                client_id: "588934513976-43q3pfmc1ug7qde73n35ita3mcarjf77.apps.googleusercontent.com",
                callback: handleGoogleResponse,
                ux_mode: "popup"
            });

            // ✅ renderButton vào div — bấm là hiện popup ngay
            google.accounts.id.renderButton(
                document.getElementById('googleBtn'), {
                    theme: "outline",
                    size: "large",
                    text: "signin_with",
                    shape: "rectangular"
                }
            );
        };

        function handleGoogleResponse(response) {
            const formData = new FormData();
            formData.append('google_token', response.credential);

            const rememberCheckbox = document.querySelector('#signInForm input[name="remember"]');
            if (rememberCheckbox?.checked) formData.append('remember', '1');

            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('redirect')) formData.append('redirect', urlParams.get('redirect'));

            fetch(`${baseUrl}auth/googleLogin`, {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        if (data.token) localStorage.setItem('token', data.token);
                        Swal.fire({
                            title: 'Thành công!',
                            text: data.message || 'Đăng nhập Google thành công!',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 2000
                        }).then(() => {
                            window.location.href = data.redirect ?
                                `${baseUrl}${data.redirect}` :
                                `${baseUrl}home`;
                        });
                    } else {
                        Swal.fire({
                            title: 'Thất bại!',
                            text: data.message || 'Đăng nhập Google thất bại.',
                            icon: 'error',
                            showConfirmButton: false,
                            timer: 2500
                        });
                    }
                })
                .catch(() => Swal.fire({
                    title: 'Lỗi!',
                    text: 'Đã có lỗi xảy ra.',
                    icon: 'error',
                    timer: 2000,
                    showConfirmButton: false
                }));
        }
        // ==================== ĐĂNG KÝ ====================
        document.getElementById('signUpForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch(`${baseUrl}auth/handleRegister`, {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        Swal.fire({
                            title: 'Thành công!',
                            text: data.message,
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 2000
                        }).then(() => {
                            container.classList.remove('sign-up-mode');
                            this.reset();
                        });
                    } else {
                        Swal.fire({
                            title: 'Lỗi!',
                            text: data.message,
                            icon: 'error',
                            timer: 2500,
                            showConfirmButton: false
                        });
                    }
                })
                .catch(() => Swal.fire({
                    title: 'Lỗi!',
                    text: 'Đã có lỗi xảy ra.',
                    icon: 'error',
                    timer: 2000,
                    showConfirmButton: false
                }));
        });

        // ==================== ĐĂNG NHẬP THƯỜNG ====================
        document.getElementById('signInForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('redirect')) {
                formData.append('redirect', urlParams.get('redirect'));
            }

            fetch(`${baseUrl}auth/handleLogin`, {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        if (data.token) localStorage.setItem('token', data.token);

                        Swal.fire({
                            title: 'Thành công!',
                            text: 'Đăng nhập thành công!',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 2000
                        }).then(() => {
                            window.location.href = data.redirect ? `${baseUrl}${data.redirect}` : `${baseUrl}home`;
                        });
                    } else {
                        Swal.fire({
                            title: 'Thất bại!',
                            text: data.message,
                            icon: 'error',
                            showConfirmButton: false,
                            timer: 2500
                        });
                    }
                })
                .catch(() => Swal.fire({
                    title: 'Lỗi!',
                    text: 'Đã có lỗi xảy ra.',
                    icon: 'error',
                    timer: 2000,
                    showConfirmButton: false
                }));
        });
    </script>

</body>