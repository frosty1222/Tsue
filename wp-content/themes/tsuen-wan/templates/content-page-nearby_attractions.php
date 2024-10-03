<?php

/**
 * Template Name: Nearby Attractions Page
 *
 */
get_header() ;
$page_fields = get_fields();
$nearby_attractions = $page_fields["nearby_attractions"];
$nearby_photo_spots = $page_fields["nearby_photo_spots"];
?>
<main id="nearby_page" class="nearby">
	<header class="banner-section" style="background-image: url('<?php echo the_post_thumbnail_url( 'full' );?>')">
        <div class="container">
            <div class="banner-section_title">                
                <h3 class="section-header">
                    <span><?php the_title() ?></span>
                </h3>
            </div>
        </div>
        <div class="fire-nearby-top fire-mb"><?php ani_firework() ?></div>
    </header>
    <div class="nearby-main">
        <div class="banner_description"><?php the_content(); ?></div>
        <div class="fire-nearby-top fire-desktop"><?php ani_firework() ?></div>
        <div class="content-wrapper">
            <?php $index = 0; ?>
            <?php foreach($nearby_attractions as $nearby_attraction){ $index++; ?>
            <div class="nearby-section">
                <div class="fire-nearby">
                    <?php ani_firework() ?>
                    <?php 
                        if ($index % 2 != 0) {
                            echo '<div class="fire-mb-item">';
                            ani_firework(2);
                            echo '</div>';
                        }
                    ?>
                </div>
                <div class="logo-top"><?= $nearby_attraction["text_before"] ?></div>
                <div class="title title-mb"><h5><?= $nearby_attraction["name"] ?></h5></div>
                <div class="nearby-section_left">                    
                    <div class="nearby-section_img" style="background-image: url('<?= $nearby_attraction["image"]["url"] ?>')"></div>
                </div>
                <div class="nearby-section_right">
                    <div class="nearby-section_right_container">
                        <div class="title title-desktop"><?= $nearby_attraction["name"] ?></div>
                        <div class="content"><?= $nearby_attraction["content"] ?></div>
                        <?php
                            $location_tmp = '';
                            if ($nearby_attraction["location"] != '') {
                                $location_tmp = 'has-link'; 
                            } else {
                                $location_tmp = 'no-link';
                            }
                        ?>
                        <div class="location <?= $location_tmp ?>">                            
                            <?php 
                                if ($nearby_attraction["location"] != '') {
                            ?>
                                <a class="map-link" href="<?= $nearby_attraction["google_map"] ?>" target="_blank">
                                    <?= $nearby_attraction["location"] ?>
                                </a>
                                <?php 
                                } else {
                                ?>
                                <span>
                                    <?php echo '-'; ?>
                                </span>
                                <?php
                                } 
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
        <div class="fire-nearby-bottom"><?php  ani_firework() ?></div>
        <div class="nearby_photo">
            <div class="near_container">
                <h3 class="heading"><span><?= $page_fields["nearby_photo_spot_heading"] ?></span></h3>
            </div> 
            <div class="nearby_slides">
                <div class="nearby_slides_splide splide cursor-hover">
                    <div class="splide__track">
                        <div class="splide__list">
                            <?php foreach ( $nearby_photo_spots as $nearby_photo_spot ) {
                                $title = $nearby_photo_spot["name"];
                                $description = $nearby_photo_spot["content"];
                                $thumbnail = $nearby_photo_spot["image"];
                                $thumbnail_url = $thumbnail["url"];
                                ?>
                                <div class="splide__slide">
                                   <?php include( locate_template( 'templates/inc/item_slider.php', false, false ) ); ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>                       
        </div>
    </div>
</main>

<?php get_footer() ?>

