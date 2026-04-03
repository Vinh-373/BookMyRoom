<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nationwide - Đặt phòng khách sạn trực tuyến</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <link rel="stylesheet" href="public/css/customer/booking/index.css">
</head>
<body>

<main class="section-container">
    
    <section>
        <h2 class="section-title">Điểm đến đang thịnh hành</h2>
        <p class="section-subtitle">Du khách tìm kiếm về Việt Nam cũng đặt chỗ ở những nơi này</p>

        <div class="trending-grid">
            <div class="dest-card large">
                <img src="https://media.istockphoto.com/id/1324017792/vi/anh/%E1%BA%A3nh-ch%E1%BB%A5p-t%E1%BB%AB-tr%C3%AAn-cao-tuy%E1%BB%87t-%C4%91%E1%BA%B9p-c%E1%BB%A7a-s%C3%A0i-g%C3%B2n-th%C3%A0nh-ph%E1%BB%91-h%E1%BB%93-ch%C3%AD-minh-v%E1%BB%81-%C4%91%C3%AAm.jpg?s=612x612&w=0&k=20&c=poxrZh-OyNJdMELgQPYzDernnhWf2CW3auY8rxnqj-o=" alt="HCM">
                <div class="dest-label">
                    Hồ Chí Minh <img src="https://flagcdn.com/w20/vn.png" class="flag" alt="VN">
                </div>
            </div>
            <div class="dest-card large">
                <img src="https://vitracotour.com/wp-content/uploads/2023/12/ha-giang-6.jpg" alt="Hà Giang">
                <div class="dest-label">
                    Hà Giang <img src="https://flagcdn.com/w20/vn.png" class="flag" alt="VN">
                </div>
            </div>
            <div class="dest-card small">
                <img src="https://pub-e12fbd31c4784b7e84101a288c658b02.r2.dev/2025/10/hanoi.jpg" alt="Hà Nội">
                <div class="dest-label">
                    Hà Nội <img src="https://flagcdn.com/w20/th.png" class="flag" alt="TH">
                </div>
            </div>
            <div class="dest-card small">
                <img src="https://ik.imagekit.io/tvlk/blog/2022/06/ban-do-du-lich-da-nang-10.jpg" alt="Đà Nẵng">
                <div class="dest-label">
                    Đà Nẵng <img src="https://flagcdn.com/w20/vn.png" class="flag" alt="VN">
                </div>
            </div>
            <div class="dest-card small">
                <img src="https://lamdong.gov.vn/sites/sct/gioithieu/SiteAssets/SitePages/quang-truong-lam-vien-da-lat-1591861838819.jpg" alt="Lâm Đồng">
                <div class="dest-label">
                    Lâm Đồng <img src="https://flagcdn.com/w20/vn.png" class="flag" alt="VN">
                </div>
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
                    <div class="prop-card">
                        <div class="prop-img-wrapper">
                            <img src="https://images.unsplash.com/photo-1510798831971-661eb04b3739?auto=format&fit=crop&w=600">
                        </div>
                        <div class="prop-content">
                            <h3 class="prop-title">Tiny House Dreischwesternherz</h3>
                            <p class="prop-location">Đức, Trier</p>
                            <div class="prop-rating">
                                <span class="score">9.5</span>
                                <span class="rating-text">Xuất sắc</span>
                            </div>
                            <div class="prop-price">Từ <strong>8.266.996₫</strong></div>
                        </div>
                    </div>

                    <div class="prop-card">
                        <div class="prop-img-wrapper">
                            <img src="https://images.unsplash.com/photo-1564013799919-ab600027ffc6?auto=format&fit=crop&w=600">
                        </div>
                        <div class="prop-content">
                            <h3 class="prop-title">Agriturismo Cabrele</h3>
                            <p class="prop-location">Ý, Santorso</p>
                            <div class="prop-rating">
                                <span class="score">9.5</span>
                                <span class="rating-text">Xuất sắc</span>
                            </div>
                            <div class="prop-price">Từ <strong>5.971.831₫</strong></div>
                        </div>
                    </div>

                    <div class="prop-card">
                        <div class="prop-img-wrapper">
                            <img src="https://images.unsplash.com/photo-1449844908441-8829872d2607?auto=format&fit=crop&w=600">
                        </div>
                        <div class="prop-content">
                            <h3 class="prop-title">Carinya Park</h3>
                            <p class="prop-location">Úc, Gembrook</p>
                            <div class="prop-rating">
                                <span class="score">9.4</span>
                                <span class="rating-text">Tuyệt hảo</span>
                            </div>
                            <div class="prop-price">Từ <strong>10.379.319₫</strong></div>
                        </div>
                    </div>

                    <div class="prop-card">
                        <div class="prop-img-wrapper">
                            <img src="https://images.unsplash.com/photo-1480074568708-e7b720bb3f09?auto=format&fit=crop&w=600">
                        </div>
                        <div class="prop-content">
                            <h3 class="prop-title">Gyttja Västergårds</h3>
                            <p class="prop-location">Phần Lan, Nauvo</p>
                            <div class="prop-rating">
                                <span class="score">9.1</span>
                                <span class="rating-text">Tuyệt hảo</span>
                            </div>
                            <div class="prop-price">Từ <strong>7.323.944₫</strong></div>
                        </div>
                    </div>

                    <div class="prop-card">
                        <div class="prop-img-wrapper">
                            <img src="https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=600">
                        </div>
                        <div class="prop-content">
                            <h3 class="prop-title">Mountain View Resort</h3>
                            <p class="prop-location">Thụy Sĩ</p>
                            <div class="prop-rating">
                                <span class="score">9.3</span>
                                <span class="rating-text">Tuyệt hảo</span>
                            </div>
                            <div class="prop-price">Từ <strong>12.000.000₫</strong></div>
                        </div>
                    </div>
                </div>
            </div>

            <button class="slider-arrow next" id="nextBtn"><i class="fa-solid fa-chevron-right"></i></button>
        </div>
    </section>

</main>
<script src="public/js/customer/booking/index.js"></script>
</body>
</html>