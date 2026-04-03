/**
 * Settings Events Handler
 * Xử lý sự kiện cho trang Cài Đặt
 */

document.addEventListener('DOMContentLoaded', function() {
    console.log('Settings JS Loaded');

    // ==================== Xử lý Profile Avatar Upload ====================
    
    const avatarImage = document.querySelector('.settings-avatar-img');
    
    if (avatarImage) {
        avatarImage.addEventListener('click', function() {
            // Mở file dialog
            const fileInput = document.createElement('input');
            fileInput.type = 'file';
            fileInput.accept = 'image/*';
            fileInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    handleAvatarUpload(file);
                }
            });
            fileInput.click();
        });
    }

    // ==================== Xử lý Form Input Changes ====================
    
    const inputFields = document.querySelectorAll('.settings-input');
    const formChanged = { changed: false };
    
    inputFields.forEach(input => {
        input.addEventListener('change', function() {
            formChanged.changed = true;
            console.log('Form changed:', this.name, '=', this.value);
            
            // Có thể thêm visual indicator cho form
            enableSaveButton();
        });

        // Real-time validation cho email
        if (input.type === 'email') {
            input.addEventListener('blur', function() {
                if (this.value && !isValidEmail(this.value)) {
                    this.classList.add('error');
                    console.warn('Invalid email:', this.value);
                } else {
                    this.classList.remove('error');
                }
            });
        }

        // Real-time validation cho password
        if (input.type === 'password' && input.name === 'password_new') {
            input.addEventListener('input', function() {
                const strength = getPasswordStrength(this.value);
                updatePasswordStrengthIndicator(strength);
            });
        }
    });

    // ==================== Xử lý Password Visibility Toggle ====================
    
    const visibilityToggles = document.querySelectorAll('.settings-password-toggle');
    
    visibilityToggles.forEach(toggle => {
        toggle.addEventListener('click', function() {
            const input = this.previousElementSibling;
            
            if (input.type === 'password') {
                input.type = 'text';
                this.textContent = 'visibility_off';
                console.log('Show password');
            } else {
                input.type = 'password';
                this.textContent = 'visibility';
                console.log('Hide password');
            }
        });
    });

    // ==================== Xử lý 2FA Toggle ====================
    
    const twoFAToggle = document.querySelector('.settings-2fa-toggle');
    
    if (twoFAToggle) {
        twoFAToggle.addEventListener('change', function() {
            const isEnabled = this.checked;
            console.log('2FA toggled:', isEnabled);
            
            if (isEnabled) {
                // TODO: Hiển thị modal setup 2FA
                setup2FA();
            } else {
                if (confirm('Bạn chắc chắn muốn tắt 2FA?')) {
                    // TODO: Gọi API disable 2FA
                    disable2FA();
                } else {
                    this.checked = true;
                }
            }
        });
    }

    // ==================== Xử lý Platform Configuration ====================
    
    const configInputs = document.querySelectorAll('.settings-config-input');
    
    configInputs.forEach(input => {
        input.addEventListener('change', function() {
            const configKey = this.name;
            const configValue = this.value;
            
            console.log('Config changed:', configKey, '=', configValue);
            enableSaveButton();
        });
    });

    // ==================== Xử lý Select Dropdowns ====================
    
    const selectFields = document.querySelectorAll('.settings-select');
    
    selectFields.forEach(select => {
        select.addEventListener('change', function() {
            console.log('Select changed:', this.name, '=', this.value);
            enableSaveButton();
        });
    });

    // ==================== Xử lý Nút "Save Changes" ====================
    
    const saveButton = document.querySelector('.settings-save-btn');
    
    if (saveButton) {
        saveButton.addEventListener('click', function() {
            if (!validateForm()) {
                console.warn('Form validation failed');
                return;
            }

            // Disable nút để tránh multiple clicks
            this.disabled = true;
            this.textContent = 'Đang lưu...';

            // Collect form data
            const formData = new FormData();
            
            // Add profile data
            inputFields.forEach(input => {
                formData.append(input.name, input.value);
            });

            console.log('Saving settings...');
            // TODO: Gọi API lưu cài đặt
            saveSettings(formData, function() {
                // Callback after success
                saveButton.disabled = false;
                saveButton.textContent = 'Lưu thay đổi';
                showNotification('Cài đặt đã được lưu thành công!', 'success');
            });
        });
    }

    // ==================== Xử lý Nút "Cancel" / Reset Form ====================
    
    const cancelButton = document.querySelector('.settings-cancel-btn');
    
    if (cancelButton) {
        cancelButton.addEventListener('click', function() {
            if (formChanged.changed) {
                if (confirm('Bạn có thay đổi chưa lưu. Bạn chắc chắn muốn hủy?')) {
                    location.reload();
                }
            }
        });
    }

    // ==================== Xử lý Tabs (nếu có) ====================
    
    const tabButtons = document.querySelectorAll('.settings-tab-btn');
    
    tabButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const tabId = this.getAttribute('data-tab');
            
            // Remove active class từ tất cả
            tabButtons.forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.settings-tab-content').forEach(content => {
                content.style.display = 'none';
            });

            // Add active class cho tab được click
            this.classList.add('active');
            document.getElementById(tabId).style.display = 'block';
            
            console.log('Tab switched to:', tabId);
        });
    });

    // ==================== Xử lý Notification Settings ====================
    
    const notificationToggles = document.querySelectorAll('.settings-notification-toggle');
    
    notificationToggles.forEach(toggle => {
        toggle.addEventListener('change', function() {
            const notificationType = this.getAttribute('data-notification');
            const isEnabled = this.checked;
            
            console.log('Notification setting:', notificationType, '=', isEnabled);
            enableSaveButton();
        });
    });

    initSettingsProfileForm();

});

