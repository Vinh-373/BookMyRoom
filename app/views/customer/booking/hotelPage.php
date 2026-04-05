<?php
echo '<pre>';
print_r($_SESSION);
echo '</pre>';

?>
<?php
$nights = 1;

if (!empty($data['filters']['dates'])) {
    $parts = explode(' to ', $data['filters']['dates']);

    $checkIn  = DateTime::createFromFormat('d/m/Y', $parts[0]);
    $checkOut = DateTime::createFromFormat('d/m/Y', $parts[1]);

    if ($checkIn && $checkOut) {
        $nights = $checkOut->diff($checkIn)->days;
    }
}
?>


<head>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
</head>
<style>
    :root {
        --primary-blue: #003580;
        --accent-blue: #006ce4;
        --yellow-gold: #ffb700;
        --bg-gray: #f5f5f5;
        --text-main: #1a1a1a;
        --text-sub: #6b6b6b;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        line-height: 1.6;
        color: #333;
        background: #fff;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }

    /* Header */
    header {
        position: sticky;
        top: 0;
        z-index: 1000;
        background: white;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .top-bar {
        border-bottom: 1px solid #e5e7eb;
        padding: 10px 0;
    }

    .top-bar .container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 14px;
        color: #6b7280;
    }

    .top-bar-left {
        display: flex;
        gap: 20px;
        font-size: 14px;

    }

    .top-bar-left a {
        color: #6b7280;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .top-bar-left a:hover {
        color: #2563eb;
    }

    .rating-display {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .star-gold {
        color: #fbbf24;
    }

    .main-nav {
        padding: 20px 0;
    }

    .main-nav .container {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .logo h1 {
        font-size: 28px;
        color: #2563eb;
        margin-bottom: 5px;
    }

    .logo p {
        font-size: 14px;
        color: #6b7280;
    }

    nav {
        display: flex;
        align-items: center;
        gap: 30px;
    }

    nav a {
        color: #374151;
        text-decoration: none;
        transition: color 0.3s;
    }

    nav a:hover {
        color: #2563eb;
    }

    .btn-primary {
        background: #2563eb;
        color: white;
        padding: 10px 24px;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        font-size: 16px;
        transition: background 0.3s;
    }

    .btn-primary:hover {
        background: #1d4ed8;
    }

    .menu-toggle {
        display: none;
        background: none;
        border: none;
        font-size: 24px;
        cursor: pointer;
    }

    .mobile-menu {
        display: none;
        padding: 20px 0;
        border-top: 1px solid #e5e7eb;
    }

    .mobile-menu.active {
        display: block;
    }

    .mobile-menu a {
        display: block;
        padding: 12px 0;
        color: #374151;
        text-decoration: none;
    }

    .gallery-section {
        padding: 30px 0;
    }

    .container {
        gap: 20px;
    }

    .gallery-grid {
        display: grid;
        flex: 6;
        grid-template-columns: repeat(4, 1fr);
        grid-template-rows: repeat(2, 250px);
        gap: 10px;

    }

    .ggmap-location {
        flex: 4;

    }

    /* Item */
    .gallery-item {
        position: relative;
        overflow: hidden;
        border-radius: 8px;
        cursor: pointer;
    }

    /* Ảnh lớn bên trái */
    .gallery-item:first-child {
        grid-column: span 2;
        grid-row: span 2;
    }

    /* Ảnh */
    .gallery-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s;
    }

    .gallery-item:hover img {
        transform: scale(1.1);
    }

    /* Overlay */
    .gallery-overlay {
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, 0.6);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 22px;
        font-weight: bold;
    }

    /* Lightbox */
    .lightbox {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.95);
        z-index: 2000;
        align-items: center;
        justify-content: center;
    }

    .lightbox.active {
        display: flex;
    }

    .lightbox-content {
        max-width: 90%;
        max-height: 90%;
        position: relative;
    }

    .lightbox img {
        max-width: 100%;
        max-height: 90vh;
        object-fit: contain;
    }

    .lightbox-close,
    .lightbox-prev,
    .lightbox-next {
        position: absolute;
        background: rgba(255, 255, 255, 0.1);
        border: none;
        color: white;
        font-size: 24px;
        padding: 15px;
        cursor: pointer;
        border-radius: 50%;
        transition: background 0.3s;
    }

    .lightbox-close:hover,
    .lightbox-prev:hover,
    .lightbox-next:hover {
        background: rgba(255, 255, 255, 0.2);
    }

    .lightbox-close {
        top: 20px;
        right: 20px;
    }

    .lightbox-prev {
        left: 20px;
        top: 50%;
        transform: translateY(-50%);
    }

    .lightbox-next {
        right: 20px;
        top: 50%;
        transform: translateY(-50%);
    }

    .lightbox-caption {
        color: white;
        text-align: center;
        margin-top: 20px;
        font-size: 18px;
    }

    /* Overview Section */
    .overview-section {
        background: #f9fafb;
        padding: 60px 0;
    }

    .overview-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 30px;
    }

    .card {
        background: white;
        border-radius: 8px;
        padding: 30px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .overview-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
    }

    .rating-badge {
        background: #2563eb;
        color: white;
        padding: 15px 20px;
        border-radius: 8px;
        text-align: center;
    }

    .rating-badge .score {
        font-size: 36px;
        font-weight: bold;
    }

    .rating-badge .label {
        font-size: 14px;
    }

    .location {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #6b7280;
        margin-bottom: 15px;
    }

    .stars {
        display: flex;
        gap: 5px;
        margin-bottom: 10px;
    }

    .highlights-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
        margin-top: 20px;
    }

    .highlight-item {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .highlight-item i {
        color: #10b981;
    }

    /* Rooms Section */
    .rooms-section {
        padding: 60px 0;
    }

    .section-header {
        text-align: center;
        margin-bottom: 50px;
    }

    .section-header h2 {
        font-size: 32px;
        margin-bottom: 15px;
    }

    .section-header p {
        color: #6b7280;
        max-width: 700px;
        margin: 0 auto;
    }

    .room-card {
        background: white;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        margin-bottom: 30px;
        overflow: hidden;
        transition: box-shadow 0.3s;
    }

    .room-card:hover {
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    }

    .room-grid {
        display: grid;
        grid-template-columns: 1fr 2fr;
        gap: 0;
    }

    .room-image {
        position: relative;
        height: 100%;
        min-height: 300px;
        overflow: hidden;
    }

    .room-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s;
    }

    .room-card:hover .room-image img {
        transform: scale(1.1);
    }

    .room-gallery {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        grid-template-rows: repeat(2, 1fr);
        height: 100%;
        gap: 5px;
    }

    .room-gallery-item {
        position: relative;
        overflow: hidden;
        cursor: pointer;
    }

    .room-gallery-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s;
    }

    .room-gallery-item:hover img {
        transform: scale(1.1);
    }

    /* overlay giống gallery trên */
    .room-gallery-item .gallery-overlay {
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 18px;
    }

    .badge {
        position: absolute;
        padding: 8px 15px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 500;
    }

    .badge-discount {
        background: #dc2626;
        color: white;
        top: 15px;
        left: 15px;
    }

    .badge-warning {
        background: #ea580c;
        color: white;
        bottom: 15px;
        left: 15px;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .room-info {
        padding: 30px;
    }

    .room-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
    }

    .room-details {
        display: flex;
        gap: 20px;
        margin-bottom: 15px;
        flex-wrap: wrap;
        font-size: 14px;
        color: #6b7280;
    }

    .room-detail-item {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .available-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #d1fae5;
        color: #065f46;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 14px;
    }

    .price-box {
        text-align: right;
    }

    .original-price {
        text-decoration: line-through;
        color: #9ca3af;
        font-size: 14px;
    }

    .current-price {
        font-size: 36px;
        color: #2563eb;
        font-weight: bold;
    }

    .price-label {
        font-size: 14px;
        color: #6b7280;
    }

    .amenities-list {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
        margin-bottom: 20px;
    }

    .amenity-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
    }

    .amenity-item i {
        color: #10b981;
    }

    /* Additional Services */
    .services-section {
        border-top: 1px solid #e5e7eb;
        padding-top: 20px;
        margin-top: 20px;
    }

    .services-toggle {
        width: 100%;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: none;
        border: none;
        font-size: 16px;
        font-weight: 600;
        padding: 0 0 15px 0;
        cursor: pointer;
        text-align: left;
    }

    .services-toggle span {
        color: #2563eb;
        font-size: 14px;
    }

    .services-grid {
        display: none;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
        background: #f9fafb;
        padding: 20px;
        border-radius: 8px;
    }

    .services-grid.active {
        display: grid;
    }

    .service-item {
        display: flex;
        align-items: start;
        gap: 12px;
        padding: 15px;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        background: white;
        cursor: pointer;
        transition: all 0.3s;
    }

    .service-item:hover {
        border-color: #93c5fd;
    }

    .service-item.selected {
        border-color: #2563eb;
        background: #eff6ff;
    }

    .service-item input[type="checkbox"] {
        margin-top: 3px;
        width: 18px;
        height: 18px;
        cursor: pointer;
    }

    .service-info {
        flex: 1;
    }

    .service-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 5px;
    }

    .service-name {
        font-weight: 600;
        font-size: 14px;
    }

    .service-price {
        color: #2563eb;
        font-weight: 600;
        font-size: 14px;
    }

    .service-desc {
        font-size: 12px;
        color: #6b7280;
    }

    .room-actions {
        display: flex;
        gap: 15px;
        margin-top: 20px;
    }

    .btn-book {
        flex: 1;
        background: #2563eb;
        color: white;
        padding: 15px;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        cursor: pointer;
        transition: background 0.3s;
    }

    .btn-book:hover {
        background: #1d4ed8;
    }

    .btn-detail {
        padding: 15px 25px;
        border: 2px solid #2563eb;
        color: #2563eb;
        background: white;
        border-radius: 8px;
        font-size: 16px;
        cursor: pointer;
        transition: background 0.3s;
    }

    .btn-detail:hover {
        background: #eff6ff;
    }

    /* Amenities Section */
    .amenities-section {
        background: #f9fafb;
        padding: 60px 0;
    }

    .amenities-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 30px;
        margin-bottom: 50px;
    }

    .amenity-card {
        background: white;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        text-align: center;
        transition: box-shadow 0.3s;
    }

    .amenity-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .amenity-icon {
        width: 64px;
        height: 64px;
        background: #dbeafe;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
    }

    .amenity-icon i {
        font-size: 32px;
        color: #2563eb;
    }

    .amenity-title {
        font-weight: 600;
        margin-bottom: 10px;
    }

    .amenity-desc {
        font-size: 14px;
        color: #6b7280;
    }

    .amenities-images {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 15px;
    }

    .amenity-image {
        position: relative;
        height: 250px;
        border-radius: 8px;
        overflow: hidden;
    }

    .amenity-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s;
    }

    .amenity-image:hover img {
        transform: scale(1.1);
    }

    .amenity-image-title {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(to top, rgba(0, 0, 0, 0.7), transparent);
        color: white;
        padding: 20px;
        font-size: 18px;
    }

    /* Reviews Section */
    .reviews-section {
        padding: 60px 0;
    }

    .reviews-overview {
        display: grid;
        grid-template-columns: 1fr 2fr;
        gap: 30px;
        margin-bottom: 50px;
    }

    .overall-rating {
        background: #f9fafb;
        border-radius: 8px;
        padding: 40px;
        text-align: center;
    }

    .overall-score {
        font-size: 64px;
        color: #2563eb;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .rating-breakdown {
        background: #f9fafb;
        border-radius: 8px;
        padding: 40px;
    }

    .rating-bar {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 15px;
    }

    .rating-label {
        min-width: 60px;
        font-size: 14px;
    }

    .bar-container {
        flex: 1;
        background: #e5e7eb;
        height: 12px;
        border-radius: 6px;
        overflow: hidden;
    }

    .bar-fill {
        background: #2563eb;
        height: 100%;
        border-radius: 6px;
        transition: width 0.5s;
    }

    .rating-count {
        min-width: 60px;
        text-align: right;
        font-size: 14px;
        color: #6b7280;
    }

    .review-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 25px;
        margin-bottom: 20px;
        transition: box-shadow 0.3s;
    }

    .review-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .review-header {
        display: flex;
        gap: 15px;
        margin-bottom: 15px;
    }

    .review-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
    }

    .review-info {
        flex: 1;
    }

    .review-meta {
        display: flex;
        justify-content: space-between;
        align-items: start;
    }

    .review-name {
        font-weight: 600;
        margin-bottom: 5px;
    }

    .review-room {
        font-size: 14px;
        color: #6b7280;
    }

    .review-rating {
        display: flex;
        gap: 3px;
        margin-bottom: 5px;
    }

    .review-date {
        font-size: 14px;
        color: #9ca3af;
    }

    .review-title {
        font-weight: 600;
        margin-bottom: 10px;
    }

    .review-text {
        color: #374151;
        line-height: 1.6;
        margin-bottom: 15px;
    }

    .review-helpful {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #6b7280;
        font-size: 14px;
        cursor: pointer;
        transition: color 0.3s;
    }

    .review-helpful:hover,
    .review-helpful.active {
        color: #2563eb;
    }

    /* Booking Modal */
    .modal {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 2000;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .modal.active {
        display: flex;
    }

    .modal-content {
        background: white;
        border-radius: 12px;
        max-width: 700px;
        width: 100%;
        max-height: 90vh;
        overflow-y: auto;
    }

    .modal-header {
        position: sticky;
        top: 0;
        background: white;
        border-bottom: 1px solid #e5e7eb;
        padding: 25px 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 24px;
        cursor: pointer;
        padding: 5px;
        border-radius: 50%;
        transition: background 0.3s;
    }

    .modal-close:hover {
        background: #f3f4f6;
    }

    .modal-body {
        padding: 30px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .required {
        color: #dc2626;
    }

    .form-input,
    .form-select,
    .form-textarea {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 16px;
        transition: border-color 0.3s;
    }

    .form-input:focus,
    .form-select:focus,
    .form-textarea:focus {
        outline: none;
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    .form-row {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }

    .info-box {
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        border-radius: 8px;
        padding: 20px;
        margin-top: 20px;
    }

    .info-box h4 {
        color: #1e3a8a;
        margin-bottom: 10px;
    }

    .info-box ul {
        list-style: none;
        font-size: 14px;
        color: #1e40af;
    }

    .info-box li {
        margin-bottom: 5px;
    }

    .modal-actions {
        display: flex;
        gap: 15px;
        margin-top: 30px;
    }

    .btn-cancel {
        flex: 1;
        padding: 15px;
        border: 1px solid #d1d5db;
        background: white;
        border-radius: 8px;
        font-size: 16px;
        cursor: pointer;
        transition: background 0.3s;
    }

    .btn-cancel:hover {
        background: #f9fafb;
    }

    .btn-submit {
        flex: 1;
        padding: 15px;
        background: #2563eb;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        cursor: pointer;
        transition: background 0.3s;
    }

    .btn-submit:hover {
        background: #1d4ed8;
    }

    /* Footer */
    footer {
        background: #111827;
        color: #9ca3af;
        padding: 60px 0 30px;
    }

    .footer-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 40px;
        margin-bottom: 40px;
    }

    .footer-section h3 {
        color: white;
        margin-bottom: 20px;
    }

    .footer-section p {
        font-size: 14px;
        line-height: 1.6;
        margin-bottom: 20px;
    }

    .footer-section ul {
        list-style: none;
    }

    .footer-section ul li {
        margin-bottom: 10px;
    }

    .footer-section a {
        color: #9ca3af;
        text-decoration: none;
        font-size: 14px;
        transition: color 0.3s;
    }

    .footer-section a:hover {
        color: white;
    }

    .social-icons {
        display: flex;
        gap: 15px;
    }

    .social-icon {
        width: 40px;
        height: 40px;
        background: #1f2937;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #9ca3af;
        transition: all 0.3s;
    }

    .social-icon:hover {
        background: #2563eb;
        color: white;
    }

    .contact-item {
        display: flex;
        align-items: start;
        gap: 10px;
        margin-bottom: 15px;
        font-size: 14px;
    }

    .contact-item i {
        margin-top: 3px;
    }

    .footer-bottom {
        border-top: 1px solid #1f2937;
        padding-top: 30px;
        text-align: center;
        font-size: 14px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .top-bar {
            display: none;
        }

        .main-nav nav {
            display: none;
        }

        .menu-toggle {
            display: block;
        }

        .gallery-grid {
            grid-template-columns: 1fr;
            height: auto;
        }

        .gallery-item:first-child {
            grid-column: span 1;
            grid-row: span 1;
            height: 300px;
        }

        .gallery-item {
            height: 200px;
        }

        .overview-grid,
        .room-grid,
        .reviews-overview {
            grid-template-columns: 1fr;
        }

        .amenities-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .amenities-images {
            grid-template-columns: repeat(2, 1fr);
        }

        .footer-grid {
            grid-template-columns: 1fr;
        }

        .form-row,
        .services-grid,
        .amenities-list {
            grid-template-columns: 1fr;
        }

        .room-image {
            min-height: 250px;
        }
    }

    /* Utility */
    .hidden {
        display: none;
    }

    .text-center {
        text-align: center;
    }

    .mb-1 {
        margin-bottom: 0.25rem;
    }

    .mb-2 {
        margin-bottom: 0.5rem;
    }

    .mb-3 {
        margin-bottom: 1rem;
    }

    .mb-4 {
        margin-bottom: 1.5rem;
    }

    .mb-5 {
        margin-bottom: 3rem;
    }

    .mt-1 {
        margin-top: 0.25rem;
    }

    .mt-2 {
        margin-top: 0.5rem;
    }

    .mt-3 {
        margin-top: 1rem;
    }

    h2 {
        font-size: 32px;
        font-weight: 700;
    }

    h3 {
        font-size: 24px;
        font-weight: 600;
    }

    h4 {
        font-size: 18px;
        font-weight: 600;
    }

    .back-to-top {
        position: fixed;
        bottom: 30px;
        right: 30px;
        z-index: 999;

        width: 45px;
        height: 45px;
        border: none;
        border-radius: 50%;

        background: #2563eb;
        color: #fff;
        font-size: 20px;
        cursor: pointer;

        display: none;
        align-items: center;
        justify-content: center;

        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease;
    }

    .back-to-top:hover {
        background: #1d4ed8;
        transform: translateY(-3px);
    }
</style>

<body>
    <button id="backToTopBtn" class="back-to-top">
        ↑
    </button>

    <section class="hero-home">
        <h1>Find your next stay</h1>
        <p>Search low prices on hotels, homes and much more...</p>
    </section>

    <form action="/BookMyRoom/booking/hotel/<?= $data['hotelData']['id'] ?>" method="GET" class="search-wrapper">
        <div class="search-box">
            <label><i class="fa-solid fa-bed"></i> Location</label>
            <input class="location" type="text" name="location" placeholder="Where are you going?" required>
        </div>

        <div class="search-box">
            <label><i class="fa-regular fa-calendar-days"></i> Check-in - Check-out</label>
            <input type="text" id="date-picker" name="dates" placeholder="Add dates" readonly value="<?= $data['filters']['dates'] ?>">
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

    <!-- Header -->
    <header>


        <div class="main-nav">
            <div class="container">
                <div class="logo">
                    <h1><?= $data['hotelData']['hotelName']  ?></h1>
                    <div class="top-bar-left">
                        <a href="tel:+84123456789">
                            <i class="fas fa-phone"></i>
                            <span><?= $data['hotelData']['taxCode']  ?></span>
                        </a>
                        <div>
                            <i class="fas fa-map-marker-alt"></i>
                            <span><?= $data['hotelData']['address'] ?>, <?= $data['hotelData']['wardName'] ?>, <?= $data['hotelData']['cityName'] ?></span>
                        </div>
                    </div>

                </div>

                <nav id="mainNav">
                    <a href="#overview">Tổng quan</a>
                    <a href="#rooms">Phòng & Giá</a>
                    <a href="#amenities">Tiện ích</a>
                    <a href="#reviews">Đánh giá</a>

                </nav>

                <button class="menu-toggle" onclick="toggleMobileMenu()">
                    <i class="fas fa-bars"></i>
                </button>

                <div class="mobile-menu" id="mobileMenu">
                    <a href="#overview" onclick="toggleMobileMenu()">Tổng quan</a>
                    <a href="#rooms" onclick="toggleMobileMenu()">Phòng & Giá</a>
                    <a href="#amenities" onclick="toggleMobileMenu()">Tiện ích</a>
                    <a href="#reviews" onclick="toggleMobileMenu()">Đánh giá</a>
                </div>
            </div>
        </div>
    </header>

    <!-- Gallery Section -->
    <?php
    $images = $data['images'] ?? [];
    $maxShow = 9;
    $totalImages = count($images);
    $showImages = array_slice($images, 0, $maxShow);

    ?>


    <section class="gallery-section">
        <div class=" container-flex container">

            <div class="gallery-grid" style="width: 70%;">
                <?php foreach ($showImages as $index => $img): ?>
                    <div class="gallery-item"
                        onclick="openLightbox(<?= $index ?>)">

                        <img src="<?= $img['imageUrl'] ?? '' ?>" alt="Hotel Image">

                        <!-- Overlay chỉ nằm ở ảnh cuối -->
                        <?php if ($index == $maxShow - 1 && $totalImages > $maxShow): ?>
                            <div class="gallery-overlay"
                                onclick="event.stopPropagation(); openLightbox(<?= $index ?>)">
                                +<?= $totalImages - $maxShow ?> ảnh
                            </div>
                        <?php endif; ?>

                    </div>
                <?php endforeach; ?>
            </div>

            <div class="ggmap-location" style="width: 30%;">
                <div id="map" style="height: 100%;"></div>
            </div>

        </div>
    </section> <!-- Lightbox -->
    <div class="lightbox" id="lightbox">
        <button class="lightbox-close" onclick="closeLightbox()">
            <i class="fas fa-times"></i>
        </button>
        <button class="lightbox-prev" onclick="previousImage()">
            <i class="fas fa-chevron-left"></i>
        </button>
        <button class="lightbox-next" onclick="nextImage()">
            <i class="fas fa-chevron-right"></i>
        </button>
        <div class="lightbox-content">
            <img id="lightboxImage" src="" alt="">
            <p class="lightbox-caption" id="lightboxCaption"></p>
        </div>
    </div>

    <!-- Overview Section -->
    <section class="overview-section" id="overview">
        <div class="container">
            <div class="overview-grid">
                <div class="card">
                    <div class="overview-header">
                        <div>
                            <h2><?= $data['hotelData']['hotelName']  ?></h2>
                            <div class="hotel-location">
                                <i class="fas fa-map-marker-alt"></i>
                                <span><?= $data['hotelData']['address'] ?>, <?= $data['hotelData']['wardName'] ?>, <?= $data['hotelData']['cityName'] ?></span>
                            </div>
                            <div class="stars">
                                <i class="fas fa-star star-gold"></i>
                                <i class="fas fa-star star-gold"></i>
                                <i class="fas fa-star star-gold"></i>
                                <i class="fas fa-star star-gold"></i>
                                <i class="fas fa-star star-gold"></i>
                                <span style="color: #6b7280; margin-left: 10px;">Khách sạn 5 sao</span>
                            </div>
                        </div>
                        <div class="rating-badge">
                            <div class="score"><?= $data['hotelData']['rating'] ?></div>
                            <div class="label">Xuất sắc</div>
                            <div style="font-size: 12px; margin-top: 5px;">1,234 đánh giá</div>
                        </div>
                    </div>

                    <div style="border-top: 1px solid #e5e7eb; padding-top: 20px;">
                        <h3 class="mb-3">Giới thiệu</h3>
                        <p style="color: #374151; line-height: 1.7; margin-bottom: 15px;">
                            <!-- <pre> -->
                            <?= $data['hotelData']['description'] ?>
                            <!-- </pre> -->
                        </p>
                    </div>

                    <div style="border-top: 1px solid #e5e7eb; padding-top: 20px; margin-top: 20px;">
                        <h4 class="mb-3">Điểm nổi bật</h4>
                        <div class="highlights-grid">
                            <div class="highlight-item">
                                <i class="fas fa-check-circle"></i>
                                <span>Vị trí trung tâm, gần Hồ Hoàn Kiếm</span>
                            </div>
                            <div class="highlight-item">
                                <i class="fas fa-check-circle"></i>
                                <span>Wifi tốc độ cao miễn phí</span>
                            </div>
                            <div class="highlight-item">
                                <i class="fas fa-check-circle"></i>
                                <span>Hồ bơi vô cực tầng thượng</span>
                            </div>
                            <div class="highlight-item">
                                <i class="fas fa-check-circle"></i>
                                <span>Nhà hàng đa quốc gia</span>
                            </div>
                            <div class="highlight-item">
                                <i class="fas fa-check-circle"></i>
                                <span>Spa & Massage cao cấp</span>
                            </div>
                            <div class="highlight-item">
                                <i class="fas fa-check-circle"></i>
                                <span>Phòng gym hiện đại 24/7</span>
                            </div>
                            <div class="highlight-item">
                                <i class="fas fa-check-circle"></i>
                                <span>Bãi đỗ xe miễn phí</span>
                            </div>
                            <div class="highlight-item">
                                <i class="fas fa-check-circle"></i>
                                <span>Dịch vụ lễ tân 24/7</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <div class="card mb-4">
                        <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
                            <i class="fas fa-award" style="font-size: 32px; color: #2563eb;"></i>
                            <div>
                                <h4>Giải thưởng</h4>
                                <p style="font-size: 14px; color: #6b7280; margin: 0;">Khách sạn tốt nhất 2025</p>
                            </div>
                        </div>
                        <p style="font-size: 14px; color: #374151;">
                            Được bình chọn là khách sạn hàng đầu tại Hà Nội bởi TripAdvisor
                        </p>
                    </div>

                    <div class="card mb-4">
                        <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
                            <i class="fas fa-clock" style="font-size: 32px; color: #2563eb;"></i>
                            <h4>Giờ nhận/trả phòng</h4>
                        </div>
                        <div style="font-size: 14px;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                                <span style="color: #6b7280;">Nhận phòng:</span>
                                <strong>14:00</strong>
                            </div>
                            <div style="display: flex; justify-content: space-between;">
                                <span style="color: #6b7280;">Trả phòng:</span>
                                <strong>12:00</strong>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
                            <i class="fas fa-coffee" style="font-size: 32px; color: #2563eb;"></i>
                            <h4>Dịch vụ miễn phí</h4>
                        </div>
                        <ul style="list-style: none; font-size: 14px; color: #374151;">
                            <li style="margin-bottom: 8px;">• Wifi tốc độ cao</li>
                            <li style="margin-bottom: 8px;">• Bữa sáng buffet</li>
                            <li style="margin-bottom: 8px;">• Đưa đón sân bay</li>
                            <li>• Bãi đỗ xe</li>
                        </ul>
                    </div>

                    <div class="card" style="background: #eff6ff; border: 1px solid #bfdbfe;">
                        <h4 style="color: #1e3a8a; margin-bottom: 10px;">Liên hệ đặt phòng</h4>
                        <p style="font-size: 14px; color: #1e40af; margin-bottom: 15px;">
                            Gọi ngay để nhận ưu đãi đặc biệt!
                        </p>
                        <a href="tel:+84123456789" class="btn-primary" style="display: block; text-align: center; text-decoration: none;">
                            <?= $data['hotelData']['taxCode']  ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Rooms Section -->
    <section class="rooms-section" id="rooms">
        <div class="container">
            <div class="section-header">
                <h2>Phòng & Giá</h2>
                <p>Chọn phòng phù hợp với nhu cầu của bạn. Tất cả các phòng đều được trang bị đầy đủ tiện nghi hiện đại.</p>

                <?php if (!$data['filters']['dates']): ?>
                    <p style="color: #dd4730;">Vui lòng chọn ngày nhận phòng và ngày trả phòng để xem giá phòng.</p>
                    <button onclick="showDatepicker()" style="background-color: #003580; padding: 10px 20px; border: none; border-radius: 4px; color: white; cursor: pointer;">Chọn ngày</button>
                <?php elseif ($data['filters']['dates']): ?>
                    <p style="color: #10b981; font-weight: bold;font-size: 16px;">Ngày nhận phòng và trả phòng: <?= $data['filters']['dates'] ?></p>
                    <button onclick="showDatepicker()" style="background-color: #003580; padding: 10px 20px; border: none; border-radius: 4px; color: white; cursor: pointer;">Thay đổi</button>
                <?php endif; ?>
            </div>

            <?php foreach ($rooms as $r): ?>

                <div class="room-card">
                    <div class="room-grid">
                        <div class="room-image">
                            <div class="room-gallery">

                                <?php foreach ($r['images'] as $index => $i): ?>

                                    <div class="room-gallery-item" onclick="openLightbox(<?= $index ?>)">
                                        <img src="<?= $i['imageUrl'] ?>" alt="Room Image">

                                        <?php if ($index == 3 && count($r['images']) > 4): ?>
                                            <div class="gallery-overlay">
                                                +<?= count($r['images']) - 4 ?> ảnh
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <?php if ($index == 3) break; ?>

                                <?php endforeach; ?>

                            </div>

                            <div class="badge badge-discount">Giảm 25%</div>
                        </div>

                        <!-- GIỮ NGUYÊN TOÀN BỘ CODE CỦA BẠN -->
                        <div class="room-info">
                            <div class="room-header">
                                <div>
                                    <h3 class="mb-2">Loại phòng: <?= $r['room']['roomTypeName'] ?></h3>
                                    <p style="color: #6b7280; margin-bottom: 15px;">Phòng Deluxe rộng rãi với thiết kế hiện đại, tầm nhìn đẹp ra thành phố.</p>
                                    <div class="room-details">
                                        <div class="room-detail-item">
                                            <i class="fas fa-expand-arrows-alt"></i>
                                            <span><?= $r['room']['area'] ?>m²</span>
                                        </div>
                                        <div class="room-detail-item">
                                            <i class="fas fa-users"></i>
                                            <span><?php
                                                    if (!empty($r['bed'])) {
                                                        echo array_sum(array_column($r['bed'], 'maxPeople'));
                                                    } else {
                                                        echo 'Chưa cập nhật';
                                                    }
                                                    ?> người</span>
                                        </div>
                                        <div class="room-detail-item">
                                            <i class="fas fa-bed"></i>
                                            <span><?php
                                                    if (!empty($r['bed'])) {
                                                        echo implode(', ', array_column($r['bed'], 'name'));
                                                    } else {
                                                        echo 'Chưa cập nhật';
                                                    }
                                                    ?></span>
                                        </div>
                                    </div>
                                    <div class="available-badge">
                                        <i class="fas fa-check-circle"></i>
                                        <span id="available-<?= $r['room']['id'] ?>"><?= $r['room']['availableRooms'] ?> phòng còn trống</span>
                                        <span style="display: none;"
                                            id="physical-<?= $r['room']['id'] ?>">
                                            <?= is_array($r['room']['availablePhysicalRoomIds'])
                                                ? implode(', ', $r['room']['availablePhysicalRoomIds'])
                                                : $r['room']['availablePhysicalRoomIds'] ?? 'Không có phòng trống' ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="price-box">
                                    <div class="original-price">2,000,000₫</div>
                                    <div class="current-price">
                                        <?= number_format($r['room']['basePrice'] * $nights, 0, ',', '.') ?> ₫
                                    </div>
                                    <div class="price-label"><?= $nights ?> đêm</div>
                                    <div class="service-count" data-room="room1" style="font-size: 12px; color: #c73518; margin-top: 5px;"> 0 dịch vụ kèm theo</div>
                                </div>
                            </div>

                            <!-- PHẦN DƯỚI GIỮ NGUYÊN -->
                            <div style="border-top: 1px solid #e5e7eb; padding-top: 20px;">
                                <h4 class="mb-3">Tiện nghi phòng:</h4>
                                <div class="amenities-list">
                                    <div class="amenity-item">
                                        <i class="fas fa-check-circle"></i>
                                        <span>Wifi miễn phí</span>
                                    </div>
                                    <div class="amenity-item">
                                        <i class="fas fa-check-circle"></i>
                                        <span>TV màn hình phẳng</span>
                                    </div>
                                    <div class="amenity-item">
                                        <i class="fas fa-check-circle"></i>
                                        <span>Điều hòa</span>
                                    </div>
                                    <div class="amenity-item">
                                        <i class="fas fa-check-circle"></i>
                                        <span>Minibar</span>
                                    </div>
                                    <div class="amenity-item">
                                        <i class="fas fa-check-circle"></i>
                                        <span>Két an toàn</span>
                                    </div>
                                    <div class="amenity-item">
                                        <i class="fas fa-check-circle"></i>
                                        <span>Phòng tắm riêng</span>
                                    </div>
                                </div>



                                <div class="room-actions">
                                    <div class="flex items-center gap-2">
                                        <!-- Nút giảm -->
                                        <button onclick="changeQty(<?= $r['room']['roomConfigId'] ?>, -1,)"
                                            class="px-3 py-1 border rounded">-</button>

                                        <!-- Hiển thị số lượng -->
                                        <input
                                            id="qty-<?= $r['room']['roomConfigId'] ?>"
                                            type="text"
                                            value="1"
                                            readonly
                                            class="w-10 text-center border rounded" />

                                        <!-- Nút tăng -->
                                        <button onclick="changeQty(<?= $r['room']['roomConfigId'] ?>, 1, <?= $r['room']['availableRoomss'] ?>)"
                                            class="px-3 py-1 border rounded">+</button>
                                    </div>

                                    <button class="btn-book"
                                        onclick="bookingRoom(<?= $r['room']['roomConfigId'] ?>)">
                                        Đặt phòng ngay
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            <?php endforeach; ?>


        </div>
    </section>

    <!-- Amenities Section -->
    <section class="amenities-section" id="amenities">
        <div class="container">
            <div class="section-header">
                <h2>Tiện Ích Khách Sạn</h2>
                <p>Trải nghiệm đầy đủ các tiện ích cao cấp được thiết kế để mang lại sự thoải mái tối đa</p>
            </div>

            <div class="amenities-grid">
                <div class="amenity-card">
                    <div class="amenity-icon">
                        <i class="fas fa-wifi"></i>
                    </div>
                    <div class="amenity-title">Wifi tốc độ cao</div>
                    <div class="amenity-desc">Miễn phí trong toàn bộ khách sạn</div>
                </div>

                <div class="amenity-card">
                    <div class="amenity-icon">
                        <i class="fas fa-swimming-pool"></i>
                    </div>
                    <div class="amenity-title">Hồ bơi vô cực</div>
                    <div class="amenity-desc">Tầng thượng với view tuyệt đẹp</div>
                </div>

                <div class="amenity-card">
                    <div class="amenity-icon">
                        <i class="fas fa-utensils"></i>
                    </div>
                    <div class="amenity-title">Nhà hàng</div>
                    <div class="amenity-desc">Ẩm thực Á - Âu cao cấp</div>
                </div>

                <div class="amenity-card">
                    <div class="amenity-icon">
                        <i class="fas fa-dumbbell"></i>
                    </div>
                    <div class="amenity-title">Phòng Gym</div>
                    <div class="amenity-desc">Trang thiết bị hiện đại 24/7</div>
                </div>

                <div class="amenity-card">
                    <div class="amenity-icon">
                        <i class="fas fa-spa"></i>
                    </div>
                    <div class="amenity-title">Spa & Massage</div>
                    <div class="amenity-desc">Dịch vụ chăm sóc sức khỏe</div>
                </div>

                <div class="amenity-card">
                    <div class="amenity-icon">
                        <i class="fas fa-cocktail"></i>
                    </div>
                    <div class="amenity-title">Quầy Bar</div>
                    <div class="amenity-desc">Đồ uống cao cấp</div>
                </div>

                <div class="amenity-card">
                    <div class="amenity-icon">
                        <i class="fas fa-car"></i>
                    </div>
                    <div class="amenity-title">Đỗ xe miễn phí</div>
                    <div class="amenity-desc">Bãi đỗ xe rộng rãi, an toàn</div>
                </div>

                <div class="amenity-card">
                    <div class="amenity-icon">
                        <i class="fas fa-briefcase"></i>
                    </div>
                    <div class="amenity-title">Phòng họp</div>
                    <div class="amenity-desc">Trang thiết bị hiện đại</div>
                </div>

                <div class="amenity-card">
                    <div class="amenity-icon">
                        <i class="fas fa-baby"></i>
                    </div>
                    <div class="amenity-title">Dịch vụ trẻ em</div>
                    <div class="amenity-desc">Khu vui chơi & giữ trẻ</div>
                </div>

                <div class="amenity-card">
                    <div class="amenity-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <div class="amenity-title">An ninh 24/7</div>
                    <div class="amenity-desc">Camera giám sát toàn bộ</div>
                </div>

                <div class="amenity-card">
                    <div class="amenity-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="amenity-title">Lễ tân 24/7</div>
                    <div class="amenity-desc">Hỗ trợ mọi lúc mọi nơi</div>
                </div>

                <div class="amenity-card">
                    <div class="amenity-icon">
                        <i class="fas fa-concierge-bell"></i>
                    </div>
                    <div class="amenity-title">Concierge</div>
                    <div class="amenity-desc">Tư vấn du lịch chuyên nghiệp</div>
                </div>
            </div>

            <div class="amenities-images">
                <div class="amenity-image">
                    <img src="https://images.unsplash.com/photo-1534612899740-55c821a90129?w=1080&q=80" alt="Hồ bơi vô cực">
                    <div class="amenity-image-title">Hồ bơi vô cực</div>
                </div>
                <div class="amenity-image">
                    <img src="https://images.unsplash.com/photo-1604161926875-bb58f9a0d81b?w=1080&q=80" alt="Spa cao cấp">
                    <div class="amenity-image-title">Spa cao cấp</div>
                </div>
                <div class="amenity-image">
                    <img src="https://images.unsplash.com/photo-1660557989710-1a91e7e89d1c?w=1080&q=80" alt="Phòng gym">
                    <div class="amenity-image-title">Phòng gym</div>
                </div>
                <div class="amenity-image">
                    <img src="https://images.unsplash.com/photo-1640108930193-76941e385e5e?w=1080&q=80" alt="Nhà hàng">
                    <div class="amenity-image-title">Nhà hàng</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Reviews Section -->
    <section class="reviews-section" id="reviews">
        <div class="container">
            <div class="section-header">
                <h2>Đánh Giá Từ Khách Hàng</h2>
                <p>Xem những đánh giá chân thực từ khách hàng đã trải nghiệm dịch vụ của chúng tôi</p>
            </div>

            <div class="reviews-overview">
                <div class="overall-rating">
                    <div class="overall-score">4.8</div>
                    <div class="stars mb-2">
                        <i class="fas fa-star star-gold"></i>
                        <i class="fas fa-star star-gold"></i>
                        <i class="fas fa-star star-gold"></i>
                        <i class="fas fa-star star-gold"></i>
                        <i class="fas fa-star star-gold"></i>
                    </div>
                    <p style="color: #6b7280; margin-bottom: 5px;">
                        <?= $data['hotelData']['rating'] >= 4.5 ? 'Xuất sắc' : ($data['hotelData']['rating'] >= 4.0 ? 'Rất tốt' : 'Tốt') ?>

                    </p>
                    <p style="color: #374151;">Dựa trên <strong><?= count($data['reviews']) ?></strong> đánh giá</p>
                </div>

                <div class="rating-breakdown">
                    <h3 class="mb-4">Phân bổ đánh giá</h3>
                    <div class="rating-bar">
                        <div class="rating-label">5 <i class="fas fa-star star-gold"></i></div>
                        <div class="bar-container">
                            <div class="bar-fill" style="width: 69%"></div>
                        </div>
                        <div class="rating-count"><?= array_filter($data['reviews'], function ($review) {
                                                        return $review['rating'] == 5;
                                                    }) ? count(array_filter($data['reviews'], function ($review) {
                                                        return $review['rating'] == 5;
                                                    })) : 0 ?></div>
                    </div>
                    <div class="rating-bar">
                        <div class="rating-label">4 <i class="fas fa-star star-gold"></i></div>
                        <div class="bar-container">
                            <div class="bar-fill" style="width: 23%"></div>
                        </div>
                        <div class="rating-count"><?= array_filter($data['reviews'], function ($review) {
                                                        return $review['rating'] == 4;
                                                    }) ? count(array_filter($data['reviews'], function ($review) {
                                                        return $review['rating'] == 4;
                                                    })) : 0 ?></div>
                    </div>
                    <div class="rating-bar">
                        <div class="rating-label">3 <i class="fas fa-star star-gold"></i></div>
                        <div class="bar-container">
                            <div class="bar-fill" style="width: 6%"></div>
                        </div>
                        <div class="rating-count"><?= array_filter($data['reviews'], function ($review) {
                                                        return $review['rating'] == 3;
                                                    }) ? count(array_filter($data['reviews'], function ($review) {
                                                        return $review['rating'] == 3;
                                                    })) : 0 ?></div>
                    </div>
                    <div class="rating-bar">
                        <div class="rating-label">2 <i class="fas fa-star star-gold"></i></div>
                        <div class="bar-container">
                            <div class="bar-fill" style="width: 1.5%"></div>
                        </div>
                        <div class="rating-count"><?= array_filter($data['reviews'], function ($review) {
                                                        return $review['rating'] == 2;
                                                    }) ? count(array_filter($data['reviews'], function ($review) {
                                                        return $review['rating'] == 2;
                                                    })) : 0 ?></div>
                    </div>
                    <div class="rating-bar">
                        <div class="rating-label">1 <i class="fas fa-star star-gold"></i></div>
                        <div class="bar-container">
                            <div class="bar-fill" style="width: 0.5%"></div>
                        </div>
                        <div class="rating-count"><?= array_filter($data['reviews'], function ($review) {
                                                        return $review['rating'] == 1;
                                                    }) ? count(array_filter($data['reviews'], function ($review) {
                                                        return $review['rating'] == 1;
                                                    })) : 0 ?></div>
                    </div>
                </div>
            </div>

            <!-- Review Cards -->

            <?php foreach ($data['reviews'] as $rv): ?>

                <div class="review-card">
                    <div class="review-header">
                        <img
                            src="<?= $rv['avatarUrl'] ?: 'data:image/jpeg;base64,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' ?>"
                            alt="Avatar"
                            class="review-avatar">

                        <div class="review-info">
                            <div class="review-meta">
                                <div>
                                    <div class="review-name"><?= $rv['fullName'] ?></div>

                                </div>

                                <div style="text-align: right;">
                                    <!-- ⭐ RATING -->
                                    <div class="review-rating">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <?php if ($i <= $rv['rating']): ?>
                                                <i class="fas fa-star star-gold"></i>
                                            <?php else: ?>
                                                <i class="far fa-star" style="color:#d1d5db;"></i>
                                            <?php endif; ?>
                                        <?php endfor; ?>
                                    </div>

                                    <!-- DATE -->
                                    <div class="review-date">
                                        <?= date('d/m/Y', strtotime($rv['createdAt'])) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TITLE -->
                    <div class="review-title">
                        <?= $rv['rating'] >= 4 ? 'Trải nghiệm tuyệt vời!' : 'Khá ổn' ?>
                    </div>

                    <!-- CONTENT -->
                    <div class="review-text">
                        <?= $rv['content'] ?>
                    </div>
                </div>

            <?php endforeach; ?>



            <div class="text-center mt-4">
                <button class="btn-detail">Xem thêm đánh giá</button>
            </div>
        </div>
    </section>

    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <script>
        const baseUrl = "<?= BASE_URL ?>";
        /*===============form search========== */
        const hotelData = <?= json_encode($hotelData) ?>;
        const locationInput = document.querySelector('.location'); // Đừng quên dấu chấm trước tên class

        if (locationInput) {
            locationInput.value = hotelData['hotelName'];
        }



        /* ================== LIGHTBOX ================== */


        const galleryImages = <?= json_encode($images) ?>




        let currentLightboxIndex = 0;

        function openLightbox(index) {
            currentLightboxIndex = index;
            document.getElementById('lightbox').classList.add('active');
            updateLightboxImage();
        }

        function updateLightboxImage() {
            if (!galleryImages || galleryImages.length === 0) return;

            const img = galleryImages[currentLightboxIndex];

            document.getElementById('lightboxImage').src = img.imageUrl;
            document.getElementById('lightboxCaption').textContent =
                `Ảnh ${currentLightboxIndex + 1}/${galleryImages.length}`;
        }

        function nextImage() {
            currentLightboxIndex = (currentLightboxIndex + 1) % galleryImages.length;
            updateLightboxImage();
        }

        function previousImage() {
            currentLightboxIndex = (currentLightboxIndex - 1 + galleryImages.length) % galleryImages.length;
            updateLightboxImage();
        }

        function closeLightbox() {
            document.getElementById('lightbox').classList.remove('active');
        }

        /* click nền để đóng */
        document.addEventListener('DOMContentLoaded', () => {
            const lightbox = document.getElementById('lightbox');
            if (lightbox) {
                lightbox.addEventListener('click', function(e) {
                    if (e.target === this) closeLightbox();
                });
            }
        });

        /* keyboard */
        document.addEventListener('keydown', function(e) {
            const lightbox = document.getElementById('lightbox');
            if (lightbox && lightbox.classList.contains('active')) {
                if (e.key === 'ArrowLeft') previousImage();
                if (e.key === 'ArrowRight') nextImage();
                if (e.key === 'Escape') closeLightbox();
            }
        });

        /* ================== UI ================== */
        function toggleMobileMenu() {
            document.getElementById('mobileMenu')?.classList.toggle('active');
        }

        function toggleServices(roomId) {
            const grid = document.getElementById(`services-${roomId}`);
            if (!grid) return;

            const btn = grid.previousElementSibling;
            const icon = btn.querySelector('.toggle-icon');

            grid.classList.toggle('active');
            icon.textContent = grid.classList.contains('active') ? 'Thu gọn ▲' : 'Xem thêm ▼';
        }

        /* ================== BACK TO TOP ================== */
        const backToTopBtn = document.getElementById("backToTopBtn");

        window.addEventListener("scroll", function() {
            if (backToTopBtn) {
                backToTopBtn.style.display = window.scrollY > 300 ? "flex" : "none";
            }
        });

        backToTopBtn?.addEventListener("click", function() {
            window.scrollTo({
                top: 0,
                behavior: "smooth"
            });
        });

        /* ================== MAP (LEAFLET) ================== */
        document.addEventListener('DOMContentLoaded', function() {
            initServices();

            // 🔥 chỉ chạy khi Leaflet đã load
            if (typeof L !== "undefined") {
                const lat = 10.777747;
                const lng = 106.698902;
                const hotelName = "Khách sạn ABC";

                const map = L.map('map').setView([lat, lng], 15);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap'
                }).addTo(map);

                L.marker([lat, lng])
                    .addTo(map)
                    .bindPopup(hotelName)
                    .openPopup();
            } else {
                console.error("Leaflet chưa load!");
            }
        });
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

        function showDatepicker() {
            window.scrollTo({
                top: document.getElementById('date-picker').offsetTop - 100,
                behavior: 'smooth'
            });
            document.getElementById('date-picker').focus();
        }

        function bookingRoom(roomConfigId) {
            const dateInput = document.getElementById('date-picker');
            const dates = dateInput?.value;
            const availableRooms = parseInt(document.getElementById(`available-${roomConfigId}`).innerText);
            const availablePhysicalRoomIds = document.getElementById(`physical-${roomConfigId}`).innerText;
           

            // ✅ 1. Check ngày
            if (!dates) {
                Swal.fire({
                    title: 'Chưa chọn ngày!',
                    text: 'Vui lòng chọn ngày nhận và trả phòng',
                    icon: 'warning',
                    confirmButtonText: 'Chọn ngay'
                }).then(result => {
                    if (result.isConfirmed) {
                        dateInput.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                        dateInput.focus();
                    }
                });
                return;
            }

            // ✅ 2. Tách ngày
            const [checkIn, checkOut] = dates.split(" to ");

            if (!checkIn || !checkOut) {
                Swal.fire({
                    title: 'Ngày không hợp lệ!',
                    text: 'Vui lòng chọn lại khoảng ngày',
                    icon: 'error'
                });
                return;
            }

            // ✅ 3. Lấy số lượng
            const qtyInput = document.getElementById(`qty-${roomConfigId}`);
            const quantity = parseInt(qtyInput?.value || 1);

            if (quantity < 1) {
                Swal.fire({
                    title: 'Số lượng không hợp lệ!',
                    icon: 'error'
                });
                return;
            }

            // ✅ 4. Check token
            const token = localStorage.getItem("token");
            if (!token) {
                return handleNotLogin();
            }
// alert('jdhdhdj'+ token)
            // ✅ 5. Check login
            fetch(`${baseUrl}auth/me`, {
                    method: 'GET',
                    headers: {
                        'Authorization': `Bearer ${token}`
                    }
                })
                .then(res => {
                    if (!res.ok) throw new Error("NOT_LOGIN");
                    return res.json();
                })
                .then(data => {
                    if (data.status !== 'success') {
                        throw new Error("NOT_LOGIN");
                    }
                                                console.log(data)
                    // ✅ 6. Gọi create (lưu session)
                    return fetch(`${baseUrl}booking/create`, {
                        method: 'POST',
                        credentials: 'include', // 🔥 để giữ session
                        headers: {
                            'Content-Type': 'application/json',
                            'Authorization': `Bearer ${token}`
                        },
                        body: JSON.stringify({
                            roomConfigId,
                            checkIn,
                            checkOut,
                            quantity,
                            availableRooms,
                            availablePhysicalRoomIds,
                            action: 'add'
                        })
                    });
                })
                .then(res => {
                    if (!res.ok) throw new Error("CREATE_FAIL");
                    return res.json();
                })
                .then(data => {
                    if (data.status !== 'success') {
                        throw new Error("CREATE_FAIL");
                    }
                                                console.log(data)

                    // ✅ 7. Redirect sau khi OK
                    window.location.href = `${baseUrl}booking/confirm`;
                })
                .catch(err => {
                    if (err.message === "NOT_LOGIN") {
                        handleNotLogin();
                    } else {
                        console.error(err);
                        Swal.fire({
                            title: 'Lỗi!',
                            text: 'Không thể xử lý đặt phòng',
                            icon: 'error'
                        });
                    }
                });
        }

        function handleNotLogin() {
            Swal.fire({
                title: 'Cần đăng nhập!',
                text: 'Bạn cần đăng nhập để đặt phòng',
                icon: 'warning',
                confirmButtonText: 'Đăng nhập ngay',
                allowOutsideClick: false
            }).then(result => {
                if (result.isConfirmed) {
                    const path = window.location.pathname + window.location.search;
                    const redirect = path.replace("/BookMyRoom/", "");

                    window.location.href =
                        `${baseUrl}auth/login?redirect=${encodeURIComponent(redirect)}`;
                }
            });
        }

        function changeQty(roomId, delta, max) {
            const input = document.getElementById(`qty-${roomId}`);
            let value = parseInt(input.value);

            value += delta;

            // ✅ giới hạn tối thiểu = 1
            if (value < 1) value = 1;

            // (tuỳ chọn) giới hạn tối đa
            if (value > max) {
                value = max;
                this.disabled = true;
                Swal.fire({
                    title: 'Thông báo?',
                    text: 'Số lượng bạn chọn và số lượng trong giỏ hàng đã vượt quá số phòng trống còn lại trong khoảng ngày tương ứng',
                    icon: 'warning',

                    timeout: 5000,
                });

            } else {
                this.disabled = false;
            }

            input.value = value;
        }
    </script>
</body>