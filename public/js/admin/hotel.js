/* =========================
   SEARCH + FILTER
========================= */
document.getElementById('hotels-search').addEventListener('input', hotels_filter);
document.getElementById('hotels-filter-partner').addEventListener('change', hotels_filter);

function hotels_filter() {
    const keyword = document.getElementById('hotels-search').value.toLowerCase();
    const partner = document.getElementById('hotels-filter-partner').value;

    const cards = document.querySelectorAll('.hotels-card');

    cards.forEach(card => {
        const name = card.querySelector('.hotels-name').innerText.toLowerCase();
        const partnerText = card.querySelector('.hotels-partner').innerText;

        let match = true;

        if (keyword && !name.includes(keyword)) {
            match = false;
        }

        if (partner && !partnerText.includes(partner)) {
            match = false;
        }

        card.style.display = match ? 'block' : 'none';
    });
}


const searchInput = document.getElementById("hotels-search");
const partnerFilter = document.getElementById("hotels-filter-partner");
const cards = document.querySelectorAll(".hotels-card");

function filterHotels() {
    const keyword = searchInput.value.toLowerCase();
    const partner = partnerFilter.value.toLowerCase();

    cards.forEach(card => {
        const name = card.querySelector(".hotels-name").innerText.toLowerCase();
        const company = card.querySelector(".hotels-partner").innerText.toLowerCase();

        const matchSearch = name.includes(keyword);
        const matchPartner = !partner || company.includes(partner);

        card.style.display = (matchSearch && matchPartner) ? "block" : "none";
    });
}

searchInput.addEventListener("input", filterHotels);
partnerFilter.addEventListener("change", filterHotels);

/* =========================
   DELETE AJAX
========================= */
function hotels_delete(id, btn) {
    if (!confirm("Bạn có chắc muốn xóa khách sạn này?")) return;

    // loading
    btn.innerText = "Đang xóa...";
    btn.style.opacity = "0.6";

    fetch(`?action=delete&id=${id}`, {
        method: 'GET'
    })
    .then(res => res.text())
    .then(() => {
        // remove card
        const card = btn.closest('.hotels-card');
        card.style.transition = "0.3s";
        card.style.opacity = "0";
        card.style.transform = "scale(0.9)";

        setTimeout(() => {
            card.remove();
        }, 300);
    })
    .catch(err => {
        alert("Xóa thất bại!");
        console.error(err);
    });
}


/* =========================
   HOVER EFFECT (mượt hơn)
========================= */
document.querySelectorAll('.hotels-card').forEach(card => {
    card.addEventListener('mouseenter', () => {
        card.style.boxShadow = "0 8px 20px rgba(0,0,0,0.1)";
    });

    card.addEventListener('mouseleave', () => {
        card.style.boxShadow = "0 3px 10px rgba(0,0,0,0.06)";
    });
});
