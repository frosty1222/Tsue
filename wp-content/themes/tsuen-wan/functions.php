<?php
/**
 * @package WordPress
 * @subpackage Classic_Theme
 */
if (!defined('_S_VERSION')) {
    // Replace the version number of the theme on each release.
    define('_S_VERSION', '1.0.0');
}
require get_template_directory() . '/inc/enqueue-styles-scripts.php';
require get_template_directory() . '/inc/post_type.php';
require get_template_directory() . '/inc/custom.php';
require get_template_directory() . '/inc/ajax.php';
require get_template_directory() . '/inc/helper_func.php';
add_theme_support( 'automatic-feed-links' );

if ( function_exists('register_sidebar') )
	register_sidebar(array(
		'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget' => '</li>',
		'before_title' => '',
		'after_title' => '',
	));

add_filter('use_block_editor_for_post', '__return_false');
add_filter('wp_lazy_loading_enabled', '__return_false');
function tsuen_wan_setup()
{
    /*
        * Make theme available for translation.
        * Translations can be filed in the /languages/ directory.
        * If you're building a theme based on Sai sha Mall, use a find and replace
        * to change 'tsuen-wan' to the name of your theme in all the template files.
        */
    load_theme_textdomain('tsuen', get_template_directory().'/languages');

    // Add default posts and comments RSS feed links to head.
    add_theme_support('automatic-feed-links');

    /*
        * Let WordPress manage the document title.
        * By adding theme support, we declare that this theme does not use a
        * hard-coded <title> tag in the document head, and expect WordPress to
        * provide it for us.
        */
    add_theme_support('title-tag');

    /*
        * Enable support for Post Thumbnails on posts and pages.
        *
        * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
        */
    add_theme_support('post-thumbnails');

    // This theme uses wp_nav_menu() in one location.
    register_nav_menus(
        [
            'menu-1' => esc_html__('Primary', 'tsuen'),
            'menu-2' => esc_html__('Header Secondary', 'tsuen'),
            'menu-3' => esc_html__('Footer #1', 'tsuen'),
            'menu-4' => esc_html__('Footer #2', 'tsuen'),
            'menu-footer-3' => esc_html__('Footer #3', 'tsuen'),
            'menu-5' => esc_html__('Footer Privacy', 'tsuen'),
            'mobile-menu'   =>  esc_html__('Mobile Menu', 'tsuen'),
            'mobile-bottom-menu'   =>  esc_html__('Mobile Bottom Menu', 'tsuen')
        ]
    );
function c_register_theme_menus(){
    register_nav_menus(
        array(
            'header-menu'   =>  'Header Menu',
            'footer-menu'   =>  'Footer Menu',
            'mobile-menu'   =>  'Mobile Menu',
            'mobile-bottom-menu'   =>  'Mobile Bottom Menu',
        )
    );
}
add_action('init', "c_register_theme_menus");
    /*
        * Switch default core markup for search form, comment form, and comments
        * to output valid HTML5.
        */
    add_theme_support(
        'html5',
        [
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script',
        ]
    );

    // Set up the WordPress core custom background feature.
    add_theme_support(
        'custom-background',
        apply_filters(
            'sai_sha_mall_custom_background_args',
            [
                'default-color' => 'ffffff',
                'default-image' => '',
            ]
        )
    );

    // Add theme support for selective refresh for widgets.
    add_theme_support('customize-selective-refresh-widgets');

    /*
     * Add support for core custom logo.
     *
     * @link https://codex.wordpress.org/Theme_Logo
     */
    add_theme_support(
        'custom-logo',
        [
            'height' => 250,
            'width' => 250,
            'flex-width' => true,
            'flex-height' => true,
        ]
    );
    add_image_size( 'homepage-happening', 419,270, true );
}

add_action('after_setup_theme', 'tsuen_wan_setup');

/**
 * Enqueue scripts and styles.
 */
