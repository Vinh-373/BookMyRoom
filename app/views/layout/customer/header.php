
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Header</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/customer/layout/header.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/customer/layout/footer.css">

    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-bootstrap-4/bootstrap-4.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
</head>

<body>

<header class="navbar">
    <div class="logo"><a href="<?= BASE_URL ?>"><strong>BookMyRoom.com</strong></a></div>

    <div class="nav-actions" id="nav-actions">
        <!-- Mặc định chưa login -->
        <span>VND</span>
        <img src="https://flagcdn.com/w20/vn.png" alt="VN">
        <i class="fa-regular fa-question-circle"></i>
        <button class="btn-light" id="btn-login">Đăng nhập / Đăng ký</button>
    </div>
</header>

<script>
    const BASE_URL = "http://localhost/BookMyRoom/";

    document.addEventListener("DOMContentLoaded", async () => {
        const token = localStorage.getItem("token");

        // Nếu chưa có token → giữ nguyên giao diện
        if (!token) return;

        try {
            const res = await fetch(BASE_URL + "auth/me", {
                method: "GET",
                headers: {
                    "Authorization": "Bearer " + token
                }
            });

            // Token sai / hết hạn
            if (res.status === 401) {
                localStorage.removeItem("token");
                return;
            }

            const data = await res.json();

            if (data.status === "success") {
                localStorage.setItem("user", JSON.stringify(data.user)); // Lưu thông tin người dùng
                renderUser(data.user);
            }
            console.log("Fetch /me response:", data);   

        } catch (err) {
            console.error("Lỗi fetch /me:", err);
        }
    });

    function renderUser(user) {
        console.log("Rendering user in header:", user); // Debug log để kiểm tra dữ liệu user nhận được
        const nav = document.getElementById("nav-actions");

        nav.innerHTML = `
            <div><a href="${BASE_URL}booking/confirm">Giỏ hàng</a></div>
            <div><a href="${BASE_URL}history">Lịch sử</a></div>
            <span>VND</span>
            <img src="https://flagcdn.com/w20/vn.png" alt="VN">
            <i class="fa-regular fa-question-circle"></i>

            <div class="user-info" style="display:flex; align-items:center; gap:10px;">
                <span ><a href="${BASE_URL}information">${user.fullName}</a></span>
                <i class="fa-solid fa-user"></i>
                <button onclick="logout()" style="margin-left:10px; font-size:12px;">Logout</button>
            </div>
        `;
    }

   function logout() {
    fetch(BASE_URL + 'auth/logout', {
        method: 'POST'
    })
    .then(res => res.json())
    .then(data => {
        // Xóa local
        localStorage.removeItem("token");
        localStorage.removeItem("user");

        // Redirect về login
        window.location.href = BASE_URL + 'auth/login';
    })
    .catch(err => console.error(err));
}

    // Nút login
    document.addEventListener("click", function(e) {
        if (e.target && e.target.id === "btn-login") {
            window.location.href = BASE_URL + "auth/";
        }
    });
</script>

