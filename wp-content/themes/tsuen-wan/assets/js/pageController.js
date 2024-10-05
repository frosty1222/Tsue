

jQuery(document).ready(function( $ ) {
    $('.waypoint_fadeIn').waypoint(function(direction, element) {
        $(this.element).addClass("active")
    }, {
        offset: '80%'
    })

    $("body").click(function(){
        $("#share").removeClass("active")
    })


    $('.selectric').selectric();
    $(".shop-main_menu__btn").click(function(){
        $(".shop-main_menuItem__select").each(function(e, i){
            $(this).find(".selectric-items li").removeClass("selected");
            $(this).find(".selectric-items li").removeClass("highlighted");
            $default_value = $(this).find(".selectric-items li").eq(0).html();
            $(this).find(".selectric-items li").eq(0).addClass("selected")
            $(this).find(".selectric .label").html($default_value);
        })
    });



    if($("#cookies_content").length > 0){
        window.cookieconsent.initialise({
            container: document.getElementById("cookies_content"),
            "palette": {
                "popup": {
                    "background": "#000000"
                },
                "button": {
                    "background": "transparent",
                    "text": "#fff",
                    "border": "#fff"
                }
            },
            "content": {
                "message": $("#cookies_content").data("cookie"),
                "link": $("#cookies_content").data("link"),
                "href": $("#cookies_content").data("href"),
                'dismiss': $("#cookies_content").data("accept"),
            },
            "cookie": {
                // "domain": ".elm.com",
                "path": "/",
                "expiryDays": "365"
            },
        })
    }

    $(".mobile-menu .menu-item.menu-item-has-children > a").click(function(event){
        event.preventDefault();
        if($(this).hasClass("active")) {
            $(".mobile-menu .menu-item.menu-item-has-children > a").removeClass("active");
            TweenMax.to($(this).next(".sub-menu"), 0.3, {height:0})
        }else{
            TweenMax.to($(".mobile-menu .menu-item.menu-item-has-children > a.active").next(".sub-menu"), 0.3, {height:0})
            $(".mobile-menu .menu-item.menu-item-has-children > a").removeClass("active");
            TweenMax.to($(this).next(".sub-menu"), 0.3, {height:'auto'})
            $(this).addClass("active")
        }
    })

    $(".responsive_btn").click(function () {
        $(this).toggleClass('is-active');
        $(".mobile-menu").toggleClass("slide-active");
        $("#search_close").trigger("click");
    })

    $("#share").click(function(e){
        if (navigator.share) {
            navigator.share({
                title: $("#share").data("title"),
                text: $("#share").data("text"),
                url: window.location.href,
            })
        }else{
            $(this).toggleClass("active");
        }
        e.stopPropagation();
    })
    $("#share-content").click(function(e){
        e.stopPropagation();
    })

    $(".share-link-btn").click(function(){
        var copyText = $(this).data("copy");
        copyTextToClipboard(copyText);
        $("#copy-success").addClass("active")
        setTimeout(function(){
            $("#copy-success").removeClass("active");
        }, 4000)
    })

    $(".shop_name_search").click(function(){
        $(".shop-main_serach").addClass("active");
    })
    $(".shop_name_close").click(function(){
        $(".shop-main_serach").removeClass("active");
    })

    function fallbackCopyTextToClipboard(text) {
        var textArea = document.createElement("textarea");
        textArea.value = text;

        // Avoid scrolling to bottom
        textArea.style.top = "0";
        textArea.style.left = "0";
        textArea.style.position = "fixed";

        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();

        try {
            var successful = document.execCommand('copy');
            var msg = successful ? 'successful' : 'unsuccessful';
            console.log('Fallback: Copying text command was ' + msg);
        } catch (err) {
            console.error('Fallback: Oops, unable to copy', err);
        }

        document.body.removeChild(textArea);
    }
    function copyTextToClipboard(text) {
        if (!navigator.clipboard) {
            fallbackCopyTextToClipboard(text);
            return;
        }
        navigator.clipboard.writeText(text).then(function() {
            console.log('Async: Copying to clipboard was successful!');
        }, function(err) {
            console.error('Async: Could not copy text: ', err);
        });
    }



    $('#shop-main_top__fix').waypoint(function(direction, element) {
        if(direction == "down"){
            $("#shop-main_top__fix").addClass("fixed")
        }else{
            $("#shop-main_top__fix").removeClass("fixed")
            $("#shop-main_top__fix").removeClass("active")
        }
    }, {
        offset: '-300'
    })

    $("#scroll_top").click(function(){
        if(!in_scroll){
            in_scroll = true;
            $('html, body').animate({
                scrollTop: 0
            }, 400, function(){
                in_scroll = false;
            });
        }
    })

    $("#shop-main_top__fix .filter_btn").click(function(){
        $("#shop-main_top__fix").toggleClass("active")
    })

    var in_scroll = false;

    $(".scrollTo").click(function(){
        if($(this).parents("#shop-main_top__fix").length >= 1) return;
        if(!in_scroll){
            in_scroll = true;
            $target = $(this).data("scrollto");
            if($target != undefined){
                $header_height = $(".header_menu_section_fix").height()
                $('html, body').animate({
                    scrollTop: $($target).offset().top - $header_height
                }, 400, function(){
                    in_scroll = false;
                });
            }
        }
    })



    $(".header-menu>.menu-item").hover(
        function(){
            $("body").addClass("menu_hover")
        },
        function(){
            $("body").removeClass("menu_hover")
        }
    )

    $("#header_menu_section .search_icon").click(function(){
        $("#header_menu_section").addClass("in_search");
        TweenMax.to($('#search_results'), 0.3, {height:'auto'});
        $("body").addClass("menu_hover")
        $(".responsive_btn").removeClass(("is-active"))
        $(".mobile-menu").removeClass("slide-active");
    })
    $("#header_menu_section #search_close, #responsive_bg").click(function(){
        $("#header_menu_section").removeClass("in_search");
        TweenMax.to($('#search_results'), 0.3, {height: 0});
        $("body").removeClass("menu_hover")
    })

    $(".happening-menu_btn").click(function(){
        if($(this).hasClass('active')){
            return
        }
        $(".happening-menu_btn").removeClass("active")
        $(this).addClass('active')
        $type = $(this).data("type");
        $(".happening-item").removeClass("active")
        if($type === "all"){
            $(".happening-item").addClass("active")
        }else{
            $(".happening-item" + "." + $type).addClass("active")
        }
        if(!$(".happening-item").hasClass("active")){
            $(".happening-items .result_not_found").addClass("active")
        }else{
            $(".happening-items .result_not_found").removeClass("active")
        }
    })

    $(".parking-menu_btn").click(function(){
        if($(this).hasClass('active')){
            return
        }
        $(".parking-menu_btn").removeClass("active")
        $(this).addClass('active')
        $type = $(this).data("type");
        $(".parking-item").removeClass("active")
        $(".parking-item" + "." + $type).addClass("active")
    })

    $(".visit-menu_btn").click(function(){
        if($(this).hasClass('active')){
            return
        }
        $(".visit-menu_btn").removeClass("active")
        $(this).addClass('active')
        $type = $(this).data("type");
        $(".visit-item").removeClass("active")
        $(".visit-item" + "." + $type).addClass("active")
    })

    if(typeof Storage !== "undefined"){
        $header_notice = $("#header_notification");
        if($header_notice.length == 1){
            var header_notice_id = $header_notice.data("id");
            var header_close = localStorage.getItem('header_close');
            var header_storage_id = localStorage.getItem('Header_id');
            if(parseInt(header_close) != 1 || header_notice_id != header_storage_id){
                TweenMax.to($header_notice, 0.3, {height: 'auto'});
                $("#header_notification .close").click(function(){
                    TweenMax.to($header_notice, 0.3, {height: 0});
                    localStorage.setItem('header_close', 1);
                    localStorage.setItem('Header_id', header_notice_id);
                })
            }
        }
        $(".header_menu_section_news .close").click(function(){

        })

        if($("#notice-dialog")){
            $notice = $("#notice-dialog");
            if($notice.length == 1){
                var notice_id = $notice.data("id");
                var hasShow = localStorage.getItem('hasShow');
                var storage_id = localStorage.getItem('id');
                if(parseInt(hasShow) != 1 || notice_id != storage_id){
                    localStorage.setItem('hasShow', 1);
                    localStorage.setItem('id', notice_id);
                    manual_popup();
                }
            }
        }
    }

    function manual_popup(){
        $.magnificPopup.open({
            items: {
                src: '#notice-dialog'
            },
            type: 'inline',
            closeOnContentClick: false,
            closeBtnInside: true,
            fixedContentPos: true,
            mainClass: 'my-mfp-zoom-in',
            zoom: {
                enabled: true,
                duration: 300 // don't foget to change the duration also in CSS
            }
        });
    }

    var movie_section = document.getElementById("movie_section");
    if(movie_section){
        get_movies();
    }
    function get_movies(){
        $lang = $("body").data("lang");
        if(!$lang) $lang = "en";
        data = {
            'action': 'get_movies',
            'lang' : $lang
        }
        $.ajax({
            url: my_ajax.ajax_url,
            data: data,
            method: 'POST',
            timeout: 20000,
            success: function(result){
                result = JSON.parse(result);
                if(result.status == 1){
                    $("#movie_section_content .movie_itmes").html(result.html);
                    $("#movie_section_content .loading_spinner").removeClass("active")
                    $("#movie_section_content .show_all").addClass("active")
                    if(result.count <= 3){
                        $("#movie_section_content .show_all").addClass("hide_tablet");
                    }
                    if(result.count <= 4){
                        $("#movie_section_content .show_all").addClass("hide_desktop");
                    }
                }else{
                    $("#movie_section_content .result_not_found").addClass("active");
                    $("#movie_section_content .loading_spinner").removeClass("active")
                }
                $("#movie_show_all").click(function(e){
                    $("#movie_section_content .flex_item").removeClass("hide_desktop")
                    $("#movie_section_content .flex_item").removeClass("hide_tablet")
                    $(this).parent(".show_all").removeClass("active");
                    e.preventDefault();
                })
                return;
            },
            error: function(xhr, textStatus, errorThrown){
                $("#movie_section_content .result_not_found").addClass("active");
                $("#movie_section_content .loading_spinner").removeClass("active")
            }
        });
    }

    $('.share-wc').magnificPopup({
        type: 'inline',

        fixedContentPos: false,
        fixedBgPos: true,

        overflowY: 'auto',

        closeBtnInside: true,
        preloader: false,

        midClick: true,
        removalDelay: 300,
        mainClass: 'my-mfp-zoom-in'
    });

    var swiper = new Swiper(".general-hero_slider", {
        pagination: {
            el: ".swiper-pagination",
            clickable: true
        },
        effect: "fade",
        autoplay: {
            delay: 8000,
        },
    });

    var swiper = new Swiper(".search-results_items__slide", {
        slidesPerView: 2,
        spaceBetween: 8,
        pagination: {
            el: ".swiper-pagination",
            clickable: true
        },
    });

    $(".services-item_header").click(function(){
        $parent_item = $(this).parent(".services-item");
        $parent_item_id = $(this).parent(".services-item").attr("id");
        if($($parent_item).hasClass("active")){
            $($parent_item).removeClass("active")
            TweenMax.to($($parent_item).find(".services-item_services"), 0.3, {height: 0});
        }else{
            TweenMax.to($(".services-item.active").find(".services-item_services"), 0.3, {height: 0});
            $(".services-item.active").removeClass("active");
            TweenMax.to($($parent_item).find(".services-item_services"), 0.3, {height: 'auto'});
            $($parent_item).addClass("active")
        }
    })


    var typingTimer;
    var doneTypingInterval = 300;
    var is_cancel = false;
    var $new_search_input = ""

    $(".search_input").keyup(function(){
        $search_input = $(".search_input").val();
        $new_search_input = $(".search_input").val();
        if($search_input.length < 1){
            $(".search-results_list").removeClass("active");
            $(".search-results_quick, .search-results_like").addClass("active");
            $(".search-results").removeClass("loading");
            is_cancel = true;
            return;
        }
        is_cancel = false;
        clearTimeout(typingTimer);
        typingTimer = setTimeout(function(){
            get_search($search_input)
        }, doneTypingInterval);
    })


    function get_search($search_input){
        if(is_cancel == true){
            return;
        }
        $(".search-results").addClass("loading");
        data = {
            'lang': $('body').data("lang"),
            'action': 'get_search',
            'search_input' : $search_input
        }
        $.ajax({
            url: my_ajax.ajax_url,
            data: data,
            method: 'POST',
            timeout: 5000,
            success: function(result){
                if($new_search_input != $search_input){
                    return;
                }
                setTimeout(function(){
                    result = JSON.parse(result);
                    if(result.status == 1){
                        $(".search-results_list").addClass("active");
                        $(".search-results_list__content").html(result.html);
                        if(result.posts.length > 0){
                            $(".search-results_quick, .search-results_like").removeClass("active");
                            if(result.posts.length > 5){
                                $(".search-results_list__btn").addClass("active");
                                $href = $(".search-results_page").attr("href");
                                var url = new URL($href);
                                var search_params = url.searchParams;
                                search_params.set('s', $search_input);
                                url.search = search_params.toString();
                                var new_url = url.toString();
                                $(".search-results_page").attr("href", new_url)
                            }else{
                                $(".search-results_list__btn").removeClass("active");
                            }
                        }else{
                            $(".search-results_quick, .search-results_like").addClass("active");
                            $(".search-results_list__btn").removeClass("active");
                        }
                    }
                    $(".search-results").removeClass("loading");
                }, 300)
            },
            error: function(xhr, textStatus, errorThrown){
            }
        });
    }


    LL = new LazyLoad({
    });
})


