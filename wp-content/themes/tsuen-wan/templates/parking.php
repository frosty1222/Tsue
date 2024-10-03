<?php

/**
 * Template Name: Parking
 */
get_header(); ?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<main class="parking page_main_content">
	<header class="banner-section" style="background-image: url('<?php echo the_post_thumbnail_url( 'full' );?>')">
		<div class="container">
			<div class="banner-section_title">
				<h1 class="title-header letter-fade"><?php the_title() ?></h1>
			</div>
		</div>
	</header>
	<div class="list-main">
		<div id="list-main_content" class="parking-main_content main_content">
			<div class="container contentparking">
				<div class="cat-happeninsg">
					<?php if( have_rows('title_tabs') ): $dem=0;?>
						<ul class="listmenu content-center tab-menu">
							<?php while( have_rows('title_tabs') ): the_row(); 
								?>									
								<li class="<?= $dem==0? 'active': ''; $dem++; ?>">
									<a href="#tab<?= sanitize_title(get_sub_field('title') ) ?>" class="shop-menu_control"> <?= get_sub_field('title_label') ? get_sub_field('title_label') : get_sub_field('title')?> </a>
								</li>
							<?php endwhile; ?>
						</ul>
					<?php endif; ?>
				</div>
				<div class="querycontent relative">
					<div class="parkingfire_center">
						<?php  ani_firework() ?>
					</div>
					<?php if( have_rows('title_tabs') ): $dem=$cnt=0;?>						
							<?php while( have_rows('title_tabs') ): the_row(); 
								$repeatername = str_replace(' ','_' ,strtolower(get_sub_field('title')))
								?>
								<div class="tab-content <?= $dem==0 ? 'active': ''; $dem++; ?>" id="tab<?= sanitize_title(get_sub_field('title') ) ?>">
									<?php if( have_rows($repeatername) ): ?>
											<?php while( have_rows($repeatername) ): the_row(); $cnt++;
												?>
												<div class="block-parking block-parking<?= $cnt ?>">
													<h5 class="title-block text-center">
														<?= get_sub_field('title')?>
													</h5>
													<div class="content-block-parking">
														<div class="inner">
															<?= get_sub_field('content')?>
														</div>
													</div>
												</div>
											<?php endwhile; ?>
									<?php endif; ?>		
								</div>						
							<?php endwhile; ?>
					<?php endif; ?>	
				</div>
			</div>
			<?php if( have_rows('parking_promotion') ): $dem=0;?>						
				<div class="parking_promo relative">
					<div class="container">
						<div class="content-promo">
							<div class="left-title">
								<h3 class="title-section">
									<?= (get_field('title_parking')) ? get_field('title_parking'): get_field_object('parking_promotion')['label'] ?>
								</h3>
								<div class="parkingfire_slider">
									<?php  ani_firework(2) ?>
								</div>
							</div>							
							<div class="fancybox_slider promo_slider swiper">
								<div class="swiper-wrapper">
									<?php while( have_rows('parking_promotion') ): the_row(); 
										$image = get_sub_field('image');
										$thumbnail = get_sub_field('thumbnail');
										?>
										<div class="splide__slide swiper-slide promo-item">
											<div class="img-slider">
												<a href="<?= wp_get_attachment_image_url( $image, 'full' )?>" target='_blank' class='img-newtab'>
													<?php 
														if( $thumbnail ) : 
															echo wp_get_attachment_image( $thumbnail, 'full' );
														elseif($image): 
															echo wp_get_attachment_image( $image, 'full' );
														endif;
													?>											
												</a>
											</div>	
											<h6 class="title-slider text-center">
												<?= get_sub_field('title')?>
											</h6>
										</div>		
									<?php endwhile; ?>
								</div>
								<?php if(count(get_field('parking_promotion'))>1): ?>
								<div class="arrow-button small">
									<a class="arrow-prev-button arrow-button-all">
										<?= esc_html__('PREV','tsuen') ?>
										<div class="svg">
											<svg width="82" height="14" viewBox="0 0 82 14" fill="none" xmlns="http://www.w3.org/2000/svg">
												<path d="M82 13.0006L2 13L19.2615 1V8.49999" stroke="black"></path>
											</svg>
										</div>
									</a>
									<div class="home-slider-progressbar"></div>
									<a class="arrow-next-button arrow-button-all">
										<?= esc_html__('NEXT','tsuen') ?>
										<div class="svg">
											<svg width="82" height="14" viewBox="0 0 82 14" fill="none" xmlns="http://www.w3.org/2000/svg">
												<path d="M0 13.0006L80 13L62.7385 1V8.5" stroke="black"></path>
											</svg>
										</div>
									</a>
								</div>
								<?php endif; ?>		
							</div>
						</div>
					</div>
				</div>
			<?php endif; ?>									
			<?php if(get_field('google_map')) : ?>
			<div class="googlemap relative">
				<div class="container relative">
					<div class="inner-map">
						<div class="content-map">
							<?= get_field('google_map') ?>
						</div>
						<div class="parking_firebottom">
							<?php  ani_firework() ?>
						</div>					
					</div>					
				</div>
			</div>
			<?php endif; ?>
	</div>	
</main>
<?php endwhile;  ?>
<?php endif; ?>

<?php 
get_footer(); 
?>