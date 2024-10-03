<?php

/**
 * Template Name: Movies
 */

$lang = get_current_lang();
get_header();
if (function_exists('get_field')):
?>
    <?php get_template_part('template-parts/banner', '', "" ) ?>
    <div class="movies_wr">
        <div class="container">
            <?php if (get_field('theatre_name')) : ?>
                <h2 class="theatre_name"><?= get_field('theatre_name') ?></h2>
            <?php endif; ?>
            <div class="inline-center content-center time-share">
                <?php
                    // Check icon_list.
                    if( have_rows('icon_list') ):
                        while( have_rows('icon_list') ) : the_row();
                            $icon = get_sub_field('icon');
                            $content = get_sub_field('content');
                            if( $icon && $content ){
                                echo '<div>';
                            ?>
                                <img src="<?= esc_url($icon['url']); ?>" alt="<?= esc_attr($icon['alt']); ?>" />
                            <?php
                                echo $content;
                                echo '</div>';
                            }
                        endwhile;
                    endif;
                ?>                
                <?php include( locate_template( 'templates/inc/block-share_items.php', false, false ) ); ?> 
            </div>
            <?php
            $number_show = (get_field('number_movies_show') )? get_field('number_movies_show') : 6 ;

            if($lang == 'en'){
                $api_movies_list = "https://api.cinema.com.hk/datafeed/ticketing/movies/MovieList.php?partyID=bw_shkl0414&lang=eng&movietype=showing&cinemaid=3&trailer=true";
                $api_movies_schedule = "https://api.cinema.com.hk/datafeed/ticketing/movies/MovieSchedule.php?partyID=bw_shkl0414&lang=eng&scheduletype=cinema&id=3&extra=true";
                $lang = 'en';
            }else{
                $api_movies_list = "https://api.cinema.com.hk/datafeed/ticketing/movies/MovieList.php?partyID=bw_shkl0414&lang=chi&movietype=showing&cinemaid=3&trailer=true";
                $api_movies_schedule = "https://api.cinema.com.hk/datafeed/ticketing/movies/MovieSchedule.php?partyID=bw_shkl0414&lang=chi&scheduletype=cinema&id=3&extra=true";
                $lang = 'tc';
            }
            $tsuen_movies = tsuen_movies_today($api_movies_list, $api_movies_schedule, $lang);
            if ($tsuen_movies){
            ?>
                <div class="movies_list">
                <?php if (count($tsuen_movies) > 0): $i = 0; ?>
                    <?php 
                    foreach ($tsuen_movies as $key => $value) { 
                        $i++;
                        $hide = '';
                        if( $i > $number_show ) $hide = " item-hide";
                    ?>
                        <div class="item<?= $hide ?>" >
                            <div class="inner-feature">
                                <?php 
                                    if($value['thumb']){
                                        echo '<div class="ratio"><img src="'. $value['thumb'] .'" alt="movies thumb" width="auto" height="auto"/></div>';
                                    }else{
                                        echo '<div class="no-image ratio11">'.pll__('No image','tsuen').'</div>';
                                    }
                                ?> 
                            </div>
                            <div class="mv-info">
                                <div class="mv-info-top">
                                    <div class="movies_name"><?= $value['name'] ?></div>
                                    <?php 
                                        $times = $value['time'];
                                        $class_mv = "movies_time";
                                        if (count($times) == 1){
                                            $class_mv = "movies_time one_item";
                                        }elseif (count($times) == 2) {
                                            $class_mv = "movies_time two_item";
                                        }
                                    ?>
                                    <ul class="<?= $class_mv ?>">
                                        <?php 
                                            foreach($times as $time){
                                        ?>
                                                <li><?= $time ?></li>
                                        <?php
                                            }
                                        ?>
                                    </ul>
                                </div>
                                <div class="action_list button"> 
                                    <a href="<?= esc_url($value['trailer']) ?>" class="button-read-more trailer" target="_blank"><span><?= pll__('Trailer','tsuen') ?></span></a>
                                    <a href="<?= esc_url($value['buy_ticket']) ?>" class="button-read-more buy-ticket" target="_blank"><span><?= pll__('Buy Ticket','tsuen') ?></span></a>
                                </div>
                            </div>
                        </div>

                    <?php } ?>
                <?php endif;?>
                </div>
                <?php
                    if((count($tsuen_movies) - $number_show) > 0){
                        echo '<div class="load_more"><button id="movies_more" data-show="'. $number_show .'" data-count="'. ceil((count($tsuen_movies) - $number_show) / $number_show) .'">'. pll__('Show More','tsuen') .'</button></div>';
                    }
                ?>
            <?php }else{
                echo '<h1>'.pll__('403 Forbidden API','tsuen').'</h1>';
            } ?>
        </div>
        <?php include( locate_template( 'templates/inc/floor-map.php', false, false ) ); ?>
    </div>
<?php endif;?>
<?php get_footer() ?>