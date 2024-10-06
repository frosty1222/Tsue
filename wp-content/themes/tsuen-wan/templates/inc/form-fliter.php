<?php $menu = ['Category','A-Z','Floor','Floor Plan']; ?>
<?php
    $post_type = get_field('choose_post');
    $categories = 'shop-categories';
    if($post_type == 'dinings') {
        $categories = 'dining-categories';
    }
?>
<?php if($menu): ?>
    <form method="get" class="filter">
        <div class="shop-menu">
            <div class="shop-top-menu">
            <ul class="shop-menu_container tab-menu listmenu">
                <?php foreach($menu as $item) : ?>
                <li class="shop-menu_btn <?= ($item == 'Category') ? 'active' : ''; ?>">
                    <a href="#tab_<?= sanitize_title($item);?>" 
                    data-href-mobile="#tab_mobile_<?= sanitize_title($item);?>" 
                    class="shop-menu_control" 
                    data-post-type="<?= $post_type; ?>" 
                    <?php if ($item == 'Floor Plan') : ?>id="triggerShopLoad"<?php endif; ?> 
                    onclick="<?= ($item == 'Floor Plan') ? 'loadShopData()' : ''; ?>">
                        <?= pll__($item) ?>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>

                <div class="filter-search">
                    <a href="#" class="button-search">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M19.7556 18.5778L14.0682 12.8904C15.1699 11.5296 15.8332 9.80039 15.8332 7.91709C15.8332 3.55213 12.2815 0.000488281 7.91656 0.000488281C3.55161 0.000488281 0 3.55209 0 7.91705C0 12.282 3.55165 15.8337 7.9166 15.8337C9.7999 15.8337 11.5291 15.1703 12.8899 14.0687L18.5773 19.7561C18.7398 19.9186 18.9531 20.0003 19.1665 20.0003C19.3798 20.0003 19.5932 19.9186 19.7557 19.7561C20.0815 19.4303 20.0815 18.9036 19.7556 18.5778ZM7.9166 14.167C4.46996 14.167 1.66666 11.3637 1.66666 7.91705C1.66666 4.47041 4.46996 1.66711 7.9166 1.66711C11.3632 1.66711 14.1665 4.47041 14.1665 7.91705C14.1665 11.3637 11.3632 14.167 7.9166 14.167Z" fill="black"/>
                        </svg>
                    </a>
                    <input class="shop_name_input" data-version="desktop" data-post-type="<?= $post_type; ?>" type="text" placeholder="<?= __('Search'); ?>">
                    <div class="shop_name_close"></div>
                </div>
            </div>
            <?php foreach( $menu as $item) : ?>
                <div id="tab_<?= sanitize_title($item);?>" class="tab-content <?= ($item == 'Category')? 'active': '';?>">
                    <?php if($item=='Category'): ?>
                        <div class="list-category">
                            <ul class="listmenu">
                                <?php
                                    $arg = array(
                                        'taxonomy'   => $categories,
                                        'hide_empty' => false,
                                    );
                                    $shop_categories  = get_terms( $arg );
                                ?>
                                <li>
                                    <input type="radio" data-post-type="<?= $post_type; ?>" data-term-id="all" value="all" id="itemall" name="<?= $categories; ?>" checked>
                                    <label for="itemall"><?= __("All"); ?></label>
                                </li>
                                <?php foreach ( $shop_categories as $shop_category ) { ?>
                                    <li>
                                        <input type="radio" data-post-type="<?= $post_type; ?>" data-term-id="<?= $shop_category->term_id; ?>" value="<?= $shop_category->slug; ?>" id="item<?= $shop_category->slug; ?>" name="<?= $categories; ?>">
                                        <label for="item<?= $shop_category->slug; ?>" ><?= $shop_category->name; ?></label>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                        <div class="list-privileges list-category">
                            <ul class="listmenu">
                                <?php
                                    $taxpr = 'privileges';
                                    $arg               = array(
                                        'taxonomy'   => $taxpr,
                                        'hide_empty' => false,
                                    );
                                    $privileges  = get_terms( $arg );
                                ?>
                                <li>
                                    <input type="radio" data-post-type="<?= $post_type; ?>" data-term-id="all_privilege" value="all_privilege" id="all_privilege" name="<?= $taxpr; ?>" checked>
                                    <label for="all_privilege"><?= __("All"); ?></label>
                                </li>
                                <?php foreach ( $privileges as $privilege ) {?>
                                    <li>
                                        <input type="radio" data-post-type="<?= $post_type; ?>" data-term-id="<?= $privilege->term_id; ?>" value="<?= $privilege->slug; ?>" id="item<?= $privilege->slug; ?>" name="<?= $taxpr; ?>">
                                        <label for="item<?= $privilege->slug; ?>">
                                            <?php
                                                $image_id = get_field('icon_tax',$privilege);
                                                if($image_id){
                                                    $image_url = wp_get_attachment_image_url( $image_id, 'full' );
                                                    $ext = pathinfo($image_url, PATHINFO_EXTENSION);
                                                    if($ext=='svg'){
                                                        $file = file_get_contents($image_url, true);
                                                    }else{
                                                        $file = wp_get_attachment_image($image_id,'full');
                                                    }
                                                    echo  $file ;
                                                }
                                            ?>
                                            <?= $privilege->name; ?>
                                        </label>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                    <?php endif;?>

                    <?php if($item == 'A-Z'): ?>
                        <div class="list-a-z">
                            <ul class="listmenu">
                                <li>
                                    <input type="radio" data-post-type="<?= $post_type; ?>" value="all" id="all-a-z" name="filter-a-z" checked>
                                    <label for="all-a-z"><?= __("All"); ?></label>
                                </li>
                                
                                <?php foreach (range('A', 'Z') as $char): ?>
                                    <li>
                                        <input type="radio" data-post-type="<?= $post_type; ?>" value="<?= strtolower($char); ?>" id="filter-<?= strtolower($char); ?>" name="filter-a-z">
                                        <label for="filter-<?= strtolower($char); ?>"><?= $char; ?></label>
                                    </li>
                                <?php endforeach; ?>
                                <li>
                                    <input type="radio" data-post-type="<?= $post_type; ?>" value="#" id="filter-sharp" name="filter-a-z">
                                    <label for="filter-sharp">#</label>
                                </li>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <?php if($item == 'Floor'): ?>
                        <div class="list-floor">
                            <ul class="listmenu">
                                <li>
                                    <input type="radio" data-post-type="<?= $post_type; ?>" value="all" id="all-floor" name="filter-floor" checked>
                                    <label for="all-floor"><?= __("All"); ?></label>
                                </li>
                                <?php $floors = ['B1','L1','L2','L3','L4','L5','L6']; ?>
                                <?php foreach ( $floors as $floor ): ?>
                                    <li>
                                        <input type="radio" data-post-type="<?= $post_type; ?>" value="<?= strtolower($floor); ?>" id="floor-<?= strtolower($floor); ?>" name="filter-floor">
                                        <label for="floor-<?= strtolower($floor); ?>"><?= $floor; ?></label>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>

            <div class="tab-content-mobile">
                <?php foreach( $menu as $item) : ?>
                    <div id="tab_mobile_<?= sanitize_title($item);?>" class="tab-content <?= ($item == 'Category')? 'active': '';?>">
                        <?php if($item == 'Category'): ?>
                            <select name="select-category" id="select-category" data-post-type="<?= $post_type; ?>">
                                <option value="all" selected="selected"><?= __('All') ?></option>
                                <?php
                                    $arg = array(
                                        'taxonomy'   => $categories,
                                        'hide_empty' => false,
                                    );
                                    $shop_categories  = get_terms( $arg );
                                ?>
                                <?php foreach ($shop_categories as $shop_category): ?>
                                    <option data-slug="<?= $shop_category->slug; ?>" value="<?= $shop_category->term_id; ?>"><?= $shop_category->name; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <select name="select-privileges" id="select-privileges">
                                <option value="" selected="selected"><?= pll__('All') ?></option>
                                <?php
                                    $taxpr = 'privileges';
                                    $arg               = array(
                                        'taxonomy'   => $taxpr,
                                        'hide_empty' => false,
                                    );
                                    $privileges  = get_terms( $arg );
                                ?>
                                <?php foreach ($privileges as $privilege): ?>
                                    <option data-slug="<?= $privilege->slug; ?>" value="<?= $privilege->term_id; ?>"><?= $privilege->name; ?></option>
                                <?php endforeach; ?>
                            </select>
                        <?php endif;?>

                        <?php if($item == 'A-Z'): ?>
                            <select name="select-a-z" id="select-a-z" data-post-type="<?= $post_type; ?>">
                                <option value="all" selected="selected"><?= __('All') ?></option>
                                <?php foreach (range('A', 'Z') as $char): ?>
                                    <option value="<?= strtolower($char); ?>"><?= $char; ?></option>
                                <?php endforeach; ?>
                            </select>
                        <?php endif; ?>

                        <?php if($item == 'Floor'): ?>
                            <select name="select-floor" id="select-floor" data-post-type="<?= $post_type; ?>">
                                <option value="all" selected="selected"><?= __('All') ?></option>
                                <?php foreach ( $floors as $floor ): ?>
                                    <option value="<?= strtolower($floor); ?>"><?= $floor; ?></option>
                                <?php endforeach; ?>
                            </select>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="tab-search-mobile">
                <div class="filter-search">
                    <a href="#" class="button-search">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M19.7556 18.5778L14.0682 12.8904C15.1699 11.5296 15.8332 9.80039 15.8332 7.91709C15.8332 3.55213 12.2815 0.000488281 7.91656 0.000488281C3.55161 0.000488281 0 3.55209 0 7.91705C0 12.282 3.55165 15.8337 7.9166 15.8337C9.7999 15.8337 11.5291 15.1703 12.8899 14.0687L18.5773 19.7561C18.7398 19.9186 18.9531 20.0003 19.1665 20.0003C19.3798 20.0003 19.5932 19.9186 19.7557 19.7561C20.0815 19.4303 20.0815 18.9036 19.7556 18.5778ZM7.9166 14.167C4.46996 14.167 1.66666 11.3637 1.66666 7.91705C1.66666 4.47041 4.46996 1.66711 7.9166 1.66711C11.3632 1.66711 14.1665 4.47041 14.1665 7.91705C14.1665 11.3637 11.3632 14.167 7.9166 14.167Z" fill="black"/>
                        </svg>
                    </a>
                    <input class="shop_name_input" data-version="mobile" data-post-type="<?= $post_type; ?>" type="text" placeholder="<?= __('Search'); ?>">
                    <div class="shop_name_close"></div>
                </div>
            </div>
        </div>
    </form>
<?php endif; ?>