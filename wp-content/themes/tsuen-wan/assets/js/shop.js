(function ($) {
    function setCookie(cname, cvalue, exdays) {
        const d = new Date();
        d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
        let expires = "expires=" + d.toUTCString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    }
    function fillter_ajax(form, ele) {
        $('body').css('opacity', '0.5');
        $.ajax({
            type: "GET",
            url: form.attr('action'),
            data: form.serialize(),
            success: function (response) {
                $data = $(response).find(ele);
                $(ele).html($data[0].innerHTML);
                $('body').css('opacity', '1');
            }
        });
    }
    async function loadmore(url, elem) {
        $('.loader').show();
        $.ajax({
            type: "GET",
            url: url,
            success: function (response) {
                $data = $(response).find(elem);
                $(elem).append($data[0].innerHTML);

                $pavi = $(response).find('.pagenavi');
                $('.pagenavi').html($pavi[0].innerHTML);
                $('.loader').hide();
            }
        });
    }
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
    $('.tab-menu a').click(function (e) {
        e.preventDefault();
        var target = $(this).attr('href')
        $('.tab-menu li').removeClass('active');
        $(this).parent().addClass('active');
        $('.tab-content').removeClass('active')
        $(target).addClass('active')
        $(target).addClass('active')
        console.log(target)
    })
    $('.expand').click(function (e) {
        e.preventDefault();
        var textchange = $(this).data('expaned');
        var textclose = $(this).data('closed');
        var crt = $(this).text().trim()
        if (crt == textchange) {
            $(this).text(textclose)
        } else {
            $(this).text(textchange)
        }
        $(this).prev().slideToggle()
    })
    $('body').on('click', '.cat-happening input[type="radio"]', function (e) {
    	fillter_ajax($('.form-filter'),'.querycontent');
    })
    $(window).on("scroll", function () {
        var height = $(window).scrollTop() + $(window).height();
        if ($('#innifyscroll').length > 0) {
            var from = $('#innifyscroll').offset().top;
            var to = from + $('.item').height();
            var scrollLoad = true;
            if ($('.pagenavi .next').length > 0) {
                var urlnext = $('.pagenavi .next').attr('href');
                if (scrollLoad && height > from && height < to) {
                    $('.pagenavi .next').remove();
                    scrollLoad = false;
                    setTimeout(function () {
                        loadmore(urlnext, '.list-items');
                    }, 500);
                }
            }
        }
        if ($('.ani-firework').length > 0) {
            $('.ani-firework').each(function () {
                var parent_firework = $(this).parent();
                if(parent_firework.data('offset') !== undefined){
                    var offset = parseInt(parent_firework.data('offset'), 10);
                    var aTop = $(this).offset().top + $(window).height() - offset;
                    if (height >= aTop) {
                        $(this).removeClass('no')
                    }
                    else {
                        $(this).addClass('no')
                    }
                }else{
                    var aTop = $(this).offset().top + $(window).height() - 400;
                    var facilitie = $('.leftfire');
                    if(facilitie.length > 0){
                        aTop = $(this).offset().top + $(window).height() - 650;
                    }
                    if (height >= aTop&&$(window).scrollTop()>0) {
                        $(this).removeClass('no')
                    }
                    else {
                        $(this).addClass('no')
                    }
                }
                
            })
        }
    });
    if ($('.promo-item').length > 1) {
        const swiper_promo = new Swiper('.promo_slider ', {
            slidesPerView: 1,
            spaceBetween: 0,
            loop: true,
            autoplay: {
                delay: 7000,
            },
            navigation: {
                nextEl: '.promo_slider .arrow-next-button',
                prevEl: '.promo_slider .arrow-prev-button',
            },
            pagination: {
                el: '.promo_slider .home-slider-progressbar',
                clickable: false,
            },
        });
    }
    $('body').on('click', '.clickpopup', function () {
        var toggle = $(this).data('toggle');
        if ($(this).data('target') == undefined) {
            var target = $(this).find('a').attr('href');
        } else {
            var target = $(this).data('target');
        }
        $(target).addClass('show');
        $('body').append('<div class="modal-backdrop show"></div>');
    })
    $('body').on('click', '.close,.modal-backdrop', function (event) {
        event.preventDefault();
        $('.modal').removeClass('show');
        $('.modal-backdrop').remove();
        var target = $(this).closest('.modal').attr('id')
        $('html').removeClass('disable_scroll')
        if (target == 'popup_christmas') {
            //setCookie('hidepoup', 'yes', 1);
        }
    })
    $('body').on('click', '.modal', function (event) {
        if ($(event.target).closest(".modal-dialog").length < 1) {
            $('.modal').removeClass('show');
            $('.modal-backdrop').remove();
        }
    });
    if ($('#popup_christmas').length > 0) {
        setTimeout(() => {
            $('#popup_christmas').addClass('show')
            if ($('#popup_christmas').hasClass('show')) {
                $('html').addClass('disable_scroll')
            }
        }, 500);
        setTimeout(() => {
            $('#popup_christmas').find('.animate_line').addClass('show')
        }, 1000);
    }

    $(".share-link-btn").click(function(){
        var copyText = $(this).data("copy");
        copyTextToClipboard(copyText);
        $(".copy-success").addClass("active")
        setTimeout(function(){
            $(".copy-success").removeClass("active");
        }, 4000)
    })
    $('.icon_share').click(function(){
        $('.share-content').slideToggle()
    })
    $(document).click(function (event) {
        var $target = $(event.target);
        if (!$target.closest('.share').length && $('.share').is(":visible")) {
            $('.share .share-content').slideUp();
        }
    });
})(jQuery);