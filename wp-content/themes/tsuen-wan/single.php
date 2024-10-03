<?php
get_header() ;
?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<main class="main_content">
	<header class="banner-section-post">
        <?php if( $images = get_field('slider_top') ): ?>
        <?php $imagesmb = get_field('slider_top_mobile') ?>
		<!-- banner -->
		<div class="home-slider single-slider">
			<div id="js-loading__mask" aria-hidden="true">
			    <div class="js-loading__elm js-loading__elm-01"></div>
			    <div class="js-loading__elm js-loading__elm-02"></div>
			    <div class="js-loading__elm js-loading__elm-03"></div>
			</div>
            <div class="swiper-container">
                <div class="swiper-wrapper">
                <?php $i =0; foreach ( $images as $image_id ):
                 $imagesid_mb = $image_id; 
                if($imagesmb) {
                    $imagesid_mb = array_key_exists($i,$imagesmb)? $imagesmb[$i] : $image_id;
                }                    
                ?>
                    <div class="swiper-slide">
                        <div class="image-slider">
                            <picture>
                                <source media="(min-width:768px)" srcset=" <?php echo wp_get_attachment_image_url( $image_id, 'full' ); ?>">
                                <source media="(max-width:767px)" srcset="<?php echo wp_get_attachment_image_url(  $imagesid_mb, 'full' ); ?>">
                                <?php echo wp_get_attachment_image( $image_id, 'full' ); ?>
                            </picture>                           
                        </div>
                    </div>
                <?php $i++; endforeach ?>
                </div>
            </div> 
            <div class="home-navigation">
                <div class="container arrow-button big">
                    <a class="arrow-prev-button arrow-button-all  ">
                        <?= esc_html__('PREV','tsuen') ?>
                        <div class="svg"><svg width="187" height="14" viewBox="0 0 187 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M186.5 13L2 13L20.5 1V8.5" stroke="black"></path>
                        </svg></div>
                    </a>
                    <div class="home-slider-progressbar"></div>
                    <a class="arrow-next-button arrow-button-all  ">
                        <?= esc_html__('NEXT','tsuen') ?>
                        <div class="svg"><svg width="187" height="14" viewBox="0 0 187 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M0 13L184.5 13L166 1V8.5" stroke="black"></path>
                            </svg>
                        </div>
                    </a>
                </div> 
            </div> 
		</div>        
		<?php endif ?>           
        <div class="container relative">
            <div class="single-firetop">
                <?php  ani_firework() ?>
            </div>            
            <div class="header-after-slider text-center">
                <h1 class="title-header letter-fade"><?php the_title() ?></h1>
                <div class="content">
                    <?php the_content(); ?>
                </div>
                <div class="inline-center content-center time-share">
                    <?php  if( get_field('list_description') ): ?>
                        <div class="date--field inline-center">
                            <svg width="18" height="26" viewBox="0 0 18 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 12.375C8.1712 12.375 7.37634 12.0458 6.79029 11.4597C6.20424 10.8737 5.875 10.0788 5.875 9.25C5.875 8.4212 6.20424 7.62634 6.79029 7.04029C7.37634 6.45424 8.1712 6.125 9 6.125C9.8288 6.125 10.6237 6.45424 11.2097 7.04029C11.7958 7.62634 12.125 8.4212 12.125 9.25C12.125 9.66038 12.0442 10.0667 11.8871 10.4459C11.7301 10.825 11.4999 11.1695 11.2097 11.4597C10.9195 11.7499 10.575 11.9801 10.1959 12.1371C9.81674 12.2942 9.41038 12.375 9 12.375ZM9 0.5C6.67936 0.5 4.45376 1.42187 2.81282 3.06282C1.17187 4.70376 0.25 6.92936 0.25 9.25C0.25 15.8125 9 25.5 9 25.5C9 25.5 17.75 15.8125 17.75 9.25C17.75 6.92936 16.8281 4.70376 15.1872 3.06282C13.5462 1.42187 11.3206 0.5 9 0.5Z" fill="#73D4D4"/>
                            </svg>
                            <span class="text"><?= get_field('list_description') ?></span>
                        </div>
                    <?php endif ?>
                    <?php  if( have_rows('opening_hours') ): ?>
                        <?php $rowCount = count( get_field('opening_hours') ); //GET THE COUNT ?>
                        <?php $i = 1; ?>
                        <div class="date--field inline-center">
                            <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M11 0C4.92514 0 0 4.92514 0 11C0 17.0749 4.92514 22 11 22C17.0749 22 22 17.0749 22 11C22 4.92514 17.0749 0 11 0ZM16.0769 12.6923H11C10.7756 12.6923 10.5604 12.6032 10.4017 12.4445C10.243 12.2858 10.1538 12.0706 10.1538 11.8462V4.23077C10.1538 4.00636 10.243 3.79113 10.4017 3.63245C10.5604 3.47376 10.7756 3.38462 11 3.38462C11.2244 3.38462 11.4396 3.47376 11.5983 3.63245C11.757 3.79113 11.8462 4.00636 11.8462 4.23077V11H16.0769C16.3013 11 16.5166 11.0891 16.6752 11.2478C16.8339 11.4065 16.9231 11.6217 16.9231 11.8462C16.9231 12.0706 16.8339 12.2858 16.6752 12.4445C16.5166 12.6032 16.3013 12.6923 16.0769 12.6923Z" fill="#73D4D4"/>
                            </svg>
                            <span class="text">
                                <?php while( have_rows('opening_hours') ) : the_row(); ?>
                                    <?= get_sub_field('open_hours_item'); ?>
                                    <?php if($i < $rowCount): ?>
                                        <br>
                                    <?php endif; ?>
                                    <?php $i++; ?>
                                <?php endwhile; ?>
                            </span>
                        </div>
                    <?php else: ?>
                        <?php  if( get_field('open_hours') ): ?>
                            <div class="date--field inline-center">
                                <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M11 0C4.92514 0 0 4.92514 0 11C0 17.0749 4.92514 22 11 22C17.0749 22 22 17.0749 22 11C22 4.92514 17.0749 0 11 0ZM16.0769 12.6923H11C10.7756 12.6923 10.5604 12.6032 10.4017 12.4445C10.243 12.2858 10.1538 12.0706 10.1538 11.8462V4.23077C10.1538 4.00636 10.243 3.79113 10.4017 3.63245C10.5604 3.47376 10.7756 3.38462 11 3.38462C11.2244 3.38462 11.4396 3.47376 11.5983 3.63245C11.757 3.79113 11.8462 4.00636 11.8462 4.23077V11H16.0769C16.3013 11 16.5166 11.0891 16.6752 11.2478C16.8339 11.4065 16.9231 11.6217 16.9231 11.8462C16.9231 12.0706 16.8339 12.2858 16.6752 12.4445C16.5166 12.6032 16.3013 12.6923 16.0769 12.6923Z" fill="#73D4D4"/>
                                </svg>
                                <span class="text"><?= get_field('open_hours') ?></span>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php  if( get_field('phone') ): ?>
                        <a href="tel:<?= get_field('phone') ?>" class="date--field inline-center">
                            <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M21.6746 26.9542C16.9889 26.9481 12.4968 25.084 9.18349 21.7707C5.87017 18.4574 4.00607 13.9653 4 9.27957C4 7.87938 4.55623 6.53653 5.54631 5.54644C6.5364 4.55636 7.87925 4.00013 9.27944 4.00013C9.57594 3.99787 9.87194 4.02478 10.1632 4.08047C10.4447 4.12213 10.7215 4.19133 10.9895 4.28706C11.178 4.3532 11.346 4.4674 11.4768 4.61837C11.6077 4.76934 11.6969 4.95184 11.7355 5.14784L13.3079 12.0341C13.3503 12.221 13.3452 12.4155 13.293 12.6C13.2409 12.7844 13.1434 12.9529 13.0095 13.0899C12.8603 13.2506 12.8488 13.2621 11.4371 13.9966C12.5676 16.4767 14.5511 18.4683 17.0264 19.6089C17.7725 18.1858 17.7839 18.1743 17.9446 18.0251C18.0817 17.8911 18.2501 17.7936 18.4346 17.7415C18.619 17.6894 18.8136 17.6843 19.0005 17.7267L25.8867 19.299C26.0764 19.343 26.2518 19.4346 26.3964 19.5651C26.541 19.6956 26.6499 19.8608 26.7131 20.045C26.8099 20.3175 26.8829 20.5978 26.9311 20.8829C26.9773 21.1713 27.0004 21.463 27 21.7551C26.9788 23.1493 26.407 24.4785 25.4092 25.4526C24.4115 26.4267 23.069 26.9665 21.6746 26.9542Z" fill="#73D4D4"/>
                            </svg>
                            <span class="text"><?= get_field('phone') ?></span>
                        </a>
                    <?php endif ?>
                    <?php include( locate_template( 'templates/inc/block-share_items.php', false, false ) ); ?> 
                </div>
                <?php
                $privileges = get_the_terms( get_the_id() , 'privileges', 'string');
                if(!empty($privileges)) : ?>
                    <ul class="listmenu list-privileges content-center ">
                        <?php foreach ( $privileges as $privilege ) : ?>
                            <?php 
                                $image_id = get_field('icon_tax',$privilege);
                                echo '<li class="inline-center">';
                                    if($image_id){
                                        $image_url = wp_get_attachment_image_url( $image_id, 'full' );
                                        $ext = pathinfo($image_url, PATHINFO_EXTENSION);
                                        if($ext=='svg'){
                                            $file = file_get_contents($image_url, true);
                                        }else{
                                            $file = wp_get_attachment_image($image_id,'full');
                                        }
                                        echo  $file ;
                                    }
                                    echo $privilege->name;
                                echo '</li>';
                            ?>
                        <?php endforeach; ?>
                    </ul>
                <?php endif;?>
            </div>
            <div class="fire-slider-bottom mobile">
                    <?php  ani_firework() ?>
            </div> 
	    </div>
	</header>
    <?php 
    $hot_picks = get_field('hot_picks_section');
    if($hot_picks&&!get_field('hide_hotpicks')): ?>
        <?php if( have_rows('hot_picks_section_hot_pick') ):?>
        <div class="hotpick text-center section home-shopping">
            <div class="container relative">
                <div class="fire-top-left mobile">
                    <?php  ani_firework(2) ?>
                </div>
                <div class="fire-bottom-right mobile">
                    <?php  ani_firework(2) ?>
                </div>
                <?php if($hot_picks['title']): ?>
                    <h3 class="title"><?= $hot_picks['title'] ?></h3>
                <?php else: ?>
                    <h3 class="title"><?= pll__( 'HOTPICKS', 'tsuen' ); ?></h3>
                <?php endif; ?>
                <div class="swiper-container">
					<div class="swiper-wrapper">         
                        <?php while( have_rows('hot_picks_section_hot_pick') ): the_row(); 
                            $image_id = get_sub_field('image')
                            ?>
                            <div class="swiper-slide item item-shop">
                                <div class="feature-image animate_line">
                                    <span class="line line1"></span>
                                    <span class="line line2"></span>
                                    <span class="line line3"></span>
                                    <span class="line line4"></span>
                                    <div class="inner-feature">
                                        <?php 
                                            if($image_id){
                                                echo '<div class="ratio11">';
                                                        echo wp_get_attachment_image( $image_id, 'full' );
                                                echo '</div>';
                                            }else{
                                                echo '<div class="no-image ratio11">'.esc_html('No image').'</div>';
                                            }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
                <div class="home-list-nav dinings-navigation">
                    <div class="container arrow-button small">
                        <div class="home-list-progressbar home-slider-progressbar"></div>
                    </div> 
				</div> 
            </div>
        </div>
        <?php endif; ?>
    <?php endif; ?>
    <?php
        $post_type = get_post_type();
        $current_post_ID = get_the_ID();
    ?>
    <?php include( locate_template( 'templates/inc/floor-map.php', false, false ) ); ?>
    <?php
        if($post_type == 'shops'){
            $taxo =  'shop-categories';
        }else{
            $taxo =  'dining-categories';
        }
        $terms = get_the_terms( $current_post_ID , $taxo, 'string');
        $term_ids = wp_list_pluck($terms,'term_id');
        $post_per_page = (get_field('number_you_may_also_like'))?get_field('number_you_may_also_like'): 4;
        $related_query = new WP_Query(array(
            'post_type' => $post_type,
            'post_status'	=> 'publish',
            'post_parent' => 0,
            'tax_query' 	=> array(
                'relation' 	=> 'AND',
                array(
                    'taxonomy' => $taxo,
                    'field' => 'id',
                    'terms' => $term_ids,
                    'operator'=> 'IN' //Or 'AND' or 'NOT IN'
                ),
            ),
            'post__not_in' => array($current_post_ID), 
            'posts_per_page' =>  $post_per_page,
        ));
        if ( $related_query->have_posts() ) : ?>
        <div class="related-post text-center section home-dining">
            <div class="container relative">
                <h3 class="title"><?= pll__( 'You may also like', 'tsuen' ) ?></h3>
                <div class="shoppings-slider">
					<div class="swiper-container">
						<div class="swiper-wrapper">
                            <?php while ( $related_query->have_posts() ) : $related_query->the_post(); ?>
								<div class="swiper-slide item item-shop">
									<?php include( locate_template( 'templates/inc/item_shop.php', false, false ) ); ?>
								</div>
                            <?php endwhile; ?>
							<?php wp_reset_postdata(); ?>
						</div>
					</div>
					<div class="home-list-nav shoppings-navigation">
						<div class="container arrow-button small">
					        <div class="home-list-progressbar home-slider-progressbar"></div>
						</div>
					</div>
				</div>
                <div class="single-firebottom">
                    <?php  ani_firework() ?>
                </div>
            </div>
        </div>
        <?php endif; wp_reset_postdata(); ?>

</main>

<?php endwhile;  ?>
<?php endif; ?>

<?php get_footer(); ?>