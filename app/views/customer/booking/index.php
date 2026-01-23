<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nationwide - Đặt phòng khách sạn trực tuyến</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        :root {
            --primary-blue: #003580;
            --accent-blue: #006ce4;
            --yellow-gold: #ffb700;
            --bg-gray: #f5f5f5;
            --text-main: #1a1a1a;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Inter', sans-serif; }
        body { background-color: white; color: var(--text-main); line-height: 1.6; }

        /* Reuse Header Style */
        .navbar {
            background-color: var(--primary-blue);
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 8%;
        }
        .nav-links a { color: white; text-decoration: none; margin-left: 25px; font-weight: 500; }
        .btn-login { border: 1px solid white; color: white; background: transparent; padding: 8px 16px; border-radius: 4px; cursor: pointer; }

        /* Hero Section Nâng Cao */
        .hero-home {
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('https://images.unsplash.com/photo-1455587734955-081b22074882?auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
            padding: 100px 8%;
            color: white;
        }
        .hero-home h1 { font-size: 48px; margin-bottom: 10px; }
        .hero-home p { font-size: 20px; margin-bottom: 40px; }

        /* Search Bar (Giữ nguyên từ trang list để đồng bộ) */
        .search-wrapper {
            background: white; padding: 4px; border-radius: 8px; display: flex;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3); border: 4px solid var(--yellow-gold);
            max-width: 1000px; margin: -50px auto 0;
        }
        .search-box { flex: 1; padding: 12px 20px; border-right: 1px solid #eee; text-align: left; }
        .search-box label { display: block; font-size: 12px; color: #666; font-weight: 700; text-transform: uppercase; }
        .search-box input { width: 100%; border: none; outline: none; font-size: 16px; margin-top: 4px; color: #333; }
        .btn-search { background: var(--accent-blue); color: white; border: none; padding: 0 30px; font-weight: bold; cursor: pointer; border-radius: 4px; margin-left: 5px; }

        /* Content Sections */
        .section-container { padding: 60px 8%; }
        .section-title { font-size: 24px; font-weight: 700; margin-bottom: 25px; }

        /* Destinations Grid */
        .dest-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; }
        .dest-card { position: relative; border-radius: 12px; overflow: hidden; height: 250px; cursor: pointer; }
        .dest-card img { width: 100%; height: 100%; object-fit: cover; transition: 0.5s; }
        .dest-card:hover img { transform: scale(1.1); }
        .dest-overlay { position: absolute; bottom: 0; left: 0; width: 100%; padding: 20px; background: linear-gradient(transparent, rgba(0,0,0,0.8)); color: white; }

        /* Promotions Section */
        .promo-banner { background: #ebf3ff; border: 1px solid var(--accent-blue); border-radius: 8px; padding: 20px; display: flex; align-items: center; gap: 20px; }
        .promo-content h4 { color: var(--accent-blue); font-size: 18px; }

        footer { background: var(--primary-blue); color: white; padding: 40px 8%; margin-top: 60px; text-align: center; }
    </style>
</head>
<body>

    <header class="navbar">
        <div class="logo"><strong>Nationwide</strong></div>
        <div class="nav-links">
            <a href="#">Hotels</a>
            <a href="#">Promotions</a>
            <a href="#">For Partners</a>
            <button class="btn-login">Login / Register</button>
        </div>
    </header>

    <section class="hero-home">
        <h1>Find your next stay</h1>
        <p>Search low prices on hotels, homes and much more...</p>
    </section>

    <form action="/hotel/search" method="GET" class="search-wrapper">
        <div class="search-box">
            <label>Location</label>
            <input type="text" name="location" placeholder="Where are you going?" required>
        </div>
        <div class="search-box">
            <label>Check-in - Check-out</label>
            <input type="text" placeholder="Add dates">
        </div>
        <div class="search-box" style="border:none">
            <label>Travelers</label>
            <input type="text" placeholder="2 adults · 0 children · 1 room">
        </div>
        <button type="submit" class="btn-search">Search</button>
    </form>

    <main class="section-container">
        
        <div class="promo-banner">
            <img src="https://cdn-icons-png.flaticon.com/512/2164/2164589.png" width="60" alt="icon">
            <div class="promo-content">
                <h4>Get instant discounts</h4>
                <p>Just sign into your account and look for the blue Genius logo to save</p>
            </div>
        </div>

        <div style="margin-top: 60px;">
            <h2 class="section-title">Trending destinations</h2>
            <div class="dest-grid">
                <div class="dest-card">
                    <img src="https://images.unsplash.com/photo-1493976040374-85c8e12f0c0e?auto=format&fit=crop&w=500&q=60" alt="Tokyo">
                    <div class="dest-overlay"><h3>Tokyo, Japan</h3></div>
                </div>
                <div class="dest-card">
                    <img src="https://images.unsplash.com/photo-1512813195386-6cf811ad3542?auto=format&fit=crop&w=500&q=60" alt="Seoul">
                    <div class="dest-overlay"><h3>Seoul, South Korea</h3></div>
                </div>
                <div class="dest-card">
                    <img src="https://images.unsplash.com/photo-1528127269322-539801943592?auto=format&fit=crop&w=500&q=60" alt="Da Nang">
                    <div class="dest-overlay"><h3>Da Nang, Vietnam</h3></div>
                </div>
                <div class="dest-card">
                    <img src="https://images.unsplash.com/photo-1506929194291-dd074965ad3f?auto=format&fit=crop&w=500&q=60" alt="Phuket">
                    <div class="dest-overlay"><h3>Phuket, Thailand</h3></div>
                </div>
            </div>
        </div>

    </main>

    <footer>
        <p>&copy; 2024 Nationwide Booking. All rights reserved.</p>
    </footer>

</body>
</html>