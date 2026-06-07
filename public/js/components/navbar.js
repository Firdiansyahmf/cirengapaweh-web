// NAVBAR JS -> KONEK FOOTER JUGA
window.addEventListener("DOMContentLoaded", () => {
    const navbar = document.querySelector(".navbar");
    const navbarCollapse = document.getElementById("navbarNav");
    let isMobileMenuOpen = false;
    let scrollTicking = false; // flag throttling scroll

    // logic scroll background
    const checkScroll = () => {
        navbar.classList.toggle("scrolled", window.scrollY > 1 || isMobileMenuOpen);
        scrollTicking = false;
    };
    checkScroll();

    window.addEventListener("scroll", () => {
        if (!scrollTicking) {
            window.requestAnimationFrame(checkScroll);
            scrollTicking = true;
        }
    }, { passive: true });

    if (navbarCollapse) {
        navbarCollapse.addEventListener("show.bs.collapse", () => { isMobileMenuOpen = true; checkScroll(); });
        navbarCollapse.addEventListener("hide.bs.collapse", () => { isMobileMenuOpen = false; checkScroll(); });
    }

    // logic scroll offset
    document.querySelectorAll(".hero .btnPlayWrap, .hero .btnOutline, .nav-link, .footer .aMenu a").forEach(trigger => {
        trigger.addEventListener("click", function (e) {
            let targetId = this.getAttribute("href") || (this.classList.contains("btnPlayWrap") ? "#reels" : this.classList.contains("btnOutline") ? "#menu" : null);

            // konek ke reels.js
            if (targetId == "#reels") {
                window.hasUserInteractedReels = true;
                window.dispatchEvent(new CustomEvent("triggerReelsAudio"));
            }

            if (targetId && targetId.startsWith("#")) {
                e.preventDefault();
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    const offset = targetId === "#reels" ? 144 : 94;
                    window.scrollTo({
                        top: targetElement.getBoundingClientRect().top + window.scrollY - offset,
                        behavior: "smooth"
                    });
                    // logic clean url beranda
                    if (targetId === "#hero") {
                        history.replaceState(null, null, window.location.pathname);
                    } else {
                        history.replaceState(null, null, targetId);
                    }
                    // logic auto close
                    if (navbarCollapse?.classList.contains("show")) {
                        bootstrap.Collapse.getInstance(navbarCollapse)?.hide();
                    }
                }
            }
        });
    });

    // logic dinamis path
    if (["/", ""].includes(window.location.pathname)) {
        const navLinks = document.querySelectorAll(".navbar-nav .nav-link");
        const pindah = new IntersectionObserver((sectionView) => {
            sectionView.forEach(area => {
                if (area.isIntersecting) {
                    const idSection = area.target.id;
                    navLinks.forEach(link => link.classList.remove("active"));

                    // logic default path ke root
                    const isDefaultZone = ["hero", "lokasi", "ctaWA", "footer"].includes(idSection);
                    if (isDefaultZone) {
                        if (navLinks[0]) navLinks[0].classList.add("active");
                        if (window.location.hash) {
                            history.replaceState(null, null, window.location.pathname);
                        }
                    } else {
                        const targetLink = document.querySelector(`.navbar-nav .nav-link[href="#${idSection}"]`);
                        if (targetLink) {
                            targetLink.classList.add("active");
                            history.replaceState(null, null, `#${idSection}`);
                        }
                    }
                }
            });
        }, { rootMargin: "-32px 0px -30% 0px", threshold: 0 });

        document.querySelectorAll("#hero, #promo, #menu, #reels, #mitra, #lokasi, #ctaWA").forEach(s => pindah.observe(s));
    }
});
