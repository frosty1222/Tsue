<div class="shop-post_top inner-container">
    <h1 class="shop-post_name">
		<?php echo $page_fields["shop_name"] ?>
    </h1>
	<?php if ( ! empty( $page_fields["shop_description"] ) ) { ?>
        <div class="shop-post_description">
			<?php echo $page_fields["shop_description"] ?>
        </div>
	<?php } ?>
</div>
<div class="shop-post_items">
	<?php if ( ! empty( $page_fields["list_description"] ) ) {
		?>
        <div class="shop-post_item shop-post_location">
            <img class="icon" src="<?php echo template_img_url( "list_location.svg" ) ?>" alt="location"/>
            <div class="item_text">
				<?php echo $page_fields["list_description"] ?>
            </div>
        </div>
	<?php } ?>
	<?php if ( ! empty( $page_fields["opening_hours"] ) ) { ?>
        <div class="shop-post_item shop-post_hours">
            <img class="icon" src="<?php echo template_img_url( "list_hour.svg" ) ?>" alt="hours"/>
            <div class="item_text">
				<?php foreach ( $page_fields["opening_hours"] as $key => $open_hour ) {
					echo $open_hour["date"] . " " . $open_hour["time"];
					if ( count( $page_fields["opening_hours"] ) - 1 > $key ) {
						echo "; ";
					}
				} ?>
            </div>
        </div>
	<?php } ?>
	<?php if ( ! empty( $page_fields["phone"] ) ) { ?>
        <div class="shop-post_item shop-post_phone">
            <img class="icon" src="<?php echo template_img_url( "list_phone.svg" ) ?>" alt="phone"/>
            <div class="item_text"><?php echo $page_fields["phone"] ?></div>
        </div>
	<?php } ?>
	<?php if ( ! empty( $page_fields["web_site"] ) ) { ?>
        <div class="shop-post_item shop-post_web">
            <img class="icon" src="<?php echo template_img_url( "site.svg" ) ?>" alt="website"/>
            <div class="item_text"><a class="img_link" href="<?php echo $page_fields["web_site"] ?>"
                                      target="_blank"><?php echo $page_fields["web_site"] ?></a></div>
        </div>
	<?php } ?>
    <div id="share" class="shop-post_item shop-post_share" data-title="<?php echo $post->post_title ?>" data-text="<?php echo $post->post_title ?>">
        <img class="icon" src="<?php echo template_img_url( "share.svg" ) ?>" alt="website"/>
        <div class="item_text"><?php pll_e("Share"); ?></div>
        <div id="share-content">
            <div class="share-content-shadow">
                <div class="share-items">
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo home_url( $wp->request ) ?>" class="share-fb share-item"></a>
                    <a href="https://wa.me/?text=<?php echo home_url( $wp->request ) ?>" class="share-whatsapp share-item"></a>
                    <a href="#wechat" class="share-wc share-item"></a>
                    <a href="https://service.weibo.com/share/share.php?url=<?php echo home_url( $wp->request ) ?>&title=<?php echo home_url( $wp->request )?>" class="share-weibo share-item"></a>
                    <a href="mailto:?body=<?php echo home_url( $wp->request ) ?>" class="share-mail share-item"></a>
                </div>
                <div class="share-link">
					<?php echo home_url( $wp->request ) ?>
                    <div class="share-link-btn" data-copy="<?php echo home_url( $wp->request ) ?>"><?php pll_e("COPY") ?></div>
                </div>
                <div id="copy-success"><img src="<?php echo template_img_url("copy_success.png") ?>" alt="">Link Copied</div>
            </div>
        </div>
    </div>
    <?php if(!empty($page_fields["others"])){
        foreach($page_fields["others"] as $other){
	        if($other["value"] == "other_2" || $other["value"] == "other_3") continue;
	        $other_name = pll__($other["label"]);
	        ?>
        <div class="shop-post_item">
            <span class="shop_other_icon active <?php echo $other["value"] ?>"></span>
            <div class="item_text">
			    <?php
                echo $other_name ?>
            </div>
        </div>
    <?php }} ?>

</div>
