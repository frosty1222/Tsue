<div class="shop-main_top">
    <div class="container">
        <div id="<?php echo $filter_content_id ?>" class="shop-main_header">
            <div class="shop-main_menu">
                <div class="shop-main_menu__btn scrollTo" ng-class="{active: sort=='category'}"
                     ng-click="sort='category'; reset_all_sort()" data-scrollTo="#filter_content">
					<?php pll_e( "Category" ); ?>
                </div>
                <div class="shop-main_menu__btn scrollTo" ng-class="{active: sort=='phase_level'}"
                     ng-click="sort='phase_level'; reset_all_sort()" data-scrollTo="#filter_content">
					<?php pll_e( "Phase & Level" ); ?>
                </div>
                <div class="shop-main_menu__btn scrollTo" ng-class="{active: sort=='alphabetical'}"
                     ng-click="sort='alphabetical'; reset_all_sort()" data-scrollTo="#filter_content">A - Z
                </div>
                <div class="shop-main_menu__btn scrollTo" ng-class="{active: sort=='floorplan'}"
                     ng-click="sort='floorplan'; reset_all_sort()" data-scrollTo="#filter_content">
					<?php pll_e( "Floor Plan" ); ?>
                </div>
            </div>
            <div class="shop-main_sort">
                <div class="shop-main_sort__btn shop-main_serach">
                    <img class="shop_name_search" src="<?php echo template_img_url( "search_icon.svg" ) ?>" alt="search">
                    <input class="shop_name_input list_input " ng-class="{active: sort!='floorplan'}" type="text" ng-model="shopFilter.shop_name" >
                    <input class="shop_name_input map_input " ng-class="{active: sort=='floorplan'}" type="text" >
                    <div class="shop_name_close"></div>
                </div>
                <div class="shop-main_sort__btn layout scrollTo" ng-click="display='grid'; is_floorplan = false"
                     ng-class="{'active' : display=='grid'}" data-target="grid_display"
                     data-scrollTo="#filter_content">
                    <img src="<?php echo template_img_url( "block.svg" ) ?>" alt="block">
                </div>
                <div class="shop-main_sort__btn layout scrollTo" ng-click="display='list'; is_floorplan = false"
                     ng-class="{'active' : display=='list'}" data-target="list_display"
                     data-scrollTo="#filter_content">
                    <img src="<?php echo template_img_url( "list.svg" ) ?>" alt="list">
                </div>
            </div>
        </div>
        <div class="shop-main_menuItems" ng-class="{'hide' : sort=='floorplan'}">
            <div class="shop-main_menuGroup" ng-class="{'active' : sort == 'category'}">
                <div class="shop-main_menuItem category">
                    <div class="shop-main_menuItem__left desktop">
						<?php pll_e( "Category" ); ?>
                    </div>
                    <div class="shop-main_menuItem__right desktop">
                        <div class="shop-main_menuItem__item active scrollTo"
                             ng-click="get_shops_by_cat('all'); "
                             ng-class="{'active' : category == 'all'}"
                             data-scrollTo="#filter_content">
                            <span><?php pll_e( "All" ); ?></span>
                        </div>
                       
						<?php foreach ( $shop_terms as $shop_term ) {

							$shop_term_fields = get_fields($shop_term->term_id);
							?>
                            <div class="shop-main_menuItem__item scrollTo"
                                 data-scrollTo="#filter_content"
                                 ng-class="{'active' : category == '<?php echo $shop_term->slug ?>'}"
                                 ng-click="get_shops_by_cat('<?php echo $shop_term->slug ?>');"
                            >
                                <span><?php echo $shop_term->name ?></span>
                            </div>
						<?php } ?>
                    </div>
                    <div class="mobile shop-main_menuItem__select">
                        <select ng-init="selected_category = 'all'"  class="selectric" ng-model="selected_category" id="selectric_cat" ng-change="get_shops_by_cat(selected_category);">
                            <option value="all"><?php pll_e( "All Category" ); ?></option>
		                    <?php foreach ( $shop_terms as $shop_term ) {
			                    $shop_term_fields = get_fields( $shop_term->term_id );
			                    ?>
                                <option value="<?php echo $shop_term->slug ?>"><?php echo $shop_term->name ?></option>
		                    <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="shop-main_menuItem others">
                    <div class="shop-main_menuItem__left desktop">
						<?php pll_e( "Others" ); ?>
                    </div>
                    <div class="shop-main_menuItem__right desktop">
                        <div class="shop-main_menuItem__item active scrollTo"
                             ng-click="get_shops_by_others('all'); "
                             ng-class="{'active' : shop_other == 'all'}"
                             data-scrollTo="#filter_content">
                            <span><?php pll_e( "None" ); ?></span>
                        </div>
						<?php foreach ( $shop_others_gifts as $key => $shop_others_gift ) {
							if($key == "other_2" || $key == "other_3") continue;
							?>
                            <div class="shop-main_menuItem__item scrollTo"
                                 data-scrollTo="#filter_content"
                                 ng-class="{'active' : shop_other == '<?php echo $key ?>'}"
                                 ng-click="get_shops_by_others('<?php echo $key ?>');"
                            >
                                    <span class="shop_other_icon <?php echo $key ?>"
                                          ng-class="{'active' : shop_other == '<?php echo $key ?>'}"></span>
                                <span><?php echo $shop_others_gift ?></span>
                            </div>
						<?php } ?>
                    </div>
                    <div class="mobile shop-main_menuItem__select">
                        <select ng-init="selected_other = 'all'" class="selectric"  ng-model="selected_other" id="selectric_other" value="all" ng-change="get_shops_by_others(selected_other);">
                            <option value="all"><?php pll_e( "All Others" ); ?></option>
		                    <?php foreach ( $shop_others_gifts as $key => $shop_others_gift ) {
                                if($key == "other_2" || $key == "other_3") continue;
                                ?>
                                <option value="<?php echo $key ?>"><?php echo $shop_others_gift ?></option>
		                    <?php } ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="shop-main_menuGroup phase_level" ng-class="{'active' : sort == 'phase_level'}">
                <div class="shop-main_menuItem phase">
                    <div class="shop-main_menuItem__left desktop">
						<?php pll_e( "Phase" ); ?>
                    </div>
                    <div class="shop-main_menuItem__right desktop">
						<?php foreach ( $shop_phases as $key => $shop_phase ) {
							?>
                            <div class="shop-main_menuItem__item scrollTo"
                                 data-scrollTo="#filter_content"
                                 ng-class="{'active' : shop_phase == '<?php echo $key ?>'}"
                                 ng-click="get_shops_by_phase('<?php echo $key ?>');"
                            >
                                <span><?php echo $shop_phase ?></span>
                            </div>
						<?php } ?>
                    </div>
                </div>
                <div class="shop-main_menuItem level">
                    <div class="shop-main_menuItem__left desktop">
						<?php pll_e( "Level" ); ?>
                    </div>
                    <div class="shop-main_menuItem__right desktop" ng-class="{'active_block' : shop_phase == 'phase_1'}">
                        <div class="shop-main_menuItem__item scrollTo"
                             data-scrollTo="#filter_content"
                             ng-class="{'active' : shop_floor == 'all'}; "
                             ng-click="get_shops_by_floor('phase_1', 'all') "
                        >
                            <span><?php pll_e( "All" ); ?></span>
                        </div>
						<?php foreach ( $shop_floors as $shop_floor ) {
							?>
                            <div class="shop-main_menuItem__item scrollTo"
                                 data-scrollTo="#filter_content"
                                 ng-class="{'active' : shop_floor == '<?php echo $shop_floor ?>'}"
                                 ng-click="get_shops_by_floor('phase_1', '<?php echo $shop_floor ?>') "
                            >
                                <span><?php echo $shop_floor ?></span>
                            </div>
						<?php } ?>
                    </div>
                    <div class="shop-main_menuItem__right desktop" ng-class="{'active_block' : shop_phase == 'phase_3'}">
                        <div class="shop-main_menuItem__item scrollTo"
                             data-scrollTo="#filter_content"
                             ng-class="{'active' : shop_floor == 'all'}; "
                             ng-click="get_shops_by_floor('phase_3', 'all') "
                        >
                            <span><?php pll_e( "All" ); ?></span>
                        </div>
		                <?php foreach ( $shop_floors_3 as $shop_floor ) {
			                ?>
                            <div class="shop-main_menuItem__item scrollTo"
                                 data-scrollTo="#filter_content"
                                 ng-class="{'active' : shop_floor == '<?php echo $shop_floor ?>'}; "
                                 ng-click="get_shops_by_floor('phase_3', '<?php echo $shop_floor ?>') "
                            >
                                <span><?php echo $shop_floor ?></span>
                            </div>
		                <?php } ?>
                    </div>
                    <div class="mobile shop-main_menuItem__select" ng-class="{'active_block' : shop_phase == 'phase_1'}">
                        <select class="selectric" ng-init="selected_phase_floor = 'all,all'"  ng-model="selected_phase_floor" id="selectric_phase_floor" value="all" ng-change="get_shops_by_phase_floor(selected_phase_floor);">
                            <option value="all,all" selected><?php pll_e( "All" ); ?></option>
			                <?php
			                $lang        = pll_current_language();
			                if($lang == "zh-hant"){
                                $phase_1 = "第一期";
				                $phase_3 = "第三期";
                            }elseif ($lang == "zh-hans"){
				                $phase_1 = "第一期";
				                $phase_3 = "第三期";
                            }else{
				                $phase_1 = "Phase I";
				                $phase_3 = "Phase III";
			                }
			                foreach ( $shop_floors as $key => $shop_floor ) {
                                ?>
                                <option value="phase_1,<?php echo $shop_floor ?>"><?php echo $phase_1." , $shop_floor" ?></option>
			                <?php } ?>
	                        <?php foreach ( $shop_floors_3 as $key => $shop_floor ) { ?>
                                <option value="phase_3,<?php echo $shop_floor ?>"><?php echo $phase_3." , $shop_floor" ?></option>
	                        <?php } ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="shop-main_menuGroup alphabetical" ng-class="{'active' : sort == 'alphabetical'}">
                <div class="shop-main_menuItem alphabetical">
                    <div class="shop-main_menuItem__left desktop">
						<?php pll_e( "A - Z" ); ?>
                    </div>
                    <div class="shop-main_menuItem__right desktop">
                        <div class="shop-main_menuItem__item active scrollTo"
                             ng-click="get_shops_by_char('all'); "
                             ng-class="{'active' : char == 'all'}"
                             data-scrollTo="#filter_content">
                            <span><?php pll_e( "All" ); ?></span>
                        </div>
						<?php
						foreach ( range( 'a', 'z' ) as $char ) {
							?>
                            <div class="shop-main_menuItem__item scrollTo"
                                 ng-class="{'active' : char == '<?php echo $char ?>'}"
                                 ng-click="get_shops_by_char('<?php echo $char ?>')"
                                 data-scrollTo="#filter_content">
								<?php echo strtoupper( $char ) ?>
                            </div>
							<?php
						}
						?>
                        <div class="shop-main_menuItem__item scrollTo" ng-class="{'active' : char == 'zz'}"
                             ng-click="get_shops_by_char('zz')" data-scrollTo="#filter_content">
                            #
                        </div>
                    </div>
                    <div class="mobile shop-main_menuItem__select">
                        <select ng-init="selected_char = 'all'" class="selectric"  ng-model="selected_char" id="selectric_char" value="all" ng-change="get_shops_by_char(selected_char);">
                            <option value="all"><?php pll_e( "All" ); ?></option>
	                        <?php foreach ( range( 'a', 'z' ) as $char ) { ?>
                                <option value="<?php echo $char ?>"><?php echo strtoupper( $char ) ?></option>
			                <?php } ?>
                            <option value="zz">#</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>