
    const slider = document.getElementById("hotelSlider");
    const nextBtn = document.getElementById("nextBtn");
    const prevBtn = document.getElementById("prevBtn");

    let position = 0;

    function getCardWidth() {
        const card = slider.querySelector(".prop-card");
        const gap = 16;
        return card.offsetWidth + gap;
    }

    function updateButtons() {
        const maxScroll = slider.scrollWidth - slider.parentElement.clientWidth;
        prevBtn.style.visibility = position <= 0 ? "hidden" : "visible";
        nextBtn.style.visibility = position >= maxScroll - 5 ? "hidden" : "visible";
    }

    function moveSlide(direction) {
        const cardWidth = getCardWidth();
        const maxScroll = slider.scrollWidth - slider.parentElement.clientWidth;

        position += direction * cardWidth;

        if (position < 0) position = 0;
        if (position > maxScroll) position = maxScroll;

        slider.style.transform = `translateX(-${position}px)`;
        updateButtons();
    }

    nextBtn.addEventListener("click", () => moveSlide(1));
    prevBtn.addEventListener("click", () => moveSlide(-1));

    window.addEventListener("resize", () => {
        position = 0;
        slider.style.transform = `translateX(0px)`;
        updateButtons();
    });

    // Khởi tạo trạng thái nút ban đầu
    setTimeout(updateButtons, 100);