function template_img_url($image_file) {
	return get_template_directory_uri()."/images/{$image_file}";
}

function tsuen_wan_scripts()
{
    //wp_enqueue_style('header-footer', get_stylesheet_directory_uri() . '/assets/css/header-footer.css', [], _S_VERSION);
    //wp_enqueue_script('vendor_js', get_template_directory_uri().'/assets/js/vendorFile.min.js', array('jquery'), '', true);
    wp_localize_script( 'custom', 'my_ajax', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
}
add_action('wp_enqueue_scripts', 'tsuen_wan_scripts');

if (function_exists('pll_register_string')) {
    pll_register_string('tsuen', 'Page not found');
    pll_register_string('tsuen', "Sorry, we can't find the page you're looking for.");
    pll_register_string('tsuen', 'Back to Home');
    pll_register_string('tsuen', 'All');
    pll_register_string('tsuen', 'Dining');
    pll_register_string('tsuen', 'Shopping');
    pll_register_string('tsuen', 'Happenings');
    pll_register_string('tsuen', 'Entertainment');
    pll_register_string('tsuen', 'NEXT');
    pll_register_string('tsuen', 'PREV');
    pll_register_string('tsuen', 'Share');
    pll_register_string('tsuen', 'No image');
    pll_register_string('tsuen', 'Trailer');
    pll_register_string('tsuen', 'Buy Ticket');
    pll_register_string('tsuen', '403 Forbidden API');
    pll_register_string('tsuen', 'Show More');
    pll_register_string('tsuen', 'Category');
    pll_register_string('tsuen', 'A-Z');
    pll_register_string('tsuen', 'Floor');
    pll_register_string('tsuen', 'Floor Plan');
    pll_register_string('tsuen', 'Sorry, no posts matched your criteria.');
    pll_register_string('tsuen', 'Others');
    pll_register_string('tsuen', 'Follow Us');
    pll_register_string('tsuen', 'Show All');
    pll_register_string('tsuen', 'View All Results');
    pll_register_string('tsuen', 'HOTPICKS');
    pll_register_string('tsuen', 'You may also like');
    pll_register_string('tsuen', 'View More');
    pll_register_string('tsuen', 'Show Less');
    pll_register_string('tsuen', 'Results Not Found');
}
function tsuen_social_fields($field_customize, $field_name, $field_label){

    // Facebook Image
    $field_customize->add_setting( ''.$field_name.'_image', array(
        'default' => '',
    ) );

    $field_customize->add_control(
        new WP_Customize_Image_Control(
            $field_customize,
            ''.$field_name.'_image',
            array(
                'label'    => __( ''.$field_label.' Image', 'tsuen' ),
                'section'  => 'social_media_section',
                'settings' => ''.$field_name.'_image',
            )
        )
    );
    // Facebook Link
    $field_customize->add_setting( ''.$field_name.'_link', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ) );

    $field_customize->add_control( ''.$field_name.'_link', array(
        'label'   => __( ''.$field_label.' Link', 'tsuen' ),
        'section' => 'social_media_section',
        'type'    => 'text',
    ) );
}

function tsuen_customize_social( $wp_customize ) {

    // Add Social Media Section
    $wp_customize->add_section( 'social_media_section', array(
        'title'    => __( 'Social Media', 'tsuen' ),
        'priority' => 120,
    ) );	
    
    $wp_customize->add_setting( 'social_heading', array(
        'default'           => ''
    ) );

    $wp_customize->add_control( 'social_heading', array(
        'label'   => __( 'Heading', 'tsuen' ),
        'section' => 'social_media_section',
        'type'    => 'text',
    ) );

	tsuen_social_fields($wp_customize, 'facebook', 'Facebook');
	tsuen_social_fields($wp_customize, 'instagram', 'Instagram');
	tsuen_social_fields($wp_customize, 'xiaohongshu', 'Xiaohongshu');
	tsuen_social_fields($wp_customize, 'weibo', 'Weibo');
	tsuen_social_fields($wp_customize, 'wechat', 'Wechat');
	tsuen_social_fields($wp_customize, 'meta', 'Meta');
	tsuen_social_fields($wp_customize, 'dianping', 'Dianping');
    
}
add_action( 'customize_register', 'tsuen_customize_social' );

