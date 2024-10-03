<?php

/**
 * Template Name: Shoppings & Dinning Page
 *
 */
get_header() ;
?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<main id="shoppings_page" class="shoppings page_main_content">
	<header class="banner-section" style="background-image: url('<?php echo the_post_thumbnail_url( 'full' );?>')">
		<div class="container">
			<div class="banner-section_title">
				<h1 class="title-header letter-fade"><?php trim(the_title()) ?></h1>
			</div>
		</div>
	</header>
	<div class="list-main">
		<div class="container">
			<div id="list-main_content" class="shop-main_content">
				<?php
					$post_type = get_field('choose_post');
					$chooser_taxonomy = 'shop-categories';
					if($post_type == 'dinings') {
						$chooser_taxonomy = 'dining-categories';
					}
				?>
				<?php if($chooser_taxonomy): ?>
					<div class="form-filter">
						<?php include( locate_template( 'templates/inc/form-fliter.php', false, false ) ); ?>
					</div>
				<?php endif; ?>
				<div class="querycontent">
					<?php
					$args = [
						'post_type' =>$post_type,
						'posts_per_page'   => -1,
						'orderby'        => 'title',
						'order'          => 'ASC',
					];
					// the query.
					$the_query = new WP_Query( $args ); ?>

					<?php if ( $the_query->have_posts() ) : ?>
						<?php $i = 1; ?>
						<div class="list-items">
						<?php
							while ( $the_query->have_posts() ) :
								$the_query->the_post(); ?>
								<?php
									$term_categories = $term_privileges = '';
									$terms = get_the_terms( get_the_ID(), $chooser_taxonomy );
									$terms_privileges = get_the_terms( get_the_ID(), 'privileges' );
									if($terms){
										foreach($terms as $term){
											if($term_categories == '') {
												$term_categories = $term->term_id;
											} else {
												$term_categories .= ','.$term->term_id;
											}
										}
									}
									if($terms_privileges){
										foreach($terms_privileges as $term){
											if($term_privileges == '') {
												$term_privileges = $term->term_id;
											} else {
												$term_privileges .= ','.$term->term_id;
											}
										}
									}
								?>
								<div class="item item-shop col-4" data-sort="<?= $i ?>" data-floor="<?php echo get_field('shop_phase_level', get_the_ID()) ?>" data-title="<?= get_the_title() ?>" data-category="<?= $term_categories ?>" data-privileges="<?= $term_privileges ?>">
								<?php include( locate_template( 'templates/inc/item_shop.php', false, false ) ); ?>
								<?php $i++; ?>
								</div>
							<?php endwhile; ?>
						</div>
						<?php wp_reset_postdata(); ?>
						<p style="display: none" class="no-posts"><?php echo pll__('Sorry, no posts matched your criteria.'); ?></p>
					<?php else : ?>
						<p><?php echo pll__('Sorry, no posts matched your criteria.'); ?></p>
					<?php endif; ?>
					<?php
						$privileges = get_terms([
							'taxonomy' => 'privileges',
							'hide_empty' => false,
						]);
						$privilege_icons = array();
						
						foreach ($privileges as $privilege){
							$image_id = get_field('icon_tax', $privilege);
							if($image_id){
								$image_url = wp_get_attachment_image_url( $image_id, 'full' );
								$ext = pathinfo($image_url, PATHINFO_EXTENSION);
								if($ext=='svg'){
									$file = file_get_contents($image_url, true);
								}else{
									$file = wp_get_attachment_image($image_id,'full');
								}
								$privilege_icons[$privilege->term_id] = $file;
							}
						}
					?>
					<div class="floorplan-content">
						<?php include( locate_template( 'map/floorplan.php', false, false ) ); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>

<?php endwhile;  ?>
<?php endif; ?>

<?php get_footer(); ?>