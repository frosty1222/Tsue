<?php
$arraypostype = ['shops'=>'Shops', 'dinings' => 'Dinings','happening' => 'Happenings', 'play-garden' => 'Play Gardens'];
foreach($arraypostype as $key => $postype){
    $labels = array(
		'name'                => _x( $postype, 'Post Type General Name', 'tsuen' ),
		'singular_name'       => _x( $postype, 'Post Type Singular Name', 'tsuen' ),
		'menu_name'           => __( $postype, 'tsuen' ),
		'parent_item_colon'   => __( 'Parent '.$postype, 'tsuen' ),
		'all_items'           => __( 'All '.$postype, 'tsuen' ),
		'view_item'           => __( 'View '.$postype, 'tsuen' ),
		'add_new_item'        => __( 'Add New '.$postype, 'tsuen' ),
		'add_new'             => __( 'Add New', 'tsuen' ),
		'edit_item'           => __( 'Edit '.$postype, 'tsuen' ),
		'update_item'         => __( 'Update '.$postype, 'tsuen' ),
		'search_items'        => __( 'Search '.$postype, 'tsuen' ),
		'not_found'           => __( 'Not Found', 'tsuen' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'tsuen' ),
	);
	
	$args = array(
		'label'               => __( $postype, 'tsuen' ),
		'description'         => __( ''.$postype.' news and reviews', 'tsuen' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'excerpt', 'thumbnail', 'custom-fields','page-attributes' ),
		'hierarchical'        => true,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 10,
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'capability_type'     => 'post',
	);
    register_post_type( $key, $args );
}
$arraytax = ['shops'=>'Shop Categories','dinings'=>'Dining Categories','shops,dinings'=>'Privileges','happening'=>'Happening Categories', 'play-garden'=>'Play Garden Categories'];

foreach($arraytax as $postype => $tax){
	$labels = array(
		'name' => _x( $tax, 'taxonomy general name' ),
		'singular_name' => _x( $tax, 'taxonomy singular name' ),
		'search_items' =>  __( 'Search '.$tax ),
		'popular_items' => __( 'Popular '.$tax ),
		'all_items' => __( 'All '.$tax ),
		'parent_item' => null,
		'parent_item_colon' => null,
		'edit_item' => __( 'Edit '.$tax ), 
		'update_item' => __( 'Update '.$tax ),
		'add_new_item' => __( 'Add New '.$tax ),
		'new_item_name' => __( 'New '.$tax.' Name' ),
		'separate_items_with_commas' => __( 'Separate '.$tax.' with commas' ),
		'add_or_remove_items' => __( 'Add or remove '.$tax ),
		'choose_from_most_used' => __( 'Choose from the most used '.$tax ),
		'menu_name' => __( $tax ),
	  ); 
	  register_taxonomy(sanitize_title($tax),explode(',',$postype),array(
		'hierarchical' => true,
		'labels' => $labels,
		'show_ui' => true,
		'show_admin_column' => true,
		'update_count_callback' => '_update_post_term_count',
		'query_var' => true,
	//	'rewrite' => array( 'slug' => ''.$tax ),
	  ));
}