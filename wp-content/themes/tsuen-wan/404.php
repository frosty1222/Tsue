<?php get_header();
?>
    
    <div class="privacy">
        <div class="wrap-page">
            <div class="draw-line bottom">
                <svg width="66" height="20" viewBox="0 0 66 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M65.5 19L22 1.5L0.5 19" stroke="#FF9A3E"/>
                </svg>
                <svg width="89" height="29" viewBox="0 0 89 29" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M88 28L87 22L34.5 1.5L0.5 28" stroke="#FF9A3E"/>
                </svg>
            </div>
            <div class="container error-page">
                <h1><?php pll_e('Page not found')?></h1>
                <p><?php pll_e("Sorry, we can't find the page you're looking for.")?></p>
                <div class="button">
                    <a href="<?php echo get_home_url() ?>" class="button-read-more">
						<span><?php pll_e('Back to Home')?></span>
					</a>
                </div>
            </div>

        </div>
    </div>
<?php get_footer() ?>