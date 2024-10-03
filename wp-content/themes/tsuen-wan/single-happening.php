<?php
get_header() ;
?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<main class="main_content">
	<header class="banner-section-post">
		<div class="container">
			<div class="section_title-post">
				<h1 class="title-header letter-fade">
					<?php the_title() ?>
				</h1>
                <div class="inline-center content-center time-share">
                        <?php  if( get_field('event_time') && get_field('end_event_time') ): ?>
                            <div class="date--field inline-center">
                            <svg width="21" height="18" viewBox="0 0 21 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M0.59908 14.3998H16.279C16.4197 14.3998 16.5556 14.3506 16.6634 14.2609C16.814 14.1348 20.2495 11.1794 20.459 4.7998H3.61899C3.41031 10.5908 0.246401 13.3127 0.213521 13.3398C0.0207616 13.5027 -0.0501582 13.7687 0.0365616 14.0055C0.122681 14.2416 0.347121 14.3998 0.59908 14.3998Z" fill="#73D4D4"/>
                                <path d="M19.8786 1.2H16.8786V0.599999C16.8786 0.263999 16.6146 0 16.2786 0C15.9426 0 15.6786 0.263999 15.6786 0.599999V1.2H12.6387V0.599999C12.6387 0.263999 12.3747 0 12.0387 0C11.7027 0 11.4387 0.263999 11.4387 0.599999V1.2H8.43866V0.599999C8.43866 0.263999 8.17466 0 7.83866 0C7.50266 0 7.23866 0.263999 7.23866 0.599999V1.2H4.23867C3.90267 1.2 3.63867 1.464 3.63867 1.8V3.59999H20.4786V1.8C20.4786 1.464 20.2146 1.2 19.8786 1.2Z" fill="#73D4D4"/>
                                <path d="M17.4335 15.1812C17.1077 15.4525 16.6988 15.6001 16.2786 15.6001H3.63867V17.4001C3.63867 17.7318 3.90703 18.0001 4.23867 18.0001H19.8786C20.2103 18.0001 20.4786 17.7318 20.4786 17.4001V10.6743C19.3218 13.5123 17.7076 14.9518 17.4335 15.1812Z" fill="#73D4D4"/>
                            </svg>
                                <?= get_field('event_time') ?> - <?= get_field('end_event_time') ?>
                            </div>
                        <?php endif ?>    

                        <?php include( locate_template( 'templates/inc/block-share_items.php', false, false ) ); ?> 
			    </div>
			</div>
		</div>
	</header>
	<div class="list-main">
		<div class="container relative">
			<div class="happi-main_content main_content text-center">
                <?php  $image = get_field('poster');  $size = 'full'; ?>
                <?php  if( $image ): ?>
                <div class="postter">
                    <div class="firetop">
                        <?php  ani_firework() ?>
                    </div>
                    <div class="firecenter">
                        <?php  ani_firework() ?>
                    </div>
                    <?php echo wp_get_attachment_image( $image, $size );?>
                    <div class="firebottom">
                        <?php  ani_firework() ?>
                    </div>
                </div>
                <?php endif ?>
                <?php 
                    $other_poster = get_field('other_poster');
                    if( $other_poster ) {
                        foreach( $other_poster as $poster ) {
                            echo '<div class="postter relative">';
                            $image = $poster['image'];
                            echo '<div class="firecenter">';
                                ani_firework();
                            echo '</div>';
                            echo '<div class="firebottom">';
                                ani_firework();
                            echo '</div>';
                            echo wp_get_attachment_image( $image, 'full' );
                            echo '</div>';
                        }
                    }               
                ?>
                <?php if(get_the_content()) : ?>
                    <div class="contentpage">
                        <div class="post_content">
                            <?php the_content()?>
                        </div>
                        <a href="#" class="expand" data-expaned="<?= pll__( 'Show Less', 'tsuen' ) ?>" data-closed="<?= pll__( 'View More', 'tsuen' ) ?>"><?= pll__( 'View More', 'tsuen' ) ?></a>
                    </div>
                <?php endif; ?>
                <?php if(get_field('list_of_participating_merchants') || get_field('coupon_acceptance_list') ): ?>
                <div class="groupbutton">
                    <?php $groupbutton = ['title_button_participating_merchants'=>'list_of_participating_merchants','title_button_coupon_acceptance'=>'coupon_acceptance_list'] ;
                        foreach($groupbutton as $key=>$button) :
                            $link = get_field($button);
                            if( $link ): 
                                $link_url = $link['url'];
                                $link_title =get_field($key)?get_field($key): $link['title'];
                                ?>
                                <a class="button" href="<?php echo esc_url( $link_url ); ?>"><?php echo esc_html( $link_title ); ?></a>
                            <?php endif; ?>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
                <?php 
                    $linkfile = get_field('terms_and_conditions_file');
                    $link = get_field('terms_and_conditions');
                    if( $linkfile ): 
                        $link_url = $linkfile['url'];
                        $link_title = $linkfile['title'];
                        ?>
                        <div class="link-codition">
                            <a class="button" href="<?php echo esc_url( $link_url ); ?>"><?php echo esc_html( $link_title ); ?></a>
                        </div>
                   <?php else :
                    if( $link ): 
                        $link_url = $link['url'];
                        $link_title = $link['title'];
                        $link_target = $link['target'] ? $link['target'] : '_self';
                        ?>
                        <div class="link-codition">
                            <a class="button" href="<?php echo esc_url( $link_url ); ?>" target="<?php echo esc_attr( $link_target ); ?>"><?php echo esc_html( $link_title ); ?></a>
                        </div>
                   <?php endif; ?>
                   <?php endif; ?>
			</div>            
            <div class="firemobile mobile">
                <?php  ani_firework() ?>
            </div>
		</div>
	</div>

</main>

<?php endwhile;  ?>
<?php endif; ?>

<?php get_footer(); ?>