/**
 * Cài đặt: form được inject qua sidebar (partial) nên không thể gắn listener lúc DOMContentLoaded.
 * Gọi lại sau khi tải partial + dùng delegation cho nút Lưu.
 */
document.addEventListener('adminPartialLoad', function(e) {
    if (e.detail && e.detail.page === 'settings') {
        initSettingsProfileForm();
    }
});

function getSettingsProfileInputs() {
    const main = document.querySelector('.main-content');
    if (!main) return null;
    const fullnameInput = main.querySelector('#profile-fullname');
    const emailInput = main.querySelector('#profile-email');
    const phoneInput = main.querySelector('#profile-phone');
    if (!fullnameInput || !emailInput || !phoneInput) return null;
    return { main, fullnameInput, emailInput, phoneInput };
}

function initSettingsProfileForm() {
    const inputs = getSettingsProfileInputs();
    if (!inputs) return;

    const { fullnameInput, emailInput } = inputs;
    const savedName = localStorage.getItem('userFullName');
    const savedEmail = localStorage.getItem('userEmail');

    if (savedName && fullnameInput.value === 'Admin Skeeyzi') {
        fullnameInput.value = savedName;
    }
    if (savedEmail && emailInput.value === 'admin@skeeyzi.com') {
        emailInput.value = savedEmail;
    }

    syncHeaderUserInfo(fullnameInput.value.trim(), emailInput.value.trim());
}

