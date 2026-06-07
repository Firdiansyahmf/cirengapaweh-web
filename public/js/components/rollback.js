(function () {
    function initRollbackToggle() {
        const btn = document.getElementById("rollbackToggle");
        if (!btn) return;

        let lastScrollY = window.scrollY || 0;
        let lastToggleState = false; 

        // initial hidden
        btn.style.display = "none";

        // Sensitivity control (reduce flicker)
        const MIN_SCROLL_Y_TO_SHOW = 200;
        const DELTA_BUFFER_PX = 20;

        function isChatbotOpen() {
            const bot = document.getElementById("chatbotBox");
            // chatbot dibuka dengan class "tampil"
            return !!(bot && bot.classList && bot.classList.contains("tampil"));
        }

        window.addEventListener(
            "scroll",
            () => {
                // Jika chatbot pop-up aktif (terutama mobile), rollback tidak boleh muncul
                if (isChatbotOpen()) {
                    btn.style.display = "none";
                    lastToggleState = false;
                    lastScrollY = window.scrollY || 0;
                    return;
                }

                const currentScrollY = window.scrollY || 0;
                const delta = currentScrollY - lastScrollY;

                const isMeaningfulUp = delta < -DELTA_BUFFER_PX;
                const isMeaningfulDown = delta > DELTA_BUFFER_PX;

                const shouldBeVisibleByPosition = currentScrollY > MIN_SCROLL_Y_TO_SHOW;
                const isNearTopToForceHide = currentScrollY <= MIN_SCROLL_Y_TO_SHOW;

                let shouldShow = lastToggleState;

                if (isNearTopToForceHide) {
                    shouldShow = false;
                } else if (isMeaningfulUp && shouldBeVisibleByPosition) {
                    shouldShow = true;
                } else if (isMeaningfulDown) {
                    shouldShow = false;
                }

                btn.style.display = shouldShow ? "flex" : "none";
                lastToggleState = shouldShow;
                lastScrollY = currentScrollY;
            },
            { passive: true }
        );
    }

    document.addEventListener("DOMContentLoaded", initRollbackToggle);
})();
