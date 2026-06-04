document.addEventListener("DOMContentLoaded", function () {
    const scrollContain = document.getElementById("menuScrollContain");
    const btnPrev = document.getElementById("LButton");
    const btnNext = document.getElementById("RButton");
    const categoryButtons = document.querySelectorAll(".menuCategory button");

    if (!scrollContain || !btnPrev || !btnNext) return;
    let maxContainerHeight = scrollContain.offsetHeight;

    window.addEventListener("load", () => {
        maxContainerHeight = scrollContain.offsetHeight;
        if (window.innerWidth > 1440) scrollContain.style.minHeight = maxContainerHeight + "px";
    });

    // logic category
    const allCards = Array.from(scrollContain.querySelectorAll(".cardContain"));
    function renderFilter(category) {
        const fragment = document.createDocumentFragment(); // optimasi DOM
        const filteredCards = allCards.filter(card => category === "semua" || card.dataset.category === category);
        // slicing loop auto
        for (let i = 0; i < filteredCards.length; i += 6) {
            const page = document.createElement("div");
            page.className = "menuPage";
            filteredCards.slice(i, i + 6).forEach(card => page.appendChild(card));
            fragment.appendChild(page);
        }
        scrollContain.innerHTML = "";
        scrollContain.appendChild(fragment);
        scrollContain.style.minHeight = window.innerWidth > 1440 ? maxContainerHeight + "px" : "auto";
        scrollContain.scrollLeft = 0;
        checkLayout();
    }

    categoryButtons.forEach(button => {
        button.addEventListener("click", function () {
            categoryButtons.forEach(btn => btn.classList.replace("btnPrimary", "btnSoft"));
            this.classList.replace("btnSoft", "btnPrimary");
            renderFilter(this.dataset.category);
        });
    });

    // logic layout & slider panah
    function checkLayout() {
        if (window.innerWidth <= 1440) return btnNext.style.display = btnPrev.style.display = "none";
        const pages = scrollContain.querySelectorAll(".menuPage").length;
        if (pages <= 1) return btnNext.style.display = btnPrev.style.display = "none";

        const currentScroll = Math.round(scrollContain.scrollLeft);
        const maxScrollLeft = scrollContain.scrollWidth - scrollContain.clientWidth;
        btnNext.style.display = currentScroll >= maxScrollLeft - 10 ? "none" : "flex";
        btnPrev.style.display = currentScroll > 10 ? "flex" : "none";
    }

    checkLayout();
    let resizeTimer;
    window.addEventListener("resize", () => {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(() => {
            if (window.innerWidth > 1440) {
                scrollContain.style.minHeight = maxContainerHeight + "px";
                const containWidth = scrollContain.clientWidth;
                scrollContain.scrollTo({ left: Math.round(scrollContain.scrollLeft / containWidth) * containWidth, behavior: "auto" });
            } else {
                scrollContain.style.minHeight = "auto";
            }
            checkLayout();
        }, 100);
    });

    scrollContain.addEventListener("scroll", checkLayout, { passive: true });
    btnNext.addEventListener("click", () => scrollContain.scrollBy({ left: scrollContain.clientWidth, behavior: "smooth" }));
    btnPrev.addEventListener("click", () => scrollContain.scrollBy({ left: -scrollContain.clientWidth, behavior: "smooth" }));
});
