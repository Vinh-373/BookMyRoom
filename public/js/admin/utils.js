/**
 * Common Utilities for Admin Pages
 * Chia sẻ các hàm chung cho tất cả các trang admin
 */

// ==================== API Helper Functions ====================

/**
 * Gọi API endpoint
 */
async function apiCall(endpoint, method = 'GET', data = null) {
    try {
        const options = {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        };

        if (data && (method === 'POST' || method === 'PUT' || method === 'PATCH')) {
            options.body = JSON.stringify(data);
        }

        const response = await fetch(`/api${endpoint}`, options);

        if (!response.ok) {
            throw new Error(`API Error: ${response.status}`);
        }

        const result = await response.json();
        return result;
    } catch (error) {
        console.error('API Call Error:', error);
        showNotification(`Lỗi: ${error.message}`, 'error');
        throw error;
    }
}

/**
 * GET request
 */
async function apiGet(endpoint) {
    return apiCall(endpoint, 'GET');
}

/**
 * POST request
 */
async function apiPost(endpoint, data) {
    return apiCall(endpoint, 'POST', data);
}

/**
 * PUT request
 */
async function apiPut(endpoint, data) {
    return apiCall(endpoint, 'PUT', data);
}

/**
 * DELETE request
 */
async function apiDelete(endpoint) {
    return apiCall(endpoint, 'DELETE');
}

// ==================== Notification System ====================

/**
 * Hiển thị toast notification
 */
function showNotification(message, type = 'info', duration = 3000) {
    // Tạo container nếu chưa tồn tại
    let container = document.getElementById('notification-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'notification-container';
        container.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 10px;
        `;
        document.body.appendChild(container);
    }

    // Tạo notification element
    const notification = document.createElement('div');
    const bgColor = {
        'success': '#10b981',
        'error': '#ef4444',
        'warning': '#f59e0b',
        'info': '#3b82f6'
    }[type] || '#3b82f6';

    notification.style.cssText = `
        background-color: ${bgColor};
        color: white;
        padding: 12px 16px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        min-width: 250px;
        animation: slideIn 0.3s ease-out;
    `;
    notification.textContent = message;

    container.appendChild(notification);

    // Auto remove
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease-out';
        setTimeout(() => notification.remove(), 300);
    }, duration);
}

/**
 * Hiển thị confirm dialog
 */
function showConfirmDialog(title, message, onConfirm, onCancel) {
    // Tạo modal
    const modal = document.createElement('div');
    modal.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10000;
    `;

    const content = document.createElement('div');
    content.style.cssText = `
        background: white;
        border-radius: 12px;
        padding: 24px;
        min-width: 320px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.2);
    `;

    content.innerHTML = `
        <h3 style="margin: 0 0 8px 0; font-size: 18px; color: #1f2937;">${title}</h3>
        <p style="margin: 0 0 24px 0; color: #6b7280;">${message}</p>
        <div style="display: flex; gap: 12px; justify-content: flex-end;">
            <button id="cancel-btn" style="padding: 8px 16px; border: 1px solid #d1d5db; border-radius: 6px; background: white; cursor: pointer;">
                Hủy
            </button>
            <button id="confirm-btn" style="padding: 8px 16px; border: none; border-radius: 6px; background: #0059bb; color: white; cursor: pointer;">
                Xác nhận
            </button>
        </div>
    `;

    modal.appendChild(content);
    document.body.appendChild(modal);

    content.querySelector('#confirm-btn').addEventListener('click', () => {
        modal.remove();
        if (onConfirm) onConfirm();
    });

    content.querySelector('#cancel-btn').addEventListener('click', () => {
        modal.remove();
        if (onCancel) onCancel();
    });

    // Close on background click
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.remove();
            if (onCancel) onCancel();
        }
    });
}

/**
 * Hiển thị loading spinner
 */
