<?php
echo '<pre>';
print_r($data);
echo '</pre>';

?>

<head>
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/customer/search/hotels.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<style>
    .sidebar {
        background: #fff;
        padding: 24px;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        height: fit-content;
        position: sticky;
        top: 20px;
    }

    .filter-section {
        margin-bottom: 28px;
    }

    .filter-section label {
        display: block;
        margin-bottom: 10px;
        font-size: 15px;
        color: #333;
    }

    .filter-select {
        width: 100%;
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 15px;
    }

    .price-range-wrapper .price-inputs {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 10px;
    }

    .price-input {
        flex: 1;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 8px;
        text-align: center;
    }

    .price-display {
        display: flex;
        justify-content: space-between;
        font-size: 14px;
        color: #006ce4;
        font-weight: 600;
    }

    .checkbox-group label {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 8px;
        font-size: 14.5px;
        cursor: pointer;
    }

    .filter-actions {
        margin-top: 20px;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .btn-apply {
        background-color: #006ce4;
        color: white;
        border: none;
        padding: 14px;
        border-radius: 8px;
        font-weight: bold;
        cursor: pointer;
        font-size: 16px;
    }

    .btn-clear {
        color: #dc3545;
        text-align: center;
        text-decoration: none;
        font-size: 14px;
    }
</style>

<body>

    <section class="hero-home">
        <h1>Find your next stay</h1>
        <p>Search low prices on hotels, homes and much more...</p>
    </section>

    <form action="/BookMyRoom/search/hotels/1" method="GET" class="search-wrapper">
        <div class="search-box">
            <label><i class="fa-solid fa-bed"></i> Location</label>
            <input class="location" type="text" name="location" placeholder="Where are you going?" value="<?= $data['filters']['location'] ?? '' ?>">
        </div>

        <div class="search-box">
            <label><i class="fa-regular fa-calendar-days"></i> Check-in - Check-out</label>
            <input type="text" id="date-picker" name="dates" placeholder="Add dates" readonly value="<?= $data['filters']['dates'] ?? '' ?>">
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

    <main class="main-content">
        <aside class="sidebar">
            <h3>Bộ lọc kết quả</h3>

            <!-- 1. Sắp xếp theo -->
            <div class="filter-section">
                <label><strong>Sắp xếp theo</strong></label>
                <select id="sortBy" class="filter-select">
                    <option value="h.id" <?= ($_GET['sortBy'] ?? 'h.id') === 'h.id' ? 'selected' : '' ?>>Mới nhất</option>
                    <option value="minPrice" <?= ($_GET['sortBy'] ?? '') === 'minPrice' && ($_GET['sortOrder'] ?? '') === 'ASC' ? 'selected' : '' ?>>Giá thấp đến cao</option>
                    <option value="minPrice" <?= ($_GET['sortBy'] ?? '') === 'minPrice' && ($_GET['sortOrder'] ?? 'DESC') === 'DESC' ? 'selected' : '' ?>>Giá cao đến thấp</option>
                    <option value="rating" <?= ($_GET['sortBy'] ?? '') === 'rating' ? 'selected' : '' ?>>Đánh giá cao nhất</option>
                    <option value="hotelName" <?= ($_GET['sortBy'] ?? '') === 'hotelName' ? 'selected' : '' ?>>Tên A → Z</option>
                </select>
            </div>

            <!-- 2. Khoảng giá mỗi đêm -->
            <div class="filter-section">
                <label><strong>Khoảng giá mỗi đêm (VND)</strong></label>
                <div class="price-range-wrapper">
                    <div class="price-inputs">
                        <input type="number" id="priceMin" class="price-input"
                            value="<?= htmlspecialchars($_GET['priceMin'] ?? '100000') ?>"
                            min="0" placeholder="100000">
                        <span class="price-separator">—</span>
                        <input type="number" id="priceMax" class="price-input"
                            value="<?= htmlspecialchars($_GET['priceMax'] ?? '10000000') ?>"
                            min="0" placeholder="10000000">
                    </div>
                    <div class="price-display">
                        <span id="priceMinDisplay"><?= number_format($_GET['priceMin'] ?? 100000) ?>đ</span>
                        <span id="priceMaxDisplay"><?= number_format($_GET['priceMax'] ?? 10000000) ?>đ</span>
                    </div>
                </div>
            </div>

            <!-- 3. Loại phòng ở -->
            <div class="filter-section">
                <label><strong>Loại phòng ở</strong></label>
                <div class="checkbox-group">
                    <label>
                        <input type="checkbox" class="filter-type" value="hotel"
                            <?= strpos($_GET['type'] ?? '', 'hotel') !== false ? 'checked' : '' ?>>
                        Standard
                    </label>
                    <label>
                        <input type="checkbox" class="filter-type" value="resort"
                            <?= strpos($_GET['type'] ?? '', 'resort') !== false ? 'checked' : '' ?>>
                        Family
                    </label>
                    <label>
                        <input type="checkbox" class="filter-type" value="resort"
                            <?= strpos($_GET['type'] ?? '', 'resort') !== false ? 'checked' : '' ?>>
                        Studio
                    </label>
                    <label>
                        <input type="checkbox" class="filter-type" value="resort"
                            <?= strpos($_GET['type'] ?? '', 'resort') !== false ? 'checked' : '' ?>>
                        Penthouse
                    </label>
                    <label>
                        <input type="checkbox" class="filter-type" value="resort"
                            <?= strpos($_GET['type'] ?? '', 'resort') !== false ? 'checked' : '' ?>>
                        Bungalow
                    </label>
                    <label>
                        <input type="checkbox" class="filter-type" value="resort"
                            <?= strpos($_GET['type'] ?? '', 'resort') !== false ? 'checked' : '' ?>>
                        Connecting
                    </label>

                    <label>
                        <input type="checkbox" class="filter-type" value="homestay"
                            <?= strpos($_GET['type'] ?? '', 'homestay') !== false ? 'checked' : '' ?>>
                        Deluxe
                    </label>
                    <label>
                        <input type="checkbox" class="filter-type" value="resort"
                            <?= strpos($_GET['type'] ?? '', 'resort') !== false ? 'checked' : '' ?>>
                        Dorm
                    </label>
                    <label>
                        <input type="checkbox" class="filter-type" value="apartment"
                            <?= strpos($_GET['type'] ?? '', 'apartment') !== false ? 'checked' : '' ?>>
                        Suite
                    </label>
                </div>
            </div>

            <div class="filter-actions">
                <button id="btn-apply-filter" class="btn-apply">
                    <i class="fa-solid fa-filter"></i> Áp dụng bộ lọc
                </button>
                <a href="<?= BASE_URL ?>search/hotels/1<?= !empty($data['filters']['location']) ? '?location=' . urlencode($data['filters']['location']) : '' ?>"
                    class="btn-clear">
                    Xóa tất cả bộ lọc
                </a>
            </div>
        </aside>
        <section class="hotel-results">
            <div id="hotel-list">
                <?php if (!empty($hotels)): ?>
                    <?php foreach ($hotels as $hotel): ?>
                        <div class="hotel-card">
                            <div class="hotel-img" style="height: 200px;">
                                <img src="<?= $hotel['imageUrl'] ?>" alt="<?= $hotel['hotelName'] ?>">
                            </div>
                            <div class="hotel-details">
                                <div class="hotel-name"><?= $hotel['hotelName'] ?></div>
                                <div class="stars">
                                    <?php for ($s = 0; $s < $hotel['rating']; $s++): ?>
                                        <i class="fa-solid fa-star"></i>
                                    <?php endfor; ?>
                                </div>
                                <div class="location">
                                    <i class="fa-solid fa-location-dot"></i> <?= $hotel['address'] ?> - <?= $hotel['wardName'] ?> - <?= $hotel['cityName'] ?>
                                </div>
                                <div class="available-rooms" style="color: <?= ($hotel['availableRooms'] > 0) ? '#28a745' : '#dc3545' ?>; font-weight: bold;">
                                    <?= $hotel['availableRooms'] ?> phòng còn trống
                                </div>
                                <div class="badge">
                                    <span class="score"><?= $hotel['rating'] ?></span>
                                    <span class="rating-text"><strong>Tuyệt vời</strong> · <?= number_format($hotel['rating']) ?> đánh giá</span>
                                </div>
                            </div>
                            <div class="hotel-pricing">
                                <p class="price-label">Giá mỗi đêm từ</p>
                                <p class="price-value"><?= number_format($hotel['minPrice'], 0, ',', '.') ?>đ</p>
                                <p class="price-tax">đã bao gồm thuế và phí</p>
                                <button class="btn-check"
                                    onclick="window.location.href='<?= BASE_URL ?>booking/hotel/<?= $hotel['id'] ?>?dates=<?= urlencode($data['filters']['dates'] ?? '') ?>'">
                                    Kiểm tra phòng
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="no-results" style="text-align: center; padding: 50px; color: #666;">
                        <i class="fa-solid fa-magnifying-glass" style="font-size: 48px; margin-bottom: 15px; color: #ccc;"></i>
                        <p>Rất tiếc, không tìm thấy khách sạn phù hợp với yêu cầu của bạn.</p>
                    </div>
                <?php endif; ?>
            </div>

            <div class="pagination" style="display: flex; justify-content: center; align-items: center; margin: 50px 0; gap: 8px; font-family: sans-serif;">

                <?php if ($currentPage > 1): ?>
                    <a href="javascript:void(0)" onclick="changePage(<?= $currentPage - 1 ?>)"
                        style="display: inline-flex; align-items: center; justify-content: center; min-width: 45px; height: 45px; padding: 0 15px; text-decoration: none; color: #003580; background-color: #f8f9fa; border: 1px solid #e7e7e7; border-radius: 8px; font-size: 20px; font-weight: bold; box-shadow: 0 2px 4px rgba(0,0,0,0.05); transition: 0.3s;">
                        &laquo;
                    </a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <?php
                    $isActive = ($i == $currentPage);
                    $style = "display: inline-flex; align-items: center; justify-content: center; min-width: 45px; height: 45px; padding: 0 10px; text-decoration: none; border-radius: 8px; font-size: 15px; font-weight: 600; transition: 0.3s; box-shadow: 0 2px 4px rgba(0,0,0,0.05); border: 1px solid " . ($isActive ? "#003580" : "#e7e7e7") . "; background-color: " . ($isActive ? "#003580" : "#ffffff") . "; color: " . ($isActive ? "#ffffff" : "#003580") . ";";
                    ?>
                    <a href="javascript:void(0)" onclick="changePage(<?= $i ?>)"
                        style="<?= $style ?>"
                        onmouseover="if(!<?= $isActive ? 'true' : 'false' ?>){this.style.backgroundColor='#f0f6ff'; this.style.transform='translateY(-2px)'}"
                        onmouseout="if(!<?= $isActive ? 'true' : 'false' ?>){this.style.backgroundColor='#ffffff'; this.style.transform='translateY(0)'}">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>

                <?php if ($currentPage < $totalPages): ?>
                    <a href="javascript:void(0)" onclick="changePage(<?= $currentPage + 1 ?>)"
                        style="display: inline-flex; align-items: center; justify-content: center; min-width: 45px; height: 45px; padding: 0 15px; text-decoration: none; color: #003580; background-color: #f8f9fa; border: 1px solid #e7e7e7; border-radius: 8px; font-size: 20px; font-weight: bold; box-shadow: 0 2px 4px rgba(0,0,0,0.05); transition: 0.3s;">
                        &raquo;
                    </a>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <script>
        // 1. Cập nhật hiển thị giá tiền
       

        // 2. Hàm thay đổi trang (Chạy theo Router: search/hotels/number)
        function changePage(pageNumber) {
            // Chúng ta sẽ giữ lại các tham số lọc hiện tại (Query String)
            const urlParams = new URLSearchParams(window.location.search);

            // Xây dựng URL mới: search/hotels/{number}?{filters}
            // Router của bạn sẽ bắt được controller=search, method=hotels, params=[page, pageNumber]
            let newUrl = BASE_URL + 'search/hotels/' + pageNumber;

            // Nếu có các tham số lọc khác (price, stars), đính kèm chúng vào sau
            if (urlParams.toString()) {
                // Loại bỏ param 'url' nếu có để tránh trùng lặp
                urlParams.delete('url');
                newUrl += '?' + urlParams.toString();
            }

            window.location.href = newUrl;
        }

        // // 3. Xử lý nút Áp dụng bộ lọc
        // document.getElementById('btn-apply-filter').addEventListener('click', function() {
        //     const selectedStars = [];
        //     document.querySelectorAll('.filter-star:checked').forEach(cb => {
        //         selectedStars.push(cb.value);
        //     });

        //     // Khi lọc mới, chúng ta mặc định quay về trang 1
        //     let filterUrl = BASE_URL + 'search/hotels/1';
        //     let params = new URLSearchParams();

        //     params.append('price', priceRange.value);
        //     if (selectedStars.length > 0) {
        //         params.append('stars', selectedStars.join(','));
        //     }

        //     window.location.href = filterUrl + '?' + params.toString();
        // });
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
        // Áp dụng bộ lọc
        document.getElementById('btn-apply-filter').addEventListener('click', function() {
            const urlParams = new URLSearchParams(window.location.search);

            // 1. Sắp xếp
            const sortBy = document.getElementById('sortBy').value;
            urlParams.set('sortBy', sortBy);

            if (sortBy === 'minPrice') {
                const isLowToHigh = document.getElementById('sortBy').selectedOptions[0].text.includes('thấp đến cao');
                urlParams.set('sortOrder', isLowToHigh ? 'ASC' : 'DESC');
            } else {
                urlParams.set('sortOrder', 'DESC'); // rating, hotelName, h.id
            }

            
            // 3. Loại chỗ ở
            const selectedTypes = [];
            document.querySelectorAll('.filter-type:checked').forEach(cb => {
                selectedTypes.push(cb.value);
            });

            if (selectedTypes.length > 0) {
                urlParams.set('type', selectedTypes.join(','));
            } else {
                urlParams.delete('type');
            }

            // Reset về trang 1 khi áp dụng bộ lọc mới
            window.location.href = BASE_URL + 'search/hotels/1?' + urlParams.toString();
        });

        
    </script>
</body>