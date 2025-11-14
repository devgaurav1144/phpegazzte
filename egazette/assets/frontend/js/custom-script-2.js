/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(function () {

//    var window_height = $(window).height();
//    var content_height = $('.main-container').height();
//
//    if (content_height < window_height) {
//        $('body').addClass('fixed-footer');
//    } else {
//        $('body').removeClass('fixed-footer');
//    }

    $(".external a").on("click", function (e) {
        e.preventDefault();
        $("#external").modal("show");
        var external_link = $(this).attr("href");
        $("#external").find("a").attr("href", external_link);


    });

    $("#external a").on("click", function () {
        $("#external").modal("hide");
    });


    $(".tooltip-button").on("click", function () {
        $(".setting-responsive").slideToggle(400);
    });
    //For Responsive Table Start
//    jQuery('.table').basictable({
//        breakpoint: 767,
//        forceResponsive: true
//    });
//    jQuery('.table').each(function (index, element) {
//        if ((jQuery(this).find('th').length > 0) || jQuery(this).find('thead').length > 0) {
//            jQuery(this).basictable({
//                breakpoint: 767,
//                forceResponsive: true
//            });
//
//        }
//    });
//For Responsive Table End

///Scroll to Top
    $(window).scroll(function () {
        if ($(this).scrollTop() > 200) {
            $('#gotoTop').fadeIn();
        } else {
            $('#gotoTop').fadeOut();
        }
    });


    $('#gotoTop').click(function () {
        $('body,html').animate({scrollTop: 0}, 400);
        return false;
    });
//Scroll to Top




    $(".responsive-menu").on("click", function () {
        $(".main-ul").removeClass('fadeOutLeftBig').addClass('fadeInLeftBig').fadeIn();
        $('.menu-overlay').fadeIn();
    });
    $(".menu_close_btn").on("click", function () {
        $(".main-ul").removeClass('fadeInLeftBig').addClass('fadeOutLeftBig').fadeOut();
        $('.menu-overlay').fadeOut();
    });

//Clock Section Start
// With this function we will assemble our clock, 
// calling up whole date and then hours, minutes, 
// and seconds individually.
    function displayTime() {
        var currentTime = new Date();
        var hours = currentTime.getHours();
        var minutes = currentTime.getMinutes();
        var seconds = currentTime.getSeconds();
        // Let's set the AM and PM meridiem and 
        // 12-hour format
        var meridiem = "AM"; // Default is AM

        // If hours is greater than 12
        if (hours > 12) {
            hours = hours - 12; // Convert to 12-hour format
            meridiem = "PM"; // Keep track of the meridiem
        }
// 0 AM and 0 PM should read as 12
        if (hours === 0) {
            hours = 12;
        }
// If the hours digit is less than 10
        if (hours < 10) {
// Add the "0" digit to the front
// so 9 becomes "09"
            hours = "0" + hours;
        }
// Format minutes and seconds the same way
        if (minutes < 10) {
            minutes = "0" + minutes;
        }
        if (seconds < 10) {
            seconds = "0" + seconds;
        }
// This gets a "handle" to the clock div in our HTML
        var clockDiv = document.getElementById('clock');
        // Then we set the text inside the clock div 
        // to the hours, minutes, and seconds of the current time
        clockDiv.innerText = hours + ":" + minutes + ":" + seconds + " " + meridiem;
    }
// This runs the displayTime function the first time
    displayTime();
    // This makes our clock 'tick' by repeatedly
    // running the displayTime function every second.
    setInterval(displayTime, 1000);
    // date and time start
    var now = new Date().toDateString();
    ;
// Saturday, June 9th, 2007, 5:46:21 PM
    $('#date').append(now);
// date and time end
// 
//Clock Section End

//Increase and Decrease font size start
    var $affectedElements = $("section p,section h1,section li,section a,section td,section th,section span, section h2, nav li a, .custom-menu li a,.submenu li a, .current-cm span, .form-group label"); // Can be extended, ex. $("div, p, span.someClass")
// Storing the original size in a data attribute so size can be reset
    $affectedElements.each(function () {
        var $this = $(this);
        $this.data("orig-size", $this.css("font-size"));
    });
    $("#btn-increase").click(function () {
        changeFontSize(1);
    });
    $("#btn-decrease").click(function () {
        changeFontSize(-1);
    });
    $("#btn-orig").click(function () {
        $affectedElements.each(function () {
            var $this = $(this);
            $this.css("font-size", $this.data("orig-size"));
        });
    });
    function changeFontSize(direction) {
        $affectedElements.each(function () {
            var $this = $(this);
            if (direction > 0) {
                if ($this.css("font-size").slice(0, -2) < 18) {
                    $this.css("font-size", parseInt($this.css("font-size")) + direction);
                }
                ;
            } else {
                if ($this.css("font-size").slice(0, -2) > 12) {
                    $this.css("font-size", parseInt($this.css("font-size")) + direction);
                }
                ;
            }
        });
    }
//Increase and Decrease font size end

    $(".skiper").click(function () {
        $('html,body').animate({
            scrollTop: 270
        },
        'slow');
//        var mk = $(location).attr('href');
//        $('.skiper').attr('href', mk + "#skip-to-main");
    });

// animation scroll reveal start

//    window.sr = ScrollReveal();
//
//    sr.reveal(".reveal-bottom", {
//        origin: "bottom",
//        distance: "20px",
//        duration: 500,
//        delay: 400,
//        opacity: 1,
//        scale: 0,
//        easing: "linear",
//        reset: !0
//    }), sr.reveal(".reveal-top", {
//        origin: "top",
//        distance: "20px",
//        duration: 500,
//        delay: 400,
//        opacity: 1,
//        scale: 0,
//        easing: "linear",
//        reset: !0
//    }), sr.reveal(".reveal-left", {
//        origin: "left",
//        distance: "50px",
//        duration: 300,
//        delay: 0,
//        opacity: 1,
//        scale: 0,
//        easing: "linear"
//    }), sr.reveal(".reveal-left-delay", {
//        origin: "left",
//        distance: "50px",
//        duration: 300,
//        delay: 300,
//        opacity: 1,
//        scale: 0,
//        easing: "linear"
//    }), sr.reveal(".reveal-right", {
//        origin: "right",
//        distance: "50px",
//        duration: 600,
//        delay: 500,
//        opacity: 1,
//        scale: 0,
//        easing: "linear"
//    }), sr.reveal(".reveal-right-fade", {
//        origin: "right",
//        distance: "50px",
//        distance: "50px",
//                duration: 800,
//        delay: 0,
//        opacity: 0,
//        scale: 0,
//        easing: "linear",
//        mobile: !1
//    }), sr.reveal(".reveal-left-fade", {
//        origin: "left",
//        distance: "50px",
//        duration: 800,
//        delay: 0,
//        opacity: 0,
//        scale: 0,
//        easing: "linear",
//        mobile: !1
//    }), sr.reveal(".fadeInRight", {
//        origin: "right",
//        distance: "50px",
//        duration: 500,
//        delay: 0,
//        opacity: 0,
//        scale: 0,
//        easing: "linear",
//        mobile: !1
//    }), sr.reveal(".fadeInRightDelay", {
//        origin: "right",
//        distance: "20px",
//        duration: 1e3,
//        delay: 0,
//        opacity: 0,
//        scale: 0,
//        easing: "linear",
//        mobile: !1
//    }, 500), sr.reveal(".fadeIn", {
//        origin: "bottom",
//        distance: "0",
//        duration: 900,
//        delay: 0,
//        opacity: 0,
//        scale: 0,
//        easing: "linear",
//        mobile: !1
//    }), sr.reveal(".fadeInScale", {
//        origin: "bottom",
//        distance: "0",
//        duration: 500,
//        delay: 0,
//        opacity: 0,
//        scale: .5,
//        easing: "linear",
//        mobile: !1
//    }), sr.reveal(".fadeInBottom", {
//        origin: "bottom",
//        distance: "40px",
//        duration: 500,
//        delay: 0,
//        opacity: 0,
//        scale: 0,
//        easing: "linear",
//        mobile: !1
//    }), sr.reveal(".fadeInBottomDelay", {
//        origin: "bottom",
//        distance: "40px",
//        duration: 500,
//        delay: 200,
//        opacity: 0,
//        scale: 0,
//        easing: "linear",
//        mobile: !1
//    }), sr.reveal(".fadeInBottomDelay-2", {
//        origin: "bottom",
//        distance: "40px",
//        duration: 500,
//        delay: 500,
//        opacity: 0,
//        scale: 0,
//        easing: "linear",
//        mobile: !1
//    }), sr.reveal(".fadeInBottomDelay-3", {
//        origin: "bottom",
//        distance: "40px",
//        duration: 500,
//        delay: 600,
//        opacity: 0,
//        scale: 0,
//        easing: "linear",
//        mobile: !1
//    });
// animation scroll reveal start
// Home Slider start
    $('.home-slider').owlCarousel({
        loop: true,
        margin: 10,
        nav: false,
        autoplay: true,
        autoplayTimeout: 5000,
        animateOut: 'fadeOut',
        autoplayHoverPause: false,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 1
            },
            1000: {
                items: 1
            }
        }
    })
// Home Slider end



//START SMOTH SCROOL JS 
    $('a[href*="#"]:not([href="#"])').click(function () {
        if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
            var target = $(this.hash);
            target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
            if (target.length) {
                $('html, body').animate({
                    scrollTop: (target.offset().top) - 80
                }, 1000);
                return false;
            }
        }
    });
// 03. END SMOTH SCROOL JS 




});


/* Change Contrast */
function changeContrast(contrast) {

    if (contrast === 'inactivate') {
        $('body').removeClass('contrast-active');
    } else {
        $('body').addClass('contrast-active');
    }

}

if ($(".full-bar-search-toggle").length) {
    $(document).on('click', '.full-bar-search-toggle', function () {
        $('.full-bar-search-wrap').toggleClass('active');
//        return false;
    });
}




$(window).resize(function () {
    if ($(window).width() > 1052) {
        $('.main-ul, .menu-overlay').removeAttr('style');
        $(".main-ul").removeClass('fadeOutLeftBig').removeAttr('style');
    }
});