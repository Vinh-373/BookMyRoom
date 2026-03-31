<div class="reviews-wrapper">
    <header class="reviews-header">
        <div class="header-left">
            <h1>Guest Reviews</h1>
            <p>Phân tích và phản hồi đánh giá từ khách hàng của bạn.</p>
        </div>
        <div class="header-right">
            <button class="btn btn-export" onclick="exportReviews()">📥 Xuất báo cáo</button>
        </div>
    </header>

    <div class="reviews-main-layout">
        <aside class="reviews-sidebar">
            <div class="sidebar-card">
                <h3>Lọc nhanh</h3>
                <div class="filter-tags">
                    <a href="?tab=all" class="tag <?= (!isset($_GET['tab']) || $_GET['tab'] == 'all') ? 'active' : '' ?>">Tất cả</a>
                    <a href="?tab=pending" class="tag <?= (isset($_GET['tab']) && ($_GET['tab'] == 'pending')) ? 'active' : '' ?>">Chưa phản hồi</a>
                    <a href="?tab=positive" class="tag <?= (isset($_GET['tab']) && ($_GET['tab'] == 'positive')) ? 'active' : '' ?>">Tích cực</a>
                    <a href="?tab=negative" class="tag <?= (isset($_GET['tab']) && ($_GET['tab'] == 'negative')) ? 'active' : '' ?>">Tiêu cực</a>
                </div>
            </div>
            <div class="sidebar-card rating-summary">
                <span class="avg-score"><?= number_format($avgRating, 1) ?></span>
                <div class="rating-label">Rất tốt</div>
                <div class="stars-gold"><?= str_repeat('★', round($avgRating)) . str_repeat('☆', 5 - round($avgRating)) ?></div>
                <p>Dựa trên <strong><?= count($reviews) ?></strong> đánh giá</p>
            </div>

            <div class="sidebar-card">
                <h3>Rating Breakdown</h3>
                <div class="breakdown-list">
                    <?php 
                    /**
                     * Dữ liệu $breakdown được đổ từ ReviewService thông qua getReviewPageData
                     * Cấu trúc mỗi item: ['label' => '5 ★', 'pc' => 75, 'color' => '#12B76A']
                     */
                    if (!empty($breakdown)): 
                        foreach($breakdown as $lv): ?>
                            <div class="breakdown-item">
                                <span class="lv-label"><?= $lv['label'] ?></span>
                                <div class="progress-bar">
                                    <div class="fill" style="width: <?= $lv['pc'] ?>%; background: <?= $lv['color'] ?>;"></div>
                                </div>
                                <span class="lv-pc"><?= $lv['pc'] ?>%</span>
                            </div>
                        <?php endforeach; 
                    else: ?>
                        <p style="font-size: 0.8rem; color: #999;">Chưa có dữ liệu phân tích.</p>
                    <?php endif; ?>
                </div>
            </div>

            
        </aside>

        <section class="reviews-feed">
            <?php if (!empty($reviews)): ?>
                <?php foreach($reviews as $r): ?>
                <div class="review-card">
                    <div class="review-card__main">
                        <div class="guest-avatar">
                            <?php if(!empty($r['avatarUrl'])): ?>
                                <img src="<?= URLROOT ?>/public/images/avatars/<?= $r['avatarUrl'] ?>" alt="Avatar">
                            <?php else: ?>
                                <div class="avatar-placeholder"><?= strtoupper(substr($r['fullName'], 0, 2)) ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="review-body">
                            <div class="review-meta-top">
                                <div class="stars-gold"><?= str_repeat('★', $r['rating']) . str_repeat('☆', 5 - $r['rating']) ?></div>
                                <span class="status-badge <?= !empty($r['replyContent']) ? 'responded' : 'pending' ?>">
                                    <?= !empty($r['replyContent']) ? '✓ Đã phản hồi' : '⌛ Chờ xử lý' ?>
                                </span>
                            </div>

                            <h3><?= $r['fullName'] ?></h3>
                            <p class="stay-info">Đã ở: <strong><?= $r['roomTypeName'] ?></strong> • <?= date('M Y', strtotime($r['checkIn'])) ?></p>
                            
                            <div class="review-text-box">
                                "<?= $r['content'] ?>"
                            </div>

                            <?php if (!empty($r['replyContent'])): ?>
                            <div class="official-reply">
                                <div class="reply-owner">
                                    <strong>Phản hồi từ khách sạn</strong>
                                    <span><?= date('d/m/Y', strtotime($r['replyDate'])) ?></span>
                                </div>
                                <p><?= $r['replyContent'] ?></p>
                            </div>
                            <?php endif; ?>

                            <div class="review-actions">
                                <span class="post-date">Ngày đăng: <?= date('d/m/Y', strtotime($r['createdAt'])) ?></span>
                                <div class="btn-group">
                                    <button class="btn-action <?= empty($r['replyContent']) ? 'btn-primary' : 'btn-outline' ?>" 
                                            onclick="openReplyModal(<?= $r['id'] ?>, '<?= $r['fullName'] ?>', '<?= addslashes($r['replyContent'] ?? '') ?>')">
                                        <?= empty($r['replyContent']) ? 'Trả lời khách' : 'Sửa phản hồi' ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-state">
                    <p>Không tìm thấy đánh giá nào phù hợp.</p>
                </div>
            <?php endif; ?>
        </section>
    </div>
