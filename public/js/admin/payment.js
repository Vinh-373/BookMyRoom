document.addEventListener("DOMContentLoaded", function () {

  const searchInput = document.getElementById("paymentSearch");
  const statusFilter = document.getElementById("paymentStatus");
  const methodFilter = document.getElementById("paymentMethod");
  const rows = document.querySelectorAll(".payments-table tbody tr");
  // Base API path
  const currentPath = window.location.pathname.replace(/\/$/, '');
  const apiBase = currentPath.endsWith('/payments') ? currentPath : currentPath + '/payments';




  function filterPayments() {
    const searchValue = searchInput.value.toLowerCase();
    const statusValue = statusFilter.value;
    const methodValue = methodFilter.value;

    rows.forEach(row => {
      const booking = row.dataset.booking.toLowerCase();
      const status = row.dataset.status;
      const method = row.dataset.method;

      let matchSearch = booking.includes(searchValue);
      let matchStatus = !statusValue || status === statusValue;
      let matchMethod = !methodValue || method === methodValue;

      row.style.display = (matchSearch && matchStatus && matchMethod) ? "" : "none";
    });
  }

  searchInput.addEventListener("input", filterPayments);
  statusFilter.addEventListener("change", filterPayments);
  methodFilter.addEventListener("change", filterPayments);

  // ===== ACTION BUTTONS =====

document.querySelectorAll(".payments-btn-success").forEach(btn => {
  btn.addEventListener("click", function () {

    if (!confirm("Xác nhận thanh toán?")) return;

    const row = this.closest("tr");
    const id = row.children[0].textContent;

    fetch(`${apiBase}/update_status_payment`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        id: id,
        status: "PAID"
      })
    })
    .then(res => res.json())
    .then(result => {
      alert(result.success ? 'Xác nhận thành công!' : 'Lỗi: ' + result.message);

      if (result.success) {
        row.querySelector(".payments-badge").textContent = "PAID";
        row.querySelector(".payments-badge").className = "payments-badge paid";
        row.dataset.status = "PAID";

        this.remove();
        updatePaymentSummary(); 
      }
    })
    .catch(err => {
      console.error(err);
      alert("Lỗi server!");
    });

  });
});

document.querySelectorAll(".payments-btn-danger").forEach(btn => {
  btn.addEventListener("click", function () {

    if (!confirm("Bạn chắc chắn muốn hoàn tiền?")) return;

    const row = this.closest("tr");
    const id = row.children[0].textContent;

    fetch(`${apiBase}/update_status_payment`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        id: id,
        status: "REFUNDED" // 👉 hoặc "REFUNDED"
      })
    })
    .then(res => res.json())
    .then(result => {
      alert(result.success ? 'Hoàn tiền thành công!' : 'Lỗi: ' + result.message);

      if (result.success) {
        row.querySelector(".payments-badge").textContent = "REFUNDED";
        row.querySelector(".payments-badge").className = "payments-badge refunded";
        row.dataset.status = "REFUNDED";

        this.remove();
        updatePaymentSummary(); 
      }
    })
    .catch(err => {
      console.error(err);
      alert("Lỗi server!");
    });

  });
});
  // ===== MODAL =====

  const modal = document.getElementById("view-modal-payment");

  document.querySelectorAll(".payments-btn-view").forEach(btn => {
    btn.addEventListener("click", function () {
      const row = this.closest("tr");

      document.getElementById("payments-view-id").textContent = row.children[0].textContent;
      document.getElementById("payments-view-booking").textContent = row.children[1].textContent;
      document.getElementById("payments-view-name").textContent = row.children[2].textContent;
      document.getElementById("payments-view-amount").textContent = row.children[3].textContent;
      document.getElementById("payments-view-method").textContent = row.children[4].textContent;
      document.getElementById("payments-view-status").textContent = row.querySelector(".payments-badge").textContent; // 🔥 FIX
      document.getElementById("payments-view-date").textContent = row.children[6].textContent;

      modal.style.display = "flex";
    });
  });

  document.getElementById("close-modal-payment").onclick = () => {
    modal.style.display = "none";
  };

  window.onclick = function(e) {
    if (e.target === modal) {
      modal.style.display = "none";
    }
  };





function updatePaymentSummary() {
  const rows = document.querySelectorAll(".payments-table tbody tr");

  let totalRevenue = 0;
  let totalPlatformFee = 0;
  let totalPartnerRevenue = 0;
  let totalTransactions = 0;
  let totalPaid = 0;
  let totalPending = 0;
  let totalFailed = 0;

  rows.forEach(row => {
    const status = row.dataset.status;
    if (status === "PAID") {
      totalTransactions++;
      totalPaid++;
      const amount = parseFloat(row.dataset.amount) || 0;
      const platformFee = parseFloat(row.dataset.platformFee) || 0;
      const partnerRevenue = parseFloat(row.dataset.partnerRevenue) || 0;

      totalRevenue += amount;
      totalPlatformFee += platformFee;
      totalPartnerRevenue += partnerRevenue;
    } else if (status === "PENDING") {
      totalPending++;
    } else if (status === "REFUNDED" || status === "FAILED") {
      totalFailed++;
    }
  });

  // Cập nhật UI
  document.getElementById("payments-sum-div").firstChild.textContent = totalRevenue.toLocaleString("vi-VN") + " đ";
  document.getElementById("payments-platformm-div").firstChild.textContent = totalPlatformFee.toLocaleString("vi-VN") + " đ";
  document.getElementById("payments-partnerFee-div").firstChild.textContent = totalPartnerRevenue.toLocaleString("vi-VN") + " đ";

  document.getElementById("payments-count-div").firstChild.textContent = rows.length;
  document.getElementById("payments-paid-div").firstChild.textContent = totalPaid;
  document.getElementById("payments-pending-div").firstChild.textContent = totalPending;
  document.getElementById("payments-faild-div").firstChild.textContent = totalFailed;
}






});