function tsuen_copyright( $wp_customize ) {

    // Add Copyright Section
    $wp_customize->add_section( 'copyright_section', array(
        'title'    => __( 'Copyright', 'tsuen' ),
        'priority' => 121,
    ) );	

	$wp_customize->add_setting( 'copyright_block', array(
        'default'           => '',
        'sanitize_callback' => '',
    ) );

    $wp_customize->add_control( 'copyright_block', array(
        'label'   => __( 'Copyright', 'tsuen' ),
        'section' => 'copyright_section',
        'type'    => 'textarea',
    ) );	
		
}
add_action( 'customize_register', 'tsuen_copyright' );


function get_current_lang(){
	return pll_current_language();
}
// get movies list
function tsuen_movies_list($api_url){

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $api_url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
      'Content-Type: application/json',
    ));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    $output = curl_exec($curl);
    curl_close($curl);
    if (strpos($output,'403 Forbidden')) {
        return false;
    }else{
        
        $movies_list = explode("\n",$output);
        array_pop($movies_list);
        $movies_lists =array();
        foreach($movies_list as $movie){
            $movie_arr = explode(",",$movie);
            $movie_name = $movie_arr[0];
            $movie_img = $movie_arr[9];
            $movie_trial = $movie_arr[11];
            $movies_lists[$movie_arr[0]] = ['name'=>$movie_name,'thumb'=>$movie_img,'trailer'=>$movie_trial];
        }
        return($movies_lists);
    }
}
// get movies schedules
function tsuen_movies_schedules($api_url,$lang){
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $api_url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
      'Content-Type: application/json',
    ));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);  
    $output = curl_exec($curl);
    curl_close($curl);
    if (strpos($output,'403 Forbidden')) {
        return false;
    }else{
        $movies_schedule = explode("\n",$output);
        array_pop($movies_schedule);
        $movies_schedules =array();
        foreach($movies_schedule as $movie){
            $movie_arr = explode(",",$movie);
            if($movie_arr[2]):
                $today = date("Y/m/d");
                $timestamp = $movie_arr[2];
                $match_date = DateTime::createFromFormat('d/m/Y H:i', $timestamp)->format('Y/m/d');
                $time = DateTime::createFromFormat('d/m/Y H:i', $timestamp)->format('H:i');
                if($today == $match_date):
                    if (array_key_exists($movie_arr[1], $movies_schedules)) {
                        $tam = $movies_schedules[$movie_arr[1]]['time'];
                        $tam[] = $time;
                        $movies_schedules[$movie_arr[1]]['time'] = $tam;
                    }else{
                        $movies_schedules[$movie_arr[1]] = ['time'=>[$time],'buy_ticket' => 'www.cinema.com.hk/'. $lang .'/ticketing/seatplan/'.$movie_arr[3]];
                    }
                endif;
            endif;
        }
        return($movies_schedules);
    }
}
// get movies schedules today
function tsuen_movies_today($list_api, $schedues_api, $lang){
    $movies_list = tsuen_movies_list($list_api);

    $movies_schedules = tsuen_movies_schedules($schedues_api, $lang);
    if ($movies_list && $movies_schedules){
        foreach ($movies_schedules as $key => $value) {
            if (array_key_exists($key, $movies_list)) {
                $movies_schedules[$key] = array_merge($movies_schedules[$key], $movies_list[$key]);
            }
        }
        return $movies_schedules;
    }else{
        return false;
    }
}
function get_image($file){
	if(!empty($file)){
		return $file;
	}else{
		global $placeholder;
		if(empty($placeholder)){
			$site_fields = get_fields( "options" );
			$placeholder = $site_fields["placeholder"];
		}
		return $placeholder;
	}
}

