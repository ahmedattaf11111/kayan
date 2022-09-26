export function owlCarouselFunction() {
    var target = $(".owl-carousel");
    if (target.length > 0) {
        target.each(function () {
            var el = $(this),
                dataAuto = el.data("owl-auto"),
                dataLoop = el.data("owl-loop"),
                dataSpeed = el.data("owl-speed"),
                dataGap = el.data("owl-gap"),
                dataNav = el.data("owl-nav"),
                dataDots = el.data("owl-dots"),
                dataAnimateIn = el.data("owl-animate-in") ? el.data("owl-animate-in") : "",
                dataAnimateOut = el.data("owl-animate-out")
                    ? el.data("owl-animate-out")
                    : "",
                dataDefaultItem = el.data("owl-item"),
                dataItemXS = el.data("owl-item-xs"),
                dataItemSM = el.data("owl-item-sm"),
                dataItemMD = el.data("owl-item-md"),
                dataItemLG = el.data("owl-item-lg"),
                dataItemXL = el.data("owl-item-xl"),
                dataNavLeft = el.data("owl-nav-right")
                    ? el.data("owl-nav-right")
                    : "<i class='fa fa-chevron-right'></i>",
                dataNavRight = el.data("owl-nav-left")
                    ? el.data("owl-nav-left")
                    : "<i class='fa fa-chevron-left'></i>",
                duration = el.data("owl-duration"),
                datamouseDrag = el.data("owl-mousedrag") == "on" ? true : false,
                center = el.data("owl-center");
            if (
                target.children("div, span, a, img, h1, h2, h3, h4, h5, h5").length >= 2
            ) {
                el.owlCarousel({
                    animateIn: dataAnimateIn,
                    animateOut: dataAnimateOut,
                    margin: dataGap,
                    autoplay: dataAuto,
                    autoplayTimeout: dataSpeed,
                    autoplayHoverPause: true,
                    loop: dataLoop,
                    nav: dataNav,
                    rtl: true,
                    mouseDrag: datamouseDrag,
                    touchDrag: true,
                    autoplaySpeed: duration,
                    navSpeed: duration,
                    dotsSpeed: duration,
                    dragEndSpeed: duration,
                    navText: [dataNavLeft, dataNavRight],
                    dots: dataDots,
                    items: dataDefaultItem,
                    center: Boolean(center),
                    responsive: {
                        0: {
                            items: dataItemXS,
                        },
                        480: {
                            items: dataItemSM,
                        },
                        768: {
                            items: dataItemMD,
                        },
                        992: {
                            items: dataItemLG,
                        },
                        1200: {
                            items: dataItemXL,
                        },
                        1680: {
                            items: dataDefaultItem,
                        },
                    },
                });

                // el.on('change.owl.carousel', function (event) {
                //     var $currentItem = $('.owl-item', el).eq(event.item.index);
                //     var $elemsToanim = $currentItem.find("[data-animation-out]");
                //     setAnimation($elemsToanim, 'out');
                // });

                // el.on('changed.owl.carousel', function (event) {
                //     var $currentItem = $('.owl-item', el).eq(event.item.index);
                //     var $elemsToanim = $currentItem.find("[data-animation-in]");
                //     setAnimation($elemsToanim, 'in');
                // });
            }
        });
    }
}
export function activateSideCategory(categoryId, categoryLevel) {
    $(".sub-category").removeClass("active");
    $(".main-category").removeClass("active");
    categoryLevel == 1 ? $(`#main-${categoryId}`).addClass("active") :
        $(`#sub-${categoryId}`).addClass("active");
}
export function toggleSideCategory(categoryId) {
    var current = $("span").parent(`#main-${categoryId}`);
    current.children('.sub-menu').slideToggle(350);
    current.toggleClass('collapsed');
}
export function toggleMobileSideCategroy() {
    $('.ps-block-control').on('click', function (event) {
        event.preventDefault();
        var parent = $(this).parent();
        parent.find('.ps-widget__content').slideToggle();
        $(this).toggleClass('active');
    });
}
export function subMenuToggle() {
    $(".ps-language-currency .sub-toggle").on("click", function (e) {
        e.preventDefault();
        var current = $(this).parent("li");

        current.children(".sub-menu").slideToggle(350);
        current.toggleClass("active");
    });
    $('.ps-menu__sticky #menu-slide').on('click', function (e) {
        e.preventDefault();
        $('.ps-menu--slidebar').addClass('active');
        $('#open-menu').parent().addClass('active');
    });
    $(".menu-slide").on("click", function (e) {
        e.preventDefault();
        $(".ps-menu--slidebar").addClass("active");
        $("#open-menu").parent().addClass("active");
    });
    $("#open-menu-top").on("click", function (e) {
        e.preventDefault();
        $(".ps-menu--slidebar").addClass("active");
        $(this).parent().addClass("active");
        $(".ps-header--mobile").addClass("slidebar-active");
    });
    $("#close-menu-top").on("click", function (e) {
        e.preventDefault();
        $(".ps-menu--slidebar").removeClass("active");
        $(this).parent().removeClass("active");
        $(".ps-header--mobile").removeClass("slidebar-active");
    });
}

