<style>
    :root {
        --primary-blue: #003580;
        --accent-blue: #006ce4;
        --yellow-gold: #ffb700;
        --bg-gray: #f5f5f5;
        --text-main: #1a1a1a;
        --text-sub: #6b6b6b;
    }
    
    /* Reuse Header Style */
    .navbar {
        background-color: var(--primary-blue);
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 8%;
    }

    .logo {
        font-size: 20;
    }

    .nav-actions {
        display: flex;
        gap: 20px;
        align-items: center;
    }

    .btn-light {
        background: white;
        color: var(--accent-blue);
        padding: 8px 16px;
        border-radius: 4px;
        font-weight: 600;
        border: none;
        cursor: pointer;
    }

    /* Hero Section Nâng Cao */
    .hero-home {
        background: var(--primary-blue);
        background-size: cover;
        background-position: center;
        padding: 80px 8%;
        color: white;
    }

    .hero-home h1 {
        font-size: 48px;
        margin-bottom: 10px;
        color: white;
    }

    .hero-home p {
        font-size: 20px;
        margin-bottom: 40px;
    }

    /* Search Bar (Giữ nguyên từ trang list để đồng bộ) */
    .search-wrapper {
        background: white;
        padding: 4px;
        border-radius: 8px;
        display: flex;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        border: 4px solid var(--yellow-gold);
        max-width: 1000px;
        margin: -50px auto 0;
    }

    .search-box {
        flex: 1;
        padding: 12px 20px;
        border-right: 1px solid #eee;
        text-align: left;
    }

    .search-box label {
        display: block;
        font-size: 12px;
        color: #666;
        font-weight: 700;
        text-transform: uppercase;
    }

    .search-box input {
        width: 100%;
        border: none;
        outline: none;
        font-size: 16px;
        margin-top: 4px;
        color: #333;
    }

    .btn-search {
        background: var(--accent-blue);
        color: white;
        border: none;
        padding: 0 30px;
        font-weight: bold;
        cursor: pointer;
        border-radius: 4px;
        margin-left: 5px;
    }
</style>
<header class="navbar">
    <div class="logo"><strong>BookMyRoom.com</strong></div>
    <div class="nav-actions">
        <span>VND</span>
        <img src="https://flagcdn.com/w20/vn.png" alt="VN">
        <i class="fa-regular fa-question-circle"></i>
        <button class="btn-light">Đăng nhập / Đăng ký</button>
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