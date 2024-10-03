<?php

/**
 * Template Name: Disclaimer
 */
?>
<?php get_header();
?>
    <?php get_template_part('template-parts/banner', '', "" ) ?>
    <div class="disclaimer">
        <div class="wrap-page">
            <div class="draw-line left bottom-fade">
                
                <svg width="300" height="109" viewBox="0 0 300 109" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M1 1L186 107.5L300.5 41.5" stroke="#4193FF"/>
                </svg>

            </div>
            <div class="draw-line left mobile center">
                <svg width="36" height="76" viewBox="0 0 36 76" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0.5 0.5V55L36 75.5" stroke="#4193FF"/>
                </svg>
                <svg width="48" height="53" viewBox="0 0 48 53" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M1 0V24.5L48 52" stroke="#4193FF"/>
                </svg>
            </div>
            <div class="container other-page">
                <div class="content">
                    <?php the_content();?>
                </div>
            </div>

            <div class="draw-line bottom">
                <svg width="142" height="43" viewBox="0 0 142 43" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M141 42L71 1.5L1 42" stroke="#FF9A3E"/>
                </svg>
                <svg width="307" height="145" viewBox="0 0 307 145" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M306 145V127.5L88.5 1.5L0 52.5" stroke="#FF9A3E"/>
                </svg>
            </div>
            <div class="draw-line bottom mobile">
                <svg width="120" height="41" viewBox="0 0 120 41" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M119 40.5L116.5 27L52 1L0.5 40.5" stroke="#FF9A3E"/>
                </svg>
                <svg width="103" height="52" viewBox="0 0 103 52" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M102.5 51L98 22.5L46 1.5L1 36.5L3 51" stroke="#FF9A3E"/>
                </svg>
            </div>
        </div>
    </div>
<?php get_footer() ?>