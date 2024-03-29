(function($) {
    $.neveUtilities = {
        isMobile: function() {
            var windowWidth = window.innerWidth;
            return windowWidth <= 960;
        },
        isElementInViewport: function(el) {
            if (typeof $ === "function" && el instanceof $) {
                el = el[0];
            }
            var rect = el.getBoundingClientRect();
            return rect.top >= 0 && rect.left >= 0 && rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) && rect.right <= (window.innerWidth || document.documentElement.clientWidth);
        }
    };
})(jQuery);

(function($) {
    var utils = $.neveUtilities;
    $.neveNavigation = {
        init: function() {
            this.repositionDropdowns();
            this.handleResponsiveNav();
            this.handleMobileDropdowns();
            this.handleSearch();
        },
        repositionDropdowns: function() {
            if (utils.isMobile()) {
                return false;
            }
            var windowWidth = window.innerWidth;
            var dropDowns = $(".sub-menu .sub-menu");
            if (dropDowns.length === 0) {
                return false;
            }
            $.each(dropDowns, function(key, dropDown) {
                var submenu = $(dropDown);
                var bounding = submenu.offset().left;
                if (/webkit.*mobile/i.test(navigator.userAgent)) {
                    bounding -= window.scrollX;
                }
                var dropDownWidth = submenu.outerWidth();
                if (bounding + dropDownWidth >= windowWidth) {
                    $(dropDown).css({
                        right: "100%",
                        left: "auto"
                    });
                }
            });
            return false;
        },
        handleResponsiveNav: function() {
            $(".navbar-toggle").on("click", function() {
                $(".dropdown-open").removeClass("dropdown-open");
                $("#nv-primary-navigation").toggleClass("responsive-opened");
                $(this).toggleClass("active");
                $("html").toggleClass("menu-opened");
            });
        },
        handleMobileDropdowns: function() {
            $(".caret-wrap").on("click touchstart", function() {
                if (!utils.isMobile()) {
                    return false;
                }
                $(this).parent().toggleClass("dropdown-open");
                return false;
            });
        },
        handleSearch: function() {
            var self = this;
            $(".nv-nav-search").on("click", function(e) {
                e.stopPropagation();
            });
            $(".menu-item-nav-search").on("click", function() {
                if (utils.isMobile()) {
                    return false;
                }
                $(this).toggleClass("active");
                self.createNavOverlay();
                $(".nv-nav-search .search-field").focus();
                return false;
            });
        },
        createNavOverlay: function() {
            if (utils.isMobile()) {
                return false;
            }
            var navClickaway = $(".nav-clickaway-overlay");
            if (navClickaway.length > 0) {
                return false;
            }
            navClickaway = document.createElement("div");
            navClickaway.setAttribute("class", "nav-clickaway-overlay");
            $("#nv-primary-navigation").after(navClickaway);
            $(navClickaway).on("touchstart click", function() {
                this.remove();
                $("#nv-primary-navigation li").removeClass("active");
            });
            return false;
        }
    };
})(jQuery);

(function($) {
    var utils = $.neveUtilities;
    $.neveBlog = {
        init: function() {
            this.handleMasonry();
            this.handleInfiniteScroll();
        },
        handleMasonry: function() {
            if (NeveProperties.masonry !== "enabled") {
                return false;
            }
            var postsWrap = $(".nv-index-posts .posts-wrapper");
            if (postsWrap.length === 0) {
                return false;
            }
            $(postsWrap).masonry({
                itemSelector: "article.layout-grid",
                columnWidth: "article.layout-grid",
                percentPosition: true
            });
        },
        handleInfiniteScroll: function() {
            if (NeveProperties.infiniteScroll !== "enabled") {
                return false;
            }
            var postsWrap = $(".nv-index-posts");
            if (!postsWrap.length) {
                return false;
            }
            var lock = false;
            var page = 2;
            $(window).scroll(function() {
                var trigger = postsWrap.find(".infinite-scroll-trigger");
                var reachedTrigger = utils.isElementInViewport(trigger);
                if (reachedTrigger === false || lock === true) {
                    return false;
                }
                if (page > NeveProperties.infiniteScrollMaxPages) {
                    return false;
                }
                var counter = $("article").length;
                lock = true;
                $.ajax({
                    type: "POST",
                    url: NeveProperties.ajaxurl,
                    data: {
                        action: "infinite_scroll",
                        page: page,
                        counter: counter,
                        nonce: NeveProperties.nonce
                    },
                    beforeSend: function() {
                        $(".nv-loader").css("display", "block");
                    },
                    success: function(response) {
                        if (response) {
                            $(".nv-loader").hide();
                            var postGrid = $(".nv-index-posts .posts-wrapper");
                            postGrid.append(response);
                            if (NeveProperties.masonry === "enabled") {
                                $(postGrid).masonry("reloadItems");
                                $(postGrid).imagesLoaded().progress(function() {
                                    $(postGrid).masonry("layout");
                                });
                            }
                            page++;
                            lock = false;
                        }
                    }
                });
            });
        }
    };
})(jQuery);

jQuery(document).ready(function() {
    jQuery.neveNavigation.init();
});

jQuery(window).load(function() {
    jQuery.neveBlog.init();
});

var resizeTimeout;

jQuery(window).on("resize", function() {
    clearTimeout(resizeTimeout);
    resizeTimeout = setTimeout(function() {
        jQuery.neveNavigation.repositionDropdowns();
    }, 500);
});
//# sourceMappingURL=script.js.map