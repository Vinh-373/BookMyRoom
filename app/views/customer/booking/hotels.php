<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nationwide - Hotel Booking</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        :root {
            --primary-blue: #003580;
            --accent-blue: #006ce4;
            --yellow-gold: #ffb700;
            --bg-gray: #f5f5f5;
            --text-main: #1a1a1a;
            --border-color: #ddd;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Inter', sans-serif; }

        body { background-color: var(--bg-gray); color: var(--text-main); line-height: 1.6; }

        /* Header Navigation */
        .navbar {
            background-color: var(--primary-blue);
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 8%;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .nav-links a { color: white; text-decoration: none; margin-left: 25px; font-weight: 500; font-size: 14px; }
        .btn-login {
            background: transparent;
            border: 1px solid white;
            color: white;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            transition: 0.3s;
        }
        .btn-login:hover { background: rgba(255,255,255,0.1); }

        /* Hero & Search */
        .hero {
            background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
            padding: 60px 8% 100px;
            text-align: center;
            color: white;
        }

        .hero h1 { font-size: 40px; margin-top: 30px; } 

        .search-wrapper {
            background: white;
            padding: 4px;
            border-radius: 8px;
            display: flex;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            border: 4px solid var(--yellow-gold);
            max-width: 1000px;
            margin: -30px auto 0;
        }

        .search-box {
            flex: 1;
            padding: 10px 15px;
            border-right: 1px solid #eee;
            text-align: left;
            color: #333;
        }
        .search-box label { display: block; font-size: 12px; color: #666; font-weight: 600; }
        .search-box input { width: 100%; border: none; outline: none; font-size: 15px; padding-top: 5px; }

        /* Layout Main */
        .main-content {
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 25px;
            padding: 40px 8%;
        }

        /* Sidebar Filter */
        .sidebar { background: white; padding: 20px; border-radius: 8px; border: 1px solid var(--border-color); height: fit-content; }
        .sidebar h3 { font-size: 18px; margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 10px; }
        .filter-section { margin-bottom: 25px; }
        .filter-section label { display: block; font-weight: 600; margin-bottom: 10px; font-size: 14px; }
        
        .price-inputs { display: flex; gap: 10px; margin-top: 10px; }
        .price-inputs input { width: 50%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; }

        .checkbox-group { display: flex; flex-direction: column; gap: 8px; font-size: 14px; }

        /* Hotel Cards */
        .hotel-card {
            background: white;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            display: flex;
            margin-bottom: 20px;
            overflow: hidden;
            transition: transform 0.2s;
        }
        .hotel-card:hover { transform: translateY(-3px); box-shadow: 0 5px 15px rgba(0,0,0,0.1); }

        .hotel-img { width: 320px; position: relative; }
        .hotel-img img { width: 100%; height: 100%; object-fit: cover; }

        .hotel-details { flex: 1; padding: 20px; display: flex; flex-direction: column; }
        .hotel-name { color: var(--accent-blue); font-size: 20px; font-weight: 700; margin-bottom: 5px; cursor: pointer; }
        .stars { color: var(--yellow-gold); font-size: 14px; }
        .location { font-size: 13px; color: var(--accent-blue); margin-bottom: 15px; text-decoration: underline; }
        
        .badge {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: auto;
        }
        .score { background: var(--primary-blue); color: white; padding: 5px 8px; border-radius: 4px; font-weight: bold; }
        .rating-text { font-size: 14px; }

        .hotel-pricing {
            width: 220px;
            padding: 20px;
            border-left: 1px solid #eee;
            text-align: right;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
        }
        .price-label { font-size: 12px; color: #666; }
        .price-value { font-size: 24px; font-weight: 800; }
        .price-tax { font-size: 11px; color: #666; margin-bottom: 15px; }
        
        .btn-check {
            background-color: var(--accent-blue);
            color: white;
            border: none;
            padding: 12px;
            border-radius: 4px;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
        }
        .btn-check:hover { background-color: #005bbd; }

        @media (max-width: 992px) {
            .main-content { grid-template-columns: 1fr; }
            .sidebar { display: none; }
            .hotel-card { flex-direction: column; }
            .hotel-img { width: 100%; height: 200px; }
            .hotel-pricing { width: 100%; border-left: none; border-top: 1px solid #eee; }
        }
    </style>
</head>
<body>

    

    <main class="main-content">
        <aside class="sidebar">
            <h3>Filter Results</h3>
            
            <div class="filter-section">
                <label>Price Range (per night)</label>
                <input type="range" style="width:100%" min="50" max="500" value="200">
                <div class="price-inputs">
                    <input type="text" placeholder="Min">
                    <input type="text" placeholder="Max">
                </div>
            </div>

            <div class="filter-section">
                <label>Star Rating</label>
                <div class="checkbox-group">
                    <label><input type="checkbox"> 5 Stars</label>
                    <label><input type="checkbox" checked> 4 Stars</label>
                    <label><input type="checkbox"> 3 Stars</label>
                </div>
            </div>
            
            <div class="filter-section">
                <label>Amenities</label>
                <div class="checkbox-group">
                    <label><input type="checkbox"> Free WiFi</label>
                    <label><input type="checkbox"> Pool</label>
                    <label><input type="checkbox"> Parking</label>
                </div>
            </div>
        </aside>

        <section class="hotel-results">
            
            <div class="hotel-card">
                <div class="hotel-img">
                    <img src="https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?auto=format&fit=crop&w=500&q=80" alt="Hotel">
                </div>
                <div class="hotel-details">
                    <div class="hotel-name">Sakura Garden Suites</div>
                    <div class="stars">
                        <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                    </div>
                    <div class="location">Chiyoda Ward, Tokyo</div>
                    <div class="badge">
                        <span class="score">9.1</span>
                        <span class="rating-text"><strong>Exceptional</strong> · 1,200 reviews</span>
                    </div>
                </div>
                <div class="hotel-pricing">
                    <p class="price-label">Starting from</p>
                    <p class="price-value">$185</p>
                    <p class="price-tax">includes taxes</p>
                    <button class="btn-check" onclick="window.location.href='/booking/create/1'">Check Availability</button>
                </div>
            </div>

            <div class="hotel-card">
                <div class="hotel-img">
                    <img src="https://images.unsplash.com/photo-1571896349842-33c89424de2d?auto=format&fit=crop&w=500&q=80" alt="Hotel">
                </div>
                <div class="hotel-details">
                    <div class="hotel-name">Business Central Inn</div>
                    <div class="stars">
                        <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                    </div>
                    <div class="location">Shinjuku, Tokyo</div>
                    <div class="badge">
                        <span class="score">8.5</span>
                        <span class="rating-text"><strong>Very Good</strong> · 850 reviews</span>
                    </div>
                </div>
                <div class="hotel-pricing">
                    <p class="price-label">Starting from</p>
                    <p class="price-value">$120</p>
                    <p class="price-tax">includes taxes</p>
                    <button class="btn-check">Check Availability</button>
                </div>
            </div>

        </section>
    </main>

    <script>
        // Ví dụ xử lý sự kiện khi nhấn Check Availability
        document.querySelectorAll('.btn-check').forEach(button => {
            button.addEventListener('click', function() {
                // Bạn có thể thêm logic AJAX hoặc chuyển hướng ở đây
                console.log("Đang kiểm tra phòng...");
            });
        });
    </script>
</body>
</html>