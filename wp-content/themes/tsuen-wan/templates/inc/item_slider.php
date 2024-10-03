<div class="image-slider item-shop">
    <div class="feature-image animate_line">
        <span class="line line1"></span>
        <span class="line line2"></span>
        <span class="line line3"></span>
        <span class="line line4"></span>
        <div class="inner-feature">
            <?php if($thumbnail) { ?>
                <div class="ratio11">
                    <div class="img-slider">
                        <a href="<?= $thumbnail_url ?>" target='_blank' class='img-newtab'>
                            <img src="<?= $thumbnail_url ?>" class="hero-image" alt="hero-image" width="auto" height="auto" />
                        </a>
                    </div>
                </div>
            <?php } else { ?>
                <div class="no-image ratio11"><?php  esc_html__('No image') ?> </div>
            <?php } ?>

            <?php  if( $title || $description ):?>
                <div class="content-slider-text">
                    <div class="text-wrapper">
                        <?php if($title): ?><div class="heading_1"><?= $title ?></div><?php endif ?>
                        <button class="btn-mb">
                            <svg width="18" height="19" viewBox="0 0 18 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect y="8.75" width="18" height="1.5" fill="white"/>
                                <rect x="9.75" y="0.5" width="18" height="1.5" transform="rotate(90 9.75 0.5)" fill="white"/>
                            </svg>
                        </button>
                        <?php if($description): ?><div class="heading_2"><?= $description ?></div><?php endif ?>
                    </div>
                    
                </div>
            <?php endif ?>
        </div>        
    </div>
</div>