/*
 * initial posts dispaly
 */
function shoppings_load_more($args = array())
{
	//initial posts load
	load_more_shoppings($args);
}

/*
 * create short code.
 */
add_shortcode('ajax_shoppings', 'shoppings_load_more');

/**
 * Load shoppings
 */
function load_more_shoppings($args)
{
    //init ajax
    $ajax = false;

    //check ajax call or not
    if (
        !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
        strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'
    ) {
        $ajax = true;
    }

    //number of posts per page default
	$posts_per_page = -1;

    //page number
	$paged = isset($_POST['paged']) ? $_POST['paged'] : 1;

    //Current custom post type and parametters
	//$custom_post_type = 'shops';
    $custom_post_type = isset($_POST['custom_post_type']) ? $_POST['custom_post_type'] : 'shops';

    //Declare $args
	$args = array(
		'post_type'      => $custom_post_type,
		'post_status' 	 => 'publish',
		'posts_per_page' => $posts_per_page,
		'paged'          => $paged,
        'orderby'        => 'title',
        'order'          => 'ASC',
	);    

    /**
     * Search
     */
    if(isset($_POST['text_search'])) {
        $args['post_title_like'] = $_POST['text_search'];
    }

    /* Check shops_categories */
    if (isset($_POST['privileges'])) {
        $args['tax_query']['relation'] = 'AND';
    }
	if (isset($_POST['category']) && $_POST['category'] != 'all') {
        $args['tax_query'][] = array(
			//'taxonomy' => isset($_POST['taxonomy']) ? $_POST['taxonomy'] : 'shop-categories',
            'taxonomy' => isset($_POST['tax_dinings']) ? 'dining-categories' : 'shop-categories',
			'field'    => 'term_id',
			'terms'    => sanitize_text_field($_POST['category']),
		);
    }

    /* Check privileges */
    if (isset($_POST['privileges']) && $_POST['privileges'] != 'all_privilege') {
        $args['tax_query'][] = array(
			'taxonomy' => 'privileges',
			'field'    => 'term_id',
			'terms'    => sanitize_text_field($_POST['privileges']),
		);
    }

    /**
     * Filer by A-Z
     */
    if(isset($_POST['title_starts_with']) && $_POST['title_starts_with'] != 'all') {
        // $args = array(
        //     'post_type'      => $custom_post_type,
        //     'post_status' 	 => 'publish',
        //     'posts_per_page' => $posts_per_page,
        //     'paged'          => $paged,
        //     'title_starts_with'=> $_POST['title_starts_with'],
        // );
        $args['title_starts_with'] = $_POST['title_starts_with'];
    }

    /**
     * Filter by Floor
     */
    if(isset($_POST['floor']) && $_POST['floor'] != 'all') {
        // $args = array(
        //     'post_type'      => $custom_post_type,
        //     'post_status' 	 => 'publish',
        //     'posts_per_page' => $posts_per_page,
        //     'paged'          => $paged,
        //     'meta_key'      => 'shop_phase_level',
        //     'meta_value'    => $_POST['floor']
        // );
        // $args['meta_query'][] = array(
        //     'key' => 'shop_phase_level',
        //     'value' => $_POST['floor'],
        //     'compare' => '='
        // );
        $args['meta_query'][] = 'shop_phase_level';
        $args['meta_value'] = $_POST['floor'];
    }

    $query = new WP_Query($args);
    
    if ($query->have_posts()) {
        while ($query->have_posts()) : $query->the_post();
            echo '<div class="item item-shop col-4">';
                include( locate_template( 'templates/inc/item_shop.php', false, false ) );
            echo '</div>';
		endwhile;
    } else {
        echo '<p>' . pll__('Sorry, no posts matched your criteria.') . '</p>';
    }

    //reset post data
	wp_reset_postdata();

	//check ajax call
	if ($ajax) die();
}