</div>

<script>
/**
 * Chức năng Phản hồi hoặc Chỉnh sửa phản hồi
 * @param {number} reviewId - ID của đánh giá
 * @param {string} guestName - Tên khách để hiển thị tiêu đề
 * @param {string} oldReply - Nội dung phản hồi cũ nếu có (để sửa)
 */
function openReplyModal(reviewId, guestName, oldReply = '') {
    Swal.fire({
        title: oldReply ? 'Chỉnh sửa phản hồi' : `Phản hồi cho ${guestName}`,
        input: 'textarea',
        inputLabel: 'Nội dung phản hồi sẽ được hiển thị công khai trên trang đặt phòng.',
        inputValue: oldReply,
        inputPlaceholder: 'Nhập lời cảm ơn hoặc giải đáp thắc mắc của khách...',
        inputAttributes: {
            'aria-label': 'Type your message here',
            'rows': '5'
        },
        showCancelButton: true,
        confirmButtonText: oldReply ? 'Cập nhật' : 'Gửi phản hồi',
        confirmButtonColor: '#2261E0',
        cancelButtonText: 'Hủy',
        showLoaderOnConfirm: true,
        preConfirm: (replyText) => {
            if (!replyText || replyText.trim().length < 5) {
                Swal.showValidationMessage('Vui lòng nhập phản hồi tối thiểu 5 ký tự!');
                return false;
            }
            
            // Gửi dữ liệu qua AJAX
            let formData = new FormData();
            formData.append('reviewId', reviewId);
            formData.append('reply', replyText);

            return fetch('<?= URLROOT ?>/partner/replyToReview', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    throw new Error(data.message || 'Không thể lưu phản hồi');
                }
                return data;
            })
            .catch(error => {
                Swal.showValidationMessage(`Lỗi: ${error.message}`);
            });
        },
        allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                icon: 'success',
                title: 'Thành công!',
                text: 'Phản hồi của bạn đã được ghi lại.',
                timer: 1500,
                showConfirmButton: false
            }).then(() => {
                location.reload(); // Tải lại trang để cập nhật giao diện
            });
        }
    });
}

/**
 * Chức năng Xuất báo cáo (Export CSV)
 */
function exportReviews() {
    Swal.fire({
        title: 'Xuất báo cáo?',
        text: "Hệ thống sẽ tạo file CSV chứa toàn bộ danh sách đánh giá hiện tại.",
        icon: 'info',
        showCancelButton: true,
        confirmButtonColor: '#2261E0',
        confirmButtonText: 'Tải về ngay'
    }).then((result) => {
        if (result.isConfirmed) {
            // Chuyển hướng đến endpoint xử lý export file
            window.location.href = '<?= URLROOT ?>/partner/exportReviewsCSV';
        }
    });
}
</script>