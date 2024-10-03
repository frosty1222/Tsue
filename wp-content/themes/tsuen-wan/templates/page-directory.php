<?php
/**
 * Template Name: Directory
 *
 */
get_header() ;
?>
<main id="directory_page" class="directory page_main_content">
    <header class="banner-section" style="background-image: url('<?php echo the_post_thumbnail_url( 'full' );?>')">
		<div class="container">
			<div class="banner-section_title">
				<h1 class="title-header">
					<?php the_title() ?>
				</h1>
			</div>
		</div>
	</header>
    <div class="visit-page">
        <div class="container">
            <div id="visit-page-content" class="shop-main_content main_content text-center">
                <div class="contentpage">
					<?php the_content()?>
                    <?php if(get_field('main_address')): ?>
                        <p class="main-address">
                            <span class="icon-address">
                                <svg width="18" height="26" viewBox="0 0 18 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9 12.375C8.1712 12.375 7.37634 12.0458 6.79029 11.4597C6.20424 10.8737 5.875 10.0788 5.875 9.25C5.875 8.4212 6.20424 7.62634 6.79029 7.04029C7.37634 6.45424 8.1712 6.125 9 6.125C9.8288 6.125 10.6237 6.45424 11.2097 7.04029C11.7958 7.62634 12.125 8.4212 12.125 9.25C12.125 9.66038 12.0442 10.0667 11.8871 10.4459C11.7301 10.825 11.4999 11.1695 11.2097 11.4597C10.9195 11.7499 10.575 11.9801 10.1959 12.1371C9.81674 12.2942 9.41038 12.375 9 12.375ZM9 0.5C6.67936 0.5 4.45376 1.42187 2.81282 3.06282C1.17187 4.70376 0.25 6.92936 0.25 9.25C0.25 15.8125 9 25.5 9 25.5C9 25.5 17.75 15.8125 17.75 9.25C17.75 6.92936 16.8281 4.70376 15.1872 3.06282C13.5462 1.42187 11.3206 0.5 9 0.5Z" fill="#73D4D4"/>
                                </svg>
                            </span>
                            <span class="text-address"><?= esc_html(get_field('main_address')); ?></p></span>
                    <?php endif; ?>
				</div>
                <?php if( have_rows('visit_list') ): ?>
                    <div class="visit-menu">
                        <ul class="visit-menu-list">
                            <?php $i = 0;while( have_rows('visit_list') ) : the_row(); ?>
                                <?php if(get_sub_field('visit_title')): ?>
                                    <li class="visit-menu-item <?php if($i == 0): ?>active<?php endif; ?>" data-list="<?= str_replace(' ','-',strtolower(get_sub_field('visit_title'))) ?>">
                                        <?php $i++;the_sub_field('visit_title'); ?>
                                    </li>
                                <?php endif; ?>
                            <?php endwhile; ?>
                        </ul>
                    </div>

                    <div class="visit-list">
                        <div class="visit-list-content">
                            <?php $k = 0;while( have_rows('visit_list') ) : the_row(); ?>
                            
                                <?php $row_index = get_row_index(); ?>
                                <?php $listItemsId = str_replace(' ','-',strtolower(get_sub_field('visit_title'))); ?>
                                <?php if( have_rows('visit_list_items') ): ?>
                                    <?php while( have_rows('visit_list_items') ) : the_row(); ?>
                                        <?php
                                            /* Count items */
                                            $i = 0;
                                            if(have_rows('visit_item')){
                                                while( have_rows('visit_item') ) : the_row();
                                                    $i++;
                                                endwhile;
                                            }
                                            $countClass = '';
                                            if($i <= 1) $countClass = 'no-content';
                                        ?>
                                        <div class="visit-list-items <?= $listItemsId; ?> <?= $countClass; ?> <?php if($k == 0): ?>active<?php endif; ?> <?php if($i > 8): ?>has-expand<?php endif; ?>" id="<?= $listItemsId; ?>">
                                            <?php if(get_sub_field('visit_list_title')): ?>
                                                <p class="visit-list-title"><?php the_sub_field('visit_list_title') ?></p>
                                            <?php endif; ?>
                                            
                                            <?php
                                                $j = 0;
                                                $columnsChot = ceil($i/2);
                                            ?>
                                            <?php if(have_rows('visit_item')): ?>
                                                <?php while( have_rows('visit_item') ) : the_row(); ?>
                                                    <?php if($j == 0 || $j == $columnsChot): ?><div class="visit-list-column"><?php endif; ?>
                                                    <?php $j++; ?>
                                                    <div class="visit-list-item">
                                                        <?php if(get_sub_field('visit_item_number')): ?>
                                                            <span class="visit-list-item-number">
                                                                <?php the_sub_field('visit_item_number'); ?>
                                                            </span>
                                                        <?php endif; ?>
                                                        <?php if(get_sub_field('visit_item_text')): ?>
                                                            <span class="visit-list-item-text">
                                                                <?php if($row_index == 5): ?>
                                                                    <?php $visit_item_text_array = explode(' ', get_sub_field('visit_item_text')); ?>
                                                                    <?php foreach ($visit_item_text_array as $x): ?>
                                                                        <?php echo '<a>'.$x.'</a>'; ?>
                                                                    <?php endforeach; ?>
                                                                <?php else: ?>
                                                                    <?php the_sub_field('visit_item_text'); ?>
                                                                <?php endif; ?>
                                                            </span>
                                                        <?php endif; ?>
                                                    </div>
                                                    <?php //Add a blank div ?>
                                                    <?php if ($j == $i && $j > 2 && $j % 2 == 1): ?>
                                                        <div class="visit-list-item no-content"></div>
                                                    <?php endif; ?>
                                                    <?php if($j == $columnsChot || $j == $i): ?></div><?php endif; ?>
                                                <?php endwhile; ?>
                                                <?php if($i > 8): ?>
                                                    <a href="#" class="expand" data-expaned="<?= __('Show Less'); ?>" data-closed="<?= __('Show More'); ?>"><?= __('Show More'); ?></a>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </div>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <div class="visit-list-items no-content <?= $listItemsId; ?> <?php if($k == 0): ?>active<?php endif; ?>" id="<?= $listItemsId; ?>"></div>
                                <?php endif; ?>
                                <?php $k++; ?>
                            <?php endwhile; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Main image -->
                <?php if(get_field('main_image')): ?>
                    <div class="visit-image-container" id="myViewport">
                        <div class="image-buttons">
                            <a href="javascript:;" id="zoom_up" class="btn btn-danger">+</a>
                            <a href="javascript:;" id="zoom_down" class="btn btn-primary">-</a>
                        </div>
                        <div class="image-content">
                            <img id="zoomImage" src="<?php the_field('main_image'); ?>" />
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <div class="getting-firework-1" data-offset="900"><?php ani_firework(); ?></div>
        </div>
        <div class="getting-firework-2"><?php ani_firework(); ?></div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            jcWheelZoom = WZoom.create('#zoomImage', {
                maxScale: 4,
                minScale: 1,
                speed: 1.2,
                alignContent: 'center',
                prepare: function (scale, correct_x, correct_y) {
                    // do smth when image prepared
                },
                rescale: function (scale, correct_x, correct_y, min_scale) {
                    // do smth when image rescaled
                }
            });

            window.addEventListener('resize', function () {
                jcWheelZoom.prepare();
            });

            document.getElementById('zoom_up').addEventListener('click', function () {
                jcWheelZoom.zoomUp();
            });

            document.getElementById('zoom_down').addEventListener('click', function () {
                jcWheelZoom.zoomDown();
            });

            window.addEventListener('resize', function () {
                jcWheelZoom.prepare();
            })
        });
    </script>
</main>
<?php get_footer(); ?>