<?php

/**
 * Template Name: About Us
 */
?>
<?php get_header();
?>
    <?php get_template_part('template-parts/banner', '', "" ) ?>
    <div class="about-us">
        <div class="wrap-page">
            <div class="draw-line left center">
                <svg width="329" height="608" viewBox="0 0 329 608" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M150.5 1L0.5 84.5V431.5L299.5 606.5L329.5 589.5" stroke="#9EC8FF"/>
                </svg>
                <svg width="102" height="469" viewBox="0 0 102 469" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M101 0.5L1 59.5V407L101 468" stroke="#9EC8FF"/>
                </svg>
            </div>
            <div class="draw-line left mobile center">
                <svg width="110" height="104" viewBox="0 0 110 104" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M1 0.5L9.5 69L87.5 102.5L109 86.5" stroke="#9EC8FF"/>
                </svg>  
                <svg width="46" height="133" viewBox="0 0 46 133" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M45.5 1L1 33L11 117.5L45.5 132.5" stroke="#9EC8FF"/>
                </svg>

            </div>
            <div class="container">
                <div class="top-container">
                    <?php 
                        if(get_field("subtitle")){ ?>
                            <h3 class="subtitle">
                                <?php the_field("subtitle") ?>
                            </h3>
                        <?php
                        }

                        if(get_field("title")){ ?>
                            <h2 class="title"><?php the_field("title") ?></h2>
                        <?php
                        }
                    ?>
                </div>
                
                <div class="content-container">
                    <?php 
                    if(get_field("content")){ ?>
                        <div class="content">
                            <?php the_field("content")?>
                        </div>
                    <?php
                    }
                    ?>

                    <?php 
                    if(get_field("information")){ 
                        $items = get_field("information");
                        ?>
                        <div class="list-item">
                            <?php 
                                if(get_field("information_title")){ ?>
                                    <h3 class="list-item-title">
                                        <?php the_field("information_title")?>
                                    </h3>
                                <?php
                                }
                            ?>
                            <?php 
                                foreach($items as $item){ 
                                    $image = $item["icon"];
                                  
                                    $link = $item["link"];
                                    if( $link ){
                                        $link_url = $link['url'];
                                        $link_title = $link['title'];
                                        $link_target = $link['target'] ? $link['target'] : '_self';
                                        ?>
                                        <a class="link" href="<?php echo esc_url( $link_url ); ?>" target="<?php echo esc_attr( $link_target ); ?>">

                                            <img src="<?php echo $image["url"]?>" alt="<?php echo $image["title"]?>">
                                        
                                            <span><?php echo esc_html( $link_title ); ?></span>

                                        </a>
                                    <?php } ?>
                                    
                                    
                                <?php
                                }
                            ?>
                        </div>
                    <?php
                    }
                    ?>


                    <?php 
                    if(get_field("leasing_enquiry")){ 
                        $items = get_field("leasing_enquiry");
                        ?>
                        <div class="list-item">
                            <?php 
                                if(get_field("leasing_enquiry_title")){ ?>
                                    <h3 class="list-item-title ">
                                        <?php the_field("leasing_enquiry_title")?>
                                    </h3>
                                <?php
                                }
                            ?>
                            <?php 
                                foreach($items as $item){ 
                                    $image = $item["icon"];
                                  
                                    $link = $item["link"];
                                    if( $link ){
                                        $link_url = $link['url'];
                                        $link_title = $link['title'];
                                        $link_target = $link['target'] ? $link['target'] : '_self';
                                        ?>
                                        <a class="link" href="<?php echo esc_url( $link_url ); ?>" target="<?php echo esc_attr( $link_target ); ?>">

                                            <img src="<?php echo $image["url"]?>" alt="<?php echo $image["title"]?>">
                                        
                                            <span><?php echo esc_html( $link_title ); ?></span>

                                        </a>
                                    <?php } ?>
                                    
                                    
                                <?php
                                }
                            ?>
                        </div>
                    <?php
                    }
                    ?>

                </div>
            </div>

            <div class="draw-line bottom">
                    
                <svg width="286" height="99" viewBox="0 0 286 99" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M285 98.5L282 71.5L135 1.5L1 92.5V98.5" stroke="#9EC8FF"/>
                </svg>

                <svg width="473" height="144" viewBox="0 0 473 144" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M472 143L358.5 1.5L39.5 48.5L1 143" stroke="#9EC8FF"/>
                </svg>
            </div>
        </div>
    </div>
<?php get_footer() ?>