document.addEventListener('click', async function(e) {
    const saveBtn = e.target.closest('#save-settings-btn');
    if (!saveBtn) return;

    const main = document.querySelector('.main-content');
    if (!main || !main.contains(saveBtn)) return;

    const fullnameInput = main.querySelector('#profile-fullname');
    const emailInput = main.querySelector('#profile-email');
    const phoneInput = main.querySelector('#profile-phone');
    if (!fullnameInput || !emailInput || !phoneInput) return;

    const fullName = fullnameInput.value.trim();
    const email = emailInput.value.trim();
    const phone = phoneInput.value.trim();

    if (!fullName || !email) {
        alert('Vui lòng điền Họ tên và Email');
        return;
    }

    syncHeaderUserInfo(fullName, email);

    try {
        const formData = new FormData();
        formData.append('action', 'updateProfile');
        formData.append('fullName', fullName);
        formData.append('email', email);
        formData.append('phone', phone);

        const response = await fetch('/BookMyRoom/api/user.php', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        if (result.success) {
            showSettingsToast('Cập nhật thông tin thành công!', 'success');
            saveBtn.textContent = 'Lưu thay đổi';
        } else {
            showSettingsToast('Lỗi: ' + (result.message || 'Không thể cập nhật'), 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showSettingsToast('Lỗi kết nối: ' + error.message, 'error');
    }
});

document.addEventListener('change', function(e) {
    const t = e.target;
    if (!t.id || !['profile-fullname', 'profile-email', 'profile-phone'].includes(t.id)) return;

    const main = document.querySelector('.main-content');
    if (!main || !main.contains(t)) return;

    const saveBtn = main.querySelector('#save-settings-btn');
    if (saveBtn) {
        saveBtn.textContent = '⚠ Lưu thay đổi';
        saveBtn.style.opacity = '1';
    }
});

// ==================== Helper Functions ====================

/**
 * Handle avatar upload
 */
function handleAvatarUpload(file) {
    console.log('Avatar file selected:', file.name);

    // Validate file size (max 5MB)
    const MAX_SIZE = 5 * 1024 * 1024;
    if (file.size > MAX_SIZE) {
        alert('File quá lớn (max 5MB)');
        return;
    }

    // Preview avatar
    const reader = new FileReader();
    reader.onload = function(e) {
        const avatarImg = document.querySelector('.settings-avatar-img');
        if (avatarImg) {
            avatarImg.src = e.target.result;
        }
    };
    reader.readAsDataURL(file);

    // TODO: Upload file
    // const formData = new FormData();
    // formData.append('avatar', file);
    // uploadAvatar(formData);
}

/**
 * Validate email format
 */
function isValidEmail(email) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
}

/**
 * Get password strength score
 */
function getPasswordStrength(password) {
    let strength = 0;

    if (password.length >= 8) strength++;
    if (password.match(/[a-z]/)) strength++;
    if (password.match(/[A-Z]/)) strength++;
    if (password.match(/[0-9]/)) strength++;
    if (password.match(/[^a-zA-Z0-9]/)) strength++;

    return strength; // 0-5
}

/**
 * Update password strength indicator
 */
function updatePasswordStrengthIndicator(strength) {
    const indicator = document.querySelector('.password-strength-indicator');
    if (!indicator) return;

    const colors = ['', 'red', 'orange', 'yellow', 'lightgreen', 'green'];
    const labels = ['', 'Yếu', 'Trung bình', 'Khá', 'Mạnh', 'Rất mạnh'];

    indicator.style.backgroundColor = colors[strength];
    indicator.textContent = labels[strength];
}

/**
 * Enable save button
 */
function enableSaveButton() {
    const saveBtn = document.querySelector('.settings-save-btn');
    if (saveBtn) {
        saveBtn.disabled = false;
        saveBtn.classList.add('highlight');
    }
}

/**
 * Validate form before saving
 */
function validateForm() {
    console.log('Validating form...');
    
    const emailInput = document.querySelector('input[type="email"]');
    if (emailInput && emailInput.value && !isValidEmail(emailInput.value)) {
        alert('Email không hợp lệ');
        return false;
    }

    const passwordInputs = document.querySelectorAll('input[name="password_new"], input[name="password_confirm"]');
    if (passwordInputs.length >= 2 && (passwordInputs[0].value || passwordInputs[1].value)) {
        if (passwordInputs[0].value !== passwordInputs[1].value) {
            alert('Mật khẩu không khớp');
            return false;
        }
        if (passwordInputs[0].value.length < 8) {
            alert('Mật khẩu phải có ít nhất 8 ký tự');
            return false;
        }
    }

    return true;
}

/**
 * Save settings to server
 */
function saveSettings(formData, callback) {
    console.log('Sending settings to server...');
    // TODO: Gọi API /api/settings/save
}

/**
 * Setup 2FA
 */
function setup2FA() {
    console.log('Setting up 2FA');
    // TODO: Hiển thị modal với QR code để scan
}

/**
 * Disable 2FA
 */
function disable2FA() {
    console.log('Disabling 2FA');
    // TODO: Gọi API để disable 2FA
}

/**
 * Show notification
 */
function showNotification(message, type = 'info') {
    console.log('Notification:', type, message);
    // TODO: Hiển thị toast notification
}

function syncHeaderUserInfo(name, email) {
    if (!name || !email) return;

    localStorage.setItem('userFullName', name);
    localStorage.setItem('userEmail', email);

    const headerUserName = document.querySelector('.user-name');
    const headerUserEmail = document.querySelector('.user-email');

    if (headerUserName) {
        headerUserName.textContent = name;
    }
    if (headerUserEmail) {
        headerUserEmail.textContent = email;
    }
}

function showSettingsToast(message, type = 'info') {
    const toast = document.createElement('div');
    const toastStyles = {
        success: 'background: #d4edda; color: #155724; border: 1px solid #c3e6cb;',
        error: 'background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb;',
        info: 'background: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb;'
    };

    toast.style.cssText = [
        'position: fixed',
        'top: 20px',
        'right: 20px',
        'padding: 15px 20px',
        'border-radius: 4px',
        'font-weight: 500',
        'z-index: 10000',
        'transition: opacity 0.3s ease',
        toastStyles[type] || toastStyles.info
    ].join(';');
    toast.textContent = message;

    document.body.appendChild(toast);
    setTimeout(() => {
        toast.style.opacity = '0';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}
