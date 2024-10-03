<?php

/**
 * Template Name: Home page
 */
?>
<?php get_header();
if (function_exists('get_field')):
    $hero = get_field('slider');
    $happenings = get_field('happenings');
    $shoppings = get_field('shoppings');
    $dinings = get_field('dinings');
    $instagram = get_field('instagram');
?>
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>

    <div <?php post_class() ?> id="post-<?php the_ID(); ?>">

        <!-- banner -->
        <div class="home-slider">
            <div id="js-loading__mask" aria-hidden="true">
                <div class="js-loading__elm js-loading__elm-01"></div>
                <div class="js-loading__elm js-loading__elm-02"></div>
                <div class="js-loading__elm js-loading__elm-03"></div>
            </div>
            <?php if( $hero ): ?>
                <div class="swiper-container">
                    <div class="swiper-wrapper">
                    <?php 
                    $i = 0;
                    foreach ($hero['slider_content'] as $content_item):
                        $i++;
                        $image_desktop = $content_item['image_desktop'];
                        $image_mb = $content_item['image_mb'];
                        $heading_1 = $content_item['heading_1'];
                        $heading_2 = $content_item['heading_2'];
                        $sub_heading = $content_item['sub_heading'];
                    ?>
                        <?php if ($image_desktop): ?>
                            <div class="swiper-slide">
                                <div class="image-slider">
                                    <picture>
                                        <source media="(min-width:768px)" srcset="<?= $image_desktop["url"]; ?>">
                                        <source media="(max-width:767px)" srcset="<?= $image_mb["url"]; ?>">
                                        <img src="<?= $image_desktop["url"] ?>" class="hero-image" alt="hero-image" width="auto" height="auto" />
                                    </picture>
                                </div>
                                <div class="content-slider absolute">
                                    <div class="container">
                                        <?php if ($heading_1): ?>
                                            <!--div class="heading_1 letter-fade"><?= $heading_1 ?></div-->
                                            <div class="heading_1"><?= $heading_1 ?></div>
                                        <?php endif ?>
                                        <?php if ($heading_2): ?>
                                            <!--div class="heading_2 letter-fade fade-delay"><?= $heading_2 ?></div-->
                                            <div class="heading_2"><?= $heading_2 ?></div>
                                        <?php endif ?>
                                        <?php if ($sub_heading): ?>
                                            <!--div class="sub_heading letter-fade fade-delay2"><?= $sub_heading ?></div-->
                                            <div class="sub_heading"><?= $sub_heading ?></div>
                                        <?php endif ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif ?>
                    <?php endforeach ?>
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
            <?php endif; ?>
        </div>
        <!-- happenings -->
        <?php if( $happenings ): ?>
        <div class="home-happenings">
            <div class="home-firetp"> <?php ani_firework() ?></div>
            <div class="home-firebt"> <?php ani_firework() ?></div>
            <div class="container">
                <div class="home-heading"><span class="letter-fade"><?= $happenings['heading'] ?></span></div>
                <div class="home-subheading"><?= $happenings['sub_heading'] ?></div>
                <?php 
                $link = $happenings['more_button'];
                if( $link ): 
                    $link_url = $link['url'];
                    $link_title = $link['title'];
                    $link_target = $link['target'] ? $link['target'] : '_self';
                    ?>
                    <div class="home-btn-more"> 
                        <div class="button">
                            <a href="<?php echo esc_url( $link_url ); ?>" class="button-read-more" target="<?php echo esc_attr( $link_target ); ?>"><span><?php echo esc_html( $link_title ); ?></span></a>
                        </div>
                    </div>
                <?php endif ?>
            </div>
            <div class="happenings-slider splide cursor-hover listhapp">
                <div class="splide__track">
                    <div class="splide__list">
                        <?php 
                        $query_happening = new WP_Query( [
                            'post_type' => 'happening',
                            'meta_query' => [
                                'relation' => 'OR',
                                ['key' => 'showhide','value' => '1','compare' => '!='],
                                ['key' => 'showhide','value' => '1','compare' => 'NOT EXISTS']
                            ],
                            'posts_per_page' => 6
                        ] );
                        if ( $query_happening->have_posts() ) :
                            while ( $query_happening->have_posts() ) : $query_happening->the_post();?>
                                <div class="splide__slide">
                                    <div class="image-slider item-shop">
                                        <a href="<?php the_permalink(); ?>">
                                            <div class="feature-image animate_line">
                                                <span class="line line1"></span>
                                                <span class="line line2"></span>
                                                <span class="line line3"></span>
                                                <span class="line line4"></span>
                                                <div class="inner-feature">
                                                    <?php 
                                                        if(has_post_thumbnail( )){
                                                            echo '<div class="feature-image"><div class="ratio11">';
                                                            the_post_thumbnail('larger');
                                                            echo '</div></div>';
                                                        }else{
                                                            echo '<div class="no-image ratio11">'.pll__('No image','tsuen').'</div>';
                                                        }
                                                    ?> 
                                                     <div class="content-hover"></div>
                                                </div>
                                            </div>
                                            <?php the_title( '<h4>', '</h4>' ); ?>
                                        </a>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        <?php endif; ?>

                        <?php wp_reset_postdata(); ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endif ?>

        <!-- shoppings -->
        <?php if( $shoppings ): ?>
        <div class="home-shopping section2_3">
            <div class="home-firetp"> <?php ani_firework(2) ?></div>
            <div class="home-firebt"> <?php ani_firework(2) ?></div>
            <div class="container">
                <div class="home-heading"><span class="letter-fade"><?= $shoppings['heading2'] ?></span></div>
                <div class="home-subheading"><?= $shoppings['sub_heading2'] ?></div>
                <?php 
                $link = $shoppings['more_button2'];
                if( $link ): 
                    $link_url = $link['url'];
                    $link_title = $link['title'];
                    $link_target = $link['target'] ? $link['target'] : '_self';
                    ?>
                    <div class="home-btn-more"> 
                        <div class="button">
                            <a href="<?php echo esc_url( $link_url ); ?>" class="button-read-more"   target="<?php echo esc_attr( $link_target ); ?>"><span><?php echo esc_html( $link_title ); ?></span></a>
                        </div>
                    </div>
                <?php endif ?>
                <?php 
                $list_posts = $shoppings['select_post_to_slider2'];
                if( $list_posts ): ?>
                <div class="shoppings-slider slider2_3">
                    <div class="swiper-container">
                        <div class="swiper-wrapper">
                            <?php foreach( $list_posts as $post ):
                                setup_postdata($post); ?>
                                <div class="swiper-slide item item-shop">
                                    <?php include( locate_template( 'templates/inc/item_shop.php', false, false ) ); ?>
                                </div>
                            <?php endforeach; ?>
                            <?php wp_reset_postdata(); ?>
                        </div>
                    </div>
                    <div class="home-list-nav shoppings-navigation">
                        <div class="container arrow-button small">
                            <a class="arrow-prev-button arrow-button-all">
                                <?= pll__('PREV','tsuen') ?>
                                <div class="svg">
                                    <svg width="82" height="14" viewBox="0 0 82 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M82 13.0006L2 13L19.2615 1V8.49999" stroke="black"></path>
                                    </svg>
                                </div>
                            </a>
                            <div class="home-list-progressbar home-slider-progressbar"></div>
                            <a class="arrow-next-button arrow-button-all">
                                <?= pll__('NEXT','tsuen') ?>
                                <div class="svg">
                                    <svg width="82" height="14" viewBox="0 0 82 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M0 13.0006L80 13L62.7385 1V8.5" stroke="black"></path>
                                    </svg>

                                </div>
                            </a>
                        </div> 
                    </div> 
                </div>
                <?php endif ?>
            </div>
        </div>
        <?php endif ?>
        
        <!-- dinings -->
        <?php if( $dinings ): ?>
        <div class="home-dining section2_3">
            <div class="home-firetp"> <?php ani_firework() ?></div>
            <div class="home-firebt"> <?php ani_firework() ?></div>
            <div class="container">
                <div class="home-heading"><span class="letter-fade"><?= $dinings['heading3'] ?></span></div>
                <div class="home-subheading"><?= $dinings['sub_heading3'] ?></div>
                <?php 
                $link = $dinings['more_button3'];
                if( $link ): 
                    $link_url = $link['url'];
                    $link_title = $link['title'];
                    $link_target = $link['target'] ? $link['target'] : '_self';
                    ?>
                    <div class="home-btn-more"> 
                        <div class="button">
                            <a href="<?php echo esc_url( $link_url ); ?>" class="button-read-more"   target="<?php echo esc_attr( $link_target ); ?>"><span><?php echo esc_html( $link_title ); ?></span></a>
                        </div>
                    </div>
                <?php endif ?>
                <?php 
                $list_posts = $dinings['select_post_to_slider3'];
                if( $list_posts ): ?>
                <div class="dinings-slider slider2_3">
                    <div class="swiper-container">
                        <div class="swiper-wrapper">
                            <?php foreach( $list_posts as $post ):
                                setup_postdata($post); ?>
                                <div class="swiper-slide item item-shop">
                                    <?php include( locate_template( 'templates/inc/item_shop.php', false, false ) ); ?>
                                </div>
                            <?php endforeach; ?>
                            <?php wp_reset_postdata(); ?>
                        </div>
                    </div>
                    <div class="home-list-nav dinings-navigation">
                        <div class="container arrow-button small">
                            <a class="arrow-prev-button arrow-button-all">
                                <?= pll__('PREV','tsuen') ?>
                                <div class="svg">
                                    <svg width="82" height="14" viewBox="0 0 82 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M82 13.0006L2 13L19.2615 1V8.49999" stroke="black"></path>
                                    </svg>
                                </div>
                            </a>
                            <div class="home-list-progressbar home-slider-progressbar"></div>
                            <a class="arrow-next-button arrow-button-all">
                                <?= pll__('NEXT','tsuen') ?>
                                <div class="svg">
                                    <svg width="82" height="14" viewBox="0 0 82 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M0 13.0006L80 13L62.7385 1V8.5" stroke="black"></path>
                                    </svg>

                                </div>
                            </a>
                        </div> 
                    </div> 
                </div>
                <?php endif ?>
            </div>
        </div>
        <?php endif ?>

        <!-- instagram -->
        <?php if( $instagram ): ?>
        <div class="home-instagram">
            <div class="home-firetp"> <?php ani_firework(2) ?></div>
            <div class="home-firebt" data-offset="600"> <?php ani_firework() ?></div>
            <div class="container">
                <div class="ins-info">
                    <div>
                        <div class="home-heading"><span><?= $instagram['heading4'] ?></span></div>
                        <div class="ins-account"><?= $instagram['instagram_account'] ?></div>
                        <div class="home-subheading"><?= $instagram['sub_heading4'] ?></div>
                    </div>
                </div>
                <div class="images-ins">
                    <div class="instagram-slider">
                        <?php 
                        $short_code_instagram = $instagram['short_code_instagram'];
                        echo do_shortcode($short_code_instagram); ?>
                        <div class="home-list-nav shoppings-navigation">
                            <div class="container">
                                <div class="home-list-progressbar home-slider-progressbar"></div>
                            </div> 
                        </div> 
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <?php 
    if(get_field('popup_christmas')&&!isset($_COOKIE['hidepoup'])) :
        $popup_christmas = get_field('popup_christmas');
    ?>
        <div class="modal fade" tabindex="-1" role="dialog" id="popup_christmas">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="animate_line">                      
                        <span class="line line1"></span>
                        <span class="line line2"></span>
                        <span class="line line3"></span>
                        <span class="line line4"></span>                        
                        <div class="modal-body">
                            <a href="#" class="close">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M18.6777 1L1 18.6777M18.6777 18.6777L1 1" stroke="black" stroke-width="2"/>
                                </svg>
                            </a>
                            <div class="inner-content">
                                <div class="header-body desktop">
                                    <h5 class="modal-title"><?= $popup_christmas['title']; ?></h5>
                                </div>
                                <?php 
                                $image =$popup_christmas['image']; 
                                $size = 'full'; 
                                if( $image ) {
                                    echo '<div class="images">';
                                    echo wp_get_attachment_image( $image, $size );
                                    echo '</div>';
                                }
                                ?>
                                <div class="title-content">
                                    <h5 class="modal-title mobile"><?= $popup_christmas['title']; ?></h5>
                                    <div class="content">
                                        <?= $popup_christmas['content']; ?>
                                    </div>
                                </div>  
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <?php endwhile; endif;?>
<?php endif; ?>
<?php get_footer(); ?>