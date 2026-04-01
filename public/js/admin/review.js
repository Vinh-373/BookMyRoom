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

        document.querySelectorAll('.reviews-item').forEach(item => {
            const name = item.dataset.name || '';
            const content = item.dataset.content || '';
            const hotel = item.dataset.hotel || '';
            const rating = parseInt(item.dataset.rating);
            const partner = item.dataset.partner || '';

            let matchSearch =
                name.includes(keyword) ||
                content.includes(keyword) ||
                hotel.includes(keyword);

            let matchRating = selectedRating_review
                ? rating === parseInt(selectedRating_review)
                : true;

            let matchPartner = selectedPartner
                ? String(partner) === String(selectedPartner)
                : true;

            item.style.display =
                (matchSearch && matchRating && matchPartner) ? 'block' : 'none';
        });

        updateAverageRating();
    }

    // ===== SEARCH =====
    searchInput_review.addEventListener('input', filterReviews);

    // ===== PARTNER FILTER =====
    partnerFilter_review.addEventListener('change', filterReviews);

    // ===== RATING CLICK =====
    ratingFilters_review.forEach(li => {
        li.addEventListener('click', () => {

            // toggle chọn lại = bỏ filter
            if (selectedRating_review === li.dataset.rating) {
                selectedRating_review = null;
                li.classList.remove('active');
            } else {
                selectedRating_review = li.dataset.rating;

                ratingFilters_review.forEach(el => el.classList.remove('active'));
                li.classList.add('active');
            }

            filterReviews();
        });
    });

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
        } else {
          alert('Lỗi: ' + result.message);
        }
      })
      .catch(err => alert('Có lỗi kết nối: ' + err.message));
  });
});



});