if(document.getElementById("tagline") != null){
    gsap.to("#tagline", {
        yPercent: -180,
        ease: "none",
        scrollTrigger: {
            trigger: ".trigger",
            // start: "top bottom", // the default values
            // end: "bottom top",
            scrub: true
        },
    });
}



gsap.utils.toArray(".mask").forEach(function(mask){
    gsap.to(mask, {
        scaleX: 0,
        scrollTrigger: {
            trigger: mask,
            toggleActions: "play none none none"
        },
        ease: 'expo.inOut',
        duration: 1.2,
    });
});

gsap.utils.toArray(".homepage_splide").forEach(function(splide){
    gsap.to(splide, {
        opacity: 1,
        translateY: 0,
        scrollTrigger: {
            trigger: splide,
            toggleActions: "play none none none"
        },
        ease: 'expo.easeInOut',
        duration: 1,
    });
});
gsap.utils.toArray(".line-trigger").forEach(function(line){
    gsap.from(line, {
        scrollTrigger: {
            trigger: line,
            toggleActions: "play none none none",
            onEnter: function() {
                line.classList.add('active');
            },
        },
    });
});

if(document.getElementsByClassName('homepage-happenings_slides').length > 0){
    var happenings_splide = new Splide( '.homepage-happenings_slides.splide', {
        type     : 'loop',
        focus    : 'center',
        autoWidth: true,
        arrows : false,
        pagination      : false,
        breakpoints: {
            767: {
                pagination      : true,
                perPage: 1,
            },
        }
    } );
    happenings_splide.mount();
    happenings_splide.on( 'drag', function () {
        cursor.classList.add('cursor--drag');
    } );
    happenings_splide.on( 'dragged', function () {
        cursor.classList.remove('cursor--drag');
    } );
}
if(document.getElementsByClassName('homepage-shoppings_slides').length > 0){
    var shoppings_splide = new Splide( '.homepage-shoppings_slides.splide', {
        type     : 'loop',
        focus    : 'center',
        autoWidth: true,
        arrows : false,
        pagination      : false,
        breakpoints: {
            767: {
                pagination      : true,
                perPage: 1,
            },
        }

    } );
    shoppings_splide.mount();
    shoppings_splide.on( 'drag', function () {
        cursor.classList.add('cursor--drag');
    } );
    shoppings_splide.on( 'dragged', function () {
        cursor.classList.remove('cursor--drag');
    } );
}
if(document.getElementsByClassName('homepage-dinings_slides').length > 0){
    var dinings_splide = new Splide( '.homepage-dinings_slides.splide', {
        type     : 'loop',
        focus    : 'center',
        autoWidth: true,
        arrows : false,
        pagination      : false,
        breakpoints: {
            767: {
                pagination      : true,
                perPage: 1,
            },
        }

    } );
    dinings_splide.mount();
    dinings_splide.on( 'drag', function () {
        cursor.classList.add('cursor--drag');
    } );
    dinings_splide.on( 'dragged', function () {
        cursor.classList.remove('cursor--drag');
    } );
}

