(function ($) {
    "use strict";

    /*===========================================
        =         On Load Function         =
    =============================================*/
    $(window).on("load", function () {
        $(".preloader").fadeOut();
    });

    $(window).on('resize', function () {
        $(".slick-slider").slick("refresh");
    });

    /*===========================================
        =         Preloader         =
    =============================================*/
    if ($(".preloader").length > 0) {
        $(".preloaderCls").each(function () {
            $(this).on("click", function (e) {
                e.preventDefault();
                $(".preloader").css("display", "none");
            });
        });
    }

    /*===========================================
        =         Mobile Menu Active         =
    =============================================*/
    $.fn.mobilemenu = function (options) {
        var opt = $.extend(
            {
                menuToggleBtn: ".menu-toggle",
                bodyToggleClass: "body-visible",
                subMenuClass: "submenu-class",
                subMenuParent: "submenu-item-has-children",
                subMenuParentToggle: "active-class",
                meanExpandClass: "mean-expand-class",
                appendElement: '<span class="mean-expand-class"></span>',
                subMenuToggleClass: "menu-open",
                toggleSpeed: 400,
            },
            options
        );

        return this.each(function () {
            var menu = $(this); // Select menu

            // Menu Show & Hide
            function menuToggle() {
                menu.toggleClass(opt.bodyToggleClass);

                // collapse submenu on menu hide or show
                var subMenu = "." + opt.subMenuClass;
                $(subMenu).each(function () {
                    if ($(this).hasClass(opt.subMenuToggleClass)) {
                        $(this).removeClass(opt.subMenuToggleClass);
                        $(this).css("display", "none");
                        $(this).parent().removeClass(opt.subMenuParentToggle);
                    }
                });
            }

            // Class Set Up for every submenu
            menu.find("li").each(function () {
                var submenu = $(this).find("ul");
                submenu.addClass(opt.subMenuClass);
                submenu.css("display", "none");
                submenu.parent().addClass(opt.subMenuParent);
                submenu.prev("a").append(opt.appendElement);
                submenu.next("a").append(opt.appendElement);
            });

            // Toggle Submenu
            function toggleDropDown($element) {
                if ($($element).next("ul").length > 0) {
                    $($element).parent().toggleClass(opt.subMenuParentToggle);
                    $($element).next("ul").slideToggle(opt.toggleSpeed);
                    $($element).next("ul").toggleClass(opt.subMenuToggleClass);
                } else if ($($element).prev("ul").length > 0) {
                    $($element).parent().toggleClass(opt.subMenuParentToggle);
                    $($element).prev("ul").slideToggle(opt.toggleSpeed);
                    $($element).prev("ul").toggleClass(opt.subMenuToggleClass);
                }
            }

            // Submenu toggle Button
            var expandToggler = "." + opt.meanExpandClass;
            $(expandToggler).each(function () {
                $(this).on("click", function (e) {
                    e.preventDefault();
                    toggleDropDown($(this).parent());
                });
            });

            // Menu Show & Hide On Toggle Btn click
            $(opt.menuToggleBtn).each(function () {
                $(this).on("click", function () {
                    menuToggle();
                });
            });

            // Hide Menu On out side click
            menu.on("click", function (e) {
                e.stopPropagation();
                menuToggle();
            });

            // Stop Hide full menu on menu click
            menu.find("div").on("click", function (e) {
                e.stopPropagation();
            });
        });
    };

    $(".mobile-menu-wrapper").mobilemenu();

    /*===========================================
        =         Sticky fix         =
    =============================================*/
    $(window).scroll(function () {
        var topPos = $(this).scrollTop();
        if (topPos > 500) {
            $('.sticky-wrapper').addClass('sticky');
        } else {
            $('.sticky-wrapper').removeClass('sticky')
        }
    })
    $(window).scroll(function () {
        var topPos = $(this).scrollTop();
        if (topPos > 500) {
            $('.my-header').addClass('sticky');
        } else {
            $('.my-header').removeClass('sticky')
        }
    })

    /*===========================================
        =         Scroll To Top         =
    =============================================*/
    // progressAvtivation
    if ($('.scroll-top')) {
        var scrollTopbtn = document.querySelector('.scroll-top');
        var progressPath = document.querySelector('.scroll-top path');
        var pathLength = progressPath.getTotalLength();
        progressPath.style.transition = progressPath.style.WebkitTransition = 'none';
        progressPath.style.strokeDasharray = pathLength + ' ' + pathLength;
        progressPath.style.strokeDashoffset = pathLength;
        progressPath.getBoundingClientRect();
        progressPath.style.transition = progressPath.style.WebkitTransition = 'stroke-dashoffset 10ms linear';
        var updateProgress = function () {
            var scroll = $(window).scrollTop();
            var height = $(document).height() - $(window).height();
            var progress = pathLength - (scroll * pathLength / height);
            progressPath.style.strokeDashoffset = progress;
        }
        updateProgress();
        $(window).scroll(updateProgress);
        var offset = 50;
        var duration = 750;
        jQuery(window).on('scroll', function () {
            if (jQuery(this).scrollTop() > offset) {
                jQuery(scrollTopbtn).addClass('show');
            } else {
                jQuery(scrollTopbtn).removeClass('show');
            }
        });
        jQuery(scrollTopbtn).on('click', function (event) {
            event.preventDefault();
            jQuery('html, body').animate({scrollTop: 0}, 1);
            return false;
        })
    }

    /*===========================================
        =         Global Slider         =
    =============================================*/
    $(".global-carousel").each(function () {
        var carouselSlide = $(this);

        // Collect Data
        function d(data) {
            return carouselSlide.data(data);
        }

        // Custom Arrow Button
        var prevButton =
                '<button type="button" class="slick-prev"><i class="' +
                d("prev-arrow") +
                '"></i></button>',
            nextButton =
                '<button type="button" class="slick-next"><i class="' +
                d("next-arrow") +
                '"></i></button>';

        // Function For Custom Arrow Btn
        $("[data-slick-next]").each(function () {
            $(this).on("click", function (e) {
                e.preventDefault();
                $($(this).data("slick-next")).slick("slickNext");
            });
        });

        $("[data-slick-prev]").each(function () {
            $(this).on("click", function (e) {
                e.preventDefault();
                $($(this).data("slick-prev")).slick("slickPrev");
            });
        });

        // Check for arrow wrapper
        if (d("arrows") == true) {
            if (!carouselSlide.closest(".arrow-wrap").length) {
                carouselSlide.closest(".container").parent().addClass("arrow-wrap");
            }
        }

        carouselSlide.slick({
            dots: d("dots") ? true : false,
            fade: d("fade") ? true : false,
            arrows: d("arrows") ? true : false,
            speed: d("speed") ? d("speed") : 1000,
            sliderNavfor: d("slidernavfor") ? d("slidernavfor") : false,
            autoplay: d("autoplay") == false ? false : true,
            infinite: d("infinite") == false ? false : true,
            slidesToShow: d("slide-show") ? d("slide-show") : 1,
            adaptiveHeight: d("adaptive-height") ? true : false,
            centerMode: d("center-mode") ? true : false,
            autoplaySpeed: d("autoplay-speed") ? d("autoplay-speed") : 3000,
            centerPadding: d("center-padding") ? d("center-padding") : "0",
            focusOnSelect: d("focuson-select") == false ? false : true,
            pauseOnFocus: d("pauseon-focus") ? true : false,
            pauseOnHover: d("pauseon-hover") ? true : false,
            variableWidth: d("variable-width") ? true : false,
            vertical: d("vertical") ? true : false,
            verticalSwiping: d("vertical") ? true : false,
            prevArrow: d("prev-arrow")
                ? prevButton
                : '<button type="button" class="slick-prev"><i class="fas fa-arrow-left"></i></button>',
            nextArrow: d("next-arrow")
                ? nextButton
                : '<button type="button" class="slick-next"><i class="fas fa-arrow-right"></i></button>',
            rtl: $("html").attr("dir") == "rtl" ? true : false,
            responsive: [
                {
                    breakpoint: 1600,
                    settings: {
                        arrows: d("xl-arrows") ? true : false,
                        dots: d("xl-dots") ? true : false,
                        slidesToShow: d("xl-slide-show")
                            ? d("xl-slide-show")
                            : d("slide-show"),
                        centerMode: d("xl-center-mode") ? true : false,
                        centerPadding: d("xl-center-padding") ? d("xl-center-padding") : "0",
                    },
                },
                {
                    breakpoint: 1400,
                    settings: {
                        arrows: d("ml-arrows") ? true : false,
                        dots: d("ml-dots") ? true : false,
                        slidesToShow: d("ml-slide-show")
                            ? d("ml-slide-show")
                            : d("slide-show"),
                        centerMode: d("ml-center-mode") ? true : false,
                        centerPadding: d("ml-center-padding") ? d("ml-center-padding") : "0",
                    },
                },
                {
                    breakpoint: 1200,
                    settings: {
                        arrows: d("lg-arrows") ? true : false,
                        dots: d("lg-dots") ? true : false,
                        slidesToShow: d("lg-slide-show")
                            ? d("lg-slide-show")
                            : d("slide-show"),
                        centerMode: d("lg-center-mode")
                            ? d("lg-center-mode")
                            : false,
                        centerPadding: d("lg-center-padding") ? d("lg-center-padding") : "0",
                    },
                },
                {
                    breakpoint: 992,
                    settings: {
                        arrows: d("md-arrows") ? true : false,
                        dots: d("md-dots") ? true : false,
                        slidesToShow: d("md-slide-show")
                            ? d("md-slide-show")
                            : 1,
                        centerMode: d("md-center-mode")
                            ? d("md-center-mode")
                            : false,
                        centerPadding: 0,
                    },
                },
                {
                    breakpoint: 768,
                    settings: {
                        arrows: d("sm-arrows") ? true : false,
                        dots: d("sm-dots") ? true : false,
                        slidesToShow: d("sm-slide-show")
                            ? d("sm-slide-show")
                            : 1,
                        centerMode: d("sm-center-mode")
                            ? d("sm-center-mode")
                            : false,
                        centerPadding: 0,
                    },
                },
                {
                    breakpoint: 576,
                    settings: {
                        arrows: d("xs-arrows") ? true : false,
                        dots: d("xs-dots") ? true : false,
                        slidesToShow: d("xs-slide-show")
                            ? d("xs-slide-show")
                            : 1,
                        centerMode: d("xs-center-mode")
                            ? d("xs-center-mode")
                            : false,
                        centerPadding: 0,
                    },
                },
                // You can unslick at a given breakpoint now by adding:
                // settings: "unslick"
                // instead of a settings object
            ],
        });
    });

    /*===========================================
        =         Custom Animaiton For Slider         =
    =============================================*/
    $('[data-ani-duration]').each(function () {
        var durationTime = $(this).data('ani-duration');
        $(this).css('animation-duration', durationTime);
    });

    $('[data-ani-delay]').each(function () {
        var delayTime = $(this).data('ani-delay');
        $(this).css('animation-delay', delayTime);
    });

    $('[data-ani]').each(function () {
        var animaionName = $(this).data('ani');
        $(this).addClass(animaionName);
        $('.slick-current [data-ani]').addClass('slider-animated');
    });

    $('.global-carousel').on('afterChange', function (event, slick, currentSlide, nextSlide) {
        $(slick.$slides).find('[data-ani]').removeClass('slider-animated');
        $(slick.$slides[currentSlide]).find('[data-ani]').addClass('slider-animated');
    })

    /*===========================================
        =         Search Box Popup         =
    =============================================*/
    function popupSarchBox($searchBox, $searchOpen, $searchCls, $toggleCls) {
        $($searchOpen).on("click", function (e) {
            e.preventDefault();
            $($searchBox).addClass($toggleCls);
        });
        $($searchBox).on("click", function (e) {
            e.stopPropagation();
            $($searchBox).removeClass($toggleCls);
        });
        $($searchBox)
            .find("form")
            .on("click", function (e) {
                e.stopPropagation();
                $($searchBox).addClass($toggleCls);
            });
        $($searchCls).on("click", function (e) {
            e.preventDefault();
            e.stopPropagation();
            $($searchBox).removeClass($toggleCls);
        });
    }

    popupSarchBox(
        ".popup-search-box",
        ".searchBoxToggler",
        ".searchClose",
        "show"
    );

    /*===========================================
        =         Popup Sidemenu         =
    =============================================*/
    function popupSideMenu($sideMenu, $sideMunuOpen, $sideMenuCls, $toggleCls) {
        // Sidebar Popup
        $($sideMunuOpen).on('click', function (e) {
            e.preventDefault();
            $($sideMenu).addClass($toggleCls);
        });
        $($sideMenu).on('click', function (e) {
            e.stopPropagation();
            $($sideMenu).removeClass($toggleCls)
        });
        var sideMenuChild = $sideMenu + ' > div';
        $(sideMenuChild).on('click', function (e) {
            e.stopPropagation();
            $($sideMenu).addClass($toggleCls)
        });
        $($sideMenuCls).on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            $($sideMenu).removeClass($toggleCls);
        });
    };
    popupSideMenu('.sidemenu-wrapper', '.sideMenuToggler', '.sideMenuCls', 'show');

    /*===========================================
        =         Magnific Popup         =
    =============================================*/
    /* magnificPopup img view */
    $(".popup-image").magnificPopup({
        type: "image",
        mainClass: 'mfp-zoom-in',
        removalDelay: 260,
        gallery: {
            enabled: true,
        },
    });

    /* magnificPopup video view */
    $(".popup-video").magnificPopup({
        type: "iframe",
        mainClass: 'mfp-zoom-in',
        removalDelay: 260,
    });

    /* magnificPopup video view */
    $(".popup-content").magnificPopup({
        type: "inline",
        midClick: true,
    });

    $(".popup-content").on("click", function () {
        $(".slick-slider").slick("refresh");
    });

    /*===========================================
        =         Isotope Filter         =
    =============================================*/
    $(".filter-active").imagesLoaded(function () {
        var $filter = ".filter-active",
            $filterItem = ".filter-item",
            $filterMenu = ".filter-menu-active";

        if ($($filter).length > 0) {
            var $grid = $($filter).isotope({
                itemSelector: $filterItem,
                filter: "*",
            });

            // filter items on button click
            $($filterMenu).on("click", "button", function () {
                var filterValue = $(this).attr("data-filter");
                $grid.isotope({
                    filter: filterValue,
                });
            });

            // Menu Active Class
            $($filterMenu).on("click", "button", function (event) {
                event.preventDefault();
                $(this).addClass("active");
                $(this).siblings(".active").removeClass("active");
            });
        }
    });

    $(".masonary-active").imagesLoaded(function () {
        var $filter = ".masonary-active",
            $filterItem = ".filter-item",
            $filterMenu = ".filter-menu-active";

        if ($($filter).length > 0) {
            var $grid = $($filter).isotope({
                itemSelector: $filterItem,
                filter: "*",
                masonry: {
                    // use outer width of grid-sizer for columnWidth
                    columnWidth: 1,
                },
            });

            // filter items on button click
            $($filterMenu).on("click", "button", function () {
                var filterValue = $(this).attr("data-filter");
                $grid.isotope({
                    filter: filterValue,
                });
            });

            // Menu Active Class
            $($filterMenu).on("click", "button", function (event) {
                event.preventDefault();
                $(this).addClass("active");
                $(this).siblings(".active").removeClass("active");
            });
        }
    });

    // Active specifix
    $('.filter-active-cat1').imagesLoaded(function () {
        var $filter = '.filter-active-cat1',
            $filterItem = '.filter-item',
            $filterMenu = '.filter-menu-active';

        if ($($filter).length > 0) {
            var $grid = $($filter).isotope({
                itemSelector: $filterItem,
                filter: '.cat1',
                masonry: {
                    // use outer width of grid-sizer for columnWidth
                    columnWidth: 1
                }
            });

            // filter items on button click
            $($filterMenu).on('click', 'button', function () {
                var filterValue = $(this).attr('data-filter');
                $grid.isotope({
                    filter: filterValue
                });
            });

            // Menu Active Class 
            $($filterMenu).on('click', 'button', function (event) {
                event.preventDefault();
                $(this).addClass('active');
                $(this).siblings('.active').removeClass('active');
            });
        }
        ;
    });

    /*===========================================
        =         Counter Up         =
    =============================================*/
    $(".counter-number").counterUp({
        delay: 10,
        time: 1000,
    });

    /*===========================================
        =         Shape Mockup         =
    =============================================*/
    $.fn.shapeMockup = function () {
        var $shape = $(this);
        $shape.each(function () {
            var $currentShape = $(this),
                shapeTop = $currentShape.data("top"),
                shapeRight = $currentShape.data("right"),
                shapeBottom = $currentShape.data("bottom"),
                shapeLeft = $currentShape.data("left");
            $currentShape
                .css({
                    top: shapeTop,
                    right: shapeRight,
                    bottom: shapeBottom,
                    left: shapeLeft,
                })
                .removeAttr("data-top")
                .removeAttr("data-right")
                .removeAttr("data-bottom")
                .removeAttr("data-left")
                .parent()
                .addClass("shape-mockup-wrap");
        });
    };

    if ($(".shape-mockup")) {
        $(".shape-mockup").shapeMockup();
    }

    /*===========================================
        =         Progress Bar Animation         =
    =============================================*/
    $('.progress-bar').waypoint(function () {
        $('.progress-bar').css({
            animation: "animate-positive 1.8s",
            opacity: "1"
        });
    }, {offset: '75%'});

    /*===========================================
	=         Marquee Active         =
    =============================================*/
    if ($(".marquee_mode").length) {

        $('.marquee_mode').marquee({
            speed: 100,
            gap: 0,
            delayBeforeStart: 0,
            direction: $('html').attr('dir') === 'rtl' ? 'right' : 'left',
            duplicated: true,
            pauseOnHover: true,
            startVisible: true,
            direction: 'right'
        });
    }

    /*===========================================
	=         Marquee Active         =
    =============================================*/
    if ($(".marquee_mode2").length) {

        $('.marquee_mode2').marquee({
            speed: 100,
            gap: 0,
            delayBeforeStart: 0,
            direction: $('html').attr('dir') === 'rtl' ? 'right' : 'left',
            duplicated: true,
            pauseOnHover: true,
            startVisible: true,
        });
    }


    // ====================== Brand Slider Js start =========================
    $('.brand-item-wrapper').slick({
        slidesToShow: 5,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 2000,
        speed: 1500,
        dots: false,
        pauseOnHover: true,
        arrows: false,
        draggable: true,
        speed: 900,
        infinite: true,
        prevArrow: '<button type="button" class="slick-prev"><i class="las la-arrow-left"></i></button>',
        nextArrow: '<button type="button" class="slick-next"><i class="las la-arrow-right"></i></button>',
        rtl: $("html").attr("dir") == "rtl" ? true : false,
        responsive: [
            {
                breakpoint: 1199,
                settings: {
                    slidesToShow: 4,
                }
            },
            {
                breakpoint: 991,
                settings: {
                    slidesToShow: 4,
                }
            },
            {
                breakpoint: 767,
                settings: {
                    slidesToShow: 3,
                }
            },
            {
                breakpoint: 575,
                settings: {
                    slidesToShow: 2,
                }
            },
        ]
    });
    // ====================== Brand Slider Js End =========================

    // ====================== Testimonials Slider Js start =========================
    $('.testimonials-slider').slick({
        slidesToShow: 2,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 2000,
        speed: 1500,
        dots: false,
        pauseOnHover: true,
        arrows: false,
        draggable: true,
        speed: 900,
        infinite: true,
        prevArrow: '<button type="button" class="slick-prev"><i class="las la-arrow-left"></i></button>',
        nextArrow: '<button type="button" class="slick-next"><i class="las la-arrow-right"></i></button>',
        rtl: $("html").attr("dir") == "rtl" ? true : false,
        responsive: [
            {
                breakpoint: 1199,
                settings: {
                    slidesToShow: 1,
                }
            },
        ]
    });
    // ====================== Testimonials Slider Js End =========================

    // ====================== Testi Two Slider Js start =========================
    $('.testi-item-slider').slick({
        slidesToShow: 2,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 2000,
        speed: 1500,
        dots: false,
        pauseOnHover: true,
        arrows: false,
        draggable: true,
        speed: 900,
        infinite: true,
        rtl: $("html").attr("dir") == "rtl" ? true : false,
        prevArrow: '<button type="button" class="slick-prev"><i class="las la-arrow-left"></i></button>',
        nextArrow: '<button type="button" class="slick-next"><i class="las la-arrow-right"></i></button>',
        responsive: [
            {
                breakpoint: 1199,
                settings: {
                    slidesToShow: 1,
                }
            },
        ]
    });
    // ====================== Testi Two Slider Js End =========================


    // ====================== Upcoming Event accordion Js start =========================
    $('.upcoming-event-item__title').on('click', function () {
        $('.upcoming-event-item__title').not($(this)).siblings('.upcoming-event-item__hidden').slideUp();
        $('.upcoming-event-item__title').not($(this)).removeClass('text-base-two');
        $('.upcoming-event-item__title').not($(this)).closest('.upcoming-event-item').removeClass('active');

        $(this).siblings('.upcoming-event-item__hidden').slideToggle();
        $(this).toggleClass('text-base-two');
        $(this).closest('.upcoming-event-item').toggleClass('active');
    });


    $('.upcoming-event-item__button').on('click', function () {
        $('.upcoming-event-item__hidden').not($(this).closest('.upcoming-event-item').find('.upcoming-event-item__hidden')).slideUp();
        $('.upcoming-event-item__button').not(this).removeClass('text-base-two');
        $('.upcoming-event-item').not($(this).closest('.upcoming-event-item')).removeClass('active');

        $(this).closest('.upcoming-event-item').find('.upcoming-event-item__hidden').slideToggle();
        $(this).toggleClass('text-base-two');
        $(this).closest('.upcoming-event-item').toggleClass('active');
    });
    // ====================== Upcoming Event accordion Js End =========================


    // ====================== Floating Label progress bar Js Start =========================
    $(".progress-wrapper").each(function () {
        var percentage = $(this).attr("data-perc");
        var floatingLabel = $(this).find(".floating-label");

        // Set CSS variable to be used in keyframes
        floatingLabel.css("--left-percentage", percentage);

        // Trigger reflow to restart animation
        floatingLabel[0].offsetWidth; // Force reflow
        floatingLabel.css("animation-name", "none");
        floatingLabel.css("inset-inline-start", percentage); // Ensure final position is correct
        floatingLabel.css("animation-name", "animateFloatingLabel");
    });

    // ====================== Floating Label progress bar Js End =========================

    // ========================= magnific Popup Icon Js Start =====================
    $('.magnific-video').magnificPopup({
        type: 'iframe'
    });
    // ========================= magnific Popup Icon Js End =====================


    // ========================= Team Section social Share Icon Js Start =====================
    $('.share-button').on('click', function () {
        $('.share-button').not(this).closest('.share-button-wrapper').removeClass('active');
        $(this).closest('.share-button-wrapper').toggleClass('active');
    });
    // ========================= Team Section social Share Icon Js End =====================


    // =============================== Home Current Js Start ===================================
    // =============================== Home Current Js Start ===================================
    // Service Slider
    $('.homeC-service-slider').slick({
        slidesToShow: 4,
        slidesToScroll: 1,
        autoplay: false,
        autoplaySpeed: 2000,
        speed: 1500,
        dots: false,
        pauseOnHover: true,
        arrows: true,
        draggable: true,
        rtl: $('html').attr('dir') === 'rtl' ? true : false,
        speed: 900,
        infinite: true,
        nextArrow: '#homeC-service-next',
        prevArrow: '#homeC-service-prev',
        responsive: [
            {
                breakpoint: 1199,
                settings: {
                    slidesToShow: 3,
                    arrows: false,
                }
            },
            {
                breakpoint: 991,
                settings: {
                    slidesToShow: 2,
                    arrows: false,
                }
            },
            {
                breakpoint: 767,
                settings: {
                    slidesToShow: 2,
                    arrows: false,
                }
            },
            {
                breakpoint: 575,
                settings: {
                    slidesToShow: 1,
                    arrows: false,
                }
            },
        ]
    });

    // Service Slider
    $('.homeC-portfolio-slider').slick({
        slidesToShow: 3,
        slidesToScroll: 1,
        autoplay: false,
        autoplaySpeed: 2000,
        speed: 1500,
        dots: false,
        pauseOnHover: true,
        arrows: true,
        draggable: true,
        rtl: $('html').attr('dir') === 'rtl' ? true : false,
        speed: 900,
        infinite: true,
        nextArrow: '#homeC-portfolio-next',
        prevArrow: '#homeC-portfolio-prev',
        responsive: [
            {
                breakpoint: 1599,
                settings: {
                    slidesToShow: 2,
                    arrows: false,
                }
            },
            {
                breakpoint: 991,
                settings: {
                    slidesToShow: 2,
                    arrows: false,
                }
            },
            {
                breakpoint: 767,
                settings: {
                    slidesToShow: 2,
                    arrows: false,
                }
            },
            {
                breakpoint: 575,
                settings: {
                    slidesToShow: 1,
                    arrows: false,
                }
            },
        ]
    });


    // ========================= Team Section Socail Infos Button Js Start ===================
    $('.social-infos .social-infos__button').on('click', function () {
        $('.social-list').not($(this).siblings('.social-list')).removeClass('d-flex');
        $('.social-infos .social-infos__button').not($(this)).removeClass('active');
        $(this).siblings('.social-list').toggleClass('d-flex');
        $(this).toggleClass('active');
    });

    // Team Slider
    $('.expert-team-slider').slick({
        slidesToShow: 4,
        slidesToScroll: 1,
        autoplay: false,
        autoplaySpeed: 2000,
        speed: 1500,
        dots: false,
        pauseOnHover: true,
        arrows: true,
        draggable: true,
        rtl: $('html').attr('dir') === 'rtl' ? true : false,
        speed: 900,
        infinite: true,
        nextArrow: '#expert-team-next',
        prevArrow: '#expert-team-prev',
        responsive: [
            {
                breakpoint: 1599,
                settings: {
                    slidesToShow: 3,
                    arrows: false,
                }
            },
            {
                breakpoint: 1199,
                settings: {
                    slidesToShow: 2,
                    arrows: false,
                }
            },
            {
                breakpoint: 991,
                settings: {
                    slidesToShow: 2,
                    arrows: false,
                }
            },
            {
                breakpoint: 767,
                settings: {
                    slidesToShow: 2,
                    arrows: false,
                }
            },
            {
                breakpoint: 575,
                settings: {
                    slidesToShow: 2,
                    arrows: false,
                }
            },
            {
                breakpoint: 424,
                settings: {
                    slidesToShow: 1,
                    arrows: false,
                }
            },
        ]
    });

    // Testimoanils Slider
    $('.homeC-testimonial-slider').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        autoplay: false,
        autoplaySpeed: 2000,
        speed: 1500,
        dots: false,
        pauseOnHover: true,
        arrows: true,
        draggable: true,
        rtl: $('html').attr('dir') === 'rtl' ? true : false,
        speed: 900,
        infinite: true,
        nextArrow: '#homeC-testimonial-next',
        prevArrow: '#homeC-testimonial-prev',
    });

    // Team Slider
    $('.homeCone-blog-slider').slick({
        slidesToShow: 3,
        slidesToScroll: 1,
        autoplay: false,
        autoplaySpeed: 2000,
        speed: 1500,
        dots: false,
        pauseOnHover: true,
        arrows: true,
        draggable: true,
        rtl: $('html').attr('dir') === 'rtl' ? true : false,
        speed: 900,
        infinite: true,
        nextArrow: '#homeCone-blog-next',
        prevArrow: '#homeCone-blog-prev',
        responsive: [
            {
                breakpoint: 1199,
                settings: {
                    slidesToShow: 2,
                    arrows: false,
                }
            },
            {
                breakpoint: 991,
                settings: {
                    slidesToShow: 2,
                    arrows: false,
                }
            },
            {
                breakpoint: 767,
                settings: {
                    slidesToShow: 2,
                    arrows: false,
                }
            },
            {
                breakpoint: 575,
                settings: {
                    slidesToShow: 2,
                    arrows: false,
                }
            },
            {
                breakpoint: 424,
                settings: {
                    slidesToShow: 1,
                    arrows: false,
                }
            },
        ]
    });

    if ($(".bg-img").length) {
        $(".bg-img").each(function () {
            var bgImage = $(this).data("background-image");
            if (bgImage) {
                $(this).css('background-image', 'url(' + bgImage + ')');
            }
        });
    }
    //  Background Image Js End 
    // =============================== Home Current Js End ===================================


    // =============================== Home Six Js Start ===================================
    $('.key-word .key').on('mouseenter', function () {
        $('.key-word .key').removeClass('active');
        $(this).addClass('active');
    });
    // =============================== Home Six Js End ===================================


    /*===========================================
	=         lettering JS         =
    =============================================*/
    function injector(t, splitter, klass, after) {
        var a = t.text().split(splitter), inject = '';
        if (a.length) {
            $(a).each(function (i, item) {
                inject += '<span class="' + klass + (i + 1) + '">' + item + '</span>' + after;
            });
            t.empty().append(inject);
        }
    }

    var methods = {
        init: function () {

            return this.each(function () {
                injector($(this), '', 'char', '');
            });

        },

        words: function () {

            return this.each(function () {
                injector($(this), ' ', 'word', ' ');
            });

        },

        lines: function () {

            return this.each(function () {
                var r = "eefec303079ad17405c889e092e105b0";
                // Because it's hard to split a <br/> tag consistently across browsers,
                // (*ahem* IE *ahem*), we replaces all <br/> instances with an md5 hash
                // (of the word "split").  If you're trying to use this plugin on that
                // md5 hash string, it will fail because you're being ridiculous.
                injector($(this).children("br").replaceWith(r).end(), r, 'line', '');
            });

        }
    };

    $.fn.lettering = function (method) {
        // Method calling logic
        if (method && methods[method]) {
            return methods[method].apply(this, [].slice.call(arguments, 1));
        } else if (method === 'letters' || !method) {
            return methods.init.apply(this, [].slice.call(arguments, 0)); // always pass an array
        }
        $.error('Method ' + method + ' does not exist on jQuery.lettering');
        return this;
    };


    $(".circle-title-anime").lettering();

})(jQuery);
