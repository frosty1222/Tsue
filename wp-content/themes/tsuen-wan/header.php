<?php
/**
 * @package WordPress
 * @subpackage Classic_Theme
 */
$site_url = pll_home_url();
$current_lang_code = get_current_lang();
$translations = pll_the_languages(array('raw'=>1));
$translations_menu = array($translations["en"], $translations["sc"], $translations["tc"]);
$site_fields = get_fields( "options" );
$parking_url = $site_fields["parking_url"];
$quick_search_placeholder = $site_fields["quick_search_placeholder"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<title><?php wp_title('&laquo;', true, 'right'); ?> <?php bloginfo('name'); ?></title>

	<style type="text/css" media="screen">
		@import url( <?php bloginfo('stylesheet_url'); ?> );
	</style>

	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
	<?php wp_get_archives(array('type' => 'monthly', 'format' => 'link')); ?>
	<?php //comments_popup_script(); // off by default ?>

	<!-- Google Tag Manager -->
	<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-WNR7FGXK');</script>
    <!-- End Google Tag Manager -->

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?> data-lang="<?php echo $current_lang_code; ?>">
<div id="page_section" class="site">
<div class="overlay-search" id="overlay-search"></div>
<header id="header_menu_section" class="header ">
            <div class="header_menu_section_fix">
                <div class="header_menu_section_container">
	                <div class="container">
                        <div class="header-content">
							<div class="header-search header-content_item mobile-search-icon">
									<div class="header-search_container">
										<img class="search_icon" src="<?php echo get_template_directory_uri();?>/assets/images/search-icon-mobile.svg" alt="search" data-was-processed="true">
									</div>
							</div>
                            <div class="header-content_left">
                                <div class="header-site_logo">
									<?php
										the_custom_logo();
									?>
                                </div>
	                        </div>
							<?php
								wp_nav_menu(
									[
										'theme_location' => 'menu-1',
										'menu_id' => 'primary-menu',
										'menu_class' => 'header-menu'
									]
								);
							?>
							
                            <div class="header-content_right">
								<div class="header-search header-content_item">
									<div class="header-search_container">
										<img class="search_icon" src="<?php echo get_template_directory_uri();?>/assets/images/search-icon.svg" alt="search" data-was-processed="true">
									</div>
								</div>
								<div class="header-lang">
									<?php 
										$lang_not_active = array();
										foreach ($translations_menu as $translation){
											$class = $translation["slug"] == $current_lang_code ? "active" : "";
											if($class == 'active') {
										?>
											<a class="<?php echo $class ?>" ><?php echo $translation["name"] ?></a>
										<?php 
											} else { 
												$lang_not_active[] = $translation;
											} 
										}
										
										if(!empty($lang_not_active)){
                                    ?>
										<div class="lang-box">
											<ul>
												<?php 
													foreach($lang_not_active as $lang_val){
														echo '<li><a class="" href="'.$lang_val['url'].'">'.$lang_val['name'].'</a></li>';
													}
												?>	
											</ul>	
										</div>	
										<?php } ?>
                                </div>
								<?php if(!empty($parking_url)): ?>
                                <a class="header-park" href="<?php echo esc_url($parking_url[$current_lang_code]); ?>">
                                    <img src="<?php echo get_template_directory_uri();?>/assets/images/parking-icon.svg" alt="park" class="loading" data-was-processed="true">
                                </a>
								<?php endif; ?>
                            </div>
							
                        </div>
                    </div>
                </div>

				<div id="search_results" class="search-results serach-header">
					<div class="search-form_section">
						<div class="search-in-header">
						<a class="search-results_page" href="<?php echo $site_url ?>">
							<img class="search_icon" src="<?php echo get_template_directory_uri();?>/assets/images/search-icon-mobile.svg" alt="search" data-was-processed="true">
						</a>
						</div>
						<?php 
						if(!empty($quick_search_placeholder)){
							$placeholder_text = $quick_search_placeholder[$current_lang_code];
						} else {
							$placeholder_text = 'Search';
						}
						?>
						<form action="<?php echo $site_url ?>">
							<input class="search_input" name="s"  type="text" placeholder="<?php echo $placeholder_text; ?>">
						</form>
						<div id="search_close"></div>
					</div>
                    <div class="search-results_container">
                        <div class="search-results_section search-results_list">
                            <div class="search-results_list__content"><!--content here--></div>
							<div class="search-results_list__btn button">
								<div class="link_btn">
									<a class="search-results_page button-read-more" href="<?php echo $site_url ?>"><span><?php pll_e("View All Results"); ?></span></a>
								</div>
							</div>
                        </div>
                    </div>
                    <div class="loading-icon">
						<div class="loader" style="display: block;"></div>
                    </div>
                </div>
              
            </div>
        </header>
	<!-- end header -->
	<div id="content">
	
