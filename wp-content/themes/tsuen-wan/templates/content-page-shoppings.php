<?php 

/**
 * Template Name: Content page Shopping
 *
 */
get_header() ;
$lang = pll_current_language();

$post_type = "shoppings";
$post_category = "shop_categories";
$post_page_id = $post_type."_page";

$shop_terms = get_terms( array(
	'taxonomy' => $post_category,
	'hide_empty' => false,
));
$current_type = "all";
$content_type = $post_type;
if(isset($wp_query->query_vars['t'])) {
	$current_type = $wp_query->query_vars['t'];
}
?>
<div ng-app="app" id="<?php echo $post_page_id ?>" class="page_section shop-page" ng-controller="ShopController" ng-init="category='<?php echo $current_type ?>';sort='category'; init('<?php echo $content_type ?>')" >
	<?php include( locate_template( 'templates/inc/section-shop_page.php', false, false ) ); ?>
</div>

<?php get_footer() ?>