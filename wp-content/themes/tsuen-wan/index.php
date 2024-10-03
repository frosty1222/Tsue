<?php get_header();
?>
    <?php get_template_part('template-parts/banner', '', "" ) ?>
    <div class="privacy">
        <div class="wrap-page">
			<div class="draw-line right">
				<svg width="185" height="560" viewBox="0 0 185 560" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M0.5 0.5L184.5 106V454L0.5 559.5" stroke="#4193FF"/>
				</svg>
				<svg width="228" height="613" viewBox="0 0 228 613" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M0.5 0.5L227.5 131.5V481L0.5 612.5" stroke="#4193FF"/>
				</svg>
            </div>
            <div class="draw-line left">
				<svg width="170" height="550" viewBox="0 0 170 550" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M169.5 0.5L1 96V447.5L169.5 549" stroke="#FF9A3E"/>
				</svg>
				<svg width="119" height="490" viewBox="0 0 119 490" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M118.5 1L0.5 69.5V417.5L118.5 489" stroke="#FF9A3E"/>
				</svg>
            </div>
            <div class="container other-page">
                <div class="content">
                    <?php the_content();?>
                </div>
            </div>

        </div>
    </div>
<?php get_footer() ?>