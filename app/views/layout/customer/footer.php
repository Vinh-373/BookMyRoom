
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BookMyRoom</title>
    <style>
        /* CSS Reset & Cơ bản */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background-color: #fff;
            color: #1a1a1a;
            line-height: 1.5;
        }

        /* Container chính của Footer */
        .footer-wrapper {
            background-color: #f5f5f5; /* Màu nền xám nhạt như hình */
            padding: 40px 0 20px 0;
            width: 100%;
            margin-top: 50px;
        }

        .container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Bố cục các cột link */
        .footer-links {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .footer-column h3 {
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 12px;
            color: #1a1a1a;
        }

        .footer-column ul {
            list-style: none;
        }

        .footer-column ul li {
            margin-bottom: 8px;
        }

        .footer-column ul li a {
            text-decoration: none;
            color: #006ce4; /* Màu xanh link đặc trưng */
            font-size: 13px;
        }

        .footer-column ul li a:hover {
            color: #003b95;
            text-decoration: underline;
        }

        /* Phần Tiền tệ và Quốc gia */
        .footer-settings {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 20px 0;
        }

        .flag-icon {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            object-fit: cover;
        }

        .currency-text {
            font-weight: bold;
            font-size: 14px;
            color: #006ce4;
        }

        /* Đường kẻ ngang mờ */
        hr {
            border: 0;
            border-top: 1px solid #e7e7e7;
            margin-bottom: 20px;
        }

        /* Phần bản quyền và logos */
        .footer-bottom {
            text-align: center;
            font-size: 12px;
            color: #4b4b4b;
        }

        .company-info {
            max-width: 800px;
            margin: 0 auto 15px auto;
        }

        .copyright {
            margin-bottom: 20px;
        }

        /* Logo các đối tác */
        .partner-logos {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 20px;
            flex-wrap: wrap;
            opacity: 0.8;
        }

        .partner-logos img {
            height: 22px;
            filter: grayscale(100%);
            transition: filter 0.3s;
        }

        .partner-logos img:hover {
            filter: grayscale(0%);
        }

        /* Responsive cho Mobile */
        @media (max-width: 600px) {
            .footer-links {
                grid-template-columns: 1fr 1fr;
            }
        }
    </style>
</head>
<body>

    

    <footer class="footer-wrapper">
        <div class="container">
            
            <div class="footer-links">
                <div class="footer-column">
                    <h3>Hỗ trợ</h3>
                    <ul>
                        <li><a href="#">Quản lý các chuyến đi</a></li>
                        <li><a href="#">Dịch vụ Khách hàng</a></li>
                        <li><a href="#">Trung tâm bảo mật</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3>Khám phá thêm</h3>
                    <ul>
                        <li><a href="#">Chương trình Genius</a></li>
                        <li><a href="#">Ưu đãi theo mùa</a></li>
                        <li><a href="#">Bài viết du lịch</a></li>
                        <li><a href="#">BookMyRoom Doanh Nghiệp</a></li>
                        <li><a href="#">Tìm chuyến bay</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3>Điều khoản và cài đặt</h3>
                    <ul>
                        <li><a href="#">Chính sách Bảo mật</a></li>
                        <li><a href="#">Điều khoản dịch vụ</a></li>
                        <li><a href="#">Tranh chấp đối tác</a></li>
                        <li><a href="#">Quyền con người</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3>Dành cho đối tác</h3>
                    <ul>
                        <li><a href="#">Đăng nhập Extranet</a></li>
                        <li><a href="#">Trợ giúp đối tác</a></li>
                        <li><a href="#">Đăng chỗ nghỉ</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3>Về chúng tôi</h3>
                    <ul>
                        <li><a href="#">Về BookMyRoom</a></li>
                        <li><a href="#">Du lịch bền vững</a></li>
                        <li><a href="#">Truyền thông</a></li>
                        <li><a href="#">Cơ hội việc làm</a></li>
                    </ul>
                </div>
            </div>

            <div class="footer-settings">
                <img src="https://flagcdn.com/w40/vn.png" alt="Vietnam" class="flag-icon">
                <span class="currency-text">VND</span>
            </div>

            <hr>

            <div class="footer-bottom">
                <p class="company-info">BookMyRoom là một phần của tập đoàn đứng đầu thế giới về du lịch trực tuyến và các dịch vụ liên quan.</p>
                <p class="copyright">Bản quyền © 1996 - <span id="year"></span> BookMyRoom™. Bảo lưu mọi quyền.</p>
                
                
            </div>
        </div>
    </footer>

    <script>
        // Tự động cập nhật năm hiện tại
        document.getElementById('year').textContent = new Date().getFullYear();
    </script>

</body>
