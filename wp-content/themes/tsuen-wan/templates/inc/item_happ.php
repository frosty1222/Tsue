<div class="item animate_line item-happ col-3">
    <a href="<?= the_permalink() ?>">
        <div class="feature-image">
            <?php 
                if(has_post_thumbnail( )){
                    echo '<div class="ratio11">';
                    the_post_thumbnail('larger');
                    echo '</div>';
                }else{
                    echo '<div class="no-image ratio11">'.esc_html('No image').'</div>';
                }
            ?> 
        </div>
    </a>
    <h5><?php the_title(); ?></h5>
</div>