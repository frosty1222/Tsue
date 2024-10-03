<?php
global $wp;
?>
<div class="share inline-center">
    <div class="date--field inline-center icon_share">
    <?php if( get_field('show_share') ):  $share_icon = get_field('share_icon'); ?>
        <img src="<?= esc_url($share_icon['url']) ?>" alt="<?= esc_attr($share_icon['alt']); ?>" />
    <?php else : ?>
        <svg width="30" height="30" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M15.9052 13.7897C15.4869 13.7913 15.0732 13.8773 14.689 14.0428C14.3049 14.2082 13.958 14.4495 13.6694 14.7523L6.09903 10.964C6.28525 10.3993 6.28525 9.78973 6.09903 9.22506L13.6819 5.36843C14.225 5.9408 14.9615 6.29077 15.7483 6.35032C16.5351 6.40988 17.316 6.17477 17.9391 5.69068C18.5622 5.2066 18.9831 4.50813 19.1199 3.73103C19.2567 2.95393 19.0997 2.15372 18.6794 1.48593C18.2591 0.818149 17.6055 0.330494 16.8456 0.117754C16.0858 -0.0949864 15.274 -0.0176129 14.5681 0.334835C13.8621 0.687283 13.3124 1.28963 13.0258 2.02479C12.7392 2.75995 12.7362 3.57541 13.0173 4.31268L5.50904 8.13204C5.10354 7.63611 4.55457 7.27773 3.9374 7.10604C3.32023 6.93435 2.66506 6.95774 2.0617 7.17301C1.45835 7.38828 0.936339 7.7849 0.567236 8.30848C0.198132 8.83207 0 9.457 0 10.0976C0 10.7382 0.198132 11.3631 0.567236 11.8867C0.936339 12.4103 1.45835 12.8069 2.0617 13.0222C2.66506 13.2375 3.32023 13.2609 3.9374 13.0892C4.55457 12.9175 5.10354 12.5591 5.50904 12.0632L12.9987 15.8329C12.8722 16.1727 12.807 16.5322 12.8062 16.8948C12.8062 17.509 12.9883 18.1093 13.3295 18.62C13.6707 19.1306 14.1557 19.5286 14.7231 19.7636C15.2905 19.9987 15.9148 20.0601 16.5172 19.9403C17.1195 19.8205 17.6728 19.5248 18.1071 19.0905C18.5413 18.6562 18.8371 18.103 18.9569 17.5006C19.0767 16.8983 19.0152 16.2739 18.7802 15.7065C18.5451 15.1391 18.1471 14.6542 17.6365 14.313C17.1259 13.9718 16.5255 13.7897 15.9114 13.7897H15.9052Z" fill="#73D4D4"/>
        </svg>
    <?php endif ?>
    <span class="text"> <?= esc_html__( 'Share', 'tsuen' ) ?></span>
    </div>
    <div class="share-content">
        <div class="share-content-shadow">
            <div  class="share-items">
                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo home_url( $wp->request ) ?>"
                    class="share-fb share-item" target=_blank>
                    <img class="img" src="<?php echo get_template_directory_uri()."/assets/images/fb.svg" ?>" alt="fb">
                </a>
                <a href="https://wa.me/?text=<?php echo home_url( $wp->request ) ?>" class="share-whatsapp share-item" target=_blank>
                    <img class="img" src="<?php echo get_template_directory_uri()."/assets/images/whatsapp.svg" ?>" alt="whatsapp">
                </a>
                <a href="#wechat" class="share-wc share-item wechat-icon"  target=_blank>
                    <img class="img" src="<?php echo get_template_directory_uri()."/assets/images/wechat.svg" ?>" alt="wechat">
                </a>
                <a href="https://service.weibo.com/share/share.php?url=<?php echo home_url( $wp->request ) ?>"
                    class="share-weibo share-item"  target=_blank>
                    <img class="img" src="<?php echo get_template_directory_uri()."/assets/images/weibo.svg" ?>" alt="weibo">
                </a>
                <a href="mailto:?body=<?php echo home_url( $wp->request ) ?>" class="share-mail share-item"  target=_blank>
                    <img class="img" src="<?php echo get_template_directory_uri()."/assets/images/email.svg" ?>" alt="email">
                </a>
            </div>
            <div class="share-link">
                <?php echo home_url( $wp->request ) ?>
                <div class="share-link-btn" data-copy="<?php echo home_url( $wp->request ) ?>">
                    <?php pll_e( "COPY" ) ?>
                </div>
            </div>
            <div class="copy-success"><?php pll_e( "Link Copied" ) ?></div>
        </div>
    </div>
</div>