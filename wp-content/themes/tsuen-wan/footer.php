<?php

/**
 * @package WordPress
 * @subpackage Classic_Theme
 */
global $lang;
$lang = get_current_lang();
$site_fields = get_fields( "options" );
$shkp = $site_fields["shkp"];
$copyright = $site_fields["copyright"];
$footer_point_member = $site_fields["footer_point_member"];
$current_lang_code = get_current_lang();
$social_media = $site_fields["social_media"];
$translations = pll_the_languages(array('raw'=>1));
$translations_menu = array($translations["en"], $translations["sc"], $translations["tc"]);
?>
<footer id="footer">
    <div class="container">
        <div class="flex top_menu">
            <div class="shkp_section top_menu_section">
                <img class="shkp_image" src="<?php echo $shkp["image"]["url"] ?>"
                         alt="<?php echo $shkp["image"]["alt"] ?>">
                <div class="group_list">
                    <div class="btn-group dropdown">
                        <a class="btn dropdown-toggle" href="#" role="button" id="shkp_drop_down"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="select_text"><?php echo $shkp["others_shkp_destinations"][ $lang ] ?></span>
                            <span class="select_arrow"></span>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="shkp_drop_down">
                            <div class="dropdown_menu_container">
                                <?php
                                if ( $shkp_group = $shkp["shkp_group"] ) {
                                    foreach ( $shkp["shkp_group"] as $shkp_group ) {
                                        $group_type = '';
                                        if ( $lang == "en" ) {
                                            $group_type = $shkp_group["type"];
                                        } else {
                                            $group_type = $shkp_group[ "type_" . $lang ];
                                        }
                                        ?>
                                        <div class="group_type"><?php echo $group_type ?></div>
                                        <?php if ( $shkp_group_links = $shkp_group["links"] ) {
                                            foreach ( $shkp_group_links as $shkp_group_link ) {
                                                ?>
                                                <a class="dropdown-item"
                                                    href="<?php echo $shkp_group_link["link"] ?>" target="_blank"><?php echo $shkp_group_link["link_text"][ $lang ] ?></a>
                                            <?php }
                                        } ?>
                                    <?php }
                                } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer_menu_section top_menu_section">
                <?php
                    wp_nav_menu(
                        [
                            'theme_location' => 'menu-3',
                            'menu_id' => 'menu-footer-1',
                            'menu_class' => 'footer_menu'
                        ]
                    );
                    wp_nav_menu(
                        [
                            'theme_location' => 'menu-4',
                            'menu_id' => 'menu-footer-2',
                            'menu_class' => 'footer_menu'
                        ]
                    );
                    wp_nav_menu(
                        [
                            'theme_location' => 'menu-footer-3',
                            'menu_id' => 'menu-footer-3',
                            'menu_class' => 'footer_menu'
                        ]
                    );
                ?>

            </div>
            <div class="qr_and_social_section top_menu_section">  
            <div class="social_section">
                    <?php 
                        $heading_social = get_theme_mod('social_heading');
                        $facebook_image = get_theme_mod('facebook_image');
                        $facebook_link = get_theme_mod('facebook_link');
                        $instagram_image = get_theme_mod('instagram_image');
                        $instagram_link = get_theme_mod('instagram_link');
                        $xiaohongshu_image = get_theme_mod('xiaohongshu_image');
                        $xiaohongshu_link = get_theme_mod('xiaohongshu_link');
                        $weibo_image = get_theme_mod('weibo_image');
                        $weibo_link = get_theme_mod('weibo_link');
                        $wechat_image = get_theme_mod('wechat_image');
                        $wechat_link = get_theme_mod('wechat_link');
                        $meta_image = get_theme_mod('meta_image');
                        $meta_link = get_theme_mod('meta_link');
                        $dianping_image = get_theme_mod('dianping_image');
                        $dianping_link = get_theme_mod('dianping_link');
                    ?>
                    <?php if($heading_social): ?>
                        <h4><?php pll_e($heading_social); ?></h4>
                    <?php endif; ?>
                    <div class="social_media_icons">
                        <div class="social_media_item">
                            <?php if (!empty($facebook_link)): ?>
                                <a href="<?php echo $facebook_link; ?>" class="" target="_blank">
                                    <?php if (!empty($facebook_image)): ?>
                                        <img class="icon loading" src="<?php echo esc_url($facebook_image); ?>" alt="" data-was-processed="true">
                                    <?php endif; ?>
                                </a>
                            <?php endif; ?>
                        </div>
                        <div class="social_media_item">
                            <?php if (!empty($instagram_link)): ?>
                                <a href="<?php echo $instagram_link; ?>" class="" target="_blank">
                                    <?php if (!empty($instagram_image)): ?>
                                        <img class="icon loading" src="<?php echo esc_url($instagram_image); ?>" alt="" data-was-processed="true">
                                    <?php endif; ?>
                                </a>
                            <?php endif; ?>
                        </div>
                        <div class="social_media_item">
                            <?php if (!empty($xiaohongshu_link)): ?>
                                <a href="<?php echo $xiaohongshu_link; ?>" class="" target="_blank">
                                    <?php if (!empty($xiaohongshu_image)): ?>
                                        <img class="icon loading" src="<?php echo esc_url($xiaohongshu_image); ?>" alt="" data-was-processed="true">
                                    <?php endif; ?>
                                </a>
                            <?php endif; ?>
                        </div>
                        <div class="social_media_item">
                            <?php if (!empty($weibo_link)): ?>
                                <a href="<?php echo $weibo_link; ?>" class="" target="_blank">
                                    <?php if (!empty($weibo_image)): ?>
                                        <img class="icon loading" src="<?php echo esc_url($weibo_image); ?>" alt="" data-was-processed="true">
                                    <?php endif; ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="social_media_icons">
                        <div class="social_media_item">
                            <?php if (!empty($wechat_link)): ?>
                                <a href="<?php echo $wechat_link; ?>" class="wechat-icon" target="_blank">
                                    <?php if (!empty($wechat_image)): ?>
                                        <img class="icon loading" src="<?php echo esc_url($wechat_image); ?>" alt="" data-was-processed="true">
                                    <?php endif; ?>
                                </a>
                            <?php endif; ?>
                        </div>
                        <div class="social_media_item">
                            <?php if (!empty($meta_link)): ?>
                                <a href="<?php echo $meta_link; ?>" class="treatment-icon" target="_blank">
                                    <?php if (!empty($meta_image)): ?>
                                        <img class="icon loading" src="<?php echo esc_url($meta_image); ?>" alt="" data-was-processed="true">
                                    <?php endif; ?>
                                </a>
                            <?php endif; ?>
                        </div>
                        <div class="social_media_item">
                            <?php if (!empty($dianping_link)): ?>
                                <a href="<?php echo $dianping_link; ?>" class="" target="_blank">
                                    <?php if (!empty($dianping_image)): ?>
                                        <img class="icon loading" src="<?php echo esc_url($dianping_image); ?>" alt="" data-was-processed="true">
                                    <?php endif; ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>      
                <div class="qr_section">
                <?php 
                    $heading_point_member = get_theme_mod('heading_point_member');
                    $pointicon_image = get_theme_mod('pointicon_image');
                    $pointicon_link = get_theme_mod('pointicon_link');
                    $qrcode_image = get_theme_mod('qrcode_image');
                    $qrcode_link = get_theme_mod('qrcode_link');
                    $appstore_image = get_theme_mod('appstore_image');
                    $appstore_link = get_theme_mod('appstore_link');
                    $googleplay_image = get_theme_mod('googleplay_image');
                    $googleplay_link = get_theme_mod('googleplay_link');
                    //print_r($footer_point_member);
                    if (!empty($footer_point_member)): ?>
                        <h4><?php echo $footer_point_member[$lang]; ?></h4>
                    <?php endif; ?>
                    <div class="qr_items_content">
                        <?php if (!empty($footer_point_member['the_point_member_group'])): ?> 
                            <div class="qr_item">
                                <div class="qr_item__container">
                                    <a href="<?php echo esc_url($footer_point_member['the_point_member_group']['link_point_member']); ?>" target="_blank"></a>
                                    <img src="<?php echo $footer_point_member['the_point_member_group']['image_point_member']; ?>" alt="" data-was-processed="true">
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($footer_point_member['qr_code_group'])): ?> 
                            <div class="qr_item">
                                <div class="qr_item__container">
                                    <a href="<?php echo esc_url($footer_point_member['qr_code_group']['link_qr_code']); ?>" target="_blank"></a>
                                    <img src="<?php echo $footer_point_member['qr_code_group']['image_qr_code']; ?>" alt="" data-was-processed="true">
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($footer_point_member['app_store_group']) || !empty($footer_point_member['google_play_group'])): ?> 
                            <div class="qr_item app-store-block">
                                <div class="qr_item__container app-store">
                                    <a href="<?php echo esc_url($footer_point_member['app_store_group']['link_app_store']); ?>" target="_blank"></a>
                                    <img src="<?php echo $footer_point_member['app_store_group']['image_app_store']; ?>" alt="" data-was-processed="true">
                                </div>
                                <div class="qr_item__container google-play">
                                    <a href="<?php echo esc_url($footer_point_member['google_play_group']['link_google_play']); ?>" target="_blank"></a>
                                    <?php if (!empty($googleplay_image)): ?>
                                        <img src="<?php echo $footer_point_member['google_play_group']['image_google_play']; ?>" alt="" data-was-processed="true">
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>

                    </div>

                </div>
            </div>
        </div>
        <div class="bottom_menu">
            <?php
                wp_nav_menu(
                    [
                        'theme_location' => 'menu-5',
                        'menu_id' => 'menu-footer-menu-en',
                        'menu_class' => 'footer_bottom_menu'
                    ]
                );
            ?>
            <?php if (!empty($copyright)): ?>
                <div class="menu-item copyright"><?php echo $copyright[ $lang ]; ?></div>
            <?php endif; ?>
        </div>
    </div>
    <div class="mobile-menu">
	    <?php
	    $args = array(
		    "theme_location" => "mobile-menu",
		    "menu"           => "mobile-menu",
		    "menu_class"     => "mobile-menu_container",
		    "container"      => "true"
	    );
	    wp_nav_menu( $args );
        ?>
        <div class="social-lang-footer_section">
            <?php 
            $lang_not_active = array();
            foreach ($translations_menu as $translation){
                if($translation["slug"] != $current_lang_code){
                    $lang_not_active[] = $translation;
                }
            }
            if(!empty($lang_not_active)){
            ?>
                <div class="lang-box-footer">
                    <?php 
                        foreach($lang_not_active as $lang_val){
                            echo '<div class="lang-item"><a class="" href="'.$lang_val['url'].'">'.$lang_val['name'].'</a></div>';
                        }
                    ?>	
                </div>	
            <?php } ?>
            <div class="social_section">
                <div class="social_media_icons">
                    <?php if ( $social_media["icons"] ) {
                        foreach ( $social_media["icons"] as $social_media_icon ) {
                            ?>
                            <div class="social_media_item">
                                <a href="<?php echo $social_media_icon["link"] ?>" target="_blank">
                                    <img class="icon" src="<?php echo $social_media_icon["icon"]["url"] ?>"
                                        alt="<?php echo $social_media_icon["icon"]["alt"] ?>">
                                </a>
                            </div>
                        <?php }
                    } ?>
                </div>
                <div class="social_media_icons">
                    <?php
                    if ( $social_media["icons_prc"] ) {
                        $i = 0;
                        foreach ( $social_media["icons_prc"] as $social_media_icon ) {
                            ?>
                            <div class="social_media_item social-item-<?php echo $i; ?>">
                                <a href="<?php echo $social_media_icon["link"] ?>" target="_blank">
                                    <img class="icon" src="<?php echo $social_media_icon["icon"]["url"] ?>"
                                        alt="<?php echo $social_media_icon["icon"]["alt"] ?>">
                                </a>
                            </div>
                        <?php $i++; }
                    } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="bottom-slide">
        <?php
        $args = array(
	        "theme_location" => "mobile-bottom-menu",
	        "menu"           => "mobile-bottom-menu",
	        "menu_class"     => "mobile-bottom-menu",
	        "container"      => "true"
        );
        wp_nav_menu( $args );
        ?>
        <button class="responsive_btn hamburger hamburger--squeeze navbar-toggle" type="button">
            <span class="hamburger-box">
                <span class="hamburger-inner"></span>
            </span>
        </button>
    </div>
</footer>
<!-- end footer -->
<?php $we_chat = get_field('we_chat', 'option'); 
if( $we_chat ):   
?>
    <div class="modal fade popup-social popup-wechat" tabindex="-1" role="dialog" id="popup_wechat">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="animate_line">						
                    <span class="line line1"></span>
                    <span class="line line2"></span>
                    <span class="line line3"></span>
                    <span class="line line4"></span>						
                    <div class="modal-body">
                        <a href="#" class="close">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M18.6777 1L1 18.6777M18.6777 18.6777L1 1" stroke="black" stroke-width="2"/>
                            </svg>
                        </a>
                        <div class="inner-content">
                            <div class="header-body desktop">
                                <h5 class="modal-title"><?= $we_chat['header'][$lang]; ?></h5>
                            </div>
                            <div class="images">
                                <img src="<?php echo esc_url( $we_chat['qr_code']['url'] ); ?>" alt="" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
<?php get_template_part('template-parts/treatment-popup'); ?>
<div id="scroll_top">
        <img src="<?php echo get_template_directory_uri(). '/assets/images/scroll_top.svg'; ?>" alt="scroll up">
</div>
</div>

</div>

<?php wp_footer(); ?>
</body>

</html>