document.addEventListener('DOMContentLoaded', () => {

  const searchInput_review = document.querySelector('.reviews-search');
  const partnerFilter_review = document.querySelector('.reviews-partners');
  const clearBtn_review = document.querySelector('.reviews-clear-btn');
  const reviewItems_review = document.querySelectorAll('.reviews-item');
  const ratingFilters_review = document.querySelectorAll('.reviews-summary li[data-rating]');
  let selectedRating_review = null;


  // Base API path
  const currentPath = window.location.pathname.replace(/\/$/, '');
  const apiBase = currentPath.endsWith('/reviews') ? currentPath : currentPath + '/reviews';


  // ===== FILTER FUNCTION =====
function filterReviews() {
    const keyword = searchInput_review.value.toLowerCase();
    const selectedPartner = partnerFilter_review.value;

    // Hiển thị review theo filter
    document.querySelectorAll('.reviews-item').forEach(item => {
        const name = item.dataset.name.toLowerCase();
        const content = item.dataset.content.toLowerCase();
        const hotel = item.dataset.hotel.toLowerCase();
        const partner = item.dataset.partner || '';
        const rating = parseInt(item.dataset.rating);

        const matchSearch =
            name.includes(keyword) ||
            content.includes(keyword) ||
            hotel.includes(keyword);

        const matchPartner = selectedPartner
            ? String(partner) === String(selectedPartner)
            : true;

        const matchRating = selectedRating_review
            ? rating === parseInt(selectedRating_review)
            : true;

        item.style.display = (matchSearch && matchPartner && matchRating) ? 'block' : 'none';
    });

    updateAverageRating();
    updateRatingSummaryCount(); // Luôn tính số lượng dựa trên partner + keyword
}


// ===== CLICK RATING FILTER =====
ratingFilters_review.forEach(li => {
    li.addEventListener('click', () => {
        // Nếu click lại vào cùng 1 sao → bỏ filter
        if (selectedRating_review === li.dataset.rating) {
            selectedRating_review = null;
            li.classList.remove('active');
        } else {
            selectedRating_review = li.dataset.rating;

            // Bỏ highlight tất cả li trước
            ratingFilters_review.forEach(el => el.classList.remove('active'));
            // Highlight li vừa click
            li.classList.add('active');
        }

        filterReviews(); // Lọc review sau khi click
    });
});

function updateTotalReviews() {
    // đếm số review đang hiển thị
    const total = Array.from(document.querySelectorAll('.reviews-item'))
        .filter(item => window.getComputedStyle(item).display !== 'none')
        .length;

    document.getElementById('reviewsTotal').innerText = `Tổng số đánh giá: ${total}`;
}

// Luôn tính số lượng từng sao dựa trên keyword + partner, không tính selectedRating_review
function updateRatingSummaryCount() {
    const keyword = searchInput_review.value.toLowerCase();
    const selectedPartner = partnerFilter_review.value;

    const ratingSummaryItems = document.querySelectorAll('.reviews-summary li[data-rating]');
    ratingSummaryItems.forEach(li => {
        const rating = parseInt(li.dataset.rating);
        const count = Array.from(document.querySelectorAll('.reviews-item'))
            .filter(item => {
                const name = item.dataset.name.toLowerCase();
                const content = item.dataset.content.toLowerCase();
                const hotel = item.dataset.hotel.toLowerCase();
                const partner = item.dataset.partner || '';
                const r = parseInt(item.dataset.rating);

                const matchSearch =
                    name.includes(keyword) ||
                    content.includes(keyword) ||
                    hotel.includes(keyword);

                const matchPartner = selectedPartner
                    ? String(partner) === String(selectedPartner)
                    : true;

                return matchSearch && matchPartner && r === rating;
            }).length;

        li.nextElementSibling.innerText = `${count} đánh giá`;
    });
        // Cập nhật tổng số đánh giá đang hiển thị
    updateTotalReviews();
}

  // ===== SEARCH =====
  searchInput_review.addEventListener('input', filterReviews);

  // ===== PARTNER FILTER =====
  partnerFilter_review.addEventListener('change', filterReviews);


  // ===== CLEAR FILTER =====
  clearBtn_review.addEventListener('click', () => {
    searchInput_review.value = '';
    partnerFilter_review.value = '';
    selectedRating_review = null;

    ratingFilters_review.forEach(el => el.classList.remove('active'));

    filterReviews();
  });


  function updateAverageRating() {
    let total = 0;
    let count = 0;

    document.querySelectorAll('.reviews-item').forEach(item => {
      if (window.getComputedStyle(item).display !== 'none') {
        total += parseFloat(item.dataset.rating);
        count++;
      }
    });

    const avg = count ? (total / count).toFixed(1) : 0;

    document.getElementById('mediumRating').innerText = `★ ${avg}`;
  }



  // ===== VIEW DETAIL =====
  document.querySelectorAll('.reviews-btn-view').forEach(btn => {
    btn.addEventListener('click', function () {
      const item = this.closest('.reviews-item');

      document.getElementById('d-name').innerText = item.dataset.name;
      document.getElementById('d-bookingId').innerText = item.dataset.bookingid;
      document.getElementById('d-hotel').innerText = item.dataset.hotel;
      document.getElementById('d-date').innerText = item.dataset.date;
      document.getElementById('d-rating').innerText = item.dataset.rating;
      document.getElementById('d-content').innerText = item.dataset.content;

      document.getElementById('reviewDetail').style.display = 'block';
    });
  });

  // ===== CLOSE DETAIL =====
  document.getElementById('reviews-closeDetail').addEventListener('click', () => {
    document.getElementById('reviewDetail').style.display = 'none';
  });


  document.querySelectorAll('.reviews-btn-delete').forEach(btn => {
    btn.addEventListener('click', function () {
      const item = this.closest('.reviews-item');
      const reviewId = item.dataset.id;

      if (!confirm('Bạn có chắc muốn xóa đánh giá này?')) return;

      fetch(`${apiBase}/delete_review`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id: reviewId })
      })
        .then(res => res.json())
        .then(result => {
          if (result.success) {
            item.remove(); // xóa khỏi UI
            updateAverageRating(); // cập nhật lại điểm trung bình
             updateRatingSummaryCount(); // cập nhật lại số lượng đánh giá cho từng sao
          } else {
            alert('Lỗi: ' + result.message);
          }
        })
        .catch(err => alert('Có lỗi kết nối: ' + err.message));
    });
  });



});