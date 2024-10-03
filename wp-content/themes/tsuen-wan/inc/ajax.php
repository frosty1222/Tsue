<?php
function get_search()
{
	$currentLanguage = empty($_POST["lang"]) ? "en" : $_POST["lang"];
	$result = array();
	if(!empty($_POST["search_input"])){
		$posts = array();
		$result["html"] = "";
		$count = 0;
		$query_args = array( 
			's' => $_POST["search_input"],
			'post_type' => array(
				'dinings',
				'shops',
				'happening',
				'play-garden'
			),
			'orderby' => 'post_type',
    		'order'   => 'ASC',
			'lang'    => '',
		);

		/* Movie */
		if (isset($_POST["search_input"])) {
			$searchInput = strtolower($_POST["search_input"]);
			if ($searchInput == 'mov' || $searchInput == 'movi' || $searchInput == 'movie' || $searchInput == '電影' || $searchInput == '戲' || $searchInput == '电影') {
				
				if($currentLanguage == 'tc'){
					$movie_field = get_field('movie_search_box', 2387);
					$moviePostType = pll__('電影');
				} else if($currentLanguage == 'sc'){
					$movie_field = get_field('movie_search_box', 2385);
					$moviePostType = pll__('电影');
				} else {
					$movie_field = get_field('movie_search_box', 2280);
					$moviePostType = pll__('Movie');
				}
				
				$movieThumb = $movie_field['thumbnail_movie'];
				$movieLink = $movie_field['link_movie'];
				$fixedTitle = $_POST["search_input"];
				
				$count++;
			}
			
		}

		$query = new WP_Query($query_args);
		//$num = $query->post_count ? $query->post_count + $count : $count; 
		if( $currentLanguage == 'sc'){
			$result["html"] .= "<div class='total-results'>共 <span class='count-result'></span> 个结果有关 “{$_POST['search_input']}”</div>";
		} else if( $currentLanguage == 'tc'){
			$result["html"] .= "<div class='total-results'>共 <span class='count-result'></span> 個結果有關 “{$_POST['search_input']}”</div>";
		} else {
			$result["html"] .= "<div class='total-results'>Total <span class='count-result'></span> results on “{$_POST['search_input']}”</div>";
		}
		//$result["html"] .= "<div class='total-results'>Total <span class='count-result'></span> results on “{$_POST['search_input']}”</div>";
		$result["html"] .= "<div class='results_list__content'>";
		if($count > 0){ // if has movie
			$result["html"] .= "
					<div class='post-result'>
						<div class='post-ct-block'>
							<div class='thumb-block'><img class='thumb-post' src='{$movieThumb}' /></div>
							<div class='title-type-block'>
								<div class='post-result_name'><a class='post-result_link' href='{$movieLink}'>{$fixedTitle}</a></div>
								<div class='post-result_type'>{$moviePostType}</div>
							</div>  
						</div>
					</div>";
		}
		/* Start the Loop */
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$current_post = $query->post;
				$post_lang = pll_get_post_language( $current_post->ID );
				if($post_lang != $currentLanguage) {
					$current_post_id = pll_get_post($current_post->ID, $currentLanguage);
					$current_post = get_post($current_post_id);
				}
				$post_type = "";
				switch($current_post->post_type){
					case "dinings" :
						$post_type = "Dining";
						break;
					case "shops" :
						$post_type = "Shopping";
						break;	
					case "happening" :
						$post_type = "Happening";
						break;	
					case "play-garden" :
						$post_type = "Entertainment";
						break;
					default:
						$post_type = $current_post->post_type;

				}
				$post = array();
				$post["title"] = $current_post->post_title;
				$post["post_type"] = $current_post->post_type == "page" ? pll__("Others") : pll__($post_type);
				$post["link"] = get_permalink($current_post);
				$item_thumb = get_the_post_thumbnail_url($current_post->ID, 'full');
				$image_thumbnail = get_field('thumbnail', $current_post->ID);
				$thumACF = wp_get_attachment_image_src( $image_thumbnail, 'full' );
				$item_thumb_url = "";
				$item_thumb_url = $item_thumb ? $item_thumb : $thumACF[0];
				$post["thumb"] = $item_thumb_url;
				if(in_array($post, $posts)){
					continue;
				}

				if($count < 5){
					$result["html"] .= "
						<div class='post-result'>
							<div class='post-ct-block'>
								<div class='thumb-block'><img class='thumb-post' src='{$post["thumb"]}' /></div>
								<div class='title-type-block'>
									<div class='post-result_name'><a class='post-result_link' href='{$post["link"]}'>{$post["title"]}</a></div>
									<div class='post-result_type'>{$post["post_type"]}</div>
								</div>	
							</div>
						</div>";
				}
				array_push($posts, $post);
				$count++;
			}
		}
		$result["html"] .= "</div>";
		wp_reset_postdata();
		
		if($count == 0){
			$result["html"] = "<div class='post-result_notfound'>".pll__("Results Not Found")."</div>";
		}
		/* Restore original Post Data */

		$result["posts"] = $posts;
		$result["status"] = 1;

	}else{
		$result["status"] = 2;
	}
	$result["count"] = $count;
	echo json_encode($result);
    exit();
	wp_die();

}
function get_shops(){
	if($_POST["type"] == "dinings"){
		$post_type = "dinings";
		$post_tax = "dining-categories";
	}elseif($_POST["type"] == "shops"){
		$post_type = "shops";
		$post_tax = "shop-categories";
	}else{
		$return["status"] = 2;
		echo json_encode($return, true);
		wp_die();
	}
	$args = array(
		'posts_per_page' => -1,
		'post_type' => $post_type,
	);
	$shoppings = get_posts($args);
	$return = array();
	if (!empty($shoppings)) {
		$shops = array();
		foreach ($shoppings as $shopping) {
			$temp = array();
			$shop_fields = get_fields($shopping);
			$temp["primary_slug"] = "";
			$temp["url"] = get_permalink($shopping);
			$temp["thumbnail"] = $shop_fields["thumbnail"];
			$temp["shop_name"] = $shop_fields["shop_name"];
			if(empty($temp["shop_name"])){
				$temp["shop_name"] = $shopping->post_title;
			}
			$temp["opening_hours"] = $shop_fields["opening_hours"];
			$temp["list_description"] = $shop_fields["list_description"];

			$temp["shop_floor_level"] = $shop_fields["shop_phase_level"];
			$shop_floor_level = explode("_", $temp["shop_floor_level"]);
			$temp["shop_floor"] = $shop_floor_level["1"];
			$temp["shop_number"] = get_shop_floor($shop_fields["shop_numbers"]);
			$temp["shop_sort"] = $temp["shop_floor"]."_".$temp["shop_number"];
			$temp["shop_phase"] = "phase_".$shop_floor_level["0"];
			$temp["shop_others"] = array();
			if(!empty($shop_fields["others"])){
				foreach($shop_fields["others"] as $other){
					if($other["value"] == "other_2" || $other["value"] == "other_3") continue;
					$temp["shop_others"][] = $other["value"];
				}
			}
			if(empty($temp["list_description"])){
				$temp["list_description"] = pll__("Shop")." {$temp["shop_number"]} {$temp["shop_floor"]} - {$temp["shop_phase"]}";
			}
			if($temp["shop_floor"] == "MTR"){
				$temp["shop_sort"] = "0".$temp["shop_sort"];
			}
			$temp["phone"] = $shop_fields["phone"];
			$temp["icon"] = $shop_fields["icon"];
			$temp["sort_name"] = $shopping->post_title;
			$temp["sort_name"] = strtolower($temp["sort_name"]);
			$temp["sort_order"] = $temp["sort_name"];
			$temp["sort_name"] = substr($temp["sort_name"], 0, 1);

			if(preg_match("/^[a-zA-Z0-9]+$/", $temp["sort_name"]) != 1) {
				$temp["sort_name"] = "zz";
				$temp["sort_order"] = "zz";
			}
			$terms = wp_get_post_terms($shopping->ID, $post_tax);
			$cats = array();
			if (!empty($terms)) {
				$primary_term = yoast_get_primary_term_id($post_tax, $shopping->ID);
				foreach ($terms as $key => $term) {
					if (empty($primary_term) && $key == 0) $primary_term = $term->term_id;
					if ($term->term_id == $primary_term) {
						$temp["primary_slug"] = $term->slug;
					}
					array_push($cats, $term->slug);
				}
			}
			$temp["cats"] = $cats;
			array_push($shops, $temp);
		}
		$return["shops"] = $shops;
		$return["status"] = 1;
	}else{
		$return["status"] = 2;
	}
	echo json_encode($return, true);
	wp_die();
}

add_action( 'wp_ajax_get_search', 'get_search' );
add_action( 'wp_ajax_nopriv_get_search', 'get_search' );
add_action( 'wp_ajax_get_shops', 'get_shops' );
