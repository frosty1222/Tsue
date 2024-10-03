jQuery(document).ready(function ($) {

    let paths = $(".draw-line path");
    paths.each(function () {
        let length = $(this).get(0).getTotalLength() + "px";
        $(this).css("stroke-dasharray", length);
        $(this).css("stroke-dashoffset", length);
    })

    // $('.letter-fade').each(function () {
    //     // var text = $(this).text();
    //     // var newText = '';
    //     // for (var i = 0; i < text.length; i++) {

    //     //   if (text[i] === ' ') {
    //     //     newText += ' ';
    //     //   } else {
    //     //     newText += '<span style="--delay: ' + i * 100 + 'ms">' + text[i] + '</span>';
    //     //   }
    //     // }
    //     // $(this).html(newText);

    //     var text = $(this).html();

    //     var text = text.split(/(<br>| )/).filter(function (element) {
    //         return element !== " ";
    //     });

    //     var text = text.map(function (element) {
    //         return element.replace(/\n/g, '');
    //     });

    //     var text = text.map(function (element) {
    //         return element.replace(/&amp;/g, '&');
    //     });

    //     var newText = '';

    //     var count = 0;

    //     for (i = 0; i < text.length; i++) {
    //         word = text[i];
    //         nextWord = text[i + 1];
    //         lastWord = "";
    //         if (nextWord == "<br>" || i + 1 == text.length) {
    //             lastWord = "last";
    //         }

    //         if (word != "<br>") {
    //             newText += "<span class='word " + lastWord + "'>";
    //             for (var j = 0; j < word.length; j++) {
    //                 newText += '<span style="--delay: ' + count * 30 + 'ms">' + word[j] + '</span>';

    //                 count++;
    //             }
    //             newText += "</span>";
    //         }
    //         else {
    //             newText += "<br>";
    //         }

    //     }

    //     $(this).html(newText);
    // });

    // activeOnScroll();
    // $(document).on("scroll", function () {
    //     activeOnScroll();
    // });

    $(".service .list .title").click(function () {
        var item = $(this).parent(".list-item");

        if (!item.hasClass("active")) {
            item.addClass("active");
            item.children(".content").slideDown();

            $(".service .list .list-item").each(function () {
                if ($(this).html() != item.html()) {
                    $(this).removeClass("active");
                    $(this).children(".content").slideUp();
                }
            })
        }
        else {
            item.removeClass("active");
            item.children(".content").slideUp();
        }

    })

})
// if (jQuery('body').hasClass('page-template-page-shoppings-dinnings')) {
//     //activeOnScroll();
//     setTimeout(function () {
//         activeOnScroll();
//         //console.log("active");
//     }, 200);
// }
function activeOnScroll() {
    var pageTop = jQuery(document).scrollTop()
    var pageBottom = pageTop + jQuery(window).height() / 2;
    var tags = jQuery(".draw-line svg");
    var letter_fades = jQuery(".letter-fade");

    tags.each(function () {
        var parent = jQuery(this).parent(".draw-line");
        if (parent.hasClass("bottom")) {
            pageBottom = pageTop + jQuery(window).height() * 0.9;
        }

        if (parent.hasClass("bottom-fade")) {
            pageBottom = pageTop + jQuery(window).height() * 0.9;
        }

        if (jQuery(this).offset().top < pageBottom) {
            parent.addClass("active")
        } else {
            // $(this).removeClass("visible")
        }
    });

    letter_fades.each(function () {
        var pageBottom = pageTop + jQuery(window).height();
        if (jQuery('.about-us').length > 0) {
            pageBottom = pageTop + jQuery(window).height() - 200;
        }
        var $_this = jQuery(this);
        if (jQuery(this).offset().top < pageBottom) {
            if (!jQuery(this).hasClass("active")) {
                if (jQuery(this).hasClass("fade-delay")) {
                    setTimeout(function () {
                        $_this.addClass("active");
                    }, 2000);
                } else if (jQuery(this).hasClass("fade-delay2")) {
                    setTimeout(function () {
                        $_this.addClass("active");
                    }, 2500);
                } else {
                    jQuery(this).addClass("active");
                }
            }
        } else {
            // $(this).removeClass("visible")
        }
    });
}