export function initializeClock(endtime) {
    var daysSpan = $('.ps-countdown___days');
    var hoursSpan = $('.ps-countdown__hours');
    var minutesSpan = $('.ps-countdown__minutes');
    var secondsSpan = $('.ps-countdown__seconds');

    if (hoursSpan && minutesSpan && secondsSpan) {
        updateClock();
        var timeinterval = setInterval(updateClock, 1000);
    }

    function updateClock() {
        var t = getTimeRemaining(endtime);

        var hoursText = ('0' + t.hours).slice(-2);
        var minutesText = ('0' + t.minutes).slice(-2);
        var secondsText = ('0' + t.seconds).slice(-2);
        var daysText = ('00' + t.days).slice(-3);

        daysSpan.each(function (index) {
            if (daysText >= 100) {
                $(this).find('.first-1st').text(daysText.slice(0, 1));
                $(this).find('.first-1st').css('display', 'inline-block');
            }
            $(this).find('.first').text(daysText.slice(1, 2));
            $(this).find('.last').text(daysText.slice(-1));
        });

        hoursSpan.each(function (index) {
            $(this).find('.first').text(hoursText.slice(0, 1));
            $(this).find('.last').text(hoursText.slice(-1));
        });

        minutesSpan.each(function (index) {
            $(this).find('.first').text(minutesText.slice(0, 1));
            $(this).find('.last').text(minutesText.slice(-1));
        });

        secondsSpan.each(function (index) {
            $(this).find('.first').text(secondsText.slice(0, 1));
            $(this).find('.last').text(secondsText.slice(-1));
        });

        if (t.total <= 0) {
            clearInterval(timeinterval);
        }
    }
    function getTimeRemaining(endtime) {
        var t = Date.parse(endtime) - Date.parse(new Date());
        var seconds = Math.floor((t / 1000) % 60);
        var minutes = Math.floor((t / 1000 / 60) % 60);
        var hours = Math.floor((t / (1000 * 60 * 60)) % 24);
        var days = Math.floor(t / (1000 * 60 * 60 * 24));
        return {
            'total': t,
            'days': days,
            'hours': hours,
            'minutes': minutes,
            'seconds': seconds
        };
    }
}


export function toggleSearchVisibility() {
    $(".open-search").on("click", function (event) {
        event.preventDefault();
        $(".ps-search").addClass("active");
    });
    $("#close-search").on("click", function (event) {
        event.preventDefault();
        $(".ps-search").removeClass("active");
    });
}

export function closeNoti() {
    $(".ps-noti__close").on("click", function (event) {
        event.preventDefault();
        $(".ps-noti").hide();
    });
}

export function toggleMobileMenu() {
    $("#open-menu").on("click", function (e) {
        e.preventDefault();
        $(".ps-menu--slidebar").addClass("active");
        $(this).parent().addClass("active");
    });

    $("#close-menu").on("click", function (e) {
        e.preventDefault();
        $(".ps-menu--slidebar").removeClass("active");
        $(this).parent().removeClass("active");
    });
}

export function stickyMenu() {
    $(window).scroll(function (event) {
        var scroll = $(window).scrollTop();
        var innerWidth = $(window).innerWidth();
        if (scroll > 100 && innerWidth > 760) {
            $(".ps-header").addClass("ps-header--sticky");
        } else if (scroll > 300 && innerWidth < 760) {
            $(".ps-header").addClass("ps-header--sticky");
        } else {
            $(".ps-header").removeClass("ps-header--sticky");
        }
        if (scroll > 100) {
            $(".scroll-top").show();
        } else {
            $(".scroll-top").hide();
        }
    });
    $(".scroll-top").on("click", function (e) {
        e.preventDefault();
        $("html,body").animate({ scrollTop: 0 }, 500);
    });
}