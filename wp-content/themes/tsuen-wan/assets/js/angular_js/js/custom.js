
jQuery(document).ready(function( $ ) {
    if($("#masterslider").length > 0){
        var masterslider = new MasterSlider();
        // masterslider.control('timebar' ,{ autohide:false, overVideo:true, align:'top', color:'#FFFFFF'  , width:4 });
        // masterslider.control('bullets' , {autohide:false  , dir:"h", align:"bottom"});
        masterslider.setup("masterslider", {
            minHeight       : 0,
            space           : 0,
            start           : 1,
            grabCursor      : true,
            swipe           : false,
            mouse           : true,
            keyboard        : false,
            layout          : "fullscreen",
            wheel           : false,
            autoplay        : true,
            instantStartLayers:true,
            mobileBGVideo:false,
            loop            : true,
            shuffle         : false,
            preload         : 0,
            heightLimit     : true,
            autoHeight      : false,
            smoothHeight    : true,
            endPause        : false,
            overPause       : false,
            fillMode        : "fill",
            centerControls  : true,
            startOnAppear   : false,
            layersMode      : "center",
            autofillTarget  : "",
            hideLayers      : false,
            fullscreenMargin: 0,
            speed           : 10,
            dir             : "h",
            parallaxMode    : 'swipe',
            view            : "parallaxMask",
            breakpoint : {
                phone: 575,
                tablet: 991,
            }
        });
        $("#home_page .slider_control .arrow_left").click(function(){
            console.log("test");
            masterslider.api.previous();
        })
        $("#home_page .slider_control .arrow_right").click(function(){
            masterslider.api.next();
        })
        masterslider.api.addEventListener(MSSliderEvent.CHANGE_START , function(){
            $("#home_page .slider_control .current_number").text(masterslider.api.index() + 1);
        });
    }


    $(".responsive_btn").click(function () {
        $(this).toggleClass('active');
        $(".header_menu").toggleClass("is-active");
        $("#responsive_menu").toggleClass("active");
        $("#responsive_bg").fadeToggle();
    })
    $("#responsive_bg").click(function () {
        $(".responsive_btn").removeClass('active');
        $("#responsive_menu").removeClass("active");
        $(".header_menu").removeClass('is-active');
        $(this).fadeOut();
    })


    $('.white_section').waypoint(function(direction, element) {
        if(direction == "down"){
            $('.header_menu_section').addClass("black_header");
        }else if(direction == "up"){
            $('.header_menu_section').removeClass("black_header");
        }
    }, {
        offset: '220'
    })
    $('.black_section').waypoint(function(direction, element) {
        if(direction == "down"){
            $('.header_menu_section').addClass("white_header");
        }else if(direction == "up"){
            $('.header_menu_section').removeClass("white_header");
        }
    }, {
        offset: '220'
    })
});
