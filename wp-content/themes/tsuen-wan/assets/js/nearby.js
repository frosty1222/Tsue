(function ($) {
    //slider on nearby page
    if(document.getElementsByClassName('nearby_slides_splide').length > 0) {
        var nearby_slides_splide = new Splide( '.nearby_slides_splide.splide', {
            type   : 'loop',
            perPage: 4,
            perMove: 1,
            rewind : true,
            autoScroll: {
                speed: 1,
            },
            autoWidth: true,
            pagination: false,
            paginationClickable: true,
            arrows : false,
            breakpoints: {
                767: {
                    pagination: true,
                    autoScroll: false,
                    perPage: 1,
                },
            }
        });
        nearby_slides_splide.mount();
    }

    //slider on play garder page
    if(document.getElementsByClassName('play-garden_slides').length > 0) {
        var playgarden_slides = new Splide( '.play-garden_slides.splide', {
            type   : 'loop',
            perPage: 4,
            perMove: 1,
            rewind : true,
            autoScroll: {
                speed: 1,
            },
            autoWidth: true,
            pagination: false,
            paginationClickable: true,
            arrows : false,
            breakpoints: {
                767: {
                    pagination: true,
                    autoScroll: false,
                    perPage: 1,
                },
            }
        });
        playgarden_slides.mount(window.splide.Extensions);
    }

    // add fancybox to slider
    var imagePaths = [];
    $('.fancybox_slider .splide__slide').each(function(index) {
        var imagePath = $(this).find('.img-newtab').attr('href');
        imagePaths.push({ src: imagePath });
    });
    $(document).ready(function() {

        checkScreenWidth();
        $(window).resize(function() {
            checkScreenWidth();
        });    
        function checkScreenWidth() {
            var screenWidth = $(window).width();

            if (screenWidth <= 768) {
                $('.fancybox_slider .splide__slide').off('click');
            } else {
                
                $('.fancybox_slider .splide__slide').on('click', function() {
                    var index = $(this).index();
                    $.fancybox.open(
                        imagePaths,
                        {   
                            wheel: false,
                            hideOnContentClick: false,
                            buttons: [],
                            index: index,
                            btnTpl: {
                                arrowLeft:
                                    '<a data-fancybox-prev class="fancybox-button fancybox-button--arrow_left arrow-prev-button arrow-button-all" title="Previous">' +
                                    scriptData.previousText +
                                    '<div class="svg"><svg width="110" height="19" viewBox="0 0 110 19" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M109.664 18.0004L2.99999 17.9996L26.0147 2V11.9997" stroke="white" stroke-width="1.3333"/></svg></div>' +
                                    "</a>",
                                arrowRight:
                                    '<a data-fancybox-next class="fancybox-button fancybox-button--arrow_right arrow-button-all arrow-button-all" title="Next">' +
                                    scriptData.nextText +
                                    '<div class="svg"><svg width="109" height="19" viewBox="0 0 109 19" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6.10352e-05 18.0004L106.664 17.9996L83.6491 2.00001V11.9997" stroke="white" stroke-width="1.3333"/></svg></div>' +
                                    "</a>"
                            },
                            afterShow: function(instance, current) {
                                if($('.page-template-parking').length>0){
                                    var position = current.$content.parent();
                                }else{
                                    var position = current.$content;
                                }
                                $('<button data-fancybox-close class="fancybox-button fancybox-button--close" title="Close">' +
                                    '<svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">' + 
                                        '<path d="M26.5166 8.83887L8.8389 26.5165" stroke="white" stroke-width="2"/>' + 
                                        '<path d="M26.5166 26.5166L8.8389 8.83893" stroke="white" stroke-width="2"/>' +
                                    '</svg>' +
                                  '</button>').appendTo(position);

                            },
                            loop: true
                        }
                    );
                });
            }
        }
    });

    //js click 
    $('.btn-mb').on('click', function() {
        var description = $(this).next('.heading_2');
        $('.heading_2').not(description).slideUp();
        description.slideToggle();
    });
})(jQuery);