add_action('wp_ajax_load_more_shoppings', 'load_more_shoppings');
add_action('wp_ajax_nopriv_load_more_shoppings', 'load_more_shoppings');

/**
 * Filter posts by post title first letter in WordPress (PHP 7.x)
 */
add_filter('posts_where', function (string $where, WP_Query $query): string {
    $wpdb = $GLOBALS['wpdb'];

    $starts_with = esc_sql($query->get('title_starts_with'));
    $post_title_like = esc_sql($query->get('post_title_like'));

    if (empty($starts_with) && empty($post_title_like)) {
        return $where;
    }

    if($starts_with == '#') {
        $where .= $wpdb->prepare(" AND $wpdb->posts.post_title REGEXP '^[^a-zA-Z]'");
    } else {
        $where .= $wpdb->prepare(" AND $wpdb->posts.post_title LIKE %s", $starts_with . '%');
    }

    if ($post_title_like) {
        $where .= ' AND ' . $wpdb->posts . '.post_title LIKE \'%' . $post_title_like . '%\'';
    }

    return $where;
}, 10, 2);

// add_filter( 'pre_get_posts', 'add_cpt_in_search_result' );

// function add_cpt_in_search_result( $query ) {

//     if ( $query->is_search ) {
//     $query->set( 'post_type', array( "dinings", "shops", "happening", "play-garden" ) );
//     }

//     return $query;
// }
function remove_comments_menu_item() {
    remove_menu_page('edit-comments.php'); // Removes the Comments menu item
}
add_action('admin_menu', 'remove_comments_menu_item');


add_action('admin_enqueue_scripts', 'remove_future_otpions');
function remove_future_otpions($hook) {
    ?>
    <style>
        .postbox-container .future-action-panel select option[value="category-add"],
        .postbox-container .future-action-panel select option[value="delete"],
        .postbox-container .future-action-panel select option[value="category-remove-all"],
        .postbox-container .future-action-panel select option[value="category-remove"],
        .postbox-container .future-action-panel select option[value="category"],
        .postbox-container .future-action-panel select option[value="stick"],
        .postbox-container .future-action-panel select option[value="unstick"]{
            display: none;
        }        
    </style>
    <?php
    wp_enqueue_script('my_custom_script', plugin_dir_url(__FILE__) . '/myscript.js');
}

function hide_editor_on_front_page() {
    global $pagenow;
    if ($pagenow !== 'post.php') return;

    $post_id = isset($_GET['post']) ? $_GET['post'] : (isset($_POST['post_ID']) ? $_POST['post_ID'] : false);

    if (!$post_id) return;

    // Check if the post ID matches the front page ID
    if ($post_id == get_option('page_on_front')) {
        // Remove the editor support for this post
        remove_post_type_support('page', 'editor');
    }
}
add_action('admin_init', 'hide_editor_on_front_page');

add_action('map_meta_cap', 'custom_manage_privacy_options', 1, 4);
function custom_manage_privacy_options($caps, $cap, $user_id, $args)
{
  if (!is_user_logged_in()) return $caps;

  $user_meta = get_userdata($user_id);
  if (array_intersect(['editor'], $user_meta->roles)) {
    if ('manage_privacy_options' === $cap) {
      $manage_name = is_multisite() ? 'manage_network' : 'manage_options';
      $caps = array_diff($caps, [ $manage_name ]);
    }
  }
  return $caps;
}

function get_shop_floor($shop_numbers){
	$shop_number_string = "";
	if(!empty($shop_numbers)){

		foreach ($shop_numbers as $key => $shop_number){
			if(empty($shop_number["shop_number"])) continue;
			if($key != 0){
				$shop_number_string .= ", ";
			}
			$shop_number_string .= $shop_number["shop_number"];
		}
	}
	return $shop_number_string;
}