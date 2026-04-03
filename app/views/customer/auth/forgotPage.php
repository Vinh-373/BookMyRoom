<style>
    /* Scoped CSS for Auth Content */
    .auth-card-content {
        background: #ffffff;
        padding: 30px;
        max-width: 420px;
        margin: auto;
    }

    .auth-step {
        display: none;
        text-align: center;
    }

    .auth-step.active {
        display: block;
        animation: authFadeIn 0.4s ease-out;
    }

    @keyframes authFadeIn {
        from {
            opacity: 0;
            transform: translateY(8px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .auth-title {
        font-size: 1.5rem;
        color: #111827;
        margin: 15px 0 10px;
        font-weight: 700;
    }

    .auth-desc {
        font-size: 0.9rem;
        color: #6b7280;
        line-height: 1.5;
        margin-bottom: 25px;
    }

    .auth-input-group {
        text-align: left;
        margin-bottom: 18px;
    }

    .auth-label {
        display: block;
        font-size: 0.85rem;
        font-weight: 600;
        margin-bottom: 6px;
        color: #374151;
    }

    .auth-input {
        width: 100%;
        padding: 12px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        box-sizing: border-box;
        font-size: 1rem;
    }

    .auth-btn-primary {
        width: 100%;
        background: #0066da;
        color: white;
        border: none;
        padding: 14px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        font-size: 1rem;
        transition: background 0.2s;
    }

    .auth-btn-primary:hover {
        background: #0056b3;
    }

    .auth-back-link {
        text-decoration: none;
        color: #6b7280;
        font-size: 0.85rem;
        display: inline-block;
        margin-bottom: 10px;
    }

    .auth-icon-circle {
        width: 48px;
        height: 48px;
        background: #eff6ff;
        color: #0066da;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        font-size: 1.2rem;
    }

    .auth-otp-container {
        display: flex;
        justify-content: space-between;
        gap: 8px;
        margin-bottom: 20px;
    }

    .auth-otp-input {
        width: 100%;
        height: 48px;
        text-align: center;
        font-size: 1.2rem;
        font-weight: bold;
        border: 1px solid #d1d5db;
        border-radius: 8px;
    }

    .auth-strength-meter {
        height: 4px;
        background: #f3f4f6;
        margin: 12px 0 6px;
        border-radius: 2px;
    }

    .auth-strength-bar {
        height: 100%;
        background: #0066da;
        border-radius: 2px;
    }

    .auth-strength-label {
        display: flex;
        justify-content: space-between;
        font-size: 0.7rem;
        font-weight: 700;
        margin-bottom: 15px;
    }

    .auth-rules {
        list-style: none;
        padding: 0;
        text-align: left;
    }

    .auth-rules li {
        font-size: 0.8rem;
        color: #9ca3af;
        margin-bottom: 5px;
    }

    .auth-rules li.done {
        color: #10b981;
    }

    .auth-footer-text {
        font-size: 0.85rem;
        color: #6b7280;
        margin-top: 20px;
    }

    .auth-footer-text a {
        color: #0066da;
        text-decoration: none;
    }
</style>

<body>
    <div class="auth-card-content">
        <div id="step1" class="auth-step active">
            <a href="javascript:void(0)" class="auth-back-link"><i class="fas fa-arrow-left"></i> Back to Login</a>
            <h2 class="auth-title">Forgot Password?</h2>
            <p class="auth-desc">Enter the email address associated with your account and we will send you a verification code.</p>
            <div class="auth-input-group">
                <label class="auth-label">Email Address</label>
                <input type="email" class="auth-input" id="emailInput" placeholder="name@company.com">
            </div>
            <button class="auth-btn-primary" onclick="sendResetLink()">Send Reset Link</button>
            <p class="auth-footer-text">Still having trouble? <a href="#">Contact Support</a></p>
        </div>

        <div id="step2" class="auth-step">
            <div class="auth-icon-circle">
                <i class="fas fa-envelope-open-text"></i>
            </div>
            <h2 class="auth-title">Verify Your Email</h2>
            <p class="auth-desc">We've sent a 6-digit code to your email. Please enter it below.</p>
            <div class="auth-otp-container">
                <input type="text" class="auth-otp-input" maxlength="1" oninput="moveNext(this)">
                <input type="text" class="auth-otp-input" maxlength="1" oninput="moveNext(this)">
                <input type="text" class="auth-otp-input" maxlength="1" oninput="moveNext(this)">
                <input type="text" class="auth-otp-input" maxlength="1" oninput="moveNext(this)">
                <input type="text" class="auth-otp-input" maxlength="1" oninput="moveNext(this)">
                <input type="text" class="auth-otp-input" maxlength="1" oninput="moveNext(this)">
            </div>
            <button class="auth-btn-primary" onclick="verifyOtp()">Confirm</button>
            <p class="auth-footer-text">Didn't receive the code? <a href="#">Resend Code</a> <span class="auth-timer">04:59</span></p>
            <a href="javascript:void(0)" class="auth-back-link" onclick="goToStep(1)">Back to login</a>
        </div>

        <div id="step3" class="auth-step">
            <div class="auth-icon-circle">
                <i class="fas fa-rotate-right"></i>
            </div>
            <h2 class="auth-title">Set New Password</h2>
            <p class="auth-desc">Please create a strong password to secure your account.</p>

            <div class="auth-input-group">
                <label class="auth-label">New Password</label>
                <input type="password" class="auth-input newpass" placeholder="••••••••">
                <div class="auth-strength-meter">
                    <div class="auth-strength-bar" style="width: 66%;"></div>
                </div>
                <div class="auth-strength-label">
                    <span>PASSWORD STRENGTH</span>
                    <span style="color: #0066da">MEDIUM</span>
                </div>
                <ul class="auth-rules">
                    <li class="done">At least 8 characters</li>
                    <li class="done">One special character (!@#$)</li>
                    <li>One number (0-9)</li>
                </ul>
            </div>

            <div class="auth-input-group">
                <label class="auth-label">Confirm New Password</label>
                <input type="password" class="auth-input confirmpass"  placeholder="••••••••">
            </div>

            <button class="auth-btn-primary" onclick="resetPassword()">Reset Password</button>
            <a href="javascript:void(0)" class="auth-back-link" onclick="goToStep(1)">Back to Login</a>
        </div>
    </div>
</body>
<script>
    const baseUrl = "<?= BASE_URL ?>";

    function sendResetLink() {
        const emailInput = document.getElementById('emailInput');
        const email = emailInput.value.trim();

        if (!email) {
            Swal.fire('Please enter your email');
            return;
        }

        console.log("--- Bắt đầu gửi yêu cầu reset mật khẩu ---");
        console.log("Target URL:", `${baseUrl}auth/checkEmail`);
        console.log("Payload:", { email: email });

        fetch(`${baseUrl}auth/checkEmail`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ email: email })
        })
        .then(async response => {
            // Đọc nội dung dưới dạng text trước để debug nếu không phải JSON
            const rawText = await response.text();
            console.log("Raw Server Response (Chuỗi thô từ server):", rawText);

            if (!response.ok) {
                console.error(`HTTP Error! Status: ${response.status}`);
            }

            try {
                // Thử parse chuỗi thô đó sang JSON
                return JSON.parse(rawText);
            } catch (err) {
                console.error("LỖI PARSE JSON: Server trả về dữ liệu không hợp lệ (có thể dính log SMTP hoặc lỗi PHP)");
                throw new Error("Phản hồi từ server không phải là JSON sạch.");
            }
        })
        .then(data => {
            console.log("Dữ liệu JSON đã nhận:", data);
            
            if (data.exists || data.status === 'success') {
                console.log(data.message || 'Verification code sent to your email!');
                goToStep(2);
            } else {
                Swal.fire(data.message || 'Email not found. Please check and try again.');
            }
        })
        .catch(err => {
            console.error("CHI TIẾT LỖI (FETCH ERROR):", err);
            Swal.fire('Có lỗi xảy ra: ' + err.message);
        });
    }

    // Giữ nguyên các hàm goToStep và moveNext của bạn...
    function goToStep(stepNumber) {
        const steps = document.querySelectorAll('.auth-step');
        steps.forEach(step => step.classList.remove('active'));
        const targetStep = document.getElementById('step' + stepNumber);
        if (targetStep) targetStep.classList.add('active');
    }
function verifyOtp() {
    // 1. Lấy tất cả các ô input OTP
    const inputs = document.querySelectorAll('.auth-otp-input');
    let otpCode = "";
    
    // 2. Ghép các ký tự lại thành chuỗi 6 số
    inputs.forEach(input => {
        otpCode += input.value;
    });

    if (otpCode.length < 6) {
        Swal.fire({
            icon: 'warning',
            title: 'Incomplete OTP',
            text: 'Vui lòng nhập đủ 6 chữ số OTP.'
        });
        return;
    }

    console.log("--- Đang xác thực mã OTP ---");
    console.log("OTP Code:", otpCode);

    // 3. Gửi lên server để kiểm tra
    fetch(`${baseUrl}auth/verifyOtp`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ otp: otpCode })
    })
    .then(async response => {
        const rawText = await response.text();
        console.log("Raw Server Response:", rawText);
        try {
            return JSON.parse(rawText);
        } catch (err) {
            throw new Error("Phản hồi server không hợp lệ");
        }
    })
    .then(data => {
        if (data.status === 'success') {
            Swal.fire("Mã OTP chính xác!");
            goToStep(3); // Chuyển sang bước đặt lại mật khẩu
        } else {
            Swal.fire(data.message || "Mã OTP không đúng hoặc đã hết hạn");
        }
    })
    .catch(err => {
        console.error("Lỗi:", err);
        Swal.fire("Có lỗi xảy ra khi xác thực OTP");
    });
}
    function moveNext(el) {
        if (el.value.length === 1 && el.nextElementSibling) {
            el.nextElementSibling.focus();
        }
    }
    function resetPassword() {
    // Lấy các trường input ở Step 3
    const step3 = document.getElementById('step3');
    const newPassword = step3.querySelector('input.newpass').value;
    const confirmPassword = step3.querySelector('input.confirmpass').value;


    // 1. Kiểm tra cơ bản ở Frontend
    if (newPassword.length < 6) {
        Swal.fire("Mật khẩu phải có ít nhất 6 ký tự.");
        return;
    }

    if (newPassword !== confirmPassword) {
        Swal.fire("Mật khẩu xác nhận không khớp.");
        return;
    }

    console.log("--- Đang tiến hành đổi mật khẩu mới ---");

    fetch(`${baseUrl}auth/updatePassword`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ 
            password: newPassword 
        })
    })
    .then(async response => {
        const rawText = await response.text();
        try {
            return JSON.parse(rawText);
        } catch (err) {
            throw new Error("Phản hồi server không hợp lệ");
        }
    })
    .then(data => {
        if (data.status === 'success') {
            Swal.fire("Chúc mừng! Mật khẩu của bạn đã được thay đổi thành công.");
            // Chuyển về trang đăng nhập
            window.location.href = `${baseUrl}auth`; 
        } else {
            Swal.fire(data.message || "Có lỗi xảy ra, vui lòng thử lại.");
        }
    })
    .catch(err => {
        console.error("Lỗi:", err);
        Swal.fire("Không thể kết nối đến server.");
    });
}
</script>