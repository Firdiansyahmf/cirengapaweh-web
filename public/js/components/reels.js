document.addEventListener("DOMContentLoaded", function () {
    const scrollContainReels = document.getElementById("reelsScrollContain");
    const btnPrevReels = document.getElementById("LButtonReels");
    const btnNextReels = document.getElementById("RButtonReels");

    if (!scrollContainReels || !btnPrevReels || !btnNextReels) return;

    const phones = scrollContainReels.querySelectorAll(".phoneContain");
    if (window.hasUserInteractedReels === undefined) window.hasUserInteractedReels = false;

    // sensor
    let reelsInView = false;
    let isAutoScrolling = false; // btn panah flicker
    const reelsSection = scrollContainReels.closest(".reels") || document.getElementById("reels");

    // logic layout btn
    const checkLayout = () => {
        const isDesktop = window.innerWidth > 1440 && phones.length > 1;
        btnNextReels.style.display = btnPrevReels.style.display = isDesktop ? "flex" : "none";
    };

    const getScrollDistance = () => 297 + (window.innerWidth > 1440 ? 32 : 16);

    // logic hitung index terpusat
    const getCurrentIndex = () => {
        const containerRect = scrollContainReels.getBoundingClientRect();
        const containerCenter = containerRect.left + (containerRect.width / 2);
        let activeIndex = 0;
        let minDistance = Infinity;
        phones.forEach((phone, index) => {
            const rect = phone.getBoundingClientRect();
            const phoneCenter = rect.left + (rect.width / 2);
            const distance = Math.abs(containerCenter - phoneCenter);
            if (distance < minDistance) {
                minDistance = distance;
                activeIndex = index;
            }
        });
        return activeIndex;
    };

    // logic audio dan logic center
    function updateActiveVideo() {
        if (isAutoScrolling) return;
        const currentIndex = getCurrentIndex();

        phones.forEach((phone, index) => {
            const video = phone.querySelector("video");
            const btnAudio = phone.querySelector(".btnAudio");
            if (!video || !btnAudio) return;

            if (index === currentIndex && reelsInView) {
                video.muted = !window.hasUserInteractedReels;
                btnAudio.src = btnAudio.src.replace(window.hasUserInteractedReels ? 'btnMute.svg' : 'btnUnmute.svg', window.hasUserInteractedReels ? 'btnUnmute.svg' : 'btnMute.svg');

                video.play().catch(() => {
                    video.muted = true;
                    btnAudio.src = btnAudio.src.replace('btnUnmute.svg', 'btnMute.svg');
                    video.play();
                });
            } else {
                video.pause();
                video.currentTime = 0;
                video.muted = true;
                btnAudio.src = btnAudio.src.replace('btnUnmute.svg', 'btnMute.svg');
            }
        });
    }

    // aktif sensor
    if (reelsSection) {
        new IntersectionObserver((e) => {
            reelsInView = e[0].isIntersecting;
            updateActiveVideo();
        }, { threshold: 0.3 }).observe(reelsSection);
    }

    // konek navbar.js
    window.addEventListener("triggerReelsAudio", updateActiveVideo);

    // konek btn audio
    phones.forEach((phone) => {
        const btnAudio = phone.querySelector(".btnAudio");
        const video = phone.querySelector("video");
        if (!btnAudio || !video) return;

        btnAudio.addEventListener("click", (e) => {
            e.preventDefault(); e.stopPropagation();
            window.hasUserInteractedReels = true;
            video.muted = !video.muted;
            btnAudio.src = btnAudio.src.replace(video.muted ? 'btnUnmute.svg' : 'btnMute.svg', video.muted ? 'btnMute.svg' : 'btnUnmute.svg');
        });
    });
    checkLayout();

    // logic scroll tablet or mobile
    scrollContainReels.addEventListener("touchstart", () => window.hasUserInteractedReels = true, { passive: true });
    scrollContainReels.addEventListener("mousedown", () => window.hasUserInteractedReels = true);

    let scrollTimeoutVideo;
    scrollContainReels.addEventListener("scroll", () => {
        if (isAutoScrolling) return;
        clearTimeout(scrollTimeoutVideo);
        scrollTimeoutVideo = setTimeout(updateActiveVideo, 150);
    }, { passive: true });

    // logic default view desktop
    setTimeout(() => {
        if (window.innerWidth > 1440 && phones.length >= 3) {
            scrollContainReels.scrollTo({ left: Math.floor(phones.length / 2) * getScrollDistance(), behavior: "instant" });
        }
        updateActiveVideo();
    }, 100);

    // logic looping btn dan resize
    let resizeTimer;
    window.addEventListener("resize", () => {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(() => {
            if (window.innerWidth > 1440) scrollContainReels.scrollTo({ left: getCurrentIndex() * getScrollDistance(), behavior: "auto" });
            checkLayout(); updateActiveVideo();
        }, 100);
    });

    // logic scroll dan btn panah
    function smoothScrollWithLock(scrollOptions) {
        window.hasUserInteractedReels = isAutoScrolling = true;
        clearTimeout(scrollTimeoutVideo);
        scrollContainReels[scrollOptions.type === 'to' ? 'scrollTo' : 'scrollBy']({ left: scrollOptions.left, behavior: "smooth" });
        setTimeout(() => { isAutoScrolling = false; updateActiveVideo(); }, 600);
    }
    btnNextReels.addEventListener("click", () => {
        getCurrentIndex() >= phones.length - 1 ? smoothScrollWithLock({ type: 'to', left: 0 }) : smoothScrollWithLock({ type: 'by', left: getScrollDistance() });
    });
    btnPrevReels.addEventListener("click", () => {
        getCurrentIndex() <= 0 ? smoothScrollWithLock({ type: 'to', left: scrollContainReels.scrollWidth }) : smoothScrollWithLock({ type: 'by', left: -getScrollDistance() });
    });
});