function showLoading(message = 'Đang tải...') {
    let loader = document.getElementById('loader-container');
    if (!loader) {
        loader = document.createElement('div');
        loader.id = 'loader-container';
        loader.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        `;
        document.body.appendChild(loader);
    }

    loader.innerHTML = `
        <div style="background: white; padding: 24px; border-radius: 12px; text-align: center;">
            <div style="width: 40px; height: 40px; border: 4px solid #e5e7eb; border-top-color: #0059bb; border-radius: 50%; animation: spin 1s linear infinite; margin: 0 auto 12px;"></div>
            <p style="margin: 0; color: #6b7280;">${message}</p>
        </div>
    `;

    loader.style.display = 'flex';
    return loader;
}

/**
 * Ẩn loading spinner
 */
function hideLoading() {
    const loader = document.getElementById('loader-container');
    if (loader) {
        loader.style.display = 'none';
    }
}

// ==================== Table Utilities ====================

/**
 * Format bảng dữ liệu
 */
function formatDataTable(data) {
    // TODO: Convert dữ liệu từ API thành HTML table rows
}

/**
 * Highlight dòng trong bảng
 */
function highlightTableRow(rowElement) {
    rowElement.style.backgroundColor = 'rgba(0, 89, 187, 0.08)';
    setTimeout(() => {
        rowElement.style.backgroundColor = '';
    }, 1500);
}

/**
 * Sort bảng theo cột
 */
function sortTable(tableElement, columnIndex, ascending = true) {
    const tbody = tableElement.querySelector('tbody');
    const rows = Array.from(tbody.querySelectorAll('tr'));

    rows.sort((a, b) => {
        const aValue = a.children[columnIndex].textContent;
        const bValue = b.children[columnIndex].textContent;

        if (!isNaN(aValue) && !isNaN(bValue)) {
            return ascending ? aValue - bValue : bValue - aValue;
        }

        return ascending ? aValue.localeCompare(bValue) : bValue.localeCompare(aValue);
    });

    rows.forEach(row => tbody.appendChild(row));
}

// ==================== Form Utilities ====================

/**
 * Get form data as object
 */
function getFormData(formElement) {
    const formData = new FormData(formElement);
    const data = {};

    formData.forEach((value, key) => {
        if (data.hasOwnProperty(key)) {
            // Nếu key đã tồn tại, convert thành array
            if (!Array.isArray(data[key])) {
                data[key] = [data[key]];
            }
            data[key].push(value);
        } else {
            data[key] = value;
        }
    });

    return data;
}

/**
 * Set form data
 */
function setFormData(formElement, data) {
    Object.keys(data).forEach(key => {
        const field = formElement.querySelector(`[name="${key}"]`);
        if (field) {
            if (field.type === 'checkbox') {
                field.checked = data[key];
            } else if (field.type === 'radio') {
                const radio = formElement.querySelector(`[name="${key}"][value="${data[key]}"]`);
                if (radio) radio.checked = true;
            } else {
                field.value = data[key];
            }
        }
    });
}

/**
 * Clear form
 */
function clearForm(formElement) {
    formElement.reset();
    formElement.querySelectorAll('.error').forEach(el => el.classList.remove('error'));
}

/**
 * Validate email
 */
function validateEmail(email) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
}

/**
 * Validate password
 */
function validatePassword(password) {
    return password && password.length >= 8;
}

// ==================== Date Utilities ====================

/**
 * Format date theo Vietnamese
 */
function formatDateVN(date) {
    return new Intl.DateTimeFormat('vi-VN', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit'
    }).format(new Date(date));
}

/**
 * Format tiền tệ theo Vietnamese
 */
function formatCurrencyVN(value) {
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND',
        minimumFractionDigits: 0
    }).format(value);
}

/**
 * Get date range (today, this week, this month)
 */
function getDateRange(type) {
    const now = new Date();
    let start = new Date();
    let end = new Date();

    switch (type) {
        case 'today':
            start.setHours(0, 0, 0, 0);
            end.setHours(23, 59, 59, 999);
            break;
        case 'week':
            const day = now.getDay();
            start.setDate(now.getDate() - day);
            start.setHours(0, 0, 0, 0);
            end = new Date();
            end.setHours(23, 59, 59, 999);
            break;
        case 'month':
            start.setDate(1);
            start.setHours(0, 0, 0, 0);
            end = new Date();
            end.setHours(23, 59, 59, 999);
            break;
    }

    return { start, end };
}

// ==================== Local Storage Utilities ====================

/**
 * Save settings to localStorage
 */
function saveToStorage(key, value) {
    try {
        localStorage.setItem(key, JSON.stringify(value));
    } catch (e) {
        console.warn('LocalStorage error:', e);
    }
}

/**
 * Get settings from localStorage
 */
function getFromStorage(key) {
    try {
        const value = localStorage.getItem(key);
        return value ? JSON.parse(value) : null;
    } catch (e) {
        console.warn('LocalStorage error:', e);
        return null;
    }
}

/**
 * Remove settings from localStorage
 */
function removeFromStorage(key) {
    localStorage.removeItem(key);
}

// ==================== CSS Animation Keyframes ====================

// Thêm CSS cho animations nếu chưa có
if (!document.querySelector('style[data-app="admin-utils"]')) {
    const style = document.createElement('style');
    style.setAttribute('data-app', 'admin-utils');
    style.textContent = `
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @keyframes fadeOut {
            from {
                opacity: 1;
            }
            to {
                opacity: 0;
            }
        }
    `;
    document.head.appendChild(style);
}

console.log('Admin Utils Loaded');
