<div class="shop-map active line-trigger">
    <div class="animated-line animated-line-left">
		<?php include( locate_template( 'template-parts/inc/svg-line_left.php', false, false ) ); ?>
    </div>
    <div class="animated-line animated-line-right">
		<?php include( locate_template( 'template-parts/inc/svg-line_right.php', false, false ) ); ?>
    </div>
    <div class="shop-map__container">
		<?php
			$shop_number_string = "";
			$lang = pll_current_language();
			$query_arr = array(
				"zoomLevel" => "2",
				"noControls" => "1",
				"lang" => $lang
			);
			if(!empty($page_fields["shop_numbers"])){
				$shop_numbers = $page_fields["shop_numbers"];
				if(!empty($shop_numbers)){
					foreach ($shop_numbers as $key => $shop_number){
						if(empty($shop_number["shop_number"])) continue;
						if($key != 0){
							$shop_number_string .= "&";
						}
						$shop_number_string .= $shop_number["shop_number"];
					}
				}
				$query_arr["shop"] = $shop_number_string;
			}
			$query = http_build_query(
				$query_arr
			);
			$floor_plan_url = $query;
			$scrollZoom = 0;
			$menu = 0;
			$dragging = true;
			$zoomLevel = 2;
			$noControl = true;
			$selected_shop = $shop_number_string;
			$shop_phase = $page_fields['shop_phase_level'];
		?>
		<?php
		wp_enqueue_style( 'map_css', get_template_directory_uri() . '/map/map.css' );
		wp_enqueue_script("map_js", get_template_directory_uri().'/map/map.js', array('jquery'), '', true);
		include( locate_template( 'map/floorplan.php', false, false ) ); ?>
    </div>
</div>