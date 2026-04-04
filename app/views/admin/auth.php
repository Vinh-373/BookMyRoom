<?php
/**
 * File: auth.php
 * Path: app/views/admin/auth.php
 */
?>
<style>
    .header{
        display: none !important;
    }
    .sidebar{
        display: none !important;

    }
</style>

<main class="min-h-screen w-full flex items-center justify-center bg-[var(--surface)] p-6 font-sans">
    <div class="w-full max-w-md z-10">
        <div class="glass-card rounded-[1.5rem] p-10 border border-[var(--outline-variant)] bg-white shadow-2xl">
            
            <div class="mb-10 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-[var(--primary)] text-white rounded-2xl mb-4 shadow-lg shadow-indigo-200">
                    <span class="material-symbols-outlined text-[32px]">hotel_class</span>
                </div>
                <h1 class="text-[2.25rem] font-extrabold tracking-tight text-[var(--on-surface)] leading-tight mb-2">BookMyRoom</h1>
                <p class="text-[var(--on-surface-variant)] text-sm font-medium">Hệ thống quản trị viên (Admin Panel)</p>
            </div>

            <form id="adminLoginForm" class="space-y-6">
                
                <div class="space-y-2">
                    <label class="block text-[0.75rem] font-bold text-[var(--on-surface-variant)] tracking-wider uppercase px-1">Tài khoản quản trị</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-4 flex items-center text-[var(--on-surface-variant)]/60 group-focus-within:text-[var(--primary)] transition-colors">
                            <span class="material-symbols-outlined text-[20px]">admin_panel_settings</span>
                        </div>
                        <input 
                            name="username" 
                            required
                            class="w-full pl-12 pr-4 py-4 bg-[var(--surface-container-low)] border-2 border-transparent rounded-xl text-[var(--on-surface)] placeholder:text-[var(--on-surface-variant)]/40 focus:border-[var(--primary)] focus:bg-white transition-all outline-none" 
                            placeholder="admin_id hoặc email" 
                            type="text"
                        />
                    </div>
                </div>

                <div class="space-y-2">
                    <div class="flex justify-between items-center px-1">
                        <label class="block text-[0.75rem] font-bold text-[var(--on-surface-variant)] tracking-wider uppercase">Mật khẩu bảo mật</label>
                    </div>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-4 flex items-center text-[var(--on-surface-variant)]/60 group-focus-within:text-[var(--primary)] transition-colors">
                            <span class="material-symbols-outlined text-[20px]">lock</span>
                        </div>
                        <input 
                            name="password"
                            id="password-input"
                            required
                            class="w-full pl-12 pr-12 py-4 bg-[var(--surface-container-low)] border-2 border-transparent rounded-xl text-[var(--on-surface)] placeholder:text-[var(--on-surface-variant)]/40 focus:border-[var(--primary)] focus:bg-white transition-all outline-none" 
                            placeholder="••••••••••••" 
                            type="password"
                        />
                        <button 
                            type="button"
                            onclick="togglePassword()"
                            class="absolute inset-y-0 right-4 flex items-center text-[var(--on-surface-variant)]/60 hover:text-[var(--primary)] transition-colors"
                        >
                            <span class="material-symbols-outlined text-[20px]" id="eye-icon">visibility</span>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between py-2">
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input name="remember" type="checkbox" class="w-5 h-5 rounded border-gray-300 text-[var(--primary)] focus:ring-[var(--primary)] cursor-pointer">
                        <span class="text-sm text-[var(--on-surface-variant)] group-hover:text-[var(--on-surface)] transition-colors">Ghi nhớ</span>
                    </label>
                    <a class="text-sm font-semibold text-[var(--primary)] hover:underline decoration-2 underline-offset-4" href="#">Quên mật khẩu?</a>
                </div>

                <button id="submitBtn" class="w-full py-4 bg-[var(--primary)] text-white font-bold rounded-xl shadow-lg shadow-indigo-200 hover:shadow-indigo-300 active:scale-[0.98] transition-all flex items-center justify-center gap-2 group" type="submit">
                    <span>Đăng nhập hệ thống</span>
                    <span class="material-symbols-outlined text-[20px] group-hover:translate-x-1 transition-transform">arrow_forward</span>
                </button>
            </form>

            <div class="mt-8 pt-8 border-t border-[var(--outline-variant)]">
                <button type="button" class="w-full flex items-center justify-center gap-3 py-3 rounded-xl border-2 border-[var(--outline-variant)] text-[var(--on-surface-variant)] hover:bg-[var(--surface-container-low)] hover:border-[var(--primary)] hover:text-[var(--primary)] transition-all font-semibold">
                    <span class="material-symbols-outlined text-[22px]">fingerprint</span>
                    <span class="text-sm">Xác thực FaceID / Vân tay</span>
                </button>
            </div>
        </div>

        <div class="mt-8 text-center">
            <div class="flex items-center justify-center gap-2 text-[var(--on-surface-variant)]/50">
                <span class="material-symbols-outlined text-[18px]">security</span>
                <p class="text-[0.65rem] uppercase tracking-[0.2em] font-black">BookMyRoom Management Security Protocol</p>
            </div>
        </div>
    </div>
</main>

<script>
    /**
     * Chức năng ẩn/hiện mật khẩu
     */
    function togglePassword() {
        const input = document.getElementById('password-input');
        const icon = document.getElementById('eye-icon');
        const isPassword = input.type === 'password';
        
        input.type = isPassword ? 'text' : 'password';
        icon.textContent = isPassword ? 'visibility_off' : 'visibility';
    }

    /**
     * Xử lý Đăng nhập qua Fetch API
     */
    const loginForm = document.getElementById('adminLoginForm');
    const submitBtn = document.getElementById('submitBtn');
    const btnSpan = submitBtn.querySelector('span:first-child');

    loginForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        // Khởi tạo FormData
        const formData = new FormData(loginForm);
        
        // KIỂM TRA DỮ LIỆU (Debug): 
        // Nếu log cái này ra mà thấy dữ liệu thì server mới nhận được
        console.log("Dữ liệu gửi đi:", Object.fromEntries(formData));

        // Trạng thái Loading UI
        submitBtn.disabled = true;
        submitBtn.style.opacity = '0.7';
        btnSpan.textContent = 'Đang xác thực...';

        try {
            const response = await fetch('/BookMyRoom/admin/auth/login', {
                method: 'POST',
                body: formData
            });

            // Kiểm tra nếu server trả về không phải JSON (lỗi 500 hoặc trang 404)
            const contentType = response.headers.get("content-type");
            if (!contentType || !contentType.includes("application/json")) {
                throw new TypeError("Server không trả về JSON hợp lệ!");
            }

            const result = await response.json();

            if (result.success) {
                
                // Thành công: Chuyển hướng
                window.location.href = result.redirect || '/BookMyRoom/admin/dashboard';
            } else {
                // Thất bại: Thông báo lỗi từ server
                alert(result.message || 'Sai thông tin đăng nhập.');
            }
        } catch (error) {
            console.error('Lỗi hệ thống:', error);
            alert('Lỗi kết nối server hoặc phản hồi không đúng định dạng.');
        } finally {
            // Hoàn tác trạng thái UI
            submitBtn.disabled = false;
            submitBtn.style.opacity = '1';
            btnSpan.textContent = 'Đăng nhập hệ thống';
        }
    });

</script>