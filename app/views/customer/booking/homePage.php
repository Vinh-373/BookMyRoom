<?php

// echo "<pre>";

// print_r($_SESSION ?? 'No role set in session');
// echo "</pre>";
// ?>

<head>
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/customer/booking/index.css">
</head>

<body>
    <style>
        #traveler-dropdown {
            position: absolute;
            top: 80%;
            right: 0%;
        }

        .traveler-dropdown {
            display: none;
            width: 100%;
            background: white;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            border-radius: 8px;
            padding: 20px;
            z-index: 2000;
        }

        .control-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .counter {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .counter button {
            width: 35px;
            height: 35px;
            border: 1px solid var(--primary-color);
            background: #fff;
            color: var(--primary-color);
            font-size: 20px;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-done {
            width: 100%;
            padding: 10px;
            border: 1px solid var(--primary-color);
            background: transparent;
            color: var(--primary-color);
            font-weight: bold;
            cursor: pointer;
            border-radius: 4px;
            margin-top: 10px;

        }

        .search-wrapper {
            position: relative;
        }
    </style>
    <section class="hero-home">
        <h1>Find your next stay</h1>
        <p>Search low prices on hotels, homes and much more...</p>
    </section>

    <form action="/BookMyRoom/search/hotels/1" method="GET" class="search-wrapper">
        <div class="search-box">
            <label><i class="fa-solid fa-bed"></i> Location</label>
            <input class="location" type="text" name="location" placeholder="Where are you going?">
        </div>

        <div class="search-box">
            <label><i class="fa-regular fa-calendar-days"></i> Check-in - Check-out</label>
            <input type="text" id="date-picker" name="dates" placeholder="Add dates" readonly>
        </div>

        <div class="search-box" style="border:none">
            <label><i class="fa-regular fa-user"></i> Travelers</label>
            <input type="text" id="traveler-input" placeholder="2 người lớn · 0 trẻ em · 1 phòng" readonly>

            <div id="traveler-dropdown" class="traveler-dropdown">
                <div class="control-row">
                    <span>Người lớn</span>
                    <div class="counter">
                        <button type="button" onclick="updateQty('adults', -1)">−</button>
                        <span id="val-adults">2</span>
                        <button type="button" onclick="updateQty('adults', 1)">+</button>
                    </div>
                </div>
                <div class="control-row">
                    <span>Trẻ em</span>
                    <div class="counter">
                        <button type="button" onclick="updateQty('children', -1)">−</button>
                        <span id="val-children">0</span>
                        <button type="button" onclick="updateQty('children', 1)">+</button>
                    </div>
                </div>
                <div class="control-row">
                    <span>Phòng</span>
                    <div class="counter">
                        <button type="button" onclick="updateQty('rooms', -1)">−</button>
                        <span id="val-rooms">1</span>
                        <button type="button" onclick="updateQty('rooms', 1)">+</button>
                    </div>
                </div>
                <button type="button" class="btn-done" onclick="closeDropdown()">Xong</button>
            </div>
        </div>

        <button type="submit" class="btn-search">Search</button>
    </form>
    <main class="section-container">

        <section>
            <h2 class="section-title">Điểm đến đang thịnh hành</h2>
            <p class="section-subtitle">Du khách tìm kiếm về Việt Nam cũng đặt chỗ ở những nơi này</p>

            <div class="trending-grid">
                <div class="dest-card large"><a href="http://localhost/BookMyRoom/search/hotels/1?location=Ho+Chi+Minh"><img src="https://media.istockphoto.com/id/1324017792/vi/anh/%E1%BA%A3nh-ch%E1%BB%A5p-t%E1%BB%AB-tr%C3%AAn-cao-tuy%E1%BB%87t-%C4%91%E1%BA%B9p-c%E1%BB%A7a-s%C3%A0i-g%C3%B2n-th%C3%A0nh-ph%E1%BB%91-h%E1%BB%93-ch%C3%AD-minh-v%E1%BB%81-%C4%91%C3%AAm.jpg?s=612x612&w=0&k=20&c=poxrZh-OyNJdMELgQPYzDernnhWf2CW3auY8rxnqj-o=" alt="HCM">
                        <div class="dest-label">
                            Hồ Chí Minh <img src="https://flagcdn.com/w20/vn.png" class="flag" alt="VN">
                        </div>
                    </a>

                </div>
                <div class="dest-card large"><a href="http://localhost/BookMyRoom/search/hotels/1?location=Ha+Giang">
                        <img src="https://vitracotour.com/wp-content/uploads/2023/12/ha-giang-6.jpg" alt="Hà Giang">
                        <div class="dest-label">
                            Hà Giang <img src="https://flagcdn.com/w20/vn.png" class="flag" alt="VN">
                        </div>
                    </a>
                </div>
                <div class="dest-card small">
                    <a href="http://localhost/BookMyRoom/search/hotels/1?location=Ha+Noi"><img src="https://pub-e12fbd31c4784b7e84101a288c658b02.r2.dev/2025/10/hanoi.jpg" alt="Hà Nội">
                        <div class="dest-label">
                            Hà Nội <img src="https://flagcdn.com/w20/th.png" class="flag" alt="TH">
                        </div>
                    </a>

                </div>
                <div class="dest-card small">
                    <a href="http://localhost/BookMyRoom/search/hotels/1?location=Da+Nang">
                        <img src="https://ik.imagekit.io/tvlk/blog/2022/06/ban-do-du-lich-da-nang-10.jpg" alt="Đà Nẵng">
                        <div class="dest-label">
                            Đà Nẵng <img src="https://flagcdn.com/w20/vn.png" class="flag" alt="VN">
                        </div>
                    </a>
                </div>
                <div class="dest-card small">
                    <a href="http://localhost/BookMyRoom/search/hotels/1?location=Lam+Dong">
                        <img src="https://lamdong.gov.vn/sites/sct/gioithieu/SiteAssets/SitePages/quang-truong-lam-vien-da-lat-1591861838819.jpg" alt="Lâm Đồng">
                        <div class="dest-label">
                            Lâm Đồng <img src="https://flagcdn.com/w20/vn.png" class="flag" alt="VN">
                        </div>
                    </a>
                </div>
            </div>
        </section>

        <section>
            <h2 class="section-title">Các khách sạn được đánh giá cao</h2>
            <p class="section-subtitle">Gợi ý những chỗ nghỉ tuyệt vời nhất cho chuyến đi của bạn</p>

            <div class="slider-wrapper">
                <button class="slider-arrow prev" id="prevBtn"><i class="fa-solid fa-chevron-left"></i></button>

                <div class="slider-container">
                    <div class="unique-grid" id="hotelSlider">
                        <?php foreach ($data['hotels'] as $hotel): ?>
                            <div class="prop-card">
                                <a href="http://localhost/BookMyRoom/booking/hotel/<?= $hotel['id'] ?>">
                                    <div class="prop-img-wrapper">
                                        <img src="<?= $hotel['imageUrl'] ?>" alt="<?= $hotel['hotelName'] ?>">
                                    </div>
                                    <div class="prop-content">
                                        <h3 class="prop-title"><?= $hotel['hotelName'] ?></h3>
                                        <p class="prop-location"><?= $hotel['address'] ?>, <?= $hotel['wardName'] ?>, <?= $hotel['cityName'] ?></p>
                                        <div class="prop-rating">
                                            <span class="score"><?= $hotel['rating'] ?></span>
                                            <span class="rating-text">Xuất sắc</span>
                                        </div>
                                        <div class="prop-price">Từ <strong><?= number_format($hotel['minPrice'], 0, ',', '.') ?>₫</strong></div>
                                    </div>
                                </a>
                            </div>
                        <?php endforeach; ?>


                    </div>
                </div>

                <button class="slider-arrow next" id="nextBtn"><i class="fa-solid fa-chevron-right"></i></button>
            </div>
        </section>

    </main>
    <script src="public/js/customer/booking/index.js"></script>
    <script>
        // 1. Khởi tạo chọn ngày (Flatpickr)
        flatpickr("#date-picker", {
            mode: "range",
            minDate: "today",
            dateFormat: "d/m/Y",
            showMonths: 2, // Hiện 2 tháng cùng lúc giống Booking
        });

        // 2. Logic chọn người và phòng
        const travelerInput = document.getElementById('traveler-input');
        const travelerDropdown = document.getElementById('traveler-dropdown');
        let guestData = {
            adults: 2,
            children: 0,
            rooms: 1
        };

        // Đóng/Mở dropdown
        travelerInput.onclick = (e) => {
            e.stopPropagation();
            travelerDropdown.style.display = (travelerDropdown.style.display === 'block') ? 'none' : 'block';
        };

        function closeDropdown() {
            travelerDropdown.style.display = 'none';
        }

        function updateQty(type, delta) {
            const minVal = (type === 'children') ? 0 : 1;
            guestData[type] = Math.max(minVal, guestData[type] + delta);

            // Cập nhật số hiển thị trong dropdown
            document.getElementById(`val-${type}`).innerText = guestData[type];

            // Cập nhật text vào input chính
            travelerInput.value = `${guestData.adults} người lớn · ${guestData.children} trẻ em · ${guestData.rooms} phòng`;
        }

        // Click ra ngoài thì đóng dropdown
        window.onclick = (e) => {
            if (!travelerDropdown.contains(e.target) && e.target !== travelerInput) {
                closeDropdown();
            }
        };
    </script>
</body>

</html>