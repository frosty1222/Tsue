<?php
/*
Template Name: Search Page
*/

get_header();
$site_url = pll_home_url();
$lang          = pll_current_language();
$allow_type = array(
    'dinings',
    'shops',
    'happening',
    'play-garden'
);
$type = "all";
$filter_type = "";
if(!empty($_GET["type"]) && in_array($_GET["type"], $allow_type))
{
	$type = $_GET["type"];
}
$post_count = 0;
$query_args = array( 
    's' => $_GET["s"],
    'post_type' =>$allow_type,
    'orderby' => 'post_type',
    'order'   => 'ASC',
    'lang'    => '',
);
$search_result = array();
$query = new WP_Query( $query_args );
if(!empty($_GET["s"])){
	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			$current_post = $query->post;
			$post_lang = pll_get_post_language( $current_post->ID );
			if($post_lang != $lang) {
				$current_post_id = pll_get_post($current_post->ID, $lang);
				$current_post = get_post($current_post_id);
			}

			$post_type    = $current_post->post_type;
			if ( ! key_exists( $post_type, $search_result ) ) {
				$search_result[ $post_type ]          = array();
				$post_type_object                     = get_post_type_object( $post_type );
                if($post_type_object->name == "page")
                {
                    $search_result[ $post_type ]["name"] = pll__("Others");
                } 
                elseif($post_type_object->name == "dinings")
                {
                    $search_result[ $post_type ]["name"] = pll__("Dining");
                } 
                elseif($post_type_object->name == "shops")
                {
                    $search_result[ $post_type ]["name"] = pll__("Shopping");
                } 
                elseif($post_type_object->name == "play-garden")
                {
                    $search_result[ $post_type ]["name"] = pll__("Entertainment");
                } 
                else
                {
                    $search_result[ $post_type ]["name"]  = pll__( $post_type_object->label );
                }
				$search_result[ $post_type ]["posts"] = array();
			}
			if(in_array($current_post, $search_result[ $post_type ]["posts"] )){
				continue;
			}
			$post_count++;
			array_push( $search_result[ $post_type ]["posts"], $current_post );
		}
	}
}
wp_reset_postdata();
$max_num = 6;
?>
    <main id="search_page" class="page_section search-page">
        <div class="top-search-form_section">
            <div class="search_input-wrap">
				<?php get_search_form(); ?>
            </div>
            <div class="search-page_count">
				<?php
                if($lang == "sc"){
	                echo sprintf( pll__( "%s 个关于 \"%s\" 的搜寻结果" ), $post_count, get_search_query() );
                }elseif ($lang == "tc"){
	                echo sprintf( pll__( "%s 個關於 \"%s\" 的搜尋結果" ), $post_count, get_search_query() );
                }else{
	                echo sprintf( pll__( "Total %s results on \"%s\"." ), $post_count, get_search_query() );
                }
                ?>
            </div>
        </div>
        <div class="container1">
            <?php 
                $order = array('dinings', 'shops', 'happening', 'play-garden');
                $sorted_array = array();
                foreach ($order as $key) {
                    if (isset($search_result[$key])) {
                        $sorted_array[$key] = $search_result[$key];
                    }
                }
                if(!empty($sorted_array)): 
            ?>
                <div class="search-page_menu general-menu">
                    <div class="general-menu_container">
                            <div class="general-menu_btn <?php if ( $type == "all" ) echo "active" ?>">
                                <a href="<?php echo esc_url( add_query_arg( 's', get_search_query(), $site_url ))  ?>"><?php pll_e( "All" ); ?></a>
                            </div>
                            <?php 
                            foreach ( $sorted_array as $key => $search_result_type ) { ?>
                                <div class="general-menu_btn <?php if ( $type == $key ) echo "active" ?>">
                                    <a href="<?php echo esc_url( add_query_arg( array('s'=> get_search_query(), 'type'=> $key), $site_url ))  ?>"><?php echo $search_result_type["name"] ?></a>
                                </div>
                            <?php } ?>
                    </div>
                </div>
            <?php endif; ?>
            <div class="search-page_results shop-result active <?php if($type != "all") { echo "selected_type"; } else { echo "all-type"; } ?> ">
                <div class="search-page_all">
					<?php
                    $index = 0;
                    foreach ( $sorted_array as $type_key => $search_result_type ) {
                        $bg_content_search = $index % 2 == 0 ? 'grey-bg' : 'white-bg';
						$is_list = false;
						$display_type = "grid_display";
                        if ($type == "all"){
	                        $is_list      = count( $search_result_type["posts"] ) > $max_num;
	                        $display_type = $is_list ? "list_display" : "grid_display";
                        } elseif ($type != "all" && $type_key != $type){
                             continue;
                        }
						?>
                        <div class="search-page_all__section shop-main <?php echo $bg_content_search; ?> <?php echo $type_key ?>">
                            <?php if($type == "all"){ ?>
                            <div class="search-page_all__section_name">
                                <div class="name_text">
									<?php echo $search_result_type["name"] ?>
                                </div>
                                <div class="home-btn-more link_btn">
                                    <div class="button">
                                        <a class="button-read-more" href="<?php echo esc_url( add_query_arg( array('s'=> get_search_query(), 'type'=> $type_key), $site_url )) ?>"><span><?php pll_e( "Show All" ); ?></span></a>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                            <div class="search-page_all__section_content <?php echo $display_type ?>">
                                <div class="container">
                                    <div class="search-slider">
                                        <div class="swiper-container">
                                            <?php $count = count($search_result_type["posts"]); ?>
                                            <div class="swiper-wrapper" data-totalItem="<?php echo $count; ?>">
                                                <?php 
                                                foreach ( $search_result_type["posts"] as $post_key => $search_result_post ) {
                                                    if($type == "all"){
                                                        if($post_key + 1 > $max_num){
                                                            break;
                                                        }
                                                    }
                                                    $item_thumb = get_the_post_thumbnail_url($search_result_post->ID, 'full');
                                                    $image_thumbnail = get_field('thumbnail', $search_result_post->ID);
                                                    $thumACF = wp_get_attachment_image_src( $image_thumbnail, 'full' );
                                                    $list_description = get_field('list_description', $search_result_post->ID);
                                                    $item_thumb_url = "";
                                                    if($item_thumb != ''){
                                                        $item_thumb_url = $item_thumb;
                                                    } elseif (!empty($thumACF)){
                                                        $item_thumb_url = $thumACF[0];
                                                    } 
                                                    $item_title = $search_result_post->post_title;
                                                    $item_link = get_permalink($search_result_post->ID);
                                                    ?>
                                                        <div class=" swiper-slide item item-search" data-link="<?php echo esc_url($item_link); ?>">
                                                            <div class="list_item animate_line">
                                                                <span class="line line1"></span>
                                                                <span class="line line2"></span>
                                                                <span class="line line3"></span>
                                                                <span class="line line4"></span>
                                                                <div class="item-search-thumb inner-feature">
                                                                    <div class="ratio11">
                                                                        <a class="image-url" href="<?php echo esc_url($item_link); ?>">
                                                                            <img src="<?php echo $item_thumb_url; ?>" alt="" />
                                                                        </a>
                                                                    </div>
                                                                    <div class="content-hover">
                                                                        <?php if( $list_description ) :?>
                                                                            <div class="list-bottom">
                                                                                <div class="rebion"> 
                                                                                    <h5 class="for-mb">
                                                                                        <a href="<?php echo esc_url($item_link); ?>">
                                                                                            <?php echo $item_title; ?>
                                                                                        </a>    
                                                                                    </h5>
                                                                                    <?php echo $list_description; ?> 
                                                                                </div>
                                                                                <?php 
                                                                                    $privileges = get_the_terms( $search_result_post->ID , 'privileges');
                                                                                    //print_r($privileges);
                                                                                    if(!empty($privileges)) : ?>
                                                                                        <ul class="listmenu list-icon">
                                                                                            <?php foreach ( $privileges as $privilege ) : ?>
                                                                                                <?php 
                                                                                                    $image_id = get_field('icon_tax',$privilege);
                                                                                                    if($image_id){
                                                                                                        echo '<li>';
                                                                                                            $image_url = wp_get_attachment_image_url( $image_id, 'full' );
                                                                                                            $ext = pathinfo($image_url, PATHINFO_EXTENSION);
                                                                                                            if($ext=='svg'){							
                                                                                                                $file = file_get_contents($image_url, true);
                                                                                                            }else{
                                                                                                                $file = wp_get_attachment_image($image_id,'full');
                                                                                                            }
                                                                                                            echo  $file ;
                                                                                                        echo '</li>';
                                                                                                    }
                                                                                                ?>
                                                                                            <?php endforeach; ?>
                                                                                        </ul>
                                                                                <?php endif;?>
                                                                            </div>    
                                                                        <?php endif;?>   
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <a class="title-outside" href="<?php echo esc_url($item_link); ?>"><?php echo $item_title; ?></a>
                                                        </div>
                                                    
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <div class="home-list-nav shoppings-navigation">
                                            <div class="container arrow-button small">
                                                <div class="home-list-progressbar home-slider-progressbar"></div>
                                            </div> 
                                        </div> 
                                    </div>
                                </div>
                            </div>
                        </div>
					<?php 
                    $index++;
                    } 
                    ?>
                </div>
            </div>
        </div>
    </main>
<?php get_footer() ?>