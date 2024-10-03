<?php
$page_fields            = get_fields();
$new_featured_shop      = $page_fields["new_featured_shop"];
$shop_floors            = array( "MTR/F", "L1", "L2", "L3", "L4", "L5", "L6" );
$shop_phases            = array();
$shop_phases["phase_1"] = pll__( "Phase" ) . " I";
$shop_phases["phase_2"] = pll__( "Phase" ) . " II";
$shop_phases["phase_3"] = pll__( "Phase" ) . " III";

$shop_others_gifts            = array();
$shop_others_gifts["other_1"] = pll__( "Point Dollar" );
//$shop_others_gifts["other_2"] = pll__( "SHKP E-gift Cert" );
//$shop_others_gifts["other_3"] = pll__( "SHKP Gift Cert" );
$shop_others_gifts["other_4"] = pll__( "SKHP Malls / New Town Plaza Gift Card" );

$lang = pll_current_language();
?>

<?php include( locate_template( 'template-parts/inc/block-hero.php', false, false ) ); ?>
<section class="shop-top line-trigger">
    <div class="animated-line animated-line-left">
		<?php include( locate_template( 'template-parts/inc/svg-line_left.php', false, false ) ); ?>
    </div>
    <div class="animated-line animated-line-right">
		<?php include( locate_template( 'template-parts/inc/svg-line_right.php', false, false ) ); ?>
    </div>
    <h2 class="shop-top_header">
		<?php echo $new_featured_shop["title"] ?>
    </h2>
    <div class="shop-top_section">
        <div class="container">
            <div class="shop-top_section__container">
				<?php foreach ( $new_featured_shop["shops"] as $shop ) {
					$shop                 = $shop["shop"];
					$shop_fields          = get_fields( $shop );
					$link                 = get_permalink( $shop );
					$type                 = "";
					$title                = "";
					$description          = "";
					$thumbnail            = $shop_fields["thumbnail"];
					$slide_up_title       = $shop->post_title;
					$slide_up_description = $shop_fields["list_description"];
					$shop_others          = $shop_fields["others"]
					?>
					<?php include( locate_template( 'template-parts/inc/block-slide.php', false, false ) ); ?>
				<?php } ?>
            </div>
        </div>
    </div>
</section>
<section class="shop-main">
    <div id="shop-main_top__fix">
        <div class="shop-main_top__fix_content">
            <div class="container">
                <div class="filter_btn"><span><?php pll_e( "Filter" ) ?></span><img
                            src="<?php echo template_img_url( "shop_filter.svg" ) ?>" alt="filter"></div>
            </div>
			<?php
			$filter_content_id = "filter_content_top";
			include( locate_template( 'template-parts/inc/block-shop_menu.php', false, false ) ); ?>
        </div>
    </div>
	<?php
	$filter_content_id = "filter_content";
	include( locate_template( 'template-parts/inc/block-shop_menu.php', false, false ) ); ?>
    <div class="shop-result active" ng-class="{'active' : sort!='floorplan'}">
        <div class="container">
            <div ng-cloak id="result_content" class="content_section list_section"
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
                                                <div class="splide__slide__innerimg lazyload lazy"
                                                     data-bg="{{ shop.thumbnail}}"></div>
                                            </div>
                                            <span class="splide__slide__link"></span>
                                        </div>
                                    </a>
                                    <a href="{{shop.url}}"
                                       class="splide__slide__outertext">
                                        <div class="splide__slide__outertitle">{{ shop.shop_name }}</div>
                                        <div class="splide__slide__outerdesc">
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
                                <img class="shop_icon lazyload" data-src="{{shop.icon}}" alt="{{shop.shop_name}}">
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
                </div>

            </div>
        </div>
    </div>
    <div class="shop-map" ng-class="{'active' : sort=='floorplan'}">
		<?php
		wp_enqueue_style( 'map_css', get_template_directory_uri() . '/map/map.css' );
		include( locate_template( 'map/block-map.php', false, false ) ); ?>
    </div>
</section>




