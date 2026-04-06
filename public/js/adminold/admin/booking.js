<<<<<<< HEAD:public/js/admin/booking.js
document.addEventListener("DOMContentLoaded", function () {

  // ===== GUARD (quan trọng) =====
  // nếu không phải trang booking thì thoát
  const table = document.querySelector(".booking-table");
  if (!table) return;

  // ===== FILTER =====
  const searchInput = document.getElementById("bookingSearch");
  const statusFilter = document.getElementById("bookingStatus");
  const rows = document.querySelectorAll(".booking-table tbody tr");

  function filterBookings() {
    const keyword = (searchInput?.value || "").toLowerCase().trim();
    const statusValue = statusFilter?.value || "";

    rows.forEach(row => {
      const name = (row.dataset.name || "").toLowerCase();
      const status = row.dataset.status || "";

      const matchName = name.includes(keyword);
      const matchStatus = !statusValue || status === statusValue;

      row.style.display = (matchName && matchStatus) ? "" : "none";
    });
  }

  if (searchInput) {
    searchInput.addEventListener("input", filterBookings);
  }

  if (statusFilter) {
    statusFilter.addEventListener("change", filterBookings);
  }

  // ===== MODAL =====
  const modal = document.getElementById("booking-modal");
  const closeBtn = document.getElementById("booking-close");

  const idEl = document.getElementById("modal-id");
  const nameEl = document.getElementById("modal-name");
  const checkinEl = document.getElementById("modal-checkin");
  const checkoutEl = document.getElementById("modal-checkout");
  const totalEl = document.getElementById("modal-total");
  const statusEl = document.getElementById("modal-status");

  const updateBtn = document.getElementById("booking-update-btn");
  const hotelEl = document.getElementById("modal-hotel");
const roomEl = document.getElementById("modal-room");

  let currentId = null;
  let currentRow = null;

  // ===== OPEN MODAL =====
  document.querySelectorAll(".booking-btn-detail").forEach(btn => {
  btn.addEventListener("click", function () {

    currentId = this.dataset.id;
    currentRow = this.closest("tr");

    if (!currentId) return;

    idEl.textContent = "#" + currentId;
    nameEl.textContent = this.dataset.name || "---";
    checkinEl.textContent = this.dataset.checkin || "---";
    checkoutEl.textContent = this.dataset.checkout || "---";

    totalEl.textContent =
      Number(this.dataset.total || 0).toLocaleString() + "đ";

    hotelEl.textContent = this.dataset.hotel || "---";
    roomEl.textContent = this.dataset.room || "---";

    statusEl.value = this.dataset.status || "PENDING";

    modal.style.display = "flex";
  });
});

  // ===== CLOSE MODAL =====
  if (closeBtn) {
    closeBtn.onclick = () => modal.style.display = "none";
  }

  window.addEventListener("click", function (e) {
    if (e.target === modal) {
      modal.style.display = "none";
    }
  });

  // ===== UPDATE STATUS =====
  if (updateBtn) {
    updateBtn.addEventListener("click", function () {

      if (!currentId) return;

      fetch("/BookMyRoom/admin/bookings/update_status", {
        method: "POST",
        headers: {
          "Content-Type": "application/json"
        },
        body: JSON.stringify({
          id: currentId,
          status: statusEl.value
        })
      })
      .then(res => res.json())
      .then(result => {

        if (result.success) {

          alert("Cập nhật thành công!");

          // ===== update UI =====
          if (currentRow) {
            currentRow.dataset.status = statusEl.value;

            const statusSpan = currentRow.querySelector(".booking-status");

            if (statusSpan) {
              statusSpan.textContent = statusEl.value;
              statusSpan.className = "booking-status " + statusEl.value.toLowerCase();
            }
          }

          modal.style.display = "none";

        } else {
          alert("Lỗi: " + result.message);
        }

      })
      .catch(() => {
        alert("Lỗi server!");
      });

    });
  }

=======
document.addEventListener("DOMContentLoaded", function () {

  // ===== GUARD (quan trọng) =====
  // nếu không phải trang booking thì thoát
  const table = document.querySelector(".booking-table");
  if (!table) return;

  // ===== FILTER =====
  const searchInput = document.getElementById("bookingSearch");
  const statusFilter = document.getElementById("bookingStatus");
  const rows = document.querySelectorAll(".booking-table tbody tr");

  function filterBookings() {
    const keyword = (searchInput?.value || "").toLowerCase().trim();
    const statusValue = statusFilter?.value || "";

    rows.forEach(row => {
      const name = (row.dataset.name || "").toLowerCase();
      const status = row.dataset.status || "";

      const matchName = name.includes(keyword);
      const matchStatus = !statusValue || status === statusValue;

      row.style.display = (matchName && matchStatus) ? "" : "none";
    });
  }

  if (searchInput) {
    searchInput.addEventListener("input", filterBookings);
  }

  if (statusFilter) {
    statusFilter.addEventListener("change", filterBookings);
  }

  // ===== MODAL =====
  const modal = document.getElementById("booking-modal");
  const closeBtn = document.getElementById("booking-close");

  const idEl = document.getElementById("modal-id");
  const nameEl = document.getElementById("modal-name");
  const checkinEl = document.getElementById("modal-checkin");
  const checkoutEl = document.getElementById("modal-checkout");
  const totalEl = document.getElementById("modal-total");
  const statusEl = document.getElementById("modal-status");

  const updateBtn = document.getElementById("booking-update-btn");
  const hotelEl = document.getElementById("modal-hotel");
const roomEl = document.getElementById("modal-room");

  let currentId = null;
  let currentRow = null;

  // ===== OPEN MODAL =====
  document.querySelectorAll(".booking-btn-detail").forEach(btn => {
  btn.addEventListener("click", function () {

    currentId = this.dataset.id;
    currentRow = this.closest("tr");

    if (!currentId) return;

    idEl.textContent = "#" + currentId;
    nameEl.textContent = this.dataset.name || "---";
    checkinEl.textContent = this.dataset.checkin || "---";
    checkoutEl.textContent = this.dataset.checkout || "---";

    totalEl.textContent =
      Number(this.dataset.total || 0).toLocaleString() + "đ";

    hotelEl.textContent = this.dataset.hotel || "---";
    roomEl.textContent = this.dataset.room || "---";

    statusEl.value = this.dataset.status || "PENDING";

    modal.style.display = "flex";
  });
});

  // ===== CLOSE MODAL =====
  if (closeBtn) {
    closeBtn.onclick = () => modal.style.display = "none";
  }

  window.addEventListener("click", function (e) {
    if (e.target === modal) {
      modal.style.display = "none";
    }
  });

  // ===== UPDATE STATUS =====
  if (updateBtn) {
    updateBtn.addEventListener("click", function () {

      if (!currentId) return;

      fetch("/BookMyRoom/admin/bookings/update_status", {
        method: "POST",
        headers: {
          "Content-Type": "application/json"
        },
        body: JSON.stringify({
          id: currentId,
          status: statusEl.value
        })
      })
      .then(res => res.json())
      .then(result => {

        if (result.success) {

          alert("Cập nhật thành công!");

          // ===== update UI =====
          if (currentRow) {
            currentRow.dataset.status = statusEl.value;

            const statusSpan = currentRow.querySelector(".booking-status");

            if (statusSpan) {
              statusSpan.textContent = statusEl.value;
              statusSpan.className = "booking-status " + statusEl.value.toLowerCase();
            }
          }

          modal.style.display = "none";

        } else {
          alert("Lỗi: " + result.message);
        }

      })
      .catch(() => {
        alert("Lỗi server!");
      });

    });
  }

>>>>>>> 00c47ad (update):public/js/adminold/admin/booking.js
});