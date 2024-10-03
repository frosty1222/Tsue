(function ($) {
    $('.visit-menu-item').click(function () {
        var dataList = $(this).data('list');
        var listItems = '#' + dataList;
        if (!$(this).hasClass('active')) {
            $('.visit-menu-item').removeClass('active');
            //$('.visit-list-items').removeClass('active');
            $(this).addClass('active');
            //$(listItems).addClass('active');
            $('.visit-list-items').each(function (i, e) {
                if ($(e).hasClass(dataList)) {
                    $(e).addClass('active');
                } else {
                    $(e).removeClass('active');
                }
            });
        }
        checkVisitListItems();
    });
    checkVisitListItems();
    $(window).resize(function () {
        checkVisitListItems();
    });
})(jQuery);

function checkVisitListItems() {
    jQuery('.visit-list-items').each(function () {
        var columns = jQuery(this).find('.visit-list-column');
        if (columns.length == 2) {
            var columns_1 = jQuery(columns[0]).find('.visit-list-item');
            var columns_2 = jQuery(columns[1]).find('.visit-list-item');
            columns_1.each(function (i, e) {
                let _height1 = jQuery(e).outerHeight();
                let _height2 = jQuery(columns_2[i]).outerHeight();
                let _height = Math.max(_height1, _height2);
                // console.log('_height1', _height1);
                // console.log('_height2', _height2);
                // console.log('_height', _height);
                // console.log('_______________________________');
                if (_height && (_height > _height1 || _height1 > _height2)) {
                    jQuery(columns_1[i]).css('height', _height + 'px');
                    jQuery(columns_2[i]).css('height', _height + 'px');
                }
            });
        }
    });
}