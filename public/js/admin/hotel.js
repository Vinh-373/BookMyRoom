document.addEventListener("DOMContentLoaded", function () {

    const hotelsList = document.getElementById("hotelsList");

    // Toolbar filters
    const searchInput = document.getElementById("hotels-search");
    const statusFilter = document.getElementById("hotelsStatusFilter");
    const partnerFilter = document.getElementById("hotels-filter-partner");
    const nameHotelFilter = document.getElementById("hotels-filter-nameHotel");

    // Modal
    const hotelModal = document.getElementById("hotelModal");
    const modalClose = hotelModal.querySelector(".hotel-modal-close");

    const modalHotelName = document.getElementById("modalHotelName");
    const modalHotelImage = document.getElementById("modalHotelImage");
    const modalHotelAddress = document.getElementById("modalHotelAddress");
    const modalHotelWard = document.getElementById("modalHotelWard");
    const modalHotelCity = document.getElementById("modalHotelCity");
    const modalHotelCompany = document.getElementById("modalHotelCompany");
    const modalHotelRooms = document.getElementById("modalHotelRooms");
    const modalHotelBookings = document.getElementById("modalHotelBookings");
    const modalHotelRevenue = document.getElementById("modalHotelRevenue");
    const modalHotelRating = document.getElementById("modalHotelRating");

    // Base API giống controller
    const currentPath = window.location.pathname.replace(/\/$/, '');
    const apiBase = currentPath.endsWith('/hotels') ? currentPath : currentPath + '/hotels';

    /* =========================
       UPDATE STATUS
    ========================= */



    // Lấy các stat card
    const statActive = document.querySelector(".hotels-stat-card.c .num");
    const statPending = document.querySelector(".hotels-stat-card.d .num");
    const statStop = document.querySelector(".hotels-stat-card.e .num");




    hotelsList.addEventListener("click", function (e) {

        if (!e.target.classList.contains("hotels-toggle-status-btn")) return;

        const btn = e.target;
        const card = btn.closest(".hotels-card");

        const id = card.dataset.id;
        const currentStatus = card.dataset.status; // trạng thái cũ
        const nextStatus = btn.dataset.nextStatus;

        if (!confirm("Bạn có chắc muốn đổi trạng thái?")) return;

        fetch(`${apiBase}/update_status_hotel`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                id: id,
                status: nextStatus
            })
        })
            .then(res => res.json())
            .then(result => {

                alert(result.success ? "Cập nhật thành công!" : "Lỗi: " + result.message);

                if (result.success) {

                    // ===== UPDATE UI =====
                    card.dataset.status = nextStatus;

                    let btnText = "";
                    let btnClass = "";
                    let newNextStatus = "";

                    if (nextStatus === "ACTIVE") {
                        btnText = "Khóa";
                        btnClass = "hotels-btn-stop";
                        newNextStatus = "STOP";
                    }
                    else if (nextStatus === "STOP") {
                        btnText = "Mở";
                        btnClass = "hotels-btn-active";
                        newNextStatus = "ACTIVE";
                    }
                    else if (nextStatus === "PENDING_STOP") {
                        btnText = "Duyệt";
                        btnClass = "hotels-btn-pending_stop";
                        newNextStatus = "ACTIVE";
                    }

                    btn.textContent = btnText;
                    btn.className = "hotels-toggle-status-btn " + btnClass;
                    btn.dataset.nextStatus = newNextStatus;

                    // ===== UPDATE STATS =====
                    if (currentStatus === "ACTIVE") statActive.textContent = parseInt(statActive.textContent) - 1;
                    if (currentStatus === "STOP") statStop.textContent = parseInt(statStop.textContent) - 1;
                    if (currentStatus === "PENDING_STOP") statPending.textContent = parseInt(statPending.textContent) - 1;

                    if (nextStatus === "ACTIVE") statActive.textContent = parseInt(statActive.textContent) + 1;
                    if (nextStatus === "STOP") statStop.textContent = parseInt(statStop.textContent) + 1;
                    if (nextStatus === "PENDING_STOP") statPending.textContent = parseInt(statPending.textContent) + 1;

                }

            })
            .catch(err => {
                console.error(err);
                alert("Lỗi server!");
            });

    });

    // =========================
    // FILTER FUNCTION
    // =========================
    function filterHotels() {
        const searchVal = searchInput.value.toLowerCase();
        const statusVal = statusFilter.value;
        const partnerVal = partnerFilter.value;
        const nameVal = nameHotelFilter.value;

        const cards = hotelsList.querySelectorAll(".hotels-card");

        cards.forEach(card => {
            const name = card.dataset.name.toLowerCase();
            const status = card.dataset.status;
            const partner = card.dataset.partner;

            let visible = true;

            if (searchVal && !name.includes(searchVal)) visible = false;
            if (statusVal && status !== statusVal) visible = false;
            if (partnerVal && partner !== partnerVal) visible = false;
            if (nameVal && name !== nameVal) visible = false;

            card.style.display = visible ? "block" : "none";
        });
    }

    // Event listeners for filters
    searchInput.addEventListener("input", filterHotels);
    statusFilter.addEventListener("change", filterHotels);
    partnerFilter.addEventListener("change", filterHotels);
    nameHotelFilter.addEventListener("change", filterHotels);

    // =========================
    // VIEW MODAL
    // =========================
    hotelsList.addEventListener("click", function (e) {
        if (e.target.classList.contains("hotels-btn-view")) {
            const card = e.target.closest(".hotels-card");

            modalHotelName.textContent = card.querySelector("h3").textContent;
            modalHotelImage.src = card.querySelector("img").src;
            modalHotelAddress.textContent = card.querySelector("p:nth-of-type(1)").textContent.replace("📍 ", "");
            modalHotelWard.textContent = card.querySelector("p:nth-of-type(2)").textContent.split(",")[0];
            modalHotelCity.textContent = card.querySelector("p:nth-of-type(2)").textContent.split(",")[1].trim();
            modalHotelCompany.textContent = card.querySelector("p:nth-of-type(3)").textContent.replace("🏢 ", "");
            modalHotelRooms.textContent = card.querySelector(".hotels-meta span:nth-of-type(1)").textContent.replace("🛏 ", "");
            modalHotelBookings.textContent = card.querySelector(".hotels-meta span:nth-of-type(2)").textContent.replace("📖 ", "");
            modalHotelRevenue.textContent = card.querySelector(".hotels-meta span:nth-of-type(3)").textContent.replace("💰 ", "");
            modalHotelRating.textContent = card.querySelector(".rating").textContent.replace("⭐ ", "");

            hotelModal.style.display = "block";
        }
    });

    // Close modal
    modalClose.addEventListener("click", () => hotelModal.style.display = "none");
    window.addEventListener("click", e => {
        if (e.target === hotelModal) hotelModal.style.display = "none";
    });




    const clearFiltersBtn = document.getElementById("btn-xoa-bo-loc-hotel");

clearFiltersBtn.addEventListener('click', () => {
    // Reset tất cả filter về mặc định
    searchInput.value = '';
    statusFilter.value = '';
    partnerFilter.value = '';
    nameHotelFilter.value = '';

    // Gọi filterHotels để áp dụng lại filter (hiển thị toàn bộ)
    filterHotels();
});




});



