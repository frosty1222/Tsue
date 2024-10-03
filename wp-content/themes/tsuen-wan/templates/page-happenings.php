<?php

/**
 * Template Name: Happenings
 *
 */
get_header() ;
?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<main class="happening page_main_content">
	<header class="banner-section" style="background-image: url('<?php echo the_post_thumbnail_url( 'full' );?>')">
		<div class="container">
			<div class="banner-section_title">
				<h1 class="title-header letter-fade">
					<?php the_title() ?>
				</h1>
			</div>
		</div>
	</header>
	<div class="list-main">
		<div class="container">
			<div id="list-main_content" class="shop-main_content main_content text-center">
				<div class="contentpage">
					<?php the_content()?>
				</div>	
				<form action="" class="form-filter" method="get" id="form-filter">
					<div class="cat-happening">
                        <ul class="listmenu content-center tab-menu">
                        <?php
						$categories = 'happening-categories';
                        $arg               = array(
                            'taxonomy'   => 'happening-categories',
                            'hide_empty' => false,
                        );
                        $shop_categories  = get_terms( $arg );									
                        ?>
                        <li>
                            <input type="radio" value="all" id="itemall" name="<?= $categories; ?>" checked>
                            <label for="itemall"><?= __("All"); ?></label>
                        </li>
						<?php if(!empty($shop_categories)) : ?>
                        <?php foreach ( $shop_categories as $shop_category ) :
                            ?>
                            <li>
                                <input type="radio" value="<?= $shop_category->slug; ?>" id="item<?= $shop_category->slug; ?>" name="<?= $categories; ?>" <?= (isset($_GET[$categories])&&$_GET[$categories]==$shop_category->slug)? 'checked' : '';?>>
                                <label for="item<?= $shop_category->slug; ?>" ><?= $shop_category->name; ?></label>	
                            </li>									
                        <?php endforeach; ?>
                        </ul>
						<?php endif; ?>
                    </div>
				</form>
				<div class="querycontent">					
					<?php					
					$posttype =  'happening';
					$argshide = [
						'post_type' =>'happening',
						'posts_per_page' =>3,
						'meta_query' => [    
							[
							  'key' => 'showhide',         
							  'value' => 1,
							  'compare' => '='
							],
						],
					];
					$queryhide = new WP_Query( $argshide );
					$post_notin = [];
					while ( $queryhide->have_posts() ) : $queryhide->the_post();
						$post_notin[] = get_the_id();
					endwhile;
					wp_reset_postdata(); 
					$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
					$args = [
						'post_type' =>$posttype ,
						'posts_per_page' =>9,
						'paged'=> $paged,
						'post_status'=>'publish',
						'post__not_in'=>$post_notin,
					];
					if(isset($_GET[$categories])&&$_GET[$categories]!='all'){
						$args['tax_query'] = [
							'relation' => 'AND', 
							[
								'taxonomy' =>$categories, 
								'field' => 'slug', 
								'terms' => $_GET[$categories], 
								'include_children' => true, 
								'operator' => 'IN'
							]
						];
					}
					// the query.
					$the_query = new WP_Query( $args ); ?>

					<?php if ( $the_query->have_posts() ) : ?>
						<div class="list-items listhapp" id="main">
						<?php
							while ( $the_query->have_posts() ) :
								$the_query->the_post(); ?>
								<?php include( locate_template( 'templates/inc/item_happ.php', false, false ) ); ?>
							<?php endwhile; ?>
						</div>
						<?php wp_reset_postdata(); ?>
						<div id="innifyscroll">
							<div class="loader"></div>
							<div class="" style="display:none"><?php tsuen_wp_corenavi($the_query) ?></div>							
						</div>

						<?php do_shortcode('[ajax-loadmore-button]');?>
					<?php else : ?>
						<p><?php esc_html_e( 'Sorry, no posts matched your criteria.' ); ?></p>
						
					<?php endif; ?>					
				</div>
			</div>
		</div>
	</div>

</main>

<?php endwhile;  ?>
<?php endif; ?>

<?php get_footer(); ?>