if(document.getElementsByClassName('homepage-instagram_slides').length > 0) {


    var dinings_splide = new Splide( '.homepage-dinings_slides.splide', {
        type     : 'loop',
        focus    : 'center',
        autoWidth: true,
        arrows : false,
        pagination      : false,
        breakpoints: {
            767: {
                pagination      : true,
                perPage: 1,
            },
        }

    } );
}
if(document.getElementsByClassName('homepage-instagram_slides_mobile').length > 0) {
    var instagram_slides_mobile_splide = new Splide( '.homepage-instagram_slides_mobile', {
        type     : 'loop',
        focus    : 'center',
        autoWidth: true,
        arrows : false,
        pagination      : false,
        breakpoints: {
            767: {
                pagination      : true,
                perPage: 1,
            },
        }

    } );
    instagram_slides_mobile_splide.mount();
    instagram_slides_mobile_splide.on('drag', function () {
        cursor.classList.add('cursor--drag');
    });
    instagram_slides_mobile_splide.on('dragged', function () {
        cursor.classList.remove('cursor--drag');
    });
}
if(document.getElementsByClassName('homepage-instagram_slides').length > 0) {

    var instagram_splide = new Splide('.homepage-instagram_slides.splide', {
        type: 'loop',
        focus: 'center',
        autoWidth: true,
        pagination: false,
        arrows : false,
        drag: 'free',
        perPage: 3,
        autoScroll: {
            speed: 2,
        },
        breakpoints: {
            767: {
                pagination: true,
                autoScroll: false,
                perPage: 1,
            },
        }
    })
    instagram_splide.mount(window.splide.Extensions);
    instagram_splide.on('drag', function () {
        cursor.classList.add('cursor--drag');
    });
    instagram_splide.on('dragged', function () {
        cursor.classList.remove('cursor--drag');
    });
}





