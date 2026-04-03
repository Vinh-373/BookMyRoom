<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Lịch sử đặt phòng</title>
</head>
<style>
    /* Reset */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: "Segoe UI", Arial;
        background: #f1f5f9;
        padding: 40px;
        color: #1e293b;
    }

    /* Container */
    .container {
        max-width: 1100px;
        margin: auto;
    }

    /* Header */
    .header {
        margin-bottom: 25px;
    }

    .header h1 {
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .header p {
        color: #64748b;
        font-size: 15px;
    }

    /* ===== CARD STATS (QUAN TRỌNG) ===== */
    .grid {
        display: flex;
        /* FIX: phải dùng flex */
        gap: 20px;
        margin-bottom: 30px;
    }

    .card {
        flex: 1;
        background: #f8fafc;
        border-radius: 15px;
        padding: 20px;
        border: 1px solid #e2e8f0;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    /* Label */
    .card b {
        font-size: 12px;
        color: #64748b;
        text-transform: uppercase;
        margin-bottom: 5px;
    }

    /* Value */
    .card span,
    .card {
        font-size: 18px;
        font-weight: 600;
    }

    /* ===== TABLE ===== */
    .table-container {
        background: #f8fafc;
        border-radius: 15px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    /* Header */
    th {
        padding: 16px;
        font-size: 12px;
        text-transform: uppercase;
        color: #64748b;
        text-align: left;
        border-bottom: 1px solid #e2e8f0;
    }

    /* Row */
    td {
        padding: 18px 16px;
        border-bottom: 1px solid #e2e8f0;
        font-size: 14px;
    }

    tr:hover {
        background: #f1f5f9;
    }

    /* ===== USER ===== */
    .flex {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .avatar {
        width: 36px;
        height: 36px;
        background: #c7d2fe;
        color: #4338ca;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
    }

    /* ===== STATUS BADGE ===== */
    .status {
        padding: 6px 14px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 600;
        border: 1px solid;
    }

    .completed {
        background: #d1fae5;
        color: #047857;
        border-color: #a7f3d0;
    }

    .confirmed {
        background: #dbeafe;
        color: #1d4ed8;
        border-color: #bfdbfe;
    }

    .pending {
        background: #fef3c7;
        color: #b45309;
        border-color: #fde68a;
    }

    .cancelled {
        background: #ffe4e6;
        color: #be123c;
        border-color: #fecdd3;
    }

    /* ===== MONEY ===== */
    td b {
        font-size: 15px;
        font-weight: 700;
    }

    /* ===== BUTTONS ===== */
    button {
        border: none;
        cursor: pointer;
    }

    /* Mail button */
    .btn-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: #e0e7ff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-right: 10px;
        font-size: 16px;
    }

    /* Detail button */
    .btn-detail {
        padding: 8px 18px;
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        color: white;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
    }

    /* ===== DETAIL ROW ===== */
    /* Card từng booking detail */
    .detail-card {
        background: white;
        border-radius: 12px;
        padding: 15px;
        margin-bottom: 15px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
    }

    /* Header */
    .detail-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
    }

    .hotel-name {
        font-weight: 700;
        font-size: 15px;
        color: #1e293b;
    }

    .room-type {
        font-size: 13px;
        color: #6366f1;
        font-weight: 600;
    }

    /* Body grid */
    .detail-body {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
    }

    /* Item */
    .detail-item {
        background: #f8fafc;
        padding: 8px;
        border-radius: 8px;
        font-size: 13px;
        display: flex;
        flex-direction: column;
    }

    .detail-item span {
        color: #64748b;
        font-size: 12px;
    }

    .detail-item b {
        font-size: 14px;
    }

    /* Tổng tiền nổi bật */
    .detail-item.total {
        background: #eef2ff;
        border: 1px solid #6366f1;
    }

    /* REVIEW BUTTON */

    .review-box {
        margin-top: 12px;
        display: flex;
        justify-content: flex-end;
        /* 🔥 đẩy sang phải */
    }

    .btn-review {
        background: linear-gradient(135deg, #f59e0b, #fbbf24);
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 10px;
        font-size: 13px;
        cursor: pointer;
        font-weight: 600;
        transition: 0.2s;
    }

    .btn-review:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    /* trạng thái đã đánh giá */
    .btn-review.disabled {
        background: #9ca3af;
        cursor: not-allowed;
    }

    .modal {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .modal-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 10px;
    }

    /* nút hủy */
    .btn-cancel {
        background: #e5e7eb;
        color: #374151;
        padding: 8px 14px;
        border-radius: 8px;
        font-size: 13px;
        cursor: pointer;
        transition: 0.2s;
    }

    .btn-cancel:hover {
        background: #d1d5db;
    }

    /* nút gửi */
    .btn-submit {
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        color: white;
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 13px;
        cursor: pointer;
        font-weight: 600;
        transition: 0.2s;
    }

    .btn-submit:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .modal-content {
        background: white;
        padding: 20px;
        border-radius: 12px;
        width: 300px;
    }

    textarea {
        width: 100%;
        height: 80px;
        margin: 10px 0;
    }

    .star-container {
        display: flex;
        gap: 5px;
        font-size: 24px;
        cursor: pointer;
    }

    .star-container span {
        color: #d1d5db;
        /* xám */
        transition: 0.2s;
    }

    .star-container span.active {
        color: #f59e0b;
        /* vàng */
    }

    .star-container span:hover {
        transform: scale(1.2);
    }
</style>

<body>
    <?php
    $history = $data['history'] ?? [];
    ?>

    <div class="container">

        <!-- Header -->
        <div class="header">
            <div>
                <h1>📜 Lịch sử đặt phòng</h1>
            </div>
        </div>

        <!-- Stats -->
        <div class="grid">
            <div class="card">
                <b>Tổng hóa đơn của bạn</b><br>
                <span id="totalBookings"></span>
            </div>

            <div class="card">
                <b>Tổng tiền đã chi</b><br>
                <span id="totalMoneySpent"></span>
            </div>
        </div>

        <!-- Table -->
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Tên khách hàng</th>
                        <th>Trạng thái</th>
                        <th>Tổng tiền</th>
                        <th>Ngày đặt</th>
                        <th>Tiền cọc</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody id="tableBody"></tbody>
            </table>
        </div>

    </div>



    <div id="reviewModal" class="modal" style="display:none;">
        <div class="modal-content">
            <h3>Đánh giá trải nghiệm</h3>

            <div id="stars" class="star-container">
                <span data-value="1">☆</span>
                <span data-value="2">☆</span>
                <span data-value="3">☆</span>
                <span data-value="4">☆</span>
                <span data-value="5">☆</span>
            </div>

            <!-- TEXT -->
            <textarea id="reviewText" placeholder="Nhập đánh giá..."></textarea>

            <button class="btn-cancel" onclick="closeReview()">Hủy</button>
            <button class="btn-submit" onclick="submitReview()">Gửi</button>

        </div>
    </div>

    <script>
        const bookings = <?= json_encode($history) ?>;
        console.log(bookings);
        function getStatusClass(status) {
            return {
                COMPLETED: "completed",
                CONFIRMED: "confirmed",
                PENDING: "pending",
                CANCELLED: "cancelled"
            }[status];
        }

        function formatMoney(money) {
            return Number(money).toLocaleString("vi-VN") + "₫";
        }

        function renderTable() {
            const tbody = document.getElementById("tableBody");
            tbody.innerHTML = "";

            bookings.forEach((b, i) => {
                tbody.innerHTML += `
            <tr>
                <td>${i + 1}</td>
                <td>
                    <div class="flex">
                        ${b.fullName}
                    </div>
                </td>
                <td><span class="status ${getStatusClass(b.status)}">${b.status}</span></td>
                <td><b>${formatMoney(b.totalAmount)}</b></td>
                <td>${b.createdAt}</td>
                <td>${formatMoney(b.deposit)}</td>
                <td>
                    <button class="btn-detail" onclick="loadDetail(${b.id}, ${i})">Chi tiết</button>
                </td>
            </tr>

            <!-- ROW DETAIL -->
            <tr id="detail-${i}" class="detail-row" style="display:none;">
                <td colspan="6">
                    <div class="detail-box">
                        ${renderDetails(b.details, b.status)}
                    </div>
                </td>
            </tr>
        `;
            });
        }

     

        function calcTotal() {
            const total = bookings.reduce((sum, b) => sum + Number(b.totalAmount), 0);
            document.getElementById("totalMoney").innerText = formatMoney(total);
        }

        renderTable();
        calcTotalBookings();
        calcTotalMoneySpent();

        function calcTotalMoneySpent() {
            const total = bookings
                .filter(b => b.status === "COMPLETED")
                .reduce((sum, b) => sum + Number(b.totalAmount), 0);

            document.getElementById("totalMoneySpent").innerText = formatMoney(total);
        }

        function calcTotalBookings() {
            const total = bookings.length;
            document.getElementById("totalBookings").innerText = total;
        }

        async function loadDetail(bookingId, index) {
            const row = document.getElementById("detail-" + index);

            // Nếu đã load rồi thì chỉ toggle
            if (row.getAttribute("data-loaded") === "true") {
                toggleRow(row);
                return;
            }

            try {
                const res = await fetch(`<?= URLROOT ?>/history/getBookingDetails/${bookingId}`);
                const data = await res.json();
                console.log(data);

                const booking = bookings[index];

                row.querySelector(".detail-box").innerHTML = renderDetails(data, booking.status);

                row.setAttribute("data-loaded", "true");

                toggleRow(row);
            } catch (err) {
                console.error(err);
                row.querySelector(".detail-box").innerHTML = "<b>Lỗi load dữ liệu</b>";
                row.style.display = "table-row";
            }
        }

        function renderDetails(details, status) {
            if (!details || details.length === 0) {
                return "<i>Không có chi tiết</i>";
            }

            return details.map(d => `
        <div class="detail-card">

            <!-- HEADER -->
            <div class="detail-header">
                <div class="hotel-name">🏨 ${d.hotelName}</div>
                <div class="room-type">${d.roomTypeName}</div>
            </div>

            <!-- BODY -->
            <div class="detail-body">

                <div class="detail-item">
                    <span>📅 Check-in</span>
                    <b>${d.checkIn}</b>
                </div>

                <div class="detail-item">
                    <span>📅 Check-out</span>
                    <b>${d.checkOut}</b>
                </div>

                <div class="detail-item">
                    <span>👥 Số lượng</span>
                    <b>${d.quantity}</b>
                </div>

                <div class="detail-item">
                    <span>🚪 Phòng</span>
                    <b>${d.roomNumber} (Tầng ${d.floor})</b>
                </div>

                <div class="detail-item">
                    <span>📐 Diện tích</span>
                    <b>${d.area} m²</b>
                </div>

                <div class="detail-item">
                    <span>💰 Giá</span>
                    <b>${formatMoney(d.price)}</b>
                </div>

                <div class="detail-item total">
                    <span>💵 Thành tiền</span>
                    <b>${formatMoney(d.amount)}</b>
                </div>
                
            </div>
            <!-- REVIEW BUTTON -->
                ${status === "COMPLETED" ? `
                <div class="review-box">
                    ${d.reviewId
                        ? `<button class="btn-review" style="background:#9ca3af; cursor:not-allowed;">
                            ✓ Đã đánh giá
                        </button>`
                        : `<button class="btn-review" onclick="openReview(${d.id}, ${d.hotelId})">
                            ⭐ Đánh giá
                        </button>`
                    }
                </div>
                ` : ""}

        </div>
    `).join("");
        }

        function toggleRow(row) {
            // đóng tất cả
            document.querySelectorAll(".detail-row").forEach(r => {
                if (r !== row) r.style.display = "none";
            });

            // toggle cái hiện tại
            row.style.display = (row.style.display === "none") ? "table-row" : "none";
        }

        // REVIEW

        let currentDetailId = null;
        let currentHotelId = null;

        function openReview(detailId, hotelId) {
            currentDetailId = detailId;
            currentHotelId = hotelId;
            document.getElementById("reviewModal").style.display = "flex";
            selectedStar = 5; // reset
            setTimeout(initStars, 0); // 🔥 quan trọng (DOM đã render)
        }

        function closeReview() {
            document.getElementById("reviewModal").style.display = "none";
            document.getElementById("reviewText").value = "";
            selectedStar = 5;
            highlightStars(selectedStar);
        }

        function updateReviewedUI(detailId) {
            const btn = document.querySelector(`button[onclick*="openReview(${detailId}"]`);

            if (btn) {
                btn.innerText = "✓ Đã đánh giá";
                btn.style.background = "#9ca3af";
                btn.style.cursor = "not-allowed";
                btn.onclick = null; // 🔥 disable click
            }
        }

        function submitReview() {
            const text = document.getElementById("reviewText").value;

            const formData = new FormData();
            formData.append("userId", 11);// Cần lấy userId thực tế từ session hoặc tham số
            formData.append("bookingDetailId", currentDetailId);
            formData.append("rating", selectedStar);
            formData.append("content", text);
            formData.append("hotelId", currentHotelId);

            console.log({
                detailId: currentDetailId,
                hotelId: currentHotelId,
                rating: selectedStar
            });

            fetch(`<?= URLROOT ?>/history/setReview`, {
                method: "POST",
                body: formData
            })
                .then(res => res.json())
                .then(data => {
                    console.log(data);

                    if (data.success) {
                        Swal.fire('Thành công!', 'Đánh giá thành công!', 'success');
                        closeReview();
                        updateReviewedUI(currentDetailId);
                    } else {
                        Swal.fire('Thất bại!', 'Lưu thất bại!', 'error');
                    }
                })
                .catch(err => {
                    console.error(err);
                    Swal.fire('Lỗi!', 'Server có vấn đề', 'error');
                });
        }

        let selectedStar = 5;

        function initStars() {
            const stars = document.querySelectorAll("#stars span");

            stars.forEach(star => {

                // hover preview
                star.addEventListener("mouseover", () => {
                    const val = star.dataset.value;
                    highlightStars(val);
                });

                // click chọn
                star.addEventListener("click", () => {
                    selectedStar = parseInt(star.dataset.value);
                    highlightStars(selectedStar);
                });
            });

            // reset khi rời chuột
            document.getElementById("stars").addEventListener("mouseleave", () => {
                highlightStars(selectedStar);
            });

            // default 5 sao
            highlightStars(selectedStar);
        }

        function highlightStars(count) {
            const stars = document.querySelectorAll("#stars span");

            stars.forEach(star => {
                star.classList.remove("active");
                if (star.dataset.value <= count) {
                    star.classList.add("active");
                }
            });
        }
      

    </script>
</body>

</html>