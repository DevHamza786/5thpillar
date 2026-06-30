document.documentElement.classList.remove("no-js");
document.documentElement.classList.add("js");

document.addEventListener("DOMContentLoaded", function () {
    (function () {
        function viewerPath(pathname) {
            if (!pathname || pathname.indexOf("/pdf-viewer/") !== -1) {
                return null;
            }
            if (!/^\/assets\/pdfs\/.+\.pdf$/i.test(pathname)) {
                return null;
            }

            return "/pdf-viewer" + pathname;
        }

        function rewritePdfLink(link) {
            var href = link.getAttribute("href");
            if (!href || href === "#" || href.indexOf("javascript:") === 0) {
                return;
            }

            try {
                var url = new URL(href, window.location.origin);
                var viewer = viewerPath(url.pathname);
                if (viewer) {
                    link.setAttribute("href", viewer + url.search + url.hash);
                }
            } catch (ignore) {
                /* invalid URL */
            }
        }

        document.querySelectorAll("a[href]").forEach(rewritePdfLink);
    })();

    var scrollButton = document.querySelector(".trx_addons_scroll_to_top");
    var header = document.querySelector(".site-header");
    var headerRows = [];
    var navPlaceholder = null;
    if (header) {
        headerRows = Array.prototype.slice.call(header.querySelectorAll(".sc_layouts_row_type_compact"));
        navPlaceholder = header.querySelector(".sc_layouts_row_fixed_placeholder");
    }

    var isHome = document.body.classList.contains("home");

    (function initMobileMenu() {
        var menu = document.querySelector(".menu_mobile");
        var overlay = document.querySelector(".menu_mobile_overlay");
        if (!menu || !overlay) {
            return;
        }

        function setMenuOpen(open) {
            menu.classList.toggle("opened", open);
            menu.classList.toggle("menu_mobile_opened", open);
            overlay.classList.toggle("menu_mobile_overlay_opened", open);
            document.body.classList.toggle("menu_mobile_active", open);
        }

        function openMenu(event) {
            if (event) {
                event.preventDefault();
                event.stopPropagation();
            }
            setMenuOpen(true);
        }

        function closeMenu(event) {
            if (event) {
                event.preventDefault();
                event.stopPropagation();
            }
            setMenuOpen(false);
        }

        document.querySelectorAll(
            ".laravel-header-mobile-toggle a, .laravel-header-mobile-toggle .sc_layouts_item_link, .sc_layouts_menu_mobile_button > a"
        ).forEach(function (link) {
            link.addEventListener("click", openMenu);
        });

        document.querySelectorAll(".menu_mobile_close, .menu_mobile_overlay").forEach(function (el) {
            el.addEventListener("click", closeMenu);
        });

        document.addEventListener("keydown", function (event) {
            if (event.key === "Escape" && menu.classList.contains("opened")) {
                closeMenu();
            }
        });
    })();

    document.querySelectorAll(".menu_mobile_nav_area .menu-item-has-children > a").forEach(function (link) {
        link.addEventListener("click", function (event) {
            if (window.innerWidth >= 1280) {
                return;
            }

            event.preventDefault();
            link.parentElement.classList.toggle("menu-item-open");
        });
    });

    document.querySelectorAll(".menu_mobile_nav_area a[target='_blank']").forEach(function (link) {
        link.addEventListener("click", function () {
            if (window.jQuery) {
                window.jQuery(".menu_mobile_close").trigger("click");
            }
        });
    });

    if (window.jQuery && window.jQuery.fn && window.jQuery.fn.superfish) {
        window.jQuery(".sc_layouts_menu_nav").superfish({
            delay: 500,
            animation: { opacity: "show" },
            animationOut: { opacity: "hide" },
            speed: 300,
            speedOut: 200,
            autoArrows: false,
            dropShadows: false
        });
    }

    function toggleScrollButton() {
        if (!scrollButton) {
            return;
        }

        if (window.scrollY > 300) {
            scrollButton.classList.add("show");
        } else {
            scrollButton.classList.remove("show");
        }
    }

    if (scrollButton) {
        scrollButton.addEventListener("click", function (event) {
            event.preventDefault();
            window.scrollTo({
                top: 0,
                behavior: "smooth"
            });
        });

        toggleScrollButton();
        window.addEventListener("scroll", toggleScrollButton, { passive: true });
    }

    function applyHeaderScrolledState() {
        if (!header) {
            return;
        }

        if (isHome) {
            var shouldCompact = window.scrollY > 50;
            header.classList.toggle("header-scrolled", shouldCompact);
            headerRows.forEach(function (row) {
                if (window.innerWidth < 1280 && row.classList.contains("laravel-header-menu-row")) {
                    row.classList.remove("sc_layouts_row_fixed_on");
                    return;
                }
                row.classList.toggle("sc_layouts_row_fixed_on", shouldCompact);
            });
            if (navPlaceholder) {
                navPlaceholder.style.height = "";
            }
            if (shouldCompact) {
                document.body.style.paddingTop = header.offsetHeight + "px";
            } else {
                document.body.style.paddingTop = "";
            }
            return;
        }

        /* Non-home: match WordPress — only the main nav row gets sc_layouts_row_fixed_on + top:0; logo/title band scrolls away. */
        header.classList.remove("header-scrolled");
        document.body.style.paddingTop = "";

        var menuRow = header.querySelector(".laravel-header-menu-row");
        var logoRow = header.querySelector(".laravel-header-logo-row");

        if (logoRow) {
            logoRow.classList.remove("sc_layouts_row_fixed_on");
        }

        if (!menuRow) {
            return;
        }

        /* Drawer layout: menu row is hidden in CSS — do not pin an invisible bar */
        if (window.innerWidth < 1280) {
            menuRow.classList.remove("sc_layouts_row_fixed_on");
            if (navPlaceholder) {
                navPlaceholder.style.height = "";
            }
            return;
        }

        var shouldFixMenu = window.scrollY > 50;

        menuRow.classList.toggle("sc_layouts_row_fixed_on", shouldFixMenu);

        if (navPlaceholder) {
            if (shouldFixMenu) {
                navPlaceholder.style.height = menuRow.offsetHeight + "px";
            } else {
                navPlaceholder.style.height = "";
            }
        }
    }

    applyHeaderScrolledState();
    window.addEventListener("scroll", applyHeaderScrolledState, { passive: true });
    window.addEventListener("resize", applyHeaderScrolledState);

    // Homepage hero slider (simple, smooth fade)
    var slider = document.querySelector("[data-hero-slider]");
    if (slider) {
        var slides = Array.prototype.slice.call(slider.querySelectorAll("[data-hero-slide]"));
        var prevBtn = slider.querySelector("[data-hero-prev]");
        var nextBtn = slider.querySelector("[data-hero-next]");
        var activeIndex = Math.max(0, slides.findIndex(function (el) { return el.classList.contains("is-active"); }));
        var timer = null;

        function setActive(index) {
            if (!slides.length) return;
            slides[activeIndex].classList.remove("is-active");
            activeIndex = (index + slides.length) % slides.length;
            slides[activeIndex].classList.add("is-active");
        }

        function next() { setActive(activeIndex + 1); }
        function prev() { setActive(activeIndex - 1); }

        function restartTimer() {
            if (timer) window.clearInterval(timer);
            timer = window.setInterval(next, 6500);
        }

        if (prevBtn) prevBtn.addEventListener("click", function () { prev(); restartTimer(); });
        if (nextBtn) nextBtn.addEventListener("click", function () { next(); restartTimer(); });

        restartTimer();
    }

    // News & events — 3-up carousel with autoplay and arrows
    var newsRoot = document.querySelector("[data-news-slider]");
    if (newsRoot) {
        var newsViewport = newsRoot.querySelector("[data-news-viewport]");
        var newsTrack = newsRoot.querySelector("[data-news-track]");
        var newsPrev = newsRoot.querySelector("[data-news-prev]");
        var newsNext = newsRoot.querySelector("[data-news-next]");
        var newsItems = newsTrack ? newsTrack.querySelectorAll(".laravel-news-item") : [];

        if (newsTrack && newsViewport && newsItems.length) {
            var newsAutoplayMs = parseInt(newsRoot.getAttribute("data-news-autoplay"), 10);
            if (isNaN(newsAutoplayMs)) {
                newsAutoplayMs = 3000;
            }
            var newsSpeedMs = parseInt(newsRoot.getAttribute("data-news-speed"), 10);
            if (isNaN(newsSpeedMs)) {
                newsSpeedMs = 500;
            }
            var slidesDesktop = parseInt(newsRoot.getAttribute("data-news-slides-desktop"), 10) || 3;
            var slidesTablet = parseInt(newsRoot.getAttribute("data-news-slides-tablet"), 10) || 2;
            var slidesMobile = parseInt(newsRoot.getAttribute("data-news-slides-mobile"), 10) || 1;

            var newsIndex = 0;
            var newsTimer = null;

            function newsSlidesToShow() {
                var w = window.innerWidth;
                if (w <= 768) {
                    return slidesMobile;
                }
                if (w <= 1024) {
                    return slidesTablet;
                }
                return slidesDesktop;
            }

            function newsMaxIndex() {
                var v = newsSlidesToShow();
                return Math.max(0, newsItems.length - v);
            }

            function newsApply() {
                var newsGapPx = (function () {
                    var cs = window.getComputedStyle(newsTrack);
                    var g = cs.columnGap || cs.gap || "32px";
                    return parseFloat(g) || 32;
                })();

                var v = newsSlidesToShow();
                var mx = newsMaxIndex();
                if (newsIndex > mx) {
                    newsIndex = mx;
                }

                newsTrack.style.transition = "transform " + newsSpeedMs + "ms ease";

                var vw = newsViewport.getBoundingClientRect().width;
                var totalGap = newsGapPx * Math.max(0, v - 1);
                var itemW = v > 0 ? (vw - totalGap) / v : vw;

                for (var j = 0; j < newsItems.length; j++) {
                    newsItems[j].style.width = itemW + "px";
                    newsItems[j].style.flex = "0 0 " + itemW + "px";
                }

                var offset = newsIndex * (itemW + newsGapPx);
                newsTrack.style.transform = "translate3d(" + -offset + "px,0,0)";
            }

            function newsGoNext() {
                var mx = newsMaxIndex();
                if (mx <= 0) {
                    return;
                }
                if (newsIndex >= mx) {
                    newsIndex = 0;
                } else {
                    newsIndex += 1;
                }
                newsApply();
            }

            function newsGoPrev() {
                var mx = newsMaxIndex();
                if (mx <= 0) {
                    return;
                }
                if (newsIndex <= 0) {
                    newsIndex = mx;
                } else {
                    newsIndex -= 1;
                }
                newsApply();
            }

            function newsRestartAutoplay() {
                if (newsTimer) {
                    window.clearInterval(newsTimer);
                    newsTimer = null;
                }
                if (newsAutoplayMs > 0 && newsMaxIndex() > 0) {
                    newsTimer = window.setInterval(newsGoNext, newsAutoplayMs);
                }
            }

            if (newsPrev) {
                newsPrev.addEventListener("click", function () {
                    newsGoPrev();
                    newsRestartAutoplay();
                });
            }
            if (newsNext) {
                newsNext.addEventListener("click", function () {
                    newsGoNext();
                    newsRestartAutoplay();
                });
            }

            newsRoot.addEventListener("mouseenter", function () {
                if (newsTimer) {
                    window.clearInterval(newsTimer);
                    newsTimer = null;
                }
            });
            newsRoot.addEventListener("mouseleave", newsRestartAutoplay);

            var newsResizeT = null;
            window.addEventListener("resize", function () {
                window.clearTimeout(newsResizeT);
                newsResizeT = window.setTimeout(function () {
                    newsApply();
                }, 120);
            });

            newsApply();
            newsRestartAutoplay();
        }
    }

    /* WPBakery vc_tta accordion (e.g. Board of Directors) — composer front-end script not bundled */
    document.querySelectorAll(".laravel-board-directors-page .vc_tta-container").forEach(function (container) {
        var panels = Array.prototype.slice.call(container.querySelectorAll(".vc_tta-panel"));

        function setIconState(panel, active) {
            var icon = panel.querySelector(".vc_tta-controls-icon");
            if (!icon) {
                return;
            }
            icon.classList.remove("vc_tta-controls-icon-plus", "vc_tta-controls-icon-minus");
            icon.classList.add(active ? "vc_tta-controls-icon-minus" : "vc_tta-controls-icon-plus");
        }

        function activate(panel) {
            panels.forEach(function (p) {
                var on = p === panel;
                p.classList.toggle("vc_active", on);
                setIconState(p, on);
                var trigger = p.querySelector(".vc_tta-panel-title > a");
                if (trigger) {
                    trigger.setAttribute("aria-expanded", on ? "true" : "false");
                }
            });
        }

        panels.forEach(function (panel) {
            var link = panel.querySelector(".vc_tta-panel-title > a");
            if (!link) {
                return;
            }
            var initiallyActive = panel.classList.contains("vc_active");
            link.setAttribute("aria-expanded", initiallyActive ? "true" : "false");
            setIconState(panel, initiallyActive);
            link.addEventListener("click", function (event) {
                event.preventDefault();
                if (panel.classList.contains("vc_active")) {
                    return;
                }
                activate(panel);
            });
        });
    });

    /* Contact Us — Complaint / Inquiry tabs (single form, hidden contact_type) */
    var contactTabsRoot = document.querySelector("[data-contact-tabs]");
    if (contactTabsRoot) {
        var typeInput = contactTabsRoot.querySelector("[data-contact-type-input]");
        var tabButtons = contactTabsRoot.querySelectorAll("[data-contact-tab]");

        function activateContactTab(type) {
            tabButtons.forEach(function (btn) {
                var on = btn.getAttribute("data-contact-tab") === type;
                btn.classList.toggle("is-active", on);
                btn.setAttribute("aria-selected", on ? "true" : "false");
            });
            if (typeInput) {
                typeInput.value = type;
            }
        }

        tabButtons.forEach(function (btn) {
            btn.addEventListener("click", function () {
                activateContactTab(btn.getAttribute("data-contact-tab"));
            });
        });

        var initialType = contactTabsRoot.getAttribute("data-initial-contact-type") || "complaint";
        activateContactTab(initialType);
    }
});
