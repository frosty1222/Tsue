<?php

/**
 * Template Name: Play Garden Page
 *
 */
get_header() ;
$page_fields = get_fields();
?>
<main id="garden_page" class="nearby garden_wrapper">
	<header class="banner-section" style="background-image: url('<?php echo the_post_thumbnail_url( 'full' );?>')">
        <div class="container">
            <div class="banner-section_title">
                <h3 class="section-header"><?php the_title() ?></h3>
            </div>
        </div>
    </header>
    <div class="nearby-main">
        <div class="banner_description"><?php the_content(); ?></div>
        <div class="content-wrapper">
            <div class="play-garden_slides fancybox_slider splide cursor-hover">
                <div class="splide__track">
                    <div class="splide__list">
                        <?php
                        $posttype =  'play-garden';
                        $args = [
                            'post_type' => $posttype ,
                            'posts_per_page' => 9,
                        ];                        
                        // the query.
                        $the_query = new WP_Query( $args ); ?>

                        <?php if ( $the_query->have_posts() ) : ?>
                            <?php
                                while ( $the_query->have_posts() ) :
                                    $the_query->the_post();
                                    $thumbnail = get_the_post_thumbnail( get_the_ID(), 'full' );
                                    $title = '';
                                    $description = '';
                                    $thumbnail_url = get_the_post_thumbnail_url(get_the_ID(),'full');
                                    if($thumbnail): ?>
                                        <div class="splide__slide">
                                            <?php include( locate_template( 'templates/inc/item_slider.php', false, false ) ); ?>
                                        </div>
                                    <?php endif ?>
                                <?php endwhile; ?>
                            <?php wp_reset_postdata(); ?>
                        <?php else : ?>
                            <p><?php esc_html_e( 'Sorry, no posts matched your criteria.' ); ?></p>
                        <?php endif; ?>                        
                    </div>
                </div>
            </div>            
        </div>
        <?php include( locate_template( 'templates/inc/floor-map.php', false, false ) ); ?>
    </div>
</main>

<?php get_footer() ?>

