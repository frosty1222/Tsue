(function ($) {
    var urlAjax = wpData.adminUrl;
    /**
     * Filter for category and privileges
     */
    $('body').on('click', '#shoppings_page .form-filter input[type="radio"]', function (e) {
        //Clear search        
        $('#shoppings_page .shop_name_input').val('');

        var taxonomy = $(this).attr('name');
        var taxId = $(this).data('term-id');
        var postType = $(this).data('post-type');

        var selectedParams = {};
        selectedParams['tax_dinings'] = false;
        selectedParams['tax_privileges'] = false;
        selectedParams['custom_post_type'] = postType;
        selectedParams['paged'] = 1;
        selectedParams['action'] = 'load_more_shoppings';
        if (taxonomy == 'shop-categories' || taxonomy == 'dining-categories') {
            selectedParams['tax_dinings'] = true;
            selectedParams['taxonomy'] = taxonomy;
            selectedParams['category'] = taxId;
            let privilegesSelected = $('.list-privileges input[type="radio"]:checked').data('term-id');
            if (privilegesSelected) selectedParams['privileges'] = privilegesSelected;
        }

        if (taxonomy == 'privileges') {
            selectedParams['privileges'] = taxId;
            let categorySelected = $('.list-category input[type="radio"]:checked').data('term-id');
            if (categorySelected) selectedParams['category'] = categorySelected.toString();
        }

        if (taxonomy == 'filter-a-z') {
            selectedParams['title_starts_with'] = $(this).val();
        }

        if (taxonomy == 'filter-floor') {
            selectedParams['floor'] = $(this).val();
        }

        // $('body').css('opacity', '0.5');
        // jQuery.ajax({
        //     url: urlAjax,
        //     type: 'post',
        //     data: selectedParams,
        //     error: function (response) {
        //         //var content = jQuery(jQuery.parseHTML(response)).find('.products-list').html();
        //         console.log('error');
        //     },
        //     success: function (response) {
        //         jQuery('.querycontent .list-items').html(jQuery.parseHTML(response));
        //         $('body').css('opacity', '1');
        //     },
        // });

        //console.log(selectedParams);
        $('.list-items .item').hide();
        //example .list-items .item has data-category = 75,85
        //show .list-items .item has data-category = selectedParams['category']
        let category = selectedParams['category'] ? selectedParams['category'].toString() : '';
        let privileges = selectedParams['privileges'] ? selectedParams['privileges'].toString() : '';
        let title_starts_with = selectedParams['title_starts_with'];
        let floor = selectedParams['floor'];
        let items = $('.list-items .item');
        let show = false;
        let has_items = false;
        for (let i = 0; i < items.length; i++) {
            let item = $(items[i]);
            let item_category = item_privileges = [];
            if (item.data('category') && item.data('category') != '') {
                item_category = item.data('category').toString().split(',');
            }
            // check if item has privileges
            if (item.data('privileges') && item.data('privileges') != '') {
                item_privileges = item.data('privileges').toString().split(',');
            }
            let item_title = item.data('title').toLowerCase();
            let item_floor = item.data('floor').toLowerCase();
            show = false;
            if (category) {
                if (category == '' || category == 'all') {
                    show = true;
                } else {
                    if (item_category.indexOf(category) != -1) show = true;
                }
                if (show == true) {
                    if (privileges) {
                        show = false;
                        if (privileges == '' || privileges == 'all_privilege') {
                            show = true;
                        } else {
                            if (item_privileges.indexOf(privileges) != -1) show = true;
                        }
                    }
                }
            } else {
                if (privileges) {
                    if (privileges == '' || privileges == 'all_privilege') {
                        show = true;
                    } else {
                        if (item_privileges.indexOf(privileges) != -1) show = true;
                    }
                }
            }
            if (title_starts_with) {
                if (title_starts_with == 'all') {
                    show = true;
                } else {
                    //compare first character of item_title with title_starts_with
                    //if title_starts_with = '#', compare with number
                    if (title_starts_with == '#') {
                        if (item_title.charAt(0) >= '0' && item_title.charAt(0) <= '9') {
                            show = true;
                        }
                    } else if (item_title.charAt(0).toLowerCase() == title_starts_with) {
                        show = true;
                    }
                }
            }

            if (floor) {
                if (floor == 'all') {
                    show = true;
                } else {
                    if (item_floor == floor) show = true;
                }
            }
            // if (privileges && item_privileges.indexOf(privileges) == -1) {
            //     show = false;
            // }
            // if (title_starts_with && item_title != title_starts_with) {
            //     show = false;
            // }
            // if (floor && item_floor != floor) {
            //     show = false;
            // }
            if (show) {
                item.show();
                has_items = true;
            }
        }

        if (has_items == false) {
            $('.no-posts').show();
        } else {
            $('.no-posts').hide();
        }
    });

    // Reset filter when change tab
    $('#shoppings_page .shop-top-menu .tab-menu a').click(function (e) {
        //Clear search        
        $('#shoppings_page .shop_name_input').val('');

        //Active mobile tab
        var target = $(this).data('href-mobile');
        $(target).addClass('active');

        var targetDesktop = $(this).attr('href');
        if (targetDesktop == '#tab_floor-plan') {
            $('.querycontent').addClass('active-floor-plan');
            $('#shoppings_page').addClass('active-floor-plan');
        } else {
            $('.querycontent').removeClass('active-floor-plan');
            $('#shoppings_page').removeClass('active-floor-plan');
        }

        var selectedParams = {};
        selectedParams['paged'] = 1;
        selectedParams['action'] = 'load_more_shoppings';
        selectedParams['custom_post_type'] = $(this).data('post-type');
        // jQuery.ajax({
        //     url: urlAjax,
        //     type: 'post',
        //     data: selectedParams,
        //     error: function (response) {
        //         //var content = jQuery(jQuery.parseHTML(response)).find('.products-list').html();
        //         console.log('error');
        //     },
        //     success: function (response) {
        //         jQuery('.querycontent .list-items').html(jQuery.parseHTML(response));
        //         $('body').css('opacity', '1');
        //     },
        // });

        //show all items
        $('.list-items .item').show();
        $('.no-posts').hide();

        //reset filter
        $('.list-category input[type="radio"]').prop('checked', false);
        $('.list-privileges input[type="radio"]').prop('checked', false);
        $('#tab_a-z input[type="radio"]').prop('checked', false);
        $('#tab_floor input[type="radio"]').prop('checked', false);

        //make the fillter list-category with id = itemall is checked
        $('.list-category input[type="radio"][data-term-id="all"]').prop('checked', true);
        //make the fillter list-privileges with id = all_privilege is checked
        $('.list-privileges input[type="radio"][data-term-id="all_privilege"]').prop('checked', true);
        //make the fillter tab_a-z with id = all-a-z is checked
        $('#tab_a-z input[type="radio"][value="all"]').prop('checked', true);
        //make the fillter tab_floor with id = all-floor is checked
        $('#tab_floor input[type="radio"][value="all"]').prop('checked', true);
    });

    $('#shoppings_page .tab-content-mobile select').selectric({
        maxHeight: 200
    });

    $('#shoppings_page .tab-content-mobile select').on('change', function () {
        //Clear search        
        $('#shoppings_page .shop_name_input').val('');

        var taxonomy = $(this).attr('name');
        var taxId = $(this).val();
        var postType = $(this).data('post-type');

        var selectedParams = {};
        selectedParams['custom_post_type'] = postType;
        selectedParams['paged'] = 1;
        selectedParams['action'] = 'load_more_shoppings';
        if (taxonomy == 'select-category') {
            if (postType == 'dinings') {
                selectedParams['taxonomy'] = 'dining-categories';
            } else {
                selectedParams['taxonomy'] = 'shop-categories';
            }
            if (taxId != 'all') selectedParams['category'] = taxId;
            let privilegesSelected = $('#select-privileges').val();
            if (privilegesSelected) selectedParams['privileges'] = privilegesSelected;
        }

        if (taxonomy == 'select-privileges') {
            selectedParams['privileges'] = taxId;
            let categorySelected = $('#select-category').val();;
            if (categorySelected) selectedParams['category'] = categorySelected;
        }

        if (taxonomy == 'select-a-z') {
            selectedParams['title_starts_with'] = $(this).val();
        }

        if (taxonomy == 'select-floor') {
            selectedParams['floor'] = $(this).val();
        }

        // $('body').css('opacity', '0.5');
        // jQuery.ajax({
        //     url: urlAjax,
        //     type: 'post',
        //     data: selectedParams,
        //     error: function (response) {
        //         //var content = jQuery(jQuery.parseHTML(response)).find('.products-list').html();
        //         console.log('error');
        //     },
        //     success: function (response) {
        //         jQuery('.querycontent .list-items').html(jQuery.parseHTML(response));
        //         $('body').css('opacity', '1');
        //     },
        // });

        $('.list-items .item').hide();
        //example .list-items .item has data-category = 75,85
        //show .list-items .item has data-category = selectedParams['category']
        let category = selectedParams['category'] ? selectedParams['category'].toString() : '';
        let privileges = selectedParams['privileges'] ? selectedParams['privileges'].toString() : '';
        let title_starts_with = selectedParams['title_starts_with'];
        let floor = selectedParams['floor'];
        let items = $('.list-items .item');
        let show = false;
        let has_items = false;
        for (let i = 0; i < items.length; i++) {
            let item = $(items[i]);
            let item_category = item_privileges = [];
            if (item.data('category') && item.data('category') != '') {
                item_category = item.data('category').toString().split(',');
            }
            // check if item has privileges
            if (item.data('privileges') && item.data('privileges') != '') {
                item_privileges = item.data('privileges').toString().split(',');
            }
            let item_title = item.data('title').toLowerCase();
            let item_floor = item.data('floor').toLowerCase();
            show = false;
            if (category) {
                if (category == '' || category == 'all') {
                    show = true;
                } else {
                    if (item_category.indexOf(category) != -1) show = true;
                }
                if (show == true) {
                    if (privileges) {
                        show = false;
                        if (privileges == '' || privileges == 'all_privilege') {
                            show = true;
                        } else {
                            if (item_privileges.indexOf(privileges) != -1) show = true;
                        }
                    }
                }
            } else {
                if (privileges) {
                    if (privileges == '' || privileges == 'all_privilege') {
                        show = true;
                    } else {
                        if (item_privileges.indexOf(privileges) != -1) show = true;
                    }
                }
            }
            if (title_starts_with) {
                if (title_starts_with == 'all') {
                    show = true;
                } else {
                    //compare first character of item_title with title_starts_with
                    //if title_starts_with = '#', compare with number
                    if (title_starts_with == '#') {
                        if (item_title.charAt(0) >= '0' && item_title.charAt(0) <= '9') {
                            show = true;
                        }
                    } else if (item_title.charAt(0).toLowerCase() == title_starts_with) {
                        show = true;
                    }
                }
            }

            if (floor) {
                if (floor == 'all') {
                    show = true;
                } else {
                    if (item_floor == floor) show = true;
                }
            }
            // if (privileges && item_privileges.indexOf(privileges) == -1) {
            //     show = false;
            // }
            // if (title_starts_with && item_title != title_starts_with) {
            //     show = false;
            // }
            // if (floor && item_floor != floor) {
            //     show = false;
            // }
            if (show) {
                item.show();
                has_items = true;
            }
        }

        if (has_items == false) {
            $('.no-posts').show();
        } else {
            $('.no-posts').hide();
        }
    })

    /**
     * Search
     */
    // Show search input
    $('#shoppings_page .button-search').click(function (e) {
        e.preventDefault();
        $(this).closest('.filter-search').addClass('active');
    });

    // hide search input
    $('#shoppings_page .shop_name_close').click(function (e) {
        e.preventDefault();
        $('#shoppings_page .shop_name_input').val('');
        $(this).closest('.filter-search').removeClass('active');
    });

    // Search
    $('#shoppings_page .shop_name_input').keyup(function () {
        var textSearch = $(this).val();
        var selectedParams = {};
        var tabActive = tabActiveHref = '';
        var serchOn = $(this).data('version');

        selectedParams['paged'] = 1;
        selectedParams['action'] = 'load_more_shoppings';
        selectedParams['custom_post_type'] = $(this).data('post-type');
        selectedParams['text_search'] = textSearch;

        // Get the tab is active
        $('.shop-menu_btn').each(function (index, element) {
            if ($(element).hasClass('active')) {
                tabActive = $(element).find('a').attr('href').substring(1);
            }
        });

        if (tabActive == 'tab_category') {
            if (serchOn == 'mobile') {
                let categorySelected = $('.tab-content-mobile #select-category');
                if ($(categorySelected).data('post-type') == 'dinings') {
                    selectedParams['taxonomy'] = 'dining-categories';
                } else {
                    selectedParams['taxonomy'] = 'shop-categories';
                }
                selectedParams['category'] = $(categorySelected).val();
                let privilegesSelected = $('.tab-content-mobile #select-privileges').val();
                if (privilegesSelected) {
                    selectedParams['privileges'] = privilegesSelected;
                }
            } else {
                let categorySelected = $('.list-category input[type="radio"]:checked');
                selectedParams['taxonomy'] = $(categorySelected).attr('name');
                selectedParams['category'] = $(categorySelected).data('term-id');
                let privilegesSelected = $('.list-privileges input[type="radio"]:checked');
                if ($(privilegesSelected).length > 0) {
                    selectedParams['privileges'] = $(privilegesSelected).data('term-id');
                }
            }
        }

        if (tabActive == 'tab_a-z') {
            if (serchOn == 'mobile') {
                selectedParams['title_starts_with'] = $('.tab-content-mobile #select-a-z').val();
            } else {
                selectedParams['title_starts_with'] = $('#tab_a-z input[type="radio"]:checked').val();
            }
        }

        if (tabActive == 'tab_floor') {
            if (serchOn == 'mobile') {
                selectedParams['floor'] = $('.tab-content-mobile #select-floor').val();
            } else {
                selectedParams['floor'] = $('#tab_floor input[type="radio"]:checked').val();
            }
        }

        // $.ajax({
        //     url: urlAjax,
        //     type: 'post',
        //     data: selectedParams,
        //     error: function (response) {
        //         //var content = jQuery(jQuery.parseHTML(response)).find('.products-list').html();
        //         console.log('error');
        //     },
        //     success: function (response) {
        //         jQuery('.querycontent .list-items').html(jQuery.parseHTML(response));
        //         $('body').css('opacity', '1');
        //     },
        // });

        $('.list-items .item').hide();
        //example .list-items .item has data-category = 75,85
        //show .list-items .item has data-category = selectedParams['category']
        let category = selectedParams['category'] ? selectedParams['category'].toString() : '';
        let privileges = selectedParams['privileges'] ? selectedParams['privileges'].toString() : '';
        let title_starts_with = selectedParams['title_starts_with'];
        let floor = selectedParams['floor'];
        let items = $('.list-items .item');
        let show = false;
        let has_items = false;
        for (let i = 0; i < items.length; i++) {
            let item = $(items[i]);
            let item_category = item_privileges = [];
            if (item.data('category') && item.data('category') != '') {
                item_category = item.data('category').toString().split(',');
            }
            // check if item has privileges
            if (item.data('privileges') && item.data('privileges') != '') {
                item_privileges = item.data('privileges').toString().split(',');
            }
            let item_title = item.data('title').toLowerCase();
            let item_floor = item.data('floor').toLowerCase();
            show = false;
            if (category) {
                if (category == '' || category == 'all') {
                    show = true;
                } else {
                    if (item_category.indexOf(category) != -1) show = true;
                }
                if (show == true) {
                    if (privileges) {
                        show = false;
                        if (privileges == '' || privileges == 'all_privilege') {
                            show = true;
                        } else {
                            if (item_privileges.indexOf(privileges) != -1) show = true;
                        }
                    }
                }
            } else {
                if (privileges) {
                    if (privileges == '' || privileges == 'all_privilege') {
                        show = true;
                    } else {
                        if (item_privileges.indexOf(privileges) != -1) show = true;
                    }
                }
            }
            if (title_starts_with) {
                if (title_starts_with == 'all') {
                    show = true;
                } else {
                    //compare first character of item_title with title_starts_with
                    //if title_starts_with = '#', compare with number
                    if (title_starts_with == '#') {
                        if (item_title.charAt(0) >= '0' && item_title.charAt(0) <= '9') {
                            show = true;
                        }
                    } else if (item_title.charAt(0).toLowerCase() == title_starts_with) {
                        show = true;
                    }
                }
            }

            if (floor) {
                if (floor == 'all') {
                    show = true;
                } else {
                    if (item_floor == floor) show = true;
                }
            }

            if (show == true) {
                //search by title
                if (textSearch != '') {
                    if (item_title.indexOf(textSearch.toLowerCase()) != -1) {
                        show = true;
                    } else {
                        show = false;
                    }
                }
            }

            // if (privileges && item_privileges.indexOf(privileges) == -1) {
            //     show = false;
            // }
            // if (title_starts_with && item_title != title_starts_with) {
            //     show = false;
            // }
            // if (floor && item_floor != floor) {
            //     show = false;
            // }
            if (show) {
                item.show();
                has_items = true;
            }
        }

        if (has_items == false) {
            $('.no-posts').show();
        } else {
            $('.no-posts').hide();
        }
    });

    /* Show/hide filer when scroll for mobile */
    var lastScrollTop = 0;
    $(window).scroll(function () {
        if ($('#shoppings_page .querycontent').length > 0) {
            var st = $(this).scrollTop();
            var topContent = $('#shoppings_page .querycontent').position();
            // if (st > topContent.top) {
            //     $("#shoppings_page .form-filter").addClass('fixed');
            // } else {
            //     $("#shoppings_page .form-filter").removeClass('fixed');
            // }
            if (st > topContent.top) {
                $('#shoppings_page .form-filter').addClass('fixed');
            } else {
                $('#shoppings_page .form-filter').removeClass('fixed');
            }
        }
    });
})(jQuery);