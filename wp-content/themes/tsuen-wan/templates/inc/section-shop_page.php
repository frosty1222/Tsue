<?php
$shop_floors            = get_levels(1);
$shop_floors_3            = get_levels(3);
$shop_phases            = get_phase();
$shop_others_gifts            = get_others_gifts();
$post_category = "dining-categories";
$shop_terms = get_terms( array(
	'taxonomy' => $post_category,
	'hide_empty' => false,
));
$lang = pll_current_language();
?>
<?php if(the_title()):?>
<header class="banner-section" style="background-image: url('<?php echo the_post_thumbnail_url( 'full' );?>')">
		<div class="container">
			<div class="banner-section_title">
				<h1 class="title-header letter-fade"><?php trim(the_title()) ?></h1>
			</div>
		</div>
</header>
<?php endif?>
<section class="shop-main">
    <div id="shop-main_top__fix">
        <div class="shop-main_top__fix_content">
            <div class="container">
                <div class="filter_btn"><span><?php pll_e( "Filter" ) ?></span><img
                            src="<?php echo template_img_url( "shop_filter.svg" ) ?>" alt="filter"></div>
            </div>
			<?php
			$filter_content_id = "filter_content_top";
			include( locate_template( 'templates/inc/block-shop_menu.php', false, false ) ); ?>
        </div>
    </div>
	<?php
	$filter_content_id = "filter_content";
	include( locate_template( 'templates/inc/block-shop_menu.php', false, false ) ); ?>
    <div class="shop-result active" ng-class="{'active' : sort!='floorplan'}">
        <div class="container">
            <div id="result_content" class="content_section list_section loading"
                 ng-class="{'grid_display' : display == 'grid', 'list_display' : display == 'list', 'loading' : loading == true, 'active' : is_floorplan == false}">
                <div class="list content_flex">
                    <div ng-repeat="shop in filteredShops  = (shops | filter:shopFilter) |  orderBy:'sort_order'"
                         class="content_flex_item">
                        <div class="list_item">
                            <a class="link list_show" href="{{shop.url}}"></a>
                            <div class="hover list_show"></div>
                            <div class="splide__slide">
                                <div class="splide__slide__hover">
                                    <a href="{{shop.url}}"
                                       class="splide__slide__bg">
                                        <div class="splide__slide__img {{ shop.type }}">
                                            <div class="splide__slide__innerimgcontainer">
                                                <div class="splide__slide__innerimg lazyItem lazy lazyload"
                                                     data-bg="{{ shop.thumbnail}}"></div>
                                            </div>
                                            <span class="splide__slide__link"></span>
                                        </div>
                                    </a>
                                    <a href="{{shop.url}}"
                                       class="splide__slide__outertext">
                                        <div class="splide__slide__outertitle">{{ shop.shop_name }}</div>
                                        <div class="splide__slide__outerdesc" ng-class="{'has_icons' : shop.shop_others.length > 0}">
                                            {{ shop.list_description }}
                                            <div class="splide__slide__others shop_other_icons">
                                                <span ng-repeat="other in shop.shop_others"
                                                      class="splide__slide__other active shop_other_icon {{ other }}"></span>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="flex_item icon_container list_show">
                                <div class="shop_img lazyload" data-bg='{{shop.icon}}' ></div>
                            </div>
                            <div class="flex_item shop_name list_show">
                                <div class="shop_name_inner">
                                    {{shop.shop_name}}
                                </div>
                            </div>
                            <div class="flex_item shop_location list_show">
                                <img class="location_icon shop_icon"
                                     src="<?php echo template_img_url( "list_location.svg" ) ?>" alt="location">
                                <span>
                                {{shop.list_description}}
                            </span>
                            </div>
                            <div class="flex_item shop_open list_show">
                                <img class="hour_icon shop_icon" src="<?php echo template_img_url( "list_hour.svg" ) ?>"
                                     alt="hour">
                                <div class="shop_open_hour">
                                    <div ng-repeat="opening_hour in shop.opening_hours">
                                        <span class="date">{{opening_hour.date}}</span><span
                                                class="time">{{opening_hour.time}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex_item shop_phone list_show">
                                <img class="phone_icon shop_icon"
                                     src="<?php echo template_img_url( "list_phone.svg" ) ?>" alt="phone"
                                     ng-if="shop.phone">
                                <span>
                                    {{shop.phone}}
                                </span>
                            </div>
                            <div class="flex_item shop_other list_show">
                                <div class="splide__slide__others shop_other_icons">
                                    <span ng-repeat="other in shop.shop_others"
                                          class="splide__slide__other active shop_other_icon {{ other }}"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="results_loading">
                    <img src="<?php echo get_template_directory_uri() . "/images/spinner.svg" ?>" alt="loading spinner">
                </div>
                <div class="result_not_found " ng-class="{'active' : filteredShops.length == 0}">
                    Result not found
                    <!-- <button ng-click="test()">Hello</button> -->
                </div>

            </div>
        </div>
    </div>
</section>