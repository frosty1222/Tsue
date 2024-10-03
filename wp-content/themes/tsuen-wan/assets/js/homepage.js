(function ($) {
    'use strict';
    AOS.init({
        once: true,
    });

    /**
     * Home hero slider
     */
    var mySwiper = new Swiper('.home-slider .swiper-container', {
        slidesPerView: 1,
        spaceBetween: 0,
        loop: true,
        autoplay: {
            delay: 7000,
        },
        navigation: {
            nextEl: '.home-slider .arrow-next-button',
            prevEl: '.home-slider .arrow-prev-button',
        },
        pagination: {
            el: '.home-slider .home-slider-progressbar',
            clickable: false,
        },
    });
    /**
     * Home hero slider mask
     */
    $(window).on('load', function () {
        $('#js-loading__mask').addClass('loading');
        $('.home-slider').addClass('loading');
        setTimeout(function () {
            $('#js-loading__mask').addClass('loaded');
            $('.home-slider').addClass('loaded');
        }, 1000);
    });
    /*
    happening slider
    */
    if (document.getElementsByClassName('happenings-slider').length > 0) {
        var playgarden_slides = new Splide('.happenings-slider.splide', {
            type: 'loop',
            perPage: 3,
            perMove: 1,
            rewind : true,
            focus: 'number',
            autoWidth: true,
            pagination: false,
            paginationClickable: true,
            arrows: false,
            focus: 'center',
            autoScroll: {
                speed: 1,
            },
            breakpoints: {
                767: {
                    //type: 'slide',
                    pagination: true,
                    autoScroll: false,
                    perPage: 1,
                },
            }
        });
        playgarden_slides.mount(window.splide.Extensions);
    }

    if (document.getElementsByClassName('cursor-hover').length > 0) {
        const cursorDiv = document.createElement('div');
        cursorDiv.classList.add('cursor');

        const cursorMoveOuterDiv = document.createElement('div');
        cursorMoveOuterDiv.classList.add('cursor-move-outer');

        const cursorOuterDiv = document.createElement('div');
        cursorOuterDiv.classList.add('cursor-outer');

        // Append the elements in the correct order
        cursorMoveOuterDiv.appendChild(cursorOuterDiv);
        cursorDiv.appendChild(cursorMoveOuterDiv);
        document.body.appendChild(cursorDiv);
        var cursor = document.querySelector('.cursor');
        var cursorOuter = document.querySelector('.cursor-move-outer');
        var cursorOuterInner = document.querySelector('.cursor-outer');
        var triggers = document.querySelectorAll('.cursor-hover');
        var mouseX = 0;
        var mouseY = 0;
        var mouseA = 0;
        var innerX = 0;
        var innerY = 0;
        var outerX = 0;
        var outerY = 0;
        var loop = null;
        document.addEventListener('mousemove', function (e) {
            mouseX = e.clientX;
            mouseY = e.clientY;
            if (!loop) {
                loop = window.requestAnimationFrame(render);
            }
        });

        triggers.forEach(
            function (trigger) {
                trigger.addEventListener('mouseenter', function () {
                    cursor.classList.add('is-hover');
                });
                trigger.addEventListener('mouseleave', function () {
                    cursor.classList.remove('is-hover');
                });
            }
        );

        function render() {
            var width = cursorOuterInner.offsetWidth;
            var height = cursorOuterInner.offsetHeight;
            loop = null;
            innerX = lerp(innerX, mouseX, 0.15);
            innerY = lerp(innerY, mouseY, 0.15);
            outerX = lerp(outerX, mouseX, 0.13);
            outerY = lerp(outerY, mouseY, 0.13);
            var normalX = Math.min(Math.floor((Math.abs(mouseX - outerX) / outerX) * 1000) / 1000, 1);
            var normalY = Math.min(Math.floor((Math.abs(mouseY - outerY) / outerY) * 1000) / 1000, 1);
            var normal = normalX + normalY * .5;
            //var skwish = normal * .7;
            cursorOuter.style.transform = "translate3d(".concat(outerX, "px, ").concat(outerY, "px, 0)");
            // Stop loop if interpolation is done.
            if (normal !== 0) {
                loop = window.requestAnimationFrame(render);
            }
        }
        function lerp(s, e, t) {
            return (1 - t) * s + t * e;
        }
    }

    /*
    shopping slider
    */
    var swiper_shops = new Swiper('.home-shopping .swiper-container', {
        loop: true,
        speed: 500,
        slidesPerView: 4,
        autoplay: false,
        spaceBetween: 0,
        navigation: {
            nextEl: '.home-shopping .arrow-next-button',
            prevEl: '.home-shopping .arrow-prev-button',
        },
        pagination: {
            el: '.home-shopping .home-list-progressbar',
            clickable: false,
        },
        breakpoints: {
            0: {
                slidesPerView: 1.087,
                spaceBetween: 8,
                loop: false,
            },
            600: {
                slidesPerView: 2,
                spaceBetween: 8,
            },
            768: {
                slidesPerView: 4,
            },
        },
        on: {
            init: function () {
                // for (var i = 0; i < this.params.slidesPerView; i++) {
                //     this.slides[i].classList.add('active');
                // }
                var _slidesPerView = this.params.slidesPerView
                document.querySelectorAll('.home-shopping .swiper-slide').forEach(function (slide) {
                    for (var i = 0; i < _slidesPerView; i++) {
                        slide.classList.add('active');
                    } 
                })    
            },
            slideChange: function () {
                document.querySelectorAll('.home-shopping .swiper-slide').forEach(function (slide) {
                    slide.classList.remove('active');
                });
                for (var i = 0; i < this.params.slidesPerView; i++) {
                    var slideIndex = this.activeIndex + i;
                    if (slideIndex < this.slides.length) {
                        this.slides[slideIndex].classList.add('active');
                    }
                }
            },
        },
    });
    /*
    dining slider
    */
    var swiper_shops = new Swiper('.home-dining .swiper-container', {
        loop: true,
        speed: 500,
        slidesPerView: 4,
        autoplay: false,
        spaceBetween: 0,
        navigation: {
            nextEl: '.home-dining .arrow-next-button',
            prevEl: '.home-dining .arrow-prev-button',
        },
        pagination: {
            el: '.home-dining .home-list-progressbar',
            clickable: false,
        },
        breakpoints: {
            0: {
                slidesPerView: 1.087,
                spaceBetween: 8,
                loop: false,
            },
            600: {
                slidesPerView: 2,
                spaceBetween: 8,
            },
            768: {
                slidesPerView: 4,
            },
        },
        on: {
            init: function () {
                for (var i = 0; i < this.params.slidesPerView; i++) {
                    this.slides[i].classList.add('active');
                }
            },
            slideChange: function () {
                document.querySelectorAll('.home-dining .swiper-slide').forEach(function (slide) {
                    slide.classList.remove('active');
                });
                for (var i = 0; i < this.params.slidesPerView; i++) {
                    var slideIndex = this.activeIndex + i;
                    if (slideIndex < this.slides.length) {
                        this.slides[slideIndex].classList.add('active');
                    }
                }
            },
        },
    });
    /*
    instagram slider
    */
    var swiper_instagram = Swiper;
    var init = false;
    function swiperMode() {
        let mobile = window.matchMedia('(min-width: 0px) and (max-width: 767.98px)');
        let tablet = window.matchMedia('(min-width: 768px) and (max-width: 1024px)');
        let desktop = window.matchMedia('(min-width: 1025px)');

        // Enable (for mobile)
        if (mobile.matches) {
            if (!init) {
                init = true;
                swiper_instagram = new Swiper('.instagram-slider .swiper-container', {
                    loop: true,
                    speed: 500,
                    slidesPerView: 3.25,
                    autoplay: false,
                    centeredSlides: true,
                    pagination: {
                        el: '.instagram-slider .home-list-progressbar',
                        clickable: false,
                    },
                    breakpoints: {
                        0: {
                            slidesPerView: 1.087,
                            spaceBetween: 8,
                            centeredSlides: false,
                            loop: false,
                        },
                        600: {
                            slidesPerView: 2,
                            spaceBetween: 8,
                            centeredSlides: false,
                        }
                    }
                });
            }
        }
        // Disable (for tablet)
        else if (tablet.matches && init) {
            swiper_instagram.destroy();
            init = false;
        }

        // Disable (for desktop)
        else if (desktop.matches && init) {
            swiper_instagram.destroy();
            init = false;
        }
    }

    /* On Load
    **************************************************************/
    window.addEventListener('load', function () {
        swiperMode();
    });
    /* On Resize
    **************************************************************/
    window.addEventListener('resize', function () {
        swiperMode();
    });

    /* Movies load more*/
    var movies_more = document.getElementById("movies_more");
    if (movies_more) {
        movies_more.addEventListener("click", function () {
            var items = document.querySelectorAll('.movies_list .item-hide');
            var count = 0;
            var loadAmount = parseInt(this.getAttribute('data-show'));
            var loadshow = parseInt(this.getAttribute('data-count'));
            items.forEach(function (item, index) {
                if (count < loadAmount) {
                    item.classList.remove('item-hide');
                    count++;
                }
            });
            loadshow = loadshow - 1;
            this.setAttribute('data-count', loadshow);
            if (loadshow == 0) this.style.display = "none";
        });
    }
})(jQuery);