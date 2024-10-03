(function ($) {
    $("#header_menu_section .search_icon").click(function(){
        if ($("#header_menu_section").hasClass("in_search")) {
            $("#header_menu_section").removeClass("in_search");
            $("body").removeClass("menu_hover");
            $(".overlay-search").css('opacity', '0');
            $(".overlay-search").css("visibility", "hidden");
            $(".search_input").removeClass('active-search');
            $('#search_results').removeClass('search-box-mobile');
            if ($(window).width() <= 767) {
                $('#search_results').addClass('close-search-box-mobile');
            }
            if ($(window).width() > 767) {
                TweenMax.to($('#search_results'), 0.5, {height: 0});
            }
        } else {
            $("#header_menu_section").addClass("in_search");
            $("body").addClass("menu_hover");
            $(".responsive_btn").removeClass(("is-active"));
            $(".mobile-menu").removeClass("slide-active");
            $(".overlay-search").css('opacity', '1');
            $(".overlay-search").css("visibility", "visible");
            $(".search_input").addClass('active-search');
            if ($(window).width() <= 767) {
                $('#search_results').addClass('search-box-mobile');
            }
            if ($(window).width() > 767) {
                TweenMax.to($('#search_results'), 0.5, {height:'auto'});
            }
        }
    })
    $("#header_menu_section #search_close, #responsive_bg").click(function(){
        $("#header_menu_section").removeClass("in_search");
        $("body").removeClass("menu_hover")
        $(".overlay-search").css('opacity', '0');
        $(".overlay-search").css("visibility", "hidden");
        $(".search_input").removeClass('active-search');
        $('#search_results').removeClass('search-box-mobile');
        if ($(window).width() <= 767) {
            $('#search_results').addClass('close-search-box-mobile');
        }
        if ($(window).width() > 767) {
            TweenMax.to($('#search_results'), 0.5, {height: 0});
        }
    })
    $(document).on("click", function(event) {
        if (!$(event.target).closest("#header_menu_section").length && !$(event.target).closest(".search_input").length) {
            $("#header_menu_section").removeClass("in_search");
            $("body").removeClass("menu_hover");
            $(".overlay-search").css('opacity', '0');
            $(".overlay-search").css("visibility", "hidden");
            $(".search_input").removeClass('active-search');
            $('#search_results').removeClass('search-box-mobile');
            if ($(window).width() <= 767) {
                $('#search_results').addClass('close-search-box-mobile');
            }
            if ($(window).width() > 767) {
                TweenMax.to($('#search_results'), 0.5, {height: 0});
            }
        }
    });

    // Quick Search
    var typingTimer;
    var doneTypingInterval = 300;
    var is_cancel = false;
    var $new_search_input = ""

    $(".search_input").keyup(function(){
        $search_input = $(".search_input").val();
        $new_search_input = $(".search_input").val();
        if($search_input.length < 1){
            $(".search-results_list").removeClass("active");
            $(".search-results_container").removeClass("active-wrap");
            $(".search-results_quick, .search-results_like").addClass("active");
            $(".search-results").removeClass("loading");
            is_cancel = true;
            return;
        }
        is_cancel = false;
        clearTimeout(typingTimer);
        typingTimer = setTimeout(function(){
            get_search($search_input);
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
                        $(".search-results_container").addClass("active-wrap");
                        $(".search-results_list__content").html(result.html);
                        $('.total-results .count-result').text(result.count);
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
        if ($('.mobile-menu').hasClass('slide-active')) {
            $(".mobile-menu").removeClass("slide-not-active");
        } else {
            $(".mobile-menu").addClass("slide-not-active");
        }
    })

    var in_scroll = false;

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
    function searchSlider(type){
        if ($(window).width() < 767) {
            const totalItems = $('.'+type+' .swiper-wrapper').attr('data-totalItem');
            var slideView = totalItems > 1 ? 1.087 : 1;
            if(slideView == 1){
                $('.'+type+' .swiper-container').addClass('one-slide');
            }
            const swiper_search= new Swiper('.all-type .'+type+' .swiper-container', {
                loop: false,
                speed: 500,
                slidesPerView: slideView,
                autoplay: false,
                spaceBetween: 0,
                pagination: {
                    el: '.all-type .'+type+' .home-list-progressbar',
                   clickable: true,
               },
                breakpoints: {
                    0: {
                    slidesPerView: slideView,
                    spaceBetween: 8,
                    loop: false,
                    },
                    600: {
                    slidesPerView: 1.5,
                    spaceBetween: 8,
                    },
                    768: {
                    slidesPerView: 3,
                    },
                },
                on: {
                    init: function () {
                        
                    for (var i = 0; i < this.params.slidesPerView; i++) {
                        if(this.slides[i] && this.slides[i].classList){
                            this.slides[i].classList.add('active');
                        }
                        
                    }
                    },
                    slideChange: function () {
                    document.querySelectorAll('.all-type .'+type+' .swiper-slide').forEach(function (slide) {
                        slide.classList.remove('active');
                    });
                    for (var i = 0; i < this.params.slidesPerView; i++) {
                        var slideIndex = this.activeIndex + i;
                        if (slideIndex < this.slides.length) {
                            this.slides[slideIndex].classList.add('active');
                        }
                    }
                    },
                }    
            
            });
        }
    }
    searchSlider('dinings');
    searchSlider('shops');
    searchSlider('happening');
    searchSlider('play-garden');
    function popupSocial(iconClass, popupClass){
        $(iconClass).click(function(e){
            e.preventDefault();
            $(popupClass).addClass('show');
            setTimeout(() => {
                $(popupClass).find('.animate_line').addClass('show');
            }, 500);
        })
    
        $(popupClass + ' .close').click(function(e){
            e.preventDefault();
            $(popupClass).find('.animate_line').removeClass('show');
        })
    }
    
    popupSocial('.wechat-icon', '.popup-wechat');
    popupSocial('.social-item-0 a', '.popup-wechat');
    popupSocial('.treatment-icon', '.popup-treatment');
    popupSocial('.social-item-1 a', '.popup-treatment');

    $('.list-item a:first, .list-item a:eq(1)').click(function(e){
        e.preventDefault();
    })

})(jQuery);

//window.onscroll = function() {headerScroll()};

var header = document.getElementById("header_menu_section");
var sticky = header.offsetTop;

function headerScroll() {
  if (window.scrollY > sticky) {
    header.classList.add("sticky");
  } else {
    header.classList.remove("sticky");
  }
}


document.addEventListener('DOMContentLoaded', function() {
    var itemSearch = document.querySelectorAll('.item-search');
    itemSearch.forEach(function(item) {
        item.addEventListener('click', function() {
            var link = item.getAttribute('data-link');
            window.location.href = link;
        });
    });
});