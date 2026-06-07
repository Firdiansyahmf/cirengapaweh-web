// NAVBAR JS -> KONEK FOOTER DAN ctaWA JUGA

const initialHash = window.location.hash;

window.addEventListener("DOMContentLoaded", () => {
    const navbar = document.querySelector(".navbar");
    const navbarCollapse = document.getElementById("navbarNav");
    let isMobileMenuOpen = false;
    let scrollTicking = false; // flag throttling

    // btn klik? kunci inieh
    let isAutoScrolling = false;
    let autoScrollTimeout = null;

    // logic scroll bg
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

    // fix offset load dari others page
    window.addEventListener("load", () => {
        if (initialHash) {
            const targetElement = document.querySelector(initialHash);
            if (targetElement) {
                setTimeout(() => {
                    const offset = initialHash === "#reels" ? 144 : 94;
                    window.scrollTo({
                        top: targetElement.getBoundingClientRect().top + window.scrollY - offset,
                        behavior: "smooth"
                    });
                }, 100);
            }
        }
    });

    // logic scroll offset halaman yg sama
    document.querySelectorAll(".hero .btnPlayWrap, .hero .btnOutline, .nav-link, .footer .aMenu a, .ctaWA .btnPrimary").forEach(trigger => {
        trigger.addEventListener("click", function (e) {
            let targetId = this.getAttribute("href") || (this.classList.contains("btnPlayWrap") ? "#reels" : this.classList.contains("btnOutline") ? "#menu" : null);

            if (targetId == "#reels") {
                window.hasUserInteractedReels = true;
                window.dispatchEvent(new CustomEvent("triggerReelsAudio"));
            }

            if (targetId && targetId.startsWith("#")) {
                e.preventDefault();

                // logic lock observer dulu
                isAutoScrolling = true;
                clearTimeout(autoScrollTimeout);
                autoScrollTimeout = setTimeout(() => {
                    isAutoScrolling = false;
                }, 1000);

                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    const offset = targetId === "#reels" ? 144 : 94;
                    window.scrollTo({
                        top: targetElement.getBoundingClientRect().top + window.scrollY - offset,
                        behavior: "smooth"
                    });

                    // red manual
                    const navLinks = document.querySelectorAll(".navbar-nav .nav-link");
                    navLinks.forEach(link => link.classList.remove("active"));
                    const isDefaultZone = ["#hero", "#lokasi", "#ctaWA", "#footer"].includes(targetId);

                    if (isDefaultZone) {
                        if (navLinks[0]) navLinks[0].classList.add("active");
                    } else {
                        const targetLink = document.querySelector(`.navbar-nav .nav-link[href="${targetId}"]`);
                        if (targetLink) targetLink.classList.add("active");
                    }

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

            // scroll karena klik? jgn beraksi
            if (isAutoScrolling) return;

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
        }, { rootMargin: "32px 0px -20% 0px", threshold: 0 });
        document.querySelectorAll("#hero, #promo, #menu, #reels, #mitra, #lokasi, #ctaWA").forEach(s => pindah.observe(s));
    }
});