if(document.getElementsByClassName('parking-promotions_items').length > 0){
    var parking_splide = new Splide( '.parking-promotions_items.splide', {
        arrows : false,
        type  : 'fade',
        pagination      : true,
        breakpoints: {
            767: {
                pagination      : true,
                perPage: 1,
            },
        }

    } );
    parking_splide.mount();
}


if(document.getElementsByClassName('splide_cursor').length > 0){
    var cursor = document.querySelector('.cursor');
    var cursorOuter = document.querySelector('.cursor-move-outer');
    var cursorOuterInner = document.querySelector('.cursor-outer');
    var triggers = document.querySelectorAll('.splide_cursor');
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
        function(trigger) {
            trigger.addEventListener('mouseenter', function () {
                cursor.classList.add('cursor--hover');
            });
            trigger.addEventListener('mouseleave', function () {
                cursor.classList.remove('cursor--hover');
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


function mobileAndTabletcheck(){
    var check = false;
    (function(a){if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino|android|ipad|playbook|silk/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4))) check = true;})(navigator.userAgent||navigator.vendor||window.opera);
    return check;
};
// app defined
App = angular.module('app', []);
App.controller('ShopController', ['$scope','$sce', '$http', function($scope,$sce,$http) {
    var default_phase = "phase_1";

    $scope.init = function($content_type){
        $scope.sort="category"
        $scope.display = 'grid';
        $scope.is_floorplan = false;
        $scope.type=$content_type;
        $scope.char="all";
        $scope.shop_floor="all";
        $scope.shop_phase=default_phase;
        $scope.shop_other="all";
        $scope.sortBy='primary_slug';
        $scope.get_shops();
    }
    $scope.resetFilter = function(currentSort){
        if($scope.sortBy == currentSort){
            $scope.reverse = ! $scope.reverse;
        }else{
            $scope.shopFilter = undefined;
        }
        $scope.sortBy = currentSort;
    }
    $scope.test = function(){
       console.log('====================================');
       console.log(123);
       console.log('====================================');
    }
    $scope.get_shops = function(){
        $scope.loading = true;
        $http({
            method: 'POST',
            url: my_ajax.ajax_url,
            params: {
                action: "get_shops"
            },
            transformRequest: function(obj) {
                var str = [];
                for(var p in obj)
                    str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
                return str.join("&");
            },
            data: {
                type: $scope.type
            },
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(function (response) {
            console.log("response",response);
            if (response.status == 200) {
                $response_data = response.data;
                console.log("data-show",$response_data)
                if ($response_data.status == 1) {
                    $scope.shops = $response_data.shops;
                    $scope.get_shops_by_cat($scope.category)
                    $scope.loading = false;
                    var myLazyLoad = new LazyLoad({
                        elements_selector: ".lazyload"
                    });
                    return
                }
            }
            $scope.loading = false;
            $scope.shops = [];
        });
    }

    $scope.get_shops_by_cat = function(category){
        $scope.category = category;
        filter_other = $scope.shop_other == "all" ? "" : $scope.shop_other;
        filter_cat = $scope.category == "all" ? "" : $scope.category;
        if(filter_other == ""){
            $scope.shopFilter = {
                cats: filter_cat
            }
        }else{
            $scope.shopFilter = {
                cats: filter_cat,
                shop_others: filter_other
            }
        }
        $scope.is_floorplan = false;
        $scope.reset_sorts("category");
    }
    $scope.get_shops_by_others = function(other){
        $scope.shop_other = other;
        filter_other = $scope.shop_other == "all" ? "" : $scope.shop_other;
        filter_cat = $scope.category == "all" ? "" : $scope.category;
        if(filter_other == ""){
            $scope.shopFilter = {
                cats: filter_cat
            }
        }else{
            $scope.shopFilter = {
                cats: filter_cat,
                shop_others: filter_other
            }
        }
        $scope.is_floorplan = false;
        $scope.reset_sorts("others");
    }
    $scope.showListShop = function(){

    }
    $scope.get_shops_by_char = function(char){
        $scope.char = char;
        if(char == "all"){
            $scope.shopFilter = undefined
        }else{
            $scope.shopFilter = {sort_name: char};
        }
        $scope.is_floorplan = false;
        $scope.reset_sorts("char");
    }
    $scope.get_shops_by_floor = function(phase, floor){
        $scope.shop_floor = floor;
        if(phase == "all"){
            $scope.shopFilter = undefined;
        }else if(floor == "all"){
            $scope.shopFilter = {shop_phase: phase};
        }else{
            $scope.shopFilter = {shop_floor: floor, shop_phase: phase};
        }
        $scope.is_floorplan = false;
        $scope.reset_sorts("floor");
    }
    $scope.get_shops_by_phase_floor = function(phase_floor){
        if(phase_floor == null) return;
        phase_floor_array = phase_floor.split(",")
        $scope.get_shops_by_floor(phase_floor_array[0], phase_floor_array[1]);
    }
    $scope.buildSvg = function(svg){
        return $sce.trustAsHtml(svg);
    }
    $scope.get_shops_by_phase = function(phase){
        $scope.shop_phase = phase;
        if(phase == "all"){
            $scope.shopFilter = undefined
        }else{
            $scope.shopFilter = {shop_phase: phase};
        }
        $scope.is_floorplan = false;
        $scope.reset_sorts("phase");
    }
    $scope.reset_sorts = function($sort){

        if($sort != "category" && $sort != "others"){
            $scope.category="all";
            $scope.shop_other="all";
        }
        if($sort != "char"){
            $scope.char="all";
        }
        if($sort != "phase" && $sort != "floor"){
            $scope.shop_phase=default_phase;
        }
        if($sort != "floor"){
            $scope.shop_floor="all";
        }
    }
    $scope.reset_all_sort = function(){
        if($scope.sort == "phase_level"){
            $scope.shopFilter = {shop_phase: default_phase};
        }else{
            $scope.shopFilter = undefined;
        }
        $scope.reset_sorts("all");
    }
}]);


