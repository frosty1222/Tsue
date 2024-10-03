<a href="<?= the_permalink() ?>">
    <div class="feature-image animate_line">
        <span class="line line1"></span>
        <span class="line line2"></span>
        <span class="line line3"></span>
        <span class="line line4"></span>
        <div class="inner-feature">
            <?= get_field('is_new') ? '<span class="feature">'.esc_html__( 'New', 'tsuen').'</span> ': '';?>
            <?php $image_thumbnail = get_field('thumbnail'); ?>
            <?php if(has_post_thumbnail( )): ?>
                <div class="ratio11">
                    <?php the_post_thumbnail(); ?>
                </div>
            <?php else: ?>
                <?php if (!empty( $image_thumbnail )): ?>
                    <div class="ratio11">
                        <?= wp_get_attachment_image( $image_thumbnail, 'full' ); ?>
                    </div>
                <?php else: ?>
                    <div class="no-image ratio11">'<?= esc_html('No image') ?> '</div>
                <?php endif; ?>
            <?php endif; ?>
            <div class="content-hover">
                <?php if(get_field('list_description')) :?>
                    <div class="list-bottom">
                        <div class="rebion"> <h5 class="for-mb"><?php the_title(); ?></h5><?= get_field('list_description')?> </div>
                        <?php
                        $privileges = get_the_terms( get_the_id() , 'privileges', 'string');
                        if(!empty($privileges)) : ?>
                            <ul class="listmenu list-icon">
                                <?php foreach ( $privileges as $privilege ) : ?>
                                    <?php
                                        $image_id = get_field('icon_tax',$privilege);
                                        if($image_id){
                                            echo '<li>';
                                                $image_url = wp_get_attachment_image_url( $image_id, 'full' );
                                                $ext = pathinfo($image_url, PATHINFO_EXTENSION);
                                                if($ext=='svg'){
                                                    $file = file_get_contents($image_url, true);
                                                }else{
                                                    $file = wp_get_attachment_image($image_id,'full');
                                                }
                                                echo  $file ;
                                            echo '</li>';
                                        }
                                    ?>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif;?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="content-for-mobile" style="display: none">
                <h5><?php the_title(); ?></h5>
                <?php if(get_field('list_description') != ''): ?>
                    <div class="shop-name">
                        <span class="icon">
                            <svg width="12" height="18" viewBox="0 0 12 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M5.99984 8.58342C5.4473 8.58342 4.9174 8.36392 4.5267 7.97322C4.136 7.58252 3.9165 7.05262 3.9165 6.50008C3.9165 5.94755 4.136 5.41764 4.5267 5.02694C4.9174 4.63624 5.4473 4.41675 5.99984 4.41675C6.55237 4.41675 7.08228 4.63624 7.47298 5.02694C7.86368 5.41764 8.08317 5.94755 8.08317 6.50008C8.08317 6.77367 8.02928 7.04458 7.92459 7.29734C7.81989 7.5501 7.66643 7.77977 7.47298 7.97322C7.27952 8.16668 7.04986 8.32013 6.79709 8.42483C6.54433 8.52953 6.27342 8.58342 5.99984 8.58342ZM5.99984 0.666748C4.45274 0.666748 2.96901 1.28133 1.87505 2.37529C0.781085 3.46925 0.166504 4.95299 0.166504 6.50008C0.166504 10.8751 5.99984 17.3334 5.99984 17.3334C5.99984 17.3334 11.8332 10.8751 11.8332 6.50008C11.8332 4.95299 11.2186 3.46925 10.1246 2.37529C9.03066 1.28133 7.54693 0.666748 5.99984 0.666748Z" fill="#73D4D4"/>
                            </svg>
                        </span>
                        <span><?= esc_html(get_field('list_description')); ?></span>
                    </div>
                <?php endif; ?>

                <?php  if( have_rows('opening_hours') ): ?>
                    <?php $rowCount = count( get_field('opening_hours') ); //GET THE COUNT ?>
                    <?php $i = 1; ?>
                    <div class="oppen-hour">
                        <span class="icon">
                            <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M7 0C3.13418 0 0 3.13418 0 7C0 10.8658 3.13418 14 7 14C10.8658 14 14 10.8658 14 7C14 3.13418 10.8658 0 7 0ZM10.2308 8.07692H7C6.85719 8.07692 6.72023 8.02019 6.61925 7.91921C6.51827 7.81823 6.46154 7.68127 6.46154 7.53846V2.69231C6.46154 2.5495 6.51827 2.41254 6.61925 2.31156C6.72023 2.21058 6.85719 2.15385 7 2.15385C7.14281 2.15385 7.27977 2.21058 7.38075 2.31156C7.48173 2.41254 7.53846 2.5495 7.53846 2.69231V7H10.2308C10.3736 7 10.5105 7.05673 10.6115 7.15771C10.7125 7.25869 10.7692 7.39565 10.7692 7.53846C10.7692 7.68127 10.7125 7.81823 10.6115 7.91921C10.5105 8.02019 10.3736 8.07692 10.2308 8.07692Z" fill="#73D4D4"/>
                            </svg>
                        </span>
                        <span>
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
                    <?php if(get_field('open_hours') != ''): ?>
                        <div class="oppen-hour">
                            <span class="icon">
                                <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M7 0C3.13418 0 0 3.13418 0 7C0 10.8658 3.13418 14 7 14C10.8658 14 14 10.8658 14 7C14 3.13418 10.8658 0 7 0ZM10.2308 8.07692H7C6.85719 8.07692 6.72023 8.02019 6.61925 7.91921C6.51827 7.81823 6.46154 7.68127 6.46154 7.53846V2.69231C6.46154 2.5495 6.51827 2.41254 6.61925 2.31156C6.72023 2.21058 6.85719 2.15385 7 2.15385C7.14281 2.15385 7.27977 2.21058 7.38075 2.31156C7.48173 2.41254 7.53846 2.5495 7.53846 2.69231V7H10.2308C10.3736 7 10.5105 7.05673 10.6115 7.15771C10.7125 7.25869 10.7692 7.39565 10.7692 7.53846C10.7692 7.68127 10.7125 7.81823 10.6115 7.91921C10.5105 8.02019 10.3736 8.07692 10.2308 8.07692Z" fill="#73D4D4"/>
                                </svg>
                            </span>
                            <span><?= esc_html(get_field('open_hours')); ?></span>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

                <?php if(get_field('phone') != ''): ?>
                    <div class="phone-number">
                        <span class="icon">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12.4496 15.9696C9.32577 15.9655 6.33105 14.7228 4.12217 12.5139C1.91328 10.305 0.670553 7.31029 0.666504 4.18646C0.666504 3.253 1.03732 2.35777 1.69738 1.69771C2.35744 1.03765 3.25267 0.666835 4.18613 0.666835C4.3838 0.66533 4.58113 0.683269 4.77529 0.720395C4.96298 0.74817 5.1475 0.794299 5.32618 0.85812C5.45186 0.902214 5.56384 0.978348 5.65107 1.07899C5.73829 1.17964 5.79774 1.30131 5.82352 1.43197L6.87176 6.02279C6.90001 6.1474 6.89661 6.2771 6.86186 6.40006C6.82711 6.52302 6.76212 6.63532 6.67282 6.72671C6.57336 6.83383 6.5657 6.84148 5.62459 7.33117C6.37824 8.98452 7.70055 10.3123 9.3508 11.0727C9.84814 10.1239 9.85579 10.1163 9.96291 10.0168C10.0543 9.9275 10.1666 9.86251 10.2896 9.82776C10.4125 9.79301 10.5422 9.78961 10.6668 9.81786L15.2577 10.8661C15.3841 10.8954 15.5011 10.9565 15.5974 11.0435C15.6938 11.1305 15.7665 11.2406 15.8085 11.3634C15.8731 11.545 15.9218 11.7319 15.9539 11.922C15.9847 12.1143 16.0001 12.3087 15.9998 12.5035C15.9857 13.433 15.6045 14.3191 14.9393 14.9685C14.2742 15.6179 13.3791 15.9777 12.4496 15.9696Z" fill="#73D4D4"/>
                        </svg>
                        </span>
                        <span><?= esc_html(get_field('phone')); ?></span>
                    </div>
                <?php endif; ?>
                <?php
                $privileges = get_the_terms( get_the_id() , 'privileges', 'string');
                if(!empty($privileges)) : ?>
                    <ul class="listmenu list-icon">
                        <?php foreach ( $privileges as $privilege ): ?>
                            <?php
                                $image_id = get_field('icon_tax',$privilege);
                                if($image_id){
                                    echo '<li>';
                                        $image_url = wp_get_attachment_image_url( $image_id, 'full' );
                                        $ext = pathinfo($image_url, PATHINFO_EXTENSION);
                                        if($ext=='svg'){
                                            $file = file_get_contents($image_url, true);
                                        }else{
                                            $file = wp_get_attachment_image($image_id,'full');
                                        }
                                        echo  $file ;
                                    echo '</li>';
                                }
                            ?>
                        <?php endforeach; ?>
                    </ul>
                <?php endif;?>
            </div>
        </div>
    </div>
    <h5><?php the_title(); ?></h5>
</a>