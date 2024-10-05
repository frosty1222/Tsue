<?php
$var_abc = get_defined_constants();
//echo '<pre>';print_r($var_abc);echo '</pre>';
// $_GETs
$lang = isset($lang) ? $lang : 'en';
$is_show_menu = isset($menu) ? (bool) $menu : false;
$scroll_zoom = isset($scrollZoom) ? $scrollZoom : true;
$dragging = isset($dragging) ? $dragging : true;
$zoom_level = 1;
$is_no_controls = isset($noControl) ? (bool) $noControl : false;
$floor = isset($floor) ? $floor : 'B1';
if (isset($_GET['floor'])) {
    $floor = $_GET['floor'];
}
// shop list
global $wpdb;

// Check if connection is successful
if (!$wpdb) {
    die("Connection failed: Unable to connect to the database.");
}

// Set character set to utf8mb4
$wpdb->query("SET NAMES 'utf8mb4'");
//echo "Connection successful and character set set to utf8mb4.";

// Function to execute queries with optional bindings
function database_query($query, $bind = [])
{
    global $wpdb;
    $prepared_query = empty($bind) ? $query : $wpdb->prepare($query, $bind);
    return $wpdb->get_results($prepared_query, ARRAY_A) ?: false;
}

// Table prefix
$_prefix = $wpdb->prefix;

// Fetch shop posts
$shops_posts = database_query(
    "SELECT p.ID, p.post_type, tr.object_id, tr.term_taxonomy_id, tt.term_taxonomy_id, tt.taxonomy, 
    tt.description, pm.post_id, pm.meta_key, 
    IF(pm.meta_key IN ('_thumbnail_id', 'thumbnail'), 
    (SELECT guid FROM {$_prefix}posts WHERE ID = pm.meta_value), pm.meta_value) AS meta_value
    FROM {$_prefix}posts p
    JOIN {$_prefix}term_relationships tr ON p.ID = tr.object_id
    JOIN {$_prefix}term_taxonomy tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
    JOIN {$_prefix}postmeta pm ON pm.post_id = p.ID
    WHERE p.post_status = 'publish' 
    AND p.post_type IN ('dinings', 'shops', 'page')
    AND tt.taxonomy = 'post_translations' 
    AND (pm.meta_key LIKE 'shop_name' OR pm.meta_key LIKE 'shop_floor' OR pm.meta_key LIKE 'list_description' 
    OR pm.meta_key LIKE 'open_hours' OR pm.meta_key LIKE 'phone' OR pm.meta_key LIKE 'shop_phase_level' 
    OR pm.meta_key LIKE 'icon' OR pm.meta_key LIKE '_thumbnail_id' OR pm.meta_key LIKE 'thumbnail' 
    OR pm.meta_key LIKE 'shop_numbers_%' OR pm.meta_key LIKE 'opening_hours_%')"
);

// Fetch shop privileges
$shops_privileges = database_query(
    "SELECT p.ID, p.post_type, tr.object_id, tr.term_taxonomy_id, tt.term_taxonomy_id, tt.taxonomy, 
    pm.post_id, tm.meta_key, 
    IF(tm.meta_key = 'icon_tax', (SELECT guid FROM {$_prefix}posts WHERE ID = tm.meta_value), tm.meta_value) AS meta_value
    FROM {$_prefix}posts p
    JOIN {$_prefix}term_relationships tr ON p.ID = tr.object_id
    JOIN {$_prefix}term_taxonomy tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
    JOIN {$_prefix}termmeta tm ON tr.term_taxonomy_id = tm.term_id
    JOIN {$_prefix}postmeta pm ON pm.post_id = p.ID
    WHERE p.post_status = 'publish' 
    AND p.post_type IN ('dinings', 'shops', 'page') 
    AND tt.taxonomy = 'privileges' 
    AND tm.meta_key = 'icon_tax'"
);

$privileges = [];
foreach ($shops_privileges as $post) {
    if ($post['meta_value'] !== null) {
        $privileges[$post['ID']][$post['term_taxonomy_id']] = $post['meta_value'];
    }
}

$shops = [];
foreach ($shops_posts as $post) {
    $lang_description = unserialize($post['description']);
    $shop_key = $post['term_taxonomy_id'];
    $shop = $shops[$shop_key] ?? ['lang_description' => $lang_description, 'langs' => []];
    $post_lang = array_search($post['ID'], $shop['lang_description']);

    if ($post_lang === false) continue;

    $shop_lang = $shop['langs'][$post_lang] ?? ['shop_numbers' => [], 'opening_hours' => []];
    $shop_lang['url'] = '/?p=' . $post['ID'];

    // Map meta keys to appropriate fields
    $meta_map = [
        'shop_name' => 'name',
        'shop_phase_level' => 'floor',
        'list_description' => 'shop_location_text',
        'open_hours' => 'open_hours',
        'phone' => 'phone',
        'icon' => 'icon',
        '_thumbnail_id' => 'featured',
        'thumbnail' => 'thumbnail',
    ];

    if (isset($meta_map[$post['meta_key']])) {
        $shop_lang[$meta_map[$post['meta_key']]] = $post['meta_value'];
    } elseif (preg_match('/^opening_hours_[0-9]+_open_hours_item$/', $post['meta_key'])) {
        $shop_lang['opening_hours'][] = $post['meta_value'];
    } elseif (preg_match('/^shop_numbers_[0-9]+_shop_number$/', $post['meta_key'])) {
        $shop_lang['shop_numbers'][] = $post['meta_value'];
    }

    if (isset($privileges[$post['ID']])) {
        $shop_lang['privileges'] = $privileges[$post['ID']];
    }

    $shop['langs'][$post_lang] = $shop_lang;
    $shops[$shop_key] = $shop;
}

// Organize shops based on language
$tmp_shops = $shops;
$shops = [];
foreach ($tmp_shops as $shop) {
    if (!isset($shop['langs'][$lang])) continue;
    $shop_data = $shop['langs'][$lang];
    foreach ($shop_data['shop_numbers'] as $shop_number) {
        $shops[$shop_number][] = $shop_data;
        foreach ($shops[$shop_number] as &$small_shop) {
            unset($small_shop['shop_numbers']);
        }
    }
}
// echo '<pre>';
// var_dump($shops);
// echo '</pre>';

$current_path = get_template_directory_uri() . "/map/";
?>
<link href="<?php echo $current_path ?>vendor/leaflet/leaflet.css" rel="stylesheet">
<style>
    <?php if ($is_no_controls) { ?>
        .controls .floors,
        .controls.facilities {
            display: none;
        }
    <?php } ?>
</style>

<!-- <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"> -->
<span class="map-name-custom map-name-custom-garden">B1</span>
<div id="map" style="cursor: default !important">
    <span class="map-name-custom">B1</span>
</div>
<div class="controls controls-right">
    <div class="floors">
        <a href="javascript:void(0)" class="floor-b1" onclick="changeFloor('B1');">B1</a>
        <a href="javascript:void(0)" class="floor-l1" onclick="changeFloor('L1');">L1</a>
        <a href="javascript:void(0)" class="floor-l2" onclick="changeFloor('L2');">L2</a>
        <a href="javascript:void(0)" class="floor-l3" onclick="changeFloor('L3');">L3</a>
        <a href="javascript:void(0)" class="floor-l4" onclick="changeFloor('L4');">L4</a>
        <a href="javascript:void(0)" class="floor-l5" onclick="changeFloor('L5');">L5</a>
        <a href="javascript:void(0)" class="floor-l6" onclick="changeFloor('L6');">L6</a>
    </div>
    <div class="zoom">
        <a href="javascript:void(0)" onclick="zoomIn();">
            <img src="<?php echo $current_path ?>resources/master/controls/button_zoom_in.svg">
        </a>
        <a href="javascript:void(0)" onclick="zoomOut();">
            <img src="<?php echo $current_path ?>resources/master/controls/button_zoom_out.svg">
        </a>
    </div>
</div>
<div class="controls floors-mobile">
    <select onchange="changeFloor($(this).val());">
        <option value="B1">B1</option>
        <option value="L1">L1</option>
        <option value="L2">L2</option>
        <option value="L3">L3</option>
        <option value="L4">L4</option>
        <option value="L5">L5</option>
        <option value="L6">L6</option>
    </select>
</div>
<div class="controls facilities">
    <?php
        $symbols = false;
        $symbolsOptions = get_field('symbols', 'option');
        if ($symbolsOptions) {
            foreach ($symbolsOptions as $symbol) {
                if ($symbol['icon'] && $symbol['icon'] != '') {
                    $symbols = true;
                }
            }
        }
        if ($symbols) { ?>
            <div class="symbols show-list">
                <div class="label active">
                    <svg width="12" height="9" viewBox="0 0 12 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5.125 7.89319L1 7.89319L6 1.10639L11 7.89355L6.875 7.89355" stroke="#1D1D1D"/>
                    </svg>
                    <span><?= __('Symbols') ?></span>
                </div>
                <div class="list-symbols" style="display: none">
                    <div class="list-symbol">
                        <?php
                        foreach ($symbolsOptions as $symbol) :
                            $text = $symbol['text'];
                            $phaseFloor = $symbol['phase_floor']; ?>
                            <span class="icons" data-phase-floor="<?= $phaseFloor; ?>">
                                <?php
                                if ($symbol['icon']) : ?>
                                    <span class="icon">
                                        <img src="<?= $symbol['icon']; ?>" />
                                    </span>
                                <?php
                                endif;
                                if ($text) : ?>
                                    <span class="text"><?= $text; ?></span>
                                <?php
                                endif; ?>
                            </span>
                        <?php
                        endforeach; ?>
                    </div>
                </div>
            </div>
            <script type="text/javascript">
                jQuery(document).ready(function($) {
                    $('.symbols .label').click(function() {
                        $(this).toggleClass('active');
                        $('.symbols').toggleClass('show-list');
                        $(this).next().slideToggle();
                        $(this).closest('.controls ').toggleClass('show-list');
                    })
                })
            </script>
        <?php
    } ?>
</div>
<div id="menu">
    <div class="menu-search">
        <?php
        $search_placeholder = 'Search';
        switch ($lang) {
            case 'en':
            default:
                break;
            case 'tc':
                $search_placeholder = '搜尋';
                break;
            case 'sc':
                $search_placeholder = '搜寻';
                break;
        }
        ?>
        <input type="text" id="menu-search-filter" name="menu-search" onkeyup="menuSearchFilter();" placeholder="<?php echo $search_placeholder ?>" style="background-image: url('<?php echo $current_path ?>resources/floorplan/icon_search.svg');">
    </div>
    <div class="floor-list">
        <?php
            $all_label = 'All';
            switch ($lang) {
                case 'en':
                default:
                    break;
                case 'tc':
                    $all_label = '全部';
                    break;
                case 'sc':
                    $all_label = '全部';
                    break;
            }
            foreach (array(
                $all_label => '',
                'B1' => 'B1',
                'L1' => 'L1',
                'L2' => 'L2',
                'L3' => 'L3',
                'L4' => 'L4',
                'L5' => 'L5',
                'L6' => 'L6',
            ) as $floor_label => $floor_value) { ?>
                <a href="javascript:void(0)" onclick="menuSearchFloor('<?php echo $floor_value ?>');" data-floor="<?php echo $floor_value ?>">
                    <?php echo $floor_label ?>
                </a>
            <?php } ?>
    </div>
    <div class="shop-list"></div>
</div>
<script src="<?php echo $current_path ?>vendor/jquery/jquery-3.3.1.min.js" type="text/javascript"></script>
<script src="<?php echo $current_path ?>vendor/leaflet/leaflet-src.js" type="text/javascript"></script>
<script src="<?php echo $current_path ?>vendor/leaflet-curve/src/leaflet.curve.js" type="text/javascript"></script>
<script src="//unpkg.com/leaflet-gesture-handling"></script>
<link rel="stylesheet" href="//unpkg.com/leaflet-gesture-handling/dist/leaflet-gesture-handling.min.css" type="text/css">
<script>
    var shopData = <?php echo json_encode($shops) ?>;
    //console.log(shopData);
    var currentFloor = '';
    var availableFacilities = {
        /*'exchange': {
            'label': {
                'en': 'ATM',
                'tc': '自動櫃員機',
                'sc': '自动柜员机'
            },
            'availableFloors': [
                'L1',
                'L2'
            ]
        },*/
        /*'childcare': {
            'label': {
                'en': 'Baby\nCare',
                'tc': '嬰兒護理間',
                'sc': '婴儿护理间'
            },
            'availableFloors': [
                'L2',
                'L3',
                'L5'
            ]
        },*/
        /*'parking': {
            'label': {
                'en': '',
                'tc': '',
                'sc': ''
            },
            'availableFloors': []
        },*/
        /*'information': {
            'label': {
                'en': 'Customer\nCare Centre',
                'tc': '顧客服務中心',
                'sc': '顾客服务中心'
            },
            'availableFloors': [
                'L1'
            ]
        },*/
        /*'disabled': {
            'label': {
                'en': 'Disabled\nToliet',
                'tc': '傷殘人士\n洗手間',
                'sc': '伤残人士\n洗手间'
            },
            'availableFloors': [
                'L1',
                'L2',
                'L3',
                'L4',
                'L5',
                'L6'
            ]
        },*/
        /*'toliet': {
            'label': {
                'en': 'Toilet',
                'tc': '洗手間',
                'sc': '洗手間'
            },
            'availableFloors': [
                'L1',
                'L2',
                'L3',
                'L4',
                'L5'
            ]
        },*/
        /*'lift': {
            'label': {
                'en': 'Lift',
                'tc': '升降機',
                'sc': '升降机'
            },
            'availableFloors': [
                'B1',
                'L1',
                'L2',
                'L3',
                'L4',
                'L5',
                'L6'
            ]
        },*/
        // 'locker': {
        //     'label': {
        //         'en': 'Locker',
        //         'tc': '儲物櫃',
        //         'sc': '储物柜'
        //     },
        //     'availableFloors': [
        //         //'B1',
        //         //'L3'
        //     ]
        // },
        /*'cinema': {
            'label': {
                'en': 'UA Cine\nMoko Box\nOffice',
                'tc': 'UA Cine\nMoko\n售票處',
                'sc': 'UA Cine\nMoko\n售票处'
            },
            'availableFloors': [
                'B1'
            ]
        }*/
    };
    if($(window).width() > 991) {
        var leaflet = L.map('map', {
            zoomControl: false,
            attributionControl: false,
            maxBounds: new L.LatLngBounds(
                new L.LatLng(-0.012355595731337616 , -0.0167924630669821084),
                new L.LatLng(0.015033266714572844 , 0.01692926829250041)
            ),
            scrollWheelZoom: true,
            gestureHandling: false,
            dragging: <?php echo $dragging ? 'true' : 'false' ?>,
            minZoom: <?php echo 14 + $zoom_level ?>,
            maxZoom: 17,
        }).fitBounds(new L.LatLngBounds(
            new L.LatLng(-0.012355595731337616 , -0.0167924630669821084),
            new L.LatLng(0.015033266714572844 , 0.01692926829250041)
        ));
    }
    if($(window).width() <= 991) {
        //leaflet.scrollWheelZoom.disable();
        //leaflet.gestureHandling.enable();
        leaflet = L.map('map', {
            zoomControl: false,
            attributionControl: false,
            maxBounds: new L.LatLngBounds(
                new L.LatLng(-0.009596066652927592 , -0.017070832783576906),
                new L.LatLng(0.009767264080431519 , 0.016243451420088206)
            ),
            scrollWheelZoom: true,
            gestureHandling: false,
            dragging: <?php echo $dragging ? 'true' : 'false' ?>,
            minZoom: <?php echo 14 + $zoom_level ?>,
            maxZoom: 17,
        }).fitBounds(new L.LatLngBounds(
            new L.LatLng(-0.009596066652927592 , -0.017070832783576906),
            new L.LatLng(0.009767264080431519 , 0.016243451420088206)
        ));
        // leaflet.fitBounds(new L.LatLngBounds(
        //     new L.LatLng(-0.010596066652927592 , -0.015070832783576906),
        //     new L.LatLng(0.009767264080431519 , 0.016243451420088206)
        // ));
    }
    // if($(window).width() <= 575) {
    //     leaflet.fitBounds(new L.LatLngBounds(
    //         new L.LatLng(0.062724399462052194 , 0.2819017922608721707),
    //         new L.LatLng(0.011119097401443085 , 0.020277500152587894)
    //     ));
    // }
    leaflet.setMinZoom(15);
    var leafletShowingPopupClickPath;
    var currentTileLayer;
    var paths = {};
    var facilities = {};

    var shopList = $('#menu .shop-list');
    leaflet.on("contextmenu", function(event) {
        console.log("Coordinates: " + event.latlng.lat + ' , ' + event.latlng.lng);
        //L.marker(event.latlng).addTo(map);
    });
    let fragment = document.createDocumentFragment(); // Create a fragment to store all elements
    function loadShopData() {
        let fragment = document.createDocumentFragment(); // Create a fragment to store all elements
        for (var shopNumber in shopData) {
            var shopRow = shopData[shopNumber][0];
            
            var shopListShop = $('<a>')
                .attr('href', 'javascript:void(0)')
                .data('shop', shopNumber)
                .addClass('shop hide-shop')
                .click(function() {
                    focusShop($(this).data('shop'));
                });
            
            var shopVerticalCenterer = $('<div>').addClass('shop-vertical-centerer');
            var imgSrc = shopRow.featured ? shopRow.featured : shopRow.thumbnail;
            shopVerticalCenterer.append($('<img>').attr('src', imgSrc));
            
            shopVerticalCenterer.append(
                $('<div>')
                    .addClass('shop-info')
                    .append($('<div>').addClass('shop-name').text(shopRow.name))
                    .append($('<div>').addClass('shop-floor-number').text(shopRow.shop_location_text))
            );
            
            let shopInfoMobile = `<div class="content-for-mobile" style="display: none">
                <h5>${shopRow.name}</h5>
                <div class="shop-name">
                    <span class="icon">
                        <svg width="12" height="18" viewBox="0 0 12 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M5.99984 8.58342C5.4473 8.58342 4.9174 8.36392 4.5267 7.97322C4.136 7.58252 3.9165 7.05262 3.9165 6.50008C3.9165 5.94755 4.136 5.41764 4.5267 5.02694C4.9174 4.63624 5.4473 4.41675 5.99984 4.41675C6.55237 4.41675 7.08228 4.63624 7.47298 5.02694C7.86368 5.41764 8.08317 5.94755 8.08317 6.50008C8.08317 6.77367 8.02928 7.04458 7.92459 7.29734C7.81989 7.5501 7.66643 7.77977 7.47298 7.97322C7.27952 8.16668 7.04986 8.32013 6.79709 8.42483C6.54433 8.52953 6.27342 8.58342 5.99984 8.58342ZM5.99984 0.666748C4.45274 0.666748 2.96901 1.28133 1.87505 2.37529C0.781085 3.46925 0.166504 4.95299 0.166504 6.50008C0.166504 10.8751 5.99984 17.3334 5.99984 17.3334C5.99984 17.3334 11.8332 10.8751 11.8332 6.50008C11.8332 4.95299 11.2186 3.46925 10.1246 2.37529C9.03066 1.28133 7.54693 0.666748 5.99984 0.666748Z" fill="#73D4D4"></path>
                        </svg>
                    </span>
                    <span>${shopRow.shop_location_text}</span>
                </div>
                <div class="oppen-hour">
                    <span class="icon">${shopRow.opening_hours.length > 0 ? shopRow.opening_hours.join('<br>') : shopRow.open_hours}</span>
                </div>
                <div class="phone-number">
                    <span class="icon">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12.4496 15.9696C9.32577 15.9655 6.33105 14.7228 4.12217 12.5139C1.91328 10.305 0.670553 7.31029 0.666504 4.18646C0.666504 3.253 1.03732 2.35777 1.69738 1.69771C2.35744 1.03765 3.25267 0.666835 4.18613 0.666835C4.3838 0.66533 4.58113 0.683269 4.77529 0.720395C4.96298 0.74817 5.1475 0.794299 5.32618 0.85812C5.45186 0.902214 5.56384 0.978348 5.65107 1.07899C5.73829 1.17964 5.79774 1.30131 5.82352 1.43197L6.87176 6.02279C6.90001 6.1474 6.89661 6.2771 6.86186 6.40006C6.82711 6.52302 6.76212 6.63532 6.67282 6.72671C6.57336 6.83383 6.5657 6.84148 5.62459 7.33117C6.37824 8.98452 7.70055 10.3123 9.3508 11.0727C9.84814 10.1239 9.85579 10.1163 9.96291 10.0168C10.0543 9.9275 10.1666 9.86251 10.2896 9.82776C10.4125 9.79301 10.5422 9.78961 10.6668 9.81786L15.2577 10.8661C15.3841 10.8954 15.5011 10.9565 15.5974 11.0435C15.6938 11.1305 15.7665 11.2406 15.8085 11.3634C15.8731 11.545 15.9218 11.7319 15.9539 11.922C15.9847 12.1143 16.0001 12.3087 15.9998 12.5035C15.9857 13.433 15.6045 14.3191 14.9393 14.9685C14.2742 15.6179 13.3791 15.9777 12.4496 15.9696Z" fill="#73D4D4"></path>
                        </svg>
                    </span>
                    <span>${shopRow.phone}</span>
                </div>
            </div>`;
            
            shopVerticalCenterer.append(shopInfoMobile);
            shopListShop.append(shopVerticalCenterer);
            fragment.appendChild(shopListShop[0]);
        }
        shopList.append(fragment);
    }
    $(document).ready(function() {
        $('#triggerShopLoad').click(function(event) {
            event.preventDefault(); 
            loadShopData();       
            return false;         
        });
    });
shopList.append(fragment);

    function menuSearchFloor(floor) {
        if (floor == null) {
            floor = '';
        }
        $('#menu .floor-list > *').removeClass('active');
        $('#menu .floor-list [data-floor="' + floor + '"]').addClass('active');
        menuSearchFilter();
    }

    function menuSearchFilter() {
        var searchFilter = $('#menu-search-filter').val();
        var searchFloor = '';
        var searchFloorActive = $('#menu .floor-list > .active');
        if (searchFloorActive.length > 0) {
            searchFloor = searchFloorActive.data('floor');
        }
        $('#menu .shop-list').children().each(function() {
            var shopNumber = $(this).data('shop');
            var shopRow = shopData[shopNumber][0];
            if (searchFloor != '' && shopRow.floor != searchFloor) {
                $(this).addClass('hidden').hide();
                return;
            }
            if (searchFilter != '' && shopRow.name.toLowerCase().indexOf(searchFilter.toLowerCase()) < 0) {
                $(this).addClass('hidden').hide();
                return;
            }
            $(this).removeClass('hidden').show();
        });
    }


    function zoomIn() {
        leaflet.zoomIn();
    }

    function zoomOut() {
        leaflet.zoomOut();
    }

    function changeFloor(floor) {
        if (floor == currentFloor) {
            return;
        }
        currentFloor = floor;
        if($('.map-name-custom').length > 0) {
            $('.map-name-custom').text(floor);
        }
        if ($('.symbols span.icons').length > 0) {
                $('.symbols .label').removeClass('active');
                $('.symbols').removeClass('show-list');
                //$('.symbols .list-symbols').hide();
                var arrIndex = [];
                var symbolShow = false;
                $('.symbols span.icons').each(function() {
                    arrIndex.push($(this).attr('data-phase-floor').indexOf(currentFloor));
                })
                
                for (var i = 0; i < arrIndex.length; i++) {
                    if (arrIndex[i] != -1) {
                        symbolShow = true;
                    }
                }
                
                if (symbolShow == true) {
                    $('.symbols').removeClass('disabled-all');
                    $('.symbols span.icons').each(function() {
                        
                        if ($(this).attr('data-phase-floor').indexOf(currentFloor) == -1) {
                            $(this).addClass('disabled');
                            $(this).hide();
                        } else {
                            $(this).removeClass('disabled');
                            $(this).show();
                        }
                    })
                } else {
                    $('.symbols').addClass('disabled-all');
                }
            }
        if (leafletShowingPopupClickPath != null) {
            leaflet.closePopup(leafletShowingPopupClickPath.popupClick);
        }
        var newLayer = L.tileLayer('<?php echo $current_path ?>resources/floorplan/tile.php?floor=' + floor + '&z={z}&x={x}&y={y}', {

        }).addTo(leaflet);
        if (currentTileLayer != null) {
            leaflet.removeLayer(currentTileLayer);
        }
        for (var faciliy in facilities) {
            leaflet.removeLayer(facilities[faciliy]);
            delete facilities[faciliy];
        }
        currentTileLayer = newLayer;
        $('.controls .floors a').removeClass('active');
        $('.controls .floors a.floor-' + floor.toLowerCase()).addClass('active');
        $('.controls.floors-mobile select').val(floor);

        for (var i in paths) {
            paths[i].remove();
        }

        var buildPaths = buildSvgPaths(floor);

        for (var shopId in buildPaths) {
            if (!shopData.hasOwnProperty(shopId)) {
                continue;
            }
            var shopDetail = shopData[shopId];
            //console.log(shopDetail);
            var buildPath = buildPaths[shopId];
            var path;
            switch (buildPath.type) {
                case 'polygon':
                    path = L.polygon(buildPath.coords, {
                        className: 'shop-clickable'
                    });
                    break;
                case 'curve':
                    path = L.curve(buildPath.coords, {
                        className: 'shop-clickable'
                    });
                    break;
                default:
                    continue;
            }
            path.addTo(leaflet);
            path.shopId = shopId;

            var popupClick = L.popup({
                className: 'shop-popup-click',
                closeOnClick: false,
                autoPan: false,
                autoClose: false,
                maxWidth: 93,
                minWidth: 93,
            });
            let shopDetailPopupHtml = '';
            for(let i=0; i < shopDetail.length; i++){
                let shopDetailThumbnail = '';
                let shopDetailAdditionClass = '';
                if(shopDetail[i].featured != null){
                    shopDetailThumbnail = '<img src="' + shopDetail[i].featured + '" class="shop-popup-click-icon" />';
                } else {
                    if(shopDetail[i].thumbnail != null){
                        shopDetailThumbnail = '<img src="' + shopDetail[i].thumbnail + '" class="shop-popup-click-icon" />';
                    } else{
                        shopDetailAdditionClass = 'no-image';
                    }
                }
                shopDetailPopupHtml += '<div class="shop-detail-popup shop-detail-popup-'+ i + ' ' + shopDetailAdditionClass +'"><a href="' + shopDetail[i].url + '" target="_top" class="shop-popup-click-content">' + shopDetailThumbnail + '<div>' + shopDetail[i].name + '</div></a></div>';
            }
            popupClick.setContent(shopDetailPopupHtml);
            popupClick.setLatLng([path.getBounds().getCenter().lat + 0.0004, path.getBounds().getCenter().lng]);
            popupClick.on('add', function() {
                leafletShowingPopupClickPath = this.path;
                $(this.path._path).addClass('active');
            });
            popupClick.on('remove', function() {
                $(this.path._path).removeClass('active');
                leafletShowingPopupClickPath = null;
            });
            path.focus = function() {
                leaflet.panTo(this.getBounds().getCenter());
            };
            path.popupClick = popupClick;
            popupClick.path = path;
            path.showPopupClick = function() {
                if (leafletShowingPopupClickPath != null) {
                    leaflet.closePopup(leafletShowingPopupClickPath.popupClick);
                }
                leaflet.openPopup(this.popupClick);
            };

            path.on('click', function() {
                this.showPopupClick();
            });

            var popupHover = L.popup({
                className: 'shop-popup-hover',
                closeButton: false,
                autoPan: false,
                autoClose: false,
                maxWidth: 93,
                minWidth: 93,
            });
            let shopDetailHoverHtml = '';
            for(let i=0; i < shopDetail.length; i++){
                shopDetailHoverHtml += '<div class="shop-detail-hover shop-detail-hover-'+ i +'">' + shopDetail[i].name + '</div>';
            }
            popupHover.setContent(shopDetailHoverHtml);
            popupHover.setLatLng([path.getBounds().getNorth() + 0.0004, path.getBounds().getCenter().lng]);
            path.popupHover = popupHover;
            path.showPopupHover = function() {
                leaflet.openPopup(this.popupHover);
            };

            path.on('mouseover', function() {
                this.showPopupHover();
            });
            path.on('mousemove', function(e) {
                this.popupHover.setLatLng([e.latlng.lat + 0.0004, e.latlng.lng]);
            });
            path.on('mouseout', function() {
                leaflet.closePopup(this.popupHover);
            });
            paths[shopId] = path;
        }
        for (var facility in availableFacilities) {
            facilities[facility] = L.imageOverlay('<?php echo $current_path ?>resources/floorplan/facilities/' + floor + '/' + facility + '.svg', new L.LatLngBounds(
                new L.LatLng(-0.014462470854731133, -0.021672248840332032),
                new L.LatLng(0.014462470854731133, 0.02167224884033203)
            ), {
                className: 'layer-facility-' + facility
            }).addTo(leaflet);
            var isAvailable = availableFacilities[facility].availableFloors.indexOf(floor) != -1;
            if (isAvailable) {
                $('body').removeClass('facility-' + facility + '-none');
                $('body').addClass('facility-' + facility + '-active');
            } else {
                $('body').removeClass('facility-' + facility + '-active');
                $('body').addClass('facility-' + facility + '-none');
            }
        }
    }

    function focusShop(number) {
        //console.log(shopData.hasOwnProperty(number));
        if (!shopData.hasOwnProperty(number)) {
            return;
        }
        changeFloor(shopData[number][0].floor);
        var path = paths[number];
        //console.log(path);
        path.showPopupClick();
        path.focus();
        // $('html,body').animate({
        //     scrollTop: 0
        // }, {
        //     duration: 200
        // });
    }

    function resize() {
        var menu = $('#menu');
        var windowWidth = $(window).width();
        var windowHeight = $(window).height();
        if (windowWidth <= 991) {
            menu.children('.shop-list').height('');
            return;
        }
        var menuShopListAvailableHeight = windowHeight;
        menu.children().filter(function() {
            return !$(this).hasClass('shop-list');
        }).each(function() {
            menuShopListAvailableHeight -= $(this).outerHeight(true);
        });
        menu.children('.shop-list').height(menuShopListAvailableHeight);
        <?php
        if ($is_no_controls) {
        ?>
            var controls = $('.controls.controls-right');
            controls.css('top', (windowHeight - controls.outerHeight(true)) / 2);
        <?php
        }
        ?>
    }

    $(window).resize(function() {
        resize();
    });

    function buildSvgPaths(floor) {
        switch (floor) {
            case 'B1':
                return {
                    "B101-B104": {
                        "type": "polygon",
                        "coords": [
                            [-0.0026822090139109757 , -0.009807930553598078],
                            [-0.003347396848675323 , -0.009807930553598078],
                            [-0.003347396848675323 , -0.009485910102673412],
                            [-0.004141330715386199 , -0.009485910102673412],
                            [-0.004141330715386199 , -0.008562784810046688],
                            [-0.006136894214335909 , -0.008562784810046688],
                            [-0.006136894214335909 , -0.004870283639519802],
                            [-0.0026822090139109757 , -0.004848815609466151],
                        ]
                    },
                    "B105-B108": {
                        "type": "polygon",
                        "coords": [
                            [-0.006158351886326997 , -0.008863337230897718],
                            [-0.007038116437376395 , -0.008841869200844068],
                            [-0.007038116437376395 , -0.008627188900227624],
                            [-0.009098052940276873 , -0.008627188900227624],
                            [-0.009098052940276873 , -0.002530268362852528],
                            [-0.006158351886326997 , -0.002530268362852528],
                        ]
                    },
                    "B109-B110": {
                        "type": "polygon",
                        "coords": [
                            [-0.0026822090139109757 , -0.0005122735370899357],
                            [-0.005257129661810734 , -0.0005122735370899357],
                            [-0.005235671989781479 , -0.0036895419861493477],
                            [-0.004956722253337553 , -0.0036895419861493477],
                            [-0.004913806909253599 , -0.0026161404830871153],
                            [-0.003798007962307454 , -0.002594672453033464],
                            [-0.00377655029022731 , -0.0037110100162229824],
                            [-0.0026607513418181097 , -0.0037110100162229824],
                        ]
                    },
                    "B111-B113": {
                        "type": "polygon",
                        "coords": [
                            [-0.0026822090139109757 , -0.0005122735370899357],
                            [-0.005257129661810734 , -0.0005122735370899357],
                            [-0.005235671989781479 , 0.004854733978181258],
                            [-0.0026822090139109757 , 0.004854733978181258],
                        ]
                    },
                    "B116": {
                        "type": "polygon",
                        "coords": [
                            [-0.006154328572832644 , -0.0021623184065489025],
                            [-0.006755143388354116 , -0.0021837864366225372],
                            [-0.00677660106031976 , -0.001325065234176748],
                            [-0.007441788890885774 , -0.001325065234176748],
                            [-0.007463246562825974 , -0.0005522161519655455],
                            [-0.007291585187304378 , -0.0004019399415300385],
                            [-0.006175786244836454 , -0.00042340797160367316],
                        ]
                    },
                    "B117-B118": {
                        "type": "polygon",
                        "coords": [
                            [-0.006154328572832644 , 0.0031617530486149907],
                            [-0.009094029626807963 , 0.0031617530486149907],
                            [-0.009094029626807963 , -0.00029459979124180086],
                            [-0.007935315345192305 , -0.00029459979124180086],
                            [-0.007162839155612294 , -0.00003698343049807207],
                            [-0.006154328572832644 , -0.0000584514605517228],
                        ]
                    },
                    "B119": {
                        "type": "polygon",
                        "coords": [
                            [-0.006154328572832644 , 0.0031617530486149907],
                            [-0.009094029626807963 , 0.0031617530486149907],
                            [-0.00907257195495682 , 0.007884719662096806],
                            [-0.00791385767327755 , 0.007884719662096806],
                            [-0.00791385767327755 , 0.0074768270909375545],
                            [-0.0076992809540663894 , 0.007240678760247478],
                            [-0.006154328572832644 , 0.007240678760247478],
                        ]
                    },
                    "B120-B122": {
                        "type": "curve",
                        "coords": [
                            "M", [-0.009094029626807963 , 0.00921275443856562],
                            "L", [-0.009094029626807963 , 0.013291680150198106],
                            "L", [-0.005961209524836516 , 0.014085997262442975],
                            "L", [-0.005467683068558041 , 0.014085997262442975],
                            "L", [-0.005467683068558041 , 0.013721040751430992],
                            "L", [-0.005253106348303658 , 0.013721040751430992],
                            "L", [-0.005253106348303658 , 0.012883787579038854],
                            "L", [-0.006218701588818631 , 0.012883787579038854],
                            "L", [-0.006218701588818631 , 0.012261214707263157],
                            "L", [-0.005853921164817463 , 0.012261214707263157],
                            "L", [-0.005832463492800931 , 0.009427434739182061],
                            "C", [-0.005832463492800931 , 0.009427434739182061],
                                 [-0.006111413228837745 , 0.008847797927513668],
                                 [-0.006111413228837745 , 0.008074948845302467],
                            "L", [-0.006798058732298126 , 0.008074948845302467],
                            "L", [-0.007055550795847685 , 0.008375501266173481],
                            "L", [-0.0070770084678006065 , 0.00921275443856562],
                            "Z"
                        ]
                    },
                    "B123-B125": {
                        "type": "polygon",
                        "coords": [
                            [0.0019352138038878513 , 0.01406444636885329],
                            [0.001913756131769541 , 0.01037194519834639],
                            [0.001785010099123289 , 0.01037194519834639],
                            [0.001785010099123289 , 0.010092860807549012],
                            [-0.0001461803913083755 , 0.010092860807549012],
                            [-0.00016763806342668598 , 0.007709909470734467],
                            [-0.0016053020952008208 , 0.007709909470734467],
                            [-0.0016482174394247197 , 0.00940588384559238],
                            [-0.005832463492800931 , 0.00940588384559238],
                            [-0.005832463492800931 , 0.01249728017439722],
                            [-0.004888325923704547 , 0.012518748204470853],
                            [-0.004866868251675292 , 0.01374242591794861],
                            [-0.0034292042234888224 , 0.01374242591794861],
                            [-0.0034292042234888224 , 0.012239663813673474],
                            [-0.0030215084538770345 , 0.012239663813673474],
                            [-0.0030215084538770345 , 0.011917643362728828],
                            [-0.0015409290788713339 , 0.011917643362728828],
                            [-0.0015194714067530235 , 0.014085914398926926],
                        ]
                    },
                    "B126": {
                        "type": "curve",
                        "coords": [
                            "M", [0.0019352138038878513 , 0.01406444636885329],
                            "L", [0.0019352138038878513 , 0.010200200957837247],
                            "L", [0.0022570788854462307 , 0.009985520657220805],
                            "C", [0.0022570788854462307 , 0.009985520657220805],
                                 [0.001849383115452776 , 0.009620564146208823],
                                 [0.0018064677712415996 , 0.008869183094031287],
                            "C", [0.0018064677712415996 , 0.008869183094031287],
                                 [0.0019566714759934396 , 0.007924589771350911],
                                 [0.0026862323274307737 , 0.007817249621062674],
                            "C", [0.0026862323274307737 , 0.007817249621062674],
                                 [0.003329962490114977 , 0.007624037350519864],
                                 [0.003866404292042239 , 0.008096334011860051],
                            "L", [0.004274100061272361 , 0.0076884414407008],
                            "L", [0.005497187367588725 , 0.0076884414407008],
                            "L", [0.00551864503961798 , 0.010114328837582679],
                            "L", [0.006827563031265199 , 0.010114328837582679],
                            "L", [0.00684902070321812 , 0.01088717791979388],
                            "L", [0.008458346097753465 , 0.010908645949867514],
                            "L", [0.008458346097753465 , 0.012475812144323584],
                            "L", [0.0068704783751837644 , 0.014085914398926926],
                            "Z"
                        ]
                    },
                    // "xxx": {
                    //     "type": "polygon",
                    //     "coords": [
                            
                    //     ]
                    // },
                    // "xx": {
                    //     "type": "curve",
                    //     "coords": [
                    //         "M", [xxxx],
                    //         "L", [xxxx],
                    //         "L", [xxxx],
                    //         "C", [xxxx],
                    //              [xxxx],
                    //              [xxxx],
                    //         "L", [xxxx],
                    //         "Z"
                    //     ]
                    // },
                };
            case 'L1':
                return {
                    "101-102": {
                        "type": "curve",
                        "coords": [
                            "M", [0.0018064677712415996 , -0.00290990845189798],
                            "L", [0.000497549772266383 , -0.00290990845189798],
                            "L", [0.000497549772266383 , -0.002158527399760413],
                            "L", [-0.0005109608173196536 , -0.002158527399760413],
                            "L", [-0.0005109608173196536 , -0.0034466092034390887],
                            "C", [-0.0005109608173196536 , -0.0034466092034390887],
                                 [-0.00046804547308303254 , -0.0038115657144710555],
                                 [0.000025480985650830485 , -0.0038330337445446894],
                            "L", [0.0018064677712415996 , -0.0033178010230772163],
                            "Z"
                        ]
                    },
                    "103-106": {
                        "type": "curve",
                        "coords": [
                            "M", [0.0018064677712415996 , -0.00290990845189798],
                            "L", [0.000497549772266383 , -0.00290990845189798],
                            "L", [0.000497549772266383 , -0.00016200060407145503],
                            "L", [-0.0004036724567408234 , 0.0007611246885552705],
                            "L", [-0.00042513012884641164 , 0.0029079276946797354],
                            "L", [0.0000683963298747292 , 0.0033372882959126224],
                            "C", [0.0000683963298747292 , 0.0033372882959126224],
                                 [0.0001542270183606933 , 0.0033802243560199234],
                                 [0.00021860003471562472 , 0.003315820265838987],
                            "L", [0.0016562640664643151 , 0.00192039831185209],
                            "L", [0.001699179410688214 , 0.00041763620757695463],
                            "L", [0.004059523340649033 , 0.0004391042376505894],
                            "L", [0.004059523340649033 , -0.000720169385666214],
                            "L", [0.0037376582595995424 , -0.0007416374157198647],
                            "L", [0.003716200587532121 , -0.0021370593697067623],
                            "L", [0.0026218593111394534 , -0.002158527399760413],
                            "Z"
                        ]
                    },
                    "107": {
                        "type": "curve",
                        "coords": [
                            "M", [0.0018922984596639527 , 0.005630504000082583],
                            "L", [0.002965182064638032 , 0.004621506587181302],
                            "L", [0.002943724392557888 , 0.00296846827247066],
                            "L", [0.0020639598365086585 , 0.00296846827247066],
                            "L", [0.0009481608867127363 , 0.0041062738657338125],
                            "C", [0.0009481608867127363 , 0.0041062738657338125],
                                 [0.0007550418376733865 , 0.004406826286564859],
                                 [0.000926703214607148 , 0.004685910677362238],
                            "Z"
                        ]
                    },
                    "108-109": {
                        "type": "polygon",
                        "coords": [
                            [0.0018922984596639527 , 0.005630504000082583],
                            [0.002965182064638032 , 0.004621506587181302],
                            [0.002965182064638032 , 0.0022600232804403935],
                            [0.0032870471459546893 , 0.0022385552504067263],
                            [0.0032870471459546893 , 0.0012080898074717796],
                            [0.002965182064638032 , 0.0011866217773981449],
                            [0.002965182064638032 , 0.0008216652663461944],
                            [0.004510134453886776 , 0.0008001972363125277],
                            [0.004531592125941475 , 0.003805721444862798],
                            [0.00517532228720079 , 0.004406826286564859],
                            [0.0029222667204523 , 0.0067039055031248304],
                        ]
                    },
                    "110": {
                        "type": "polygon",
                        "coords": [
                            [0.00517532228720079 , 0.004406826286564859],
                            [0.0029222667204523 , 0.0067039055031248304],
                            [0.004080981012716455 , 0.007884647156495284],
                            [0.005904883135839235 , 0.006038396571241834],
                            [0.007106512766742235 , 0.006038396571241834],
                            [0.0071279704386951566 , 0.005351419609285202],
                            [0.006033629167849376 , 0.005329951579211567],
                        ]
                    },
                    "111": {
                        "type": "polygon",
                        "coords": [
                            [0.004080981012716455 , 0.007884647156495284],
                            [0.005904883135839235 , 0.006038396571241834],
                            [0.007106512766742235 , 0.006038396571241834],
                            [0.0071279704386951566 , 0.007519690645443334],
                            [0.0064842402796622305 , 0.007541158675516969],
                            [0.0051538646151715346 , 0.008893644569356597],
                        ]
                    },
                    "112A": {
                        "type": "polygon",
                        "coords": [
                            [0.0071279704386951566 , 0.007519690645443334],
                            [0.0064842402796622305 , 0.007541158675516969],
                            [0.0051538646151715346 , 0.008893644569356597],
                            [0.006248205887811148 , 0.01005027691745486],
                            [0.006376951919744956 , 0.009900000707019352],
                            [0.006398409591736044 , 0.008976875414372644],
                            [0.0071279704386951566 , 0.00824696239230871],
                        ]
                    },
                    "112B&113": {
                        "type": "polygon",
                        "coords": [
                            [0.006205290543828972 , 0.010074199779785967],
                            [0.006376951919744956 , 0.009900000707019352],
                            [0.006398409591736044 , 0.008976875414372644],
                            [0.0071279704386951566 , 0.00824696239230871],
                            [0.007728785252969852 , 0.008805131173903469],
                            [0.008029192659801865 , 0.008461642692925153],
                            [0.008780211175889568 , 0.008461642692925153],
                            [0.008780211175889568 , 0.010479637518687747],
                            [0.006634443983523515 , 0.010482092350945217],
                        ]
                    },
                    "115": {
                        "type": "polygon",
                        "coords": [
                            [0.004059523340649033 , 0.014021862478759143],
                            [0.004059523340649033 , 0.012476164314376705],
                            [0.004510134453886776 , 0.01198239962296288],
                            [0.005325525991405574 , 0.012669376584919515],
                            [0.00526115297531781 , 0.014021862478759143],
                        ]
                    },
                    "116-120": {
                        "type": "curve",
                        "coords": [
                            "M", [0.00021860003471562472 , 0.008461290522872034],
                            "L", [0.0002400577068339352 , 0.014042978338779658],
                            "L", [0.004059523340649033 , 0.014042978338779658],
                            "L", [0.004059523340649033 , 0.01249728017439722],
                            "L", [0.004488676781832076 , 0.012003515482983397],
                            "L", [0.0012056529521070176 , 0.008783310973816684],
                            "C", [0.0012056529521070176 , 0.008783310973816684],
                                 [0.0007121264934367655 , 0.008353950372583798],
                                 [0.00021860003471562472 , 0.008461290522872034],
                            "Z"
                        ]
                    },
                    "121": {
                        "type": "curve",
                        "coords": [
                            "M", [0.00021860003471562472 , 0.008461290522872034],
                            "L", [0.00021860003471562472 , 0.012282599873780777],
                            "L", [-0.0015194714067530235 , 0.012282599873780777],
                            "L", [-0.0015194714067530235 , 0.009599096116135188],
                            "C", [-0.0015194714067530235 , 0.009599096116135188],
                                 [-0.00100448727601535 , 0.00940588384559238],
                                 [-0.0007899105548576896 , 0.009019459304466794],
                            "C", [-0.0007899105548576896 , 0.009019459304466794],
                                 [-0.00042513012884641164 , 0.008418354462764734],
                                 [0.00021860003471562472 , 0.008461290522872034],
                            "Z"
                        ]
                    },
                    "122-127": {
                        "type": "curve",
                        "coords": [
                            "M", [-0.004630833859162654 , 0.01402151030874599],
                            "L", [-0.004630833859162654 , 0.012282599873780777],
                            "L", [-0.007055550795847685 , 0.012304067903854412],
                            "C", [-0.007055550795847685 , 0.012304067903854412],
                                 [-0.0070984661397535285 , 0.011874707302621527],
                                 [-0.00701263545192912 , 0.01172443109218602],
                            "C", [-0.00701263545192912 , 0.01172443109218602],
                                 [-0.0068624317481950576 , 0.010693965649251071],
                                 [-0.005853921164817463 , 0.010092860807549012],
                            "C", [-0.005853921164817463 , 0.010092860807549012],
                                 [-0.0055105984125911065 , 0.009770840356604362],
                                 [-0.004201680418055944 , 0.009620564146208823],
                            "L", [-0.002034455537363475 , 0.009599096116135188],
                            "L", [-0.002034455537363475 , 0.01402151030874599],
                            "Z"
                        ]
                    },
                    "128-130": {
                        "type": "polygon",
                        "coords": [
                            [-0.004630833859162654 , 0.01402151030874599],
                            [-0.004609376187120677 , 0.012282599873780777],
                            [-0.007055550795847685 , 0.012282599873780777],
                            [-0.007034093123882042 , 0.01365655379769404],
                            [-0.0055320560846203615 , 0.01406444636885329],
                        ]
                    },
                    "131A": {
                        "type": "polygon",
                        "coords": [
                            [-0.008707791533321985 , 0.009814149302673771],
                            [-0.008707791533321985 , 0.010565530354811338],
                            [-0.00754907725057405 , 0.010565530354811338],
                            [-0.00754907725057405 , 0.009814149302673771],
                        ]
                    },
                    "131": {
                        "type": "polygon",
                        "coords": [
                            [-0.008707791533321985 , 0.007624410236402036],
                            [-0.006669312700466096 , 0.007624410236402036],
                            [-0.006669312700466096 , 0.009406256731474551],
                            [-0.008707791533321985 , 0.009427724761548186],
                        ]
                    },
                    "139&142": {
                        "type": "polygon",
                        "coords": [
                            [-0.006840974076229413 , 0.0024972695529879023],
                            [-0.006840974076229413 , -0.0008517431365606677],
                            [-0.008750706877062439 , -0.0008732111666143184],
                            [-0.008772164548926304 , 0.002518737583061537],
                        ]
                    },
                    "140": {
                        "type": "polygon",
                        "coords": [
                            [-0.006840974076229413 , 0.0024972695529879023],
                            [-0.0068195164042637695 , 0.0009300826426228427],
                            [-0.004351884122477006 , 0.0009515506726964775],
                            [-0.004330426450422307 , 0.0025187168671525484],
                        ]
                    },
                    "141": {
                        "type": "polygon",
                        "coords": [
                            [-0.006840974076229413 , -0.0008517431365606677],
                            [-0.0068195164042637695 , 0.0009300826426228427],
                            [-0.004351884122477006 , 0.0009515506726964775],
                            [-0.004373341794531706 , -0.0008946999125569734],
                        ]
                    },
                    "143-145": {
                        "type": "polygon",
                        "coords": [
                            [-0.004373341794531706 , -0.0008946999125569734],
                            [-0.004373341794531706 , -0.002590674287394901],
                            [-0.0073345005311975 , -0.002590674287394901],
                            [-0.0073345005311975 , -0.0008732318825033226],
                        ]
                    },
                    "132-138": {
                        "type": "curve",
                        "coords": [
                            "M", [-0.006647855028500452 , 0.007624648469075625],
                            "L", [-0.008707791533321985 , 0.007624648469075625],
                            "L", [-0.008707791533321985 , 0.0028158097353792044],
                            "L", [-0.005360394708437211 , 0.0028158097353792044],
                            "L", [-0.005338937036407956 , 0.0034383826071549004],
                            "L", [-0.005145817988144661 , 0.0036101268476240738],
                            "L", [-0.004330426450422307 , 0.0036315948776977085],
                            "L", [-0.004308968778354886 , 0.007538776348821053],
                            "C", [-0.004308968778354886 , 0.007538776348821053],
                                 [-0.0045664608430112785 , 0.0085263057316487],
                                 [-0.005059987300014919 , 0.008655113912010572],
                            "C", [-0.005059987300014919 , 0.008655113912010572],
                                 [-0.0057895481487933095 , 0.00912741057335076],
                                 [-0.006647855028500452 , 0.009191814663531694],
                            "Z"
                        ]
                    },
                    "146-170": {
                        "type": "curve",
                        "coords": [
                            "M", [0.0003902614116748307 , -0.013945107737836084],
                            "L", [-0.008772164548926304 , -0.013945107737836084],
                            "L", [-0.008772164548926304 , -0.010059394296746406],
                            "L", [-0.005296021692362168 , -0.010037926266692754],
                            "L", [-0.005296021692362168 , -0.008943056733576872],
                            "L", [-0.008772164548926304 , -0.008943056733576872],
                            "L", [-0.008750706877062439 , -0.002588519835458048],
                            "L", [-0.004373341794531706 , -0.002588519835458048],
                            "L", [-0.004351884122477006 , -0.0034687090679574877],
                            "L", [-0.0019271671768355334 , -0.0034687090679574877],
                            "C", [-0.0019271671768355334 , -0.0034687090679574877],
                                 [-0.0016911327836358961 , -0.004091281939733183],
                                 [-0.0011761486529491114 , -0.004585046631147006],
                            "C", [-0.0011761486529491114 , -0.004585046631147006],
                                 [-0.0007684528827393792 , -0.005100279352614479],
                                 [0.00041171908379314116 , -0.004992939202306258],
                            "Z"
                        ]
                    },
                    "Broadway Cinema": {
                        "type": "polygon",
                        "coords": [
                            [0.00041171908379314116 , -0.013943792279194067],
                            [0.00041171908379314116 , -0.004991623743664242],
                            [0.00461742281414755 , -0.004218774661473025],
                            [0.008844584191493888 , -0.004218795377342044],
                            [0.008844584191493888 , -0.012269306650298796],
                            [0.007149428110648079 , -0.013965281025136726],
                        ]
                    },
                };
            case 'L2':
                return {
                    "201-202": {
                        "type": "polygon",
                        "coords": [
                            [0.0014416873453575439 , -0.0054038666504951935],
                            [0.0014416873453575439 , -0.003149723494062507],
                            [0.007192343454553922 , -0.003149723494062507],
                            [0.007192343454553922 , -0.005382398620441542],
                        ]
                    },
                    "203-205": {
                        "type": "curve",
                        "coords": [
                            "M", [0.0014202296732519556 , -0.003149723494062507],
                            "L", [0.0014202296732519556 , -0.0016469613897873716],
                            "C", [0.0014202296732519556 , -0.0016469613897873716],
                                 [0.001699179410688214 , -0.000981452457884391],
                                 [0.0022785365575645413 , -0.0007238360971606462],
                            "L", [0.003265589473861823 , -0.001475217149298214],
                            "L", [0.003265589473861823 , -0.003128255464008856],
                            "Z"
                        ]
                    },
                    "206-207": {
                        "type": "polygon",
                        "coords": [
                            [0.0035874545550767024 , 0.00034956540590158635],
                            [0.0043384730774491806 , -0.0004447517063632667],
                            [0.005089491599071048 , -0.0004447517063632667],
                            [0.005089491599071048 , -0.001303472908809056],
                            [0.004510134453886776 , -0.001324940938862706],
                            [0.004510134453886776 , -0.0017113654799683078],
                            [0.004123896356838576 , -0.0016898974499146568],
                            [0.0025789439669537213 , -0.000509155796544203],
                        ]
                    },
                    "208": {
                        "type": "polygon",
                        "coords": [
                            [0.0035874545550767024 , 0.00034956540590158635],
                            [0.0043384730774491806 , -0.0004447517063632667],
                            [0.005089491599071048 , -0.0004447517063632667],
                            [0.005110949271100303 , 0.0003066293457942848],
                            [0.004317015405381759 , 0.0010794784280054872],
                        ]
                    },
                    "209-210": {
                        "type": "polygon",
                        "coords": [
                            [0.005089491599071048 , -0.0004447517063632667],
                            [0.005089491599071048 , -0.001303472908809056],
                            [0.004510134453886776 , -0.001324940938862706],
                            [0.004531592125941475 , -0.0028706391032651273],
                            [0.006119459855839174 , -0.002849171073211477],
                            [0.006162375199834073 , 0.0010150743378245508],
                            [0.005883425463835425 , 0.000993606307750916],
                            [0.00506803392702907 , 0.0018308594801430549],
                            [0.004317015405381759 , 0.0010794784280054872],
                            [0.005110949271100303 , 0.0003066293457942848],
                        ]
                    },
                    "211": {
                        "type": "curve",
                        "coords": [
                            "M", [0.005904883135839235 , 0.0030330691635471756],
                            "L", [0.006441324935705498 , 0.0027969208328970656],
                            "L", [0.0075142085336823575 , 0.002775452802823431],
                            "L", [0.0075142085336823575 , 0.0013585628188028667],
                            "L", [0.00622674821582006 , 0.0013585628188028667],
                            "L", [0.00545427202355566 , 0.0021743479611213705],
                            "C", [0.00545427202355566 , 0.0021743479611213705],
                                 [0.005776137103790928 , 0.0025607725022069876],
                                 [0.005904883135839235 , 0.0030330691635471756],
                            "Z"
                        ]
                    },
                    "212": {
                        "type": "curve",
                        "coords": [
                            "M", [0.005904883135839235 , 0.0030330691635471756],
                            "L", [0.006441324935705498 , 0.0027969208328970656],
                            "L", [0.008866041863345032 , 0.002775452802823431],
                            "L", [0.008887499535208898 , 0.0042567468770648995],
                            "L", [0.006033629167849376 , 0.004278214907098566],
                            "C", [0.006033629167849376 , 0.004278214907098566],
                                 [0.006119459855839174 , 0.003634174005289204],
                                 [0.005904883135839235 , 0.0030330691635471756],
                            "Z"
                        ]
                    },
                    "213": {
                        "type": "polygon",
                        "coords": [
                            [0.008887499535208898 , 0.004276164034586749],
                            [0.006033629167849376 , 0.004254696004513114],
                            [0.006055086839853187 , 0.005306629477521697],
                            [0.008887499535208898 , 0.005349565537628998],
                        ]
                    },
                    "215": {
                        "type": "polygon",
                        "coords": [
                            [0.006055086839853187 , 0.005306629477521697],
                            [0.007449835517874481 , 0.005328097507595332],
                            [0.007449835517874481 , 0.0061224146198402005],
                            [0.006033629167849376 , 0.0061224146198402005],
                        ]
                    },
                    "216": {
                        "type": "polygon",
                        "coords": [
                            [0.007449835517874481 , 0.0061224146198402005],
                            [0.006033629167849376 , 0.0061224146198402005],
                            [0.006033629167849376 , 0.006873795671977768],
                            [0.007449835517874481 , 0.006873795671977768],
                        ]
                    },
                    "217-220": {
                        "type": "curve",
                        "coords": [
                            "M", [0.008866041863345032 , 0.007796920964624477],
                            "L", [0.006033629167849376 , 0.007796920964624477],
                            "C", [0.006033629167849376 , 0.007796920964624477],
                                 [0.006033629167849376 , 0.00852683398668841],
                                 [0.005754679431787118 , 0.00884885443763306],
                            "C", [0.005754679431787118 , 0.00884885443763306],
                                 [0.00517532228720079 , 0.009428491249261485],
                                 [0.005218237631272022 , 0.01041602063208913],
                            "C", [0.005218237631272022 , 0.01041602063208913],
                                 [0.0051324069431422795 , 0.01093125335353662],
                                 [0.0054113566794971495 , 0.011639698345566886],
                            "C", [0.0054113566794971495 , 0.011639698345566886],
                                 [0.0054113566794971495 , 0.011747038495895092],
                                 [0.005604475727684111 , 0.011789974556002392],
                            "C", [0.005604475727684111 , 0.011789974556002392],
                                 [0.005720481267015142 , 0.011345381007465962],
                                 [0.006053075183112371 , 0.010980424496414013],
                            "C", [0.006053075183112371 , 0.010980424496414013],
                                 [0.006332024919021964 , 0.010572531925254758],
                                 [0.0067417323433771785 , 0.010287212451727258],
                            "L", [0.008887499535208898 , 0.010308680481760927],
                            "Z"
                        ]
                    },
                    "221-222": {
                        "type": "polygon",
                        "coords": [
                            [-0.0006182491779239281 , 0.0027737437423702715],
                            [0.0000683963298747292 , 0.0020438307203063393],
                            [0.000540465116503004 , 0.002473191321539226],
                            [0.0007550418376733865 , 0.0022585110209227825],
                            [0.001634806394358727 , 0.0031387002534222224],
                            [0.00157043337802924 , 0.003396316614145967],
                            [0.0016777217385826257 , 0.0035251247945078394],
                            [0.002149790524931012 , 0.0035251247945078394],
                            [0.0024287402622527703 , 0.0038042091853052193],
                            [0.001227110624225328 , 0.005006418868749307],
                            [0.0004760921001353504 , 0.004255037816611741],
                            [0.0006692111492001446 , 0.004083293576102599],
                        ]
                    },
                    "223-225": {
                        "type": "polygon",
                        "coords": [
                            [0.0017420947549121127 , 0.005671927800632305],
                            [0.0029222667204523 , 0.004491186147261851],
                            [0.003565996883009281 , 0.005092290989003879],
                            [0.0032226741297015356 , 0.005457247500015861],
                            [0.004402846093626001 , 0.0066379891533863145],
                            [0.00435993074950388 , 0.006960009604330963],
                            [0.0045745074700508735 , 0.0071746899049474075],
                            [0.004960745566857352 , 0.007196157934981073],
                            [0.004960745566857352 , 0.007883134896937706],
                            [0.00461742281414755 , 0.008248091407989655],
                            [0.003437250850515696 , 0.007067349754619203],
                            [0.0032870471459546893 , 0.007239093995128344],
                        ]
                    },
                    "226-229": {
                        "type": "curve",
                        "coords": [
                            "M", [0.0037376582595995424 , 0.0021941069307418464],
                            "L", [0.002943724392557888 , 0.0021941069307418464],
                            "L", [0.002943724392557888 , 0.0028166798025175415],
                            "L", [0.0024287402622527703 , 0.0028166798025175415],
                            "L", [0.0024287402622527703 , 0.0033319125239650313],
                            "L", [0.004102438684783876 , 0.005006418868749307],
                            "L", [0.003930777308244504 , 0.0052210991693657505],
                            "L", [0.0048749148786894425 , 0.006187160522119762],
                            "L", [0.005046576254999816 , 0.006187160522119762],
                            "L", [0.005046576254999816 , 0.0038042091853052193],
                            "C", [0.005046576254999816 , 0.0038042091853052193],
                                 [0.005089491599071048 , 0.003503656764474173],
                                 [0.00489637255073142 , 0.0031816363135295234],
                            "C", [0.00489637255073142 , 0.0031816363135295234],
                                 [0.004638880486189528 , 0.002752275712336605],
                                 [0.004209727045082818 , 0.002408787231358289],
                            "C", [0.004209727045082818 , 0.002408787231358289],
                                 [0.004080981012716455 , 0.0021941069307418464],
                                 [0.0037376582595995424 , 0.0021941069307418464],
                            "Z"
                        ]
                    },
                    "230-231": {
                        "type": "curve",
                        "coords": [
                            "M", [0.0023214519017502734 , 0.002752275712336605],
                            "L", [0.001634806394358727 , 0.0027737437423702715],
                            "L", [0.0006477534770818342 , 0.0015500660288925163],
                            "L", [0.0015489757059109295 , 0.0005840046761385054],
                            "C", [0.0015489757059109295 , 0.0005840046761385054],
                                 [0.0018922984596639527 , 0.00006877195469101595],
                                 [0.0023214519017502734 , 0.00036932437552206215],
                            "C", [0.0023214519017502734 , 0.00036932437552206215],
                                 [0.0026218593111394534 , 0.0004122604356693316],
                                 [0.0029222667204523 , 0.0008416210368622502],
                            "L", [0.0023214519017502734 , 0.0008416210368622502],
                            "Z"
                        ]
                    },
                    "232-233": {
                        "type": "curve",
                        "coords": [
                            "M", [0.003930777308244504 , 0.013763893948022247],
                            "L", [0.0025574862948608552 , 0.013763893948022247],
                            "L", [0.0025574862948608552 , 0.012669024414866396],
                            "L", [0.0032012164576213915 , 0.012003515482983397],
                            "L", [0.0037591159316796865 , 0.012540216234504522],
                            "C", [0.0037591159316796865 , 0.012540216234504522],
                                 [0.004102438684783876 , 0.013184257136353851],
                                 [0.003930777308244504 , 0.013763893948022247],
                            "Z"
                        ]
                    },
                    "235-236": {
                        "type": "polygon",
                        "coords": [
                            [0.0025574862948608552 , 0.013763893948022247],
                            [0.0025574862948608552 , 0.012669024414866396],
                            [0.0032012164576213915 , 0.012003515482983397],
                            [0.002407282590159904 , 0.011166262310591259],
                            [0.0013129413126731254 , 0.012304067903854412],
                            [0.00019714236259731427 , 0.012304067903854412],
                            [0.0001756846904790038 , 0.012561684264578156],
                            [-0.00008180737495344409 , 0.012561684264578156],
                            [-0.00006034970283513361 , 0.013763893948022247],
                        ]
                    },
                    "237": {
                        "type": "polygon",
                        "coords": [
                            [0.002407282590159904 , 0.011166262310591259],
                            [0.0013129413126731254 , 0.012304067903854412],
                            [0.00019714236259731427 , 0.012304067903854412],
                            [0.00019714236259731427 , 0.011810303212440589],
                            [0.0016133487222404165 , 0.01037194519834639],
                        ]
                    },
                    "238": {
                        "type": "polygon",
                        "coords": [
                            [0.00019714236259731427 , 0.011810303212440589],
                            [0.0016133487222404165 , 0.01037194519834639],
                            [0.0010339915751859782 , 0.009792308386677996],
                            [0.0001542270183606933 , 0.01065102958914377],
                            [-0.0003178417682548593 , 0.01065102958914377],
                            [-0.0003178417682548593 , 0.011788835182366952],
                        ]
                    },
                    "239": {
                        "type": "polygon",
                        "coords": [
                            [0.0010339915751859782 , 0.009792308386677996],
                            [0.0001542270183606933 , 0.01065102958914377],
                            [-0.0003178417682548593 , 0.01065102958914377],
                            [-0.0003392994403731697 , 0.010092860807549012],
                            [0.0005190074443719713 , 0.009298543695264172],
                        ]
                    },
                    "240-243": {
                        "type": "curve",
                        "coords": [
                            "M", [-0.001154690980830801 , 0.008525694613052972],
                            "L", [-0.0011761486529491114 , 0.010908645949867514],
                            "L", [-0.0003392994403731697 , 0.010908645949867514],
                            "L", [-0.0003392994403731697 , 0.010092860807549012],
                            "L", [0.0005190074443719713 , 0.009298543695264172],
                            "C", [0.0005190074443719713 , 0.009298543695264172],
                                 [0.00021860003471562472 , 0.008912119154178557],
                                 [-0.00006034970283513361 , 0.00884771506399762],
                            "C", [-0.00006034970283513361 , 0.00884771506399762],
                                 [-0.000532418489437964 , 0.008568630673200241],
                                 [-0.001154690980830801 , 0.008525694613052972],
                            "Z"
                        ]
                    },
                    "245-247": {
                        "type": "polygon",
                        "coords": [
                            [-0.004952698939830478 , 0.013764546498356015],
                            [-0.004974156611859733 , 0.009900301087339969],
                            [-0.004523545498914602 , 0.009900301087339969],
                            [-0.004502087826859903 , 0.009105983975055134],
                            [-0.0022490322584066353 , 0.009105983975055134],
                            [-0.0022490322584066353 , 0.009513876546254353],
                            [-0.0016053020952008208 , 0.009513876546254353],
                            [-0.0015838444230825105 , 0.012540868784878258],
                            [-0.002055913209469063 , 0.012540868784878258],
                            [-0.002055913209469063 , 0.013764546498356015],
                        ]
                    },
                    "248-249": {
                        "type": "polygon",
                        "coords": [
                            [-0.004952698939830478 , 0.013764546498356015],
                            [-0.004974156611859733 , 0.009900301087339969],
                            [-0.004523545498914602 , 0.009900301087339969],
                            [-0.004502087826859903 , 0.009105983975055134],
                            [-0.004351884122477006 , 0.009105983975055134],
                            [-0.004373341794531706 , 0.00749588172049176],
                            [-0.00469520687531403 , 0.007517349750525428],
                            [-0.00469520687531403 , 0.007689093991034569],
                            [-0.005703717460739901 , 0.007689093991034569],
                            [-0.005703717460739901 , 0.007367073540129889],
                            [-0.0073345005311975 , 0.007367073540129889],
                            [-0.0073345005311975 , 0.0083331348928839],
                            [-0.008772164548926304 , 0.0083331348928839],
                            [-0.008772164548926304 , 0.010265257598391921],
                            [-0.007034093123882042 , 0.010265257598391921],
                            [-0.007034093123882042 , 0.013378121957270396],
                            [-0.0055320560846203615 , 0.013764546498356015],
                        ]
                    },
                    "250": {
                        "type": "polygon",
                        "coords": [
                            [-0.004373341794531706 , 0.00749588172049176],
                            [-0.00469520687531403 , 0.007517349750525428],
                            [-0.00469520687531403 , 0.007689093991034569],
                            [-0.005703717460739901 , 0.007689093991034569],
                            [-0.005703717460739901 , 0.007367073540129889],
                            [-0.006175786244836454 , 0.007367073540129889],
                            [-0.006175786244836454 , 0.006637160518025987],
                            [-0.004373341794531706 , 0.0066156924879923204],
                        ]
                    },
                    "250A": {
                        "type": "polygon",
                        "coords": [
                            [-0.0073345005311975 , 0.007367073540129889],
                            [-0.006175786244836454 , 0.007367073540129889],
                            [-0.006175786244836454 , 0.006637160518025987],
                            [-0.004373341794531706 , 0.0066156924879923204],
                            [-0.004373341794531706 , 0.005735503255452913],
                            [-0.0073345005311975 , 0.005735503255452913],
                        ]
                    },
                    "251-253": {
                        "type": "polygon",
                        "coords": [
                            [-0.008772164548926304 , 0.002903059461902836],
                            [-0.0069482624360449104 , 0.002903059461902836],
                            [-0.006905347092126345 , 0.0030318676422647077],
                            [-0.004394799466586405 , 0.0030318676422647077],
                            [-0.004373341794531706 , 0.004706373987048985],
                            [-0.005274564020332913 , 0.004706373987048985],
                            [-0.005446225396528786 , 0.004942522317739064],
                            [-0.005446225396528786 , 0.005350414888898314],
                            [-0.007570534922501527 , 0.005350414888898314],
                            [-0.007828026985605809 , 0.005629499279695694],
                            [-0.007849484657520563 , 0.0074972178950227794],
                            [-0.008793622220802894 , 0.007518685925056446],
                        ]
                    },
                    "255": {
                        "type": "polygon",
                        "coords": [
                            [-0.006905347092126345 , 0.0030318676422647077],
                            [-0.006905347092126345 , 0.0017652538686796506],
                            [-0.004931241267788501 , 0.0017652538686796506],
                            [-0.0042875111063001865 , 0.0030533356723383425],
                        ]
                    },
                    "256": {
                        "type": "polygon",
                        "coords": [
                            [-0.005446225396528786 , 0.00015515161407630987],
                            [-0.006926804764079267 , 0.00015515161407630987],
                            [-0.006905347092126345 , -0.0014978867006343326],
                            [-0.006283074604791896 , -0.001519354730687983],
                            [-0.006304532276782984 , -0.0010041220092205096],
                            [-0.0060684978848428465 , -0.0007894417086240504],
                            [-0.005446225396528786 , -0.0007679736785504156],
                        ]
                    },
                    "257-258": {
                        "type": "polygon",
                        "coords": [
                            [-0.008772164548926304 , 0.002903059461902836],
                            [-0.0069482624360449104 , 0.002903059461902836],
                            [-0.006905347092126345 , 0.0017652538686796506],
                            [-0.004931241267788501 , 0.0017652538686796506],
                            [-0.005446225396528786 , 0.0005630441852355618],
                            [-0.005446225396528786 , 0.00015515161407630987],
                            [-0.006926804764079267 , 0.00015515161407630987],
                            [-0.0069482624360449104 , -0.0004888892877530361],
                            [-0.008772164548926304 , -0.0004888892877530361],
                        ]
                    },
                    "259": {
                        "type": "polygon",
                        "coords": [
                            [-0.005446225396528786 , -0.0027189438547825517],
                            [-0.006712228044410106 , -0.0027189438547825517],
                            [-0.006712228044410106 , -0.0028906880952717096],
                            [-0.007828026985605809 , -0.0028906880952717096],
                            [-0.007828026985605809 , -0.002353987343750586],
                            [-0.0071199238117064505 , -0.002353987343750586],
                            [-0.0070984661397535285 , -0.0018602226523367629],
                            [-0.005446225396528786 , -0.0018602226523367629],
                        ]
                    },
                    "260": {
                        "type": "polygon",
                        "coords": [
                            [-0.005446225396528786 , -0.0027189438547825517],
                            [-0.006712228044410106 , -0.0027189438547825517],
                            [-0.006712228044410106 , -0.0028906880952717096],
                            [-0.007828026985605809 , -0.0028906880952717096],
                            [-0.007828026985605809 , -0.002353987343750586],
                            [-0.008772164548926304 , -0.002375455373804236],
                            [-0.008772164548926304 , -0.0036850052075365633],
                            [-0.005446225396528786 , -0.0036850052075365633],
                        ]
                    },
                    "261": {
                        "type": "polygon",
                        "coords": [
                            [-0.008793622220802894 , -0.004908682921014319],
                            [-0.008772164548926304 , -0.006325572905054868],
                            [-0.006926804764079267 , -0.006325572905054868],
                            [-0.006926804764079267 , -0.004887214890960668],
                        ]
                    },
                    "262A": {
                        "type": "polygon",
                        "coords": [
                            [-0.008772164548926304 , -0.006325572905054868],
                            [-0.006926804764079267 , -0.006325572905054868],
                            [-0.006905347092126345 , -0.005037491101396175],
                            [-0.006111413228837745 , -0.005037491101396175],
                            [-0.006089955556846657 , -0.005359511552300857],
                            [-0.0063259899487613505 , -0.005617127913044585],
                            [-0.0063259899487613505 , -0.006540253205671311],
                            [-0.00677660106031976 , -0.006540253205671311],
                            [-0.007055550795847685 , -0.006797869566415038],
                            [-0.007055550795847685 , -0.006926677746776911],
                            [-0.008772164548926304 , -0.006926677746776911],
                        ]
                    },
                    "262": {
                        "type": "polygon",
                        "coords": [
                            [-0.006111413228837745 , -0.005037491101396175],
                            [-0.006089955556846657 , -0.005359511552300857],
                            [-0.0063259899487613505 , -0.005617127913044585],
                            [-0.0063259899487613505 , -0.006540253205671311],
                            [-0.004781037563494661 , -0.006540253205671311],
                            [-0.004781037563494661 , -0.005380979582374492],
                            [-0.004609376187120677 , -0.005380979582374492],
                            [-0.004587918515065978 , -0.00501602307132254],
                        ]
                    },
                    "263-265": {
                        "type": "curve",
                        "coords": [
                            "M", [ -0.004781037563494661 , -0.006540253205671311],
                            "L", [-0.004781037563494661 , -0.005380979582374492],
                            "L", [-0.004609376187120677 , -0.005380979582374492],
                            "L", [-0.004587918515065978 , -0.004608130500163288],
                            "L", [-0.0034292042234888224 , -0.004608130500163288],
                            "L", [-0.0034292042234888224 , -0.005230703371938984],
                            "L", [-0.0029785931096913023 , -0.005252171402012618],
                            "L", [-0.0029785931096913023 , -0.006282636844947566],
                            "C", [-0.0029785931096913023 , -0.006282636844947566],
                                 [-0.0035364925838895418 , -0.006626125325925881],
                                 [-0.004115849729811703 , -0.006561721235744944],
                            "Z"
                        ]
                    },
                    "266": {
                        "type": "curve",
                        "coords": [
                            "M", [-0.0034292042234888224 , -0.004608130500163288],
                            "L", [-0.0034292042234888224 , -0.005230703371938984],
                            "L", [-0.0029785931096913023 , -0.005252171402012618],
                            "L", [-0.0029785931096913023 , -0.006282636844947566],
                            "C", [-0.0029785931096913023 , -0.006282636844947566],
                                 [-0.0023777782910147202 , -0.006046488514257487],
                                 [-0.002034455537363475 , -0.005531255792790014],
                            "C", [-0.002034455537363475 , -0.005531255792790014],
                                 [-0.0016911327836358961 , -0.005144831251704398],
                                 [-0.002055913209469063 , -0.004608130500163288],
                            "Z"
                        ]
                    },
                    "267-268": {
                        "type": "curve",
                        "coords": [
                            "M", [-0.007313042859244578 , -0.012784487582053753],
                            "L", [-0.007313042859244578 , -0.011582277898629648],
                            "L", [-0.0077421962979213445 , -0.011582277898629648],
                            "L", [-0.0077421962979213445 , -0.007331607946508002],
                            "L", [-0.00660493968454372 , -0.007310139916454352],
                            "L", [-0.006583482012565354 , -0.0069451834054024015],
                            "L", [-0.004051476713609437 , -0.0069451834054024015],
                            "C", [-0.004051476713609437 , -0.0069451834054024015],
                                 [-0.0040085613694873165 , -0.007524820217070795],
                                 [-0.0039012730091502086 , -0.0075892243072517305],
                            "C", [-0.0039012730091502086 , -0.0075892243072517305],
                                 [-0.0037081539605052474 , -0.008018584908464634],
                                 [-0.003579407928037107 , -0.008040052938538269],
                            "C", [-0.003579407928037107 , -0.008040052938538269],
                                 [-0.0029356777655182927 , -0.008254805744736249],
                                 [-0.0030429661259571785 , -0.009091986411526866],
                            "L", [-0.0030429661259571785 , -0.011238789417651331],
                            "L", [-0.0028498470771468284 , -0.01138906562808684],
                            "L", [-0.0028283894050539623 , -0.012634211371618244],
                            "L", [-0.004502087826859903 , -0.012634211371618244],
                            "L", [-0.004502087826859903 , -0.012805955612107402],
                            "Z"
                        ]
                    },
                    "269": {
                        "type": "curve",
                        "coords": [
                            "M", [0.00041171908379314116 , -0.008040052938538269],
                            "L", [ 0.00041171908379314116 , -0.007417480066762573],
                            "L", [-0.0013692677019630168 , -0.0071813317360724955],
                            "C", [-0.0013692677019630168 , -0.0071813317360724955],
                                 [-0.0014980137346474352 , -0.007589503971683344],
                                 [-0.0015838444230825105 , -0.008061800633023532],
                            "Z"
                        ]
                    },
                    "270": {
                        "type": "curve",
                        "coords": [
                            "M", [0.00041171908379314116 , -0.007417480066762573],
                            "L", [-0.0013692677019630168 , -0.0071813317360724955],
                            "C", [-0.0013692677019630168 , -0.0071813317360724955],
                                 [-0.0013048946856335298 , -0.006752250799291205],
                                 [-0.0010903179644885918 , -0.006473166408493825],
                            "L", [0.00041171908379314116 , -0.006687846709110269],
                            "Z"
                        ]
                    },
                    "271": {
                        "type": "curve",
                        "coords": [
                            "M", [-0.0010903179644885918 , -0.006473166408493825],
                            "L", [0.00041171908379314116 , -0.006687846709110269],
                            "L", [0.00043317675591145167 , -0.007267483520758679],
                            "L", [0.003351420162207843 , -0.0072460154907050284],
                            "L", [0.003351420162207843 , -0.0069454630698539965],
                            "L", [0.0037591159316796865 , -0.006923995039780362],
                            "L", [0.0037591159316796865 , -0.005399764905431593],
                            "L", [0.0014416873453575439 , -0.005399764905431593],
                            "L", [0.0014202296732519556 , -0.004433703552677582],
                            "C", [0.0014202296732519556 , -0.004433703552677582],
                                 [0.0008194148540283179 , -0.004476639612804867],
                                 [0.0002615153789649679 , -0.004841596123856817],
                            "C", [0.0002615153789649679 , -0.004841596123856817],
                                 [-0.00018909573554499643 , -0.005099212484580563],
                                 [-0.000532418489437964 , -0.005571509145920751],
                            "C", [-0.000532418489437964 , -0.005571509145920751],
                                 [-0.0008328258990943105 , -0.00582912550666448],
                                 [-0.0010903179644885918 , -0.006473166408493825],
                            "Z"
                        ]
                    },
                    "Open Cafe A": {
                        "type": "curve",
                        "coords": [
                            "M", [-0.0009401142596731407 , 0.0003539468154167836],
                            "L", [-0.0009401142596731407 , 0.001599092558928206],
                            "C", [-0.0009401142596731407 , 0.001599092558928206],
                                 [-0.001433640718305226 , 0.0019211130098728548],
                                 [-0.0018627941605187687 , 0.0020069851301274255],
                            "C", [-0.0018627941605187687 , 0.0020069851301274255],
                                 [-0.002356320618909132 , 0.002200197400670234],
                                 [-0.0030000507817841684 , 0.001835240889618284],
                            "C", [-0.0030000507817841684 , 0.001835240889618284],
                                 [-0.0037296116325726695 , 0.0012985401380971597],
                                 [-0.0041587650739338235 , 0.0006759672663214645],
                            "L", [-0.0041587650739338235 , -0.0001183498459433885],
                            "C", [-0.0041587650739338235 , -0.0001183498459433885],
                                 [-0.0034506618955689665 , 0.0005900951460668936],
                                 [-0.002485066651517217 , 0.0006330312061741952],
                            "C", [-0.002485066651517217 , 0.0006330312061741952],
                                 [-0.0016911327836358961 , 0.000804775446683337],
                                 [-0.0009401142596731407 , 0.0003539468154167836],
                            "Z"
                        ]
                    },
                };
            case 'L3':
                return {
                    "301-302": {
                        "type": "polygon",
                        "coords": [
                            [0.0088231265196173 , -0.011433565737444253],
                            [0.0088231265196173 , -0.0046282002080588755],
                            [0.007278174142340164 , -0.0046282002080588755],
                            [0.007278174142340164 , -0.0026531414424235678],
                            [0.006548613295610051 , -0.0026531414424235678],
                            [0.006548613295610051 , -0.004928752628909905],
                            [0.006119459855839174 , -0.004928752628909905],
                            [0.006119459855839174 , -0.006581790943620547],
                            [0.005733221759770585 , -0.006581790943620547],
                            [0.005733221759770585 , -0.007869872747299222],
                            [0.005304068319376319 , -0.007891340777352875],
                            [0.004445761437722678 , -0.008342169408639412],
                            [0.004982203238886607 , -0.009694655302499024],
                            [0.006119459855839174 , -0.011390629677336951],
                        ]
                    },
                    "303": {
                        "type": "curve",
                        "coords": [
                            "M", [0.006548613295610051 , -0.004928752628909905],
                            "L", [0.006119459855839174 , -0.004928752628909905],
                            "L", [0.006119459855839174 , -0.006581790943620547],
                            "L", [0.005733221759770585 , -0.006581790943620547],
                            "L", [0.005733221759770585 , -0.007869872747299222],
                            "L", [0.005304068319376319 , -0.007891340777352875],
                            "L", [0.004445761437722678 , -0.008342169408639412],
                            "C", [0.004445761437722678 , -0.008342169408639412],
                                 [0.004252642389204938 , -0.007955744867533811],
                                 [0.003909319636177082 , -0.007869872747299222],
                            "C", [0.003909319636177082 , -0.007869872747299222],
                                 [0.00363036989923699 , -0.007805468657098304],
                                 [0.0033728778342752646 , -0.007547852296374559],
                            "C", [0.0033728778342752646 , -0.007547852296374559],
                                 [0.002836436032093558 , -0.007182895785342593],
                                 [0.0023214519017502734 , -0.006302706552823167],
                            "L", [0.0028578937041864243 , -0.006281238522769518],
                            "L", [0.00287935137627929 , -0.0031683741638910416],
                            "L", [0.002643316983232319 , -0.0031683741638910416],
                            "L", [0.002643316983232319 , -0.0019017603902860007],
                            "L", [0.003973692652379347 , -0.0019017603902860007],
                            "L", [0.004510134453886776 , -0.002438461141807125],
                            "L", [0.004553049797996174 , -0.004456455967569718],
                            "L", [0.006527155623631685 , -0.004456455967569718],
                            "Z"
                        ]
                    },
                    "305": {
                        "type": "curve",
                        "coords": [
                            "M", [0.0023214519017502734 , -0.006302706552823167],
                            "L", [0.0028578937041864243 , -0.006281238522769518],
                            "L", [0.00287935137627929 , -0.0031683741638910416],
                            "L", [0.0023214519017502734 , -0.0031683741638910416],
                            "L", [0.0022999942296574073 , -0.003490394614815706],
                            "L", [0.0014202296732519556 , -0.003468926584742072],
                            "C", [0.0014202296732519556 , -0.003468926584742072],
                                 [0.0012914836405548149 , -0.004134435516645052],
                                 [0.00157043337802924 , -0.004649668238112526],
                            "C", [0.00157043337802924 , -0.004649668238112526],
                                 [0.001763552427017701 , -0.005508389440558315],
                                 [0.0023214519017502734 , -0.006302706552823167],
                            "Z"
                        ]
                    },
                    "306-307": {
                        "type": "curve",
                        "coords": [
                            "M", [0.0023214519017502734 , -0.0031683741638910416],
                            "L", [0.0022999942296574073 , -0.003490394614815706],
                            "L", [0.0014202296732519556 , -0.003468926584742072],
                            "C", [0.0014202296732519556 , -0.003468926584742072],
                                 [0.0013129413126731254 , -0.0025458012921153465],
                                 [0.00157043337802924 , -0.0019232284203396512],
                            "L", [0.0026218593111394534 , -0.001944696450393302],
                            "L", [0.0026004016390465874 , -0.0031683741638910416],
                            "Z"
                        ]
                    },
                    "308-313": {
                        "type": "polygon",
                        "coords": [
                            [0.0026218593111394534 , -0.0006995507068419118],
                            [0.003458708522608562 , -0.001558271909307685],
                            [0.004059523340649033 , -0.001558271909307685],
                            [0.004789084190521534 , -0.0021593767510097455],
                            [0.006291121231793324 , -0.00218084478108338],
                            [0.006269663559802237 , 0.0008246794275068582],
                            [0.005196779959230045 , 0.0018980809305491069],
                        ]
                    },
                    "315": {
                        "type": "curve",
                        "coords": [
                            "M", [0.0059477984798468565 , 0.003035886523812259],
                            "L", [0.006505697951640597 , 0.0029070783434104186],
                            "L", [0.0088231265196173 , 0.0029070783434104186],
                            "L", [0.008844584191493888 , -0.0012577194884366707],
                            "L", [0.0075142085336823575 , -0.0012791875185103052],
                            "L", [0.007471293189814681 , 0.001082295788230603],
                            "L", [0.007278174142340164 , 0.0010608277581569683],
                            "L", [0.006999224406952183 , 0.0014257842692089186],
                            "L", [0.006269663559802237 , 0.0014257842692089186],
                            "L", [0.005497187367588725 , 0.0022630374416010572],
                            "C", [0.005497187367588725 , 0.0022630374416010572],
                                 [0.005797594775794739 , 0.002585057892505738],
                                 [0.0059477984798468565 , 0.003035886523812259],
                            "Z"
                        ]
                    },
                    "316": {
                        "type": "curve",
                        "coords": [
                            "M", [0.0088231265196173 , 0.004109288026854507],
                            "L", [0.006033629167849376 , 0.004109288026854507],
                            "C", [0.006033629167849376 , 0.004109288026854507],
                                 [0.006098002183848086 , 0.0035725872753333836],
                                 [0.0059477984798468565 , 0.003035886523812259],
                            "L", [0.006505697951640597 , 0.0029070783434104186],
                            "L", [0.0088231265196173 , 0.0029070783434104186],
                            "Z"
                        ]
                    },
                    "317": {
                        "type": "polygon",
                        "coords": [
                            [0.006033629167849376 , 0.0052685616501513275],
                            [0.008844584191493888 , 0.0052685616501513275],
                            [0.0088231265196173 , 0.004109288026854507],
                            [0.006033629167849376 , 0.004109288026854507],
                        ]
                    },
                    "318-320": {
                        "type": "polygon",
                        "coords": [
                            [0.006033629167849376 , 0.0052685616501513275],
                            [0.006033629167849376 , 0.006878663904754669],
                            [0.00749275086175488 , 0.006878663904754669],
                            [0.00749275086175488 , 0.0052685616501513275],
                        ]
                    },
                    "321-323": {
                        "type": "curve",
                        "coords": [
                            "M", [0.008801668847766156 , 0.007924589771350911],
                            "L", [0.006033629167849376 , 0.007924589771350911],
                            "C", [0.006033629167849376 , 0.007924589771350911],
                                 [0.006012171495845566 , 0.008697438853562112],
                                 [0.005668848743733709 , 0.009384415815518745],
                            "C", [0.005668848743733709 , 0.009384415815518745],
                                 [0.005325525991405574 , 0.010264605048018184],
                                 [0.0047676265184795575 , 0.01065102958914377],
                            "C", [0.0047676265184795575 , 0.01065102958914377],
                                 [0.004145354028905998 , 0.011037454130229388],
                                 [0.0040380656685816115 , 0.011702963062112383],
                            "C", [0.0040380656685816115 , 0.011702963062112383],
                                 [0.003909319636177082 , 0.013033980925918346],
                                 [0.00401660799651419 , 0.01382829803820318],
                            "L", [0.0054113566794971495 , 0.01382829803820318],
                            "C", [0.0054113566794971495 , 0.01382829803820318],
                                 [0.005282610647347065 , 0.013270129256608424],
                                 [ 0.005389899007480617 , 0.012797832595268236],
                            "C", [ 0.005389899007480617 , 0.012797832595268236],
                                 [0.005497187367588725 , 0.01176736715229332],
                                 [0.005904883135839235 , 0.011209198370738528],
                            "C", [0.005904883135839235 , 0.011209198370738528],
                                 [0.006119459855839174 , 0.010715433679324707],
                                 [0.0067417323433771785 , 0.01028607307809182],
                            "L", [0.0088231265196173 , 0.01028607307809182],
                            "Z"
                        ]
                    },
                    "328-329": {
                        "type": "curve",
                        "coords": [
                            "M", [0.004810541862563512 , 0.003051346947473555],
                            "L", [0.003544539210929137 , 0.004360896781205882],
                            "L", [0.0043384730774491806 , 0.00515521389345075],
                            "L", [0.005046576254999816 , 0.00515521389345075],
                            "L", [0.00506803392702907 , 0.0038027279996111223],
                            "C", [0.00506803392702907 , 0.0038027279996111223],
                                 [0.005046576254999816 , 0.003394835428451871],
                                 [0.004810541862563512 , 0.003051346947473555],
                            "Z"
                        ]
                    },
                    "330-331": {
                        "type": "curve",
                        "coords": [
                            "M", [0.0022999942296574073 , 0.0028151986167834764],
                            "L", [ 0.0021712481970366 , 0.0029654748272189843],
                            "L", [0.0034157931784482743 , 0.004232088600844009],
                            "L", [0.004703253502328181 , 0.0028581346769307463],
                            "L", [0.004123896356838576 , 0.002257029835188718],
                            "C", [0.004123896356838576 , 0.002257029835188718],
                                 [0.0036947429154646993 , 0.0018706052941031008],
                                 [0.0032012164576213915 , 0.0021496896849004803],
                            "L", [0.0032012164576213915 , 0.0028581346769307463],
                            "Z"
                        ]
                    },
                    "332": {
                        "type": "curve",
                        "coords": [
                            "M", [0.0011198222636464978 , 0.0010762881818182637],
                            "L", [0.001849383115452776 , 0.0018276692339957992],
                            "L", [0.0022785365575645413 , 0.0018276692339957992],
                            "L", [0.0023214519017502734 , 0.0007757357609872174],
                            "L", [0.002814978360000692 , 0.0007757357609872174],
                            "C", [0.002814978360000692 , 0.0007757357609872174],
                                 [0.0024287402622527703 , 0.00007093676458236332],
                                 [0.0017420947549121127 , 0.00045371531004256843],
                            "Z"
                        ]
                    },
                    "333": {
                        "type": "polygon",
                        "coords": [
                            [0.0011198222636464978 , 0.0010762881818182637],
                            [0.001849383115452776 , 0.0018276692339957992],
                            [0.0022785365575645413 , 0.0018276692339957992],
                            [0.0023214519017502734 , 0.0028151986167834764],
                            [0.0019566714759934396 , 0.0031586870977617925],
                            [0.0005190074443719713 , 0.0016988610535939586],
                        ]
                    },
                    "325-327 &335-336": {
                        "type": "polygon",
                        "coords": [
                            [0.0005190074443719713 , 0.0016988610535939586],
                            [0.00506803392702907 , 0.006335955546821205],
                            [0.00506803392702907 , 0.007924589771350911],
                            [0.004660338158231504 , 0.008332482342510161],
                            [-0.0006611645221605491 , 0.0029440067971453487],
                        ]
                    },
                    "338A": {
                        "type": "curve",
                        "coords": [
                            "M", [0.0026091188183458435 , 0.010335798370855187],
                            "L", [0.003327950833361439 , 0.011183785558284143],
                            "C", [0.003327950833361439 , 0.011183785558284143],
                                 [0.0033923238496145927 , 0.011280391693535565],
                                 [0.003456696865842302 , 0.011173051543247327],
                            "L", [0.004121884700085038 , 0.010303596325744735],
                            "C", [0.004121884700085038 , 0.010303596325744735],
                                 [0.004239901896424051 , 0.01015332011530923],
                                 [0.0040575116838954955 , 0.010099650040165109],
                            "L", [0.0026627629985843696 , 0.010099650040165109],
                            "C", [0.0026627629985843696 , 0.010099650040165109],
                                 [0.002523288129948935 , 0.010110384055201928],
                                 [0.0026091188183458435 , 0.010335798370855187],
                            "Z"
                        ]
                    },
                    "337": {
                        "type": "curve",
                        "coords": [
                            "M", [-0.001326352357739118 , 0.010264605048018184],
                            "L", [-0.001326352357739118 , 0.013720957887874976],
                            "L", [0.0028578937041864243 , 0.013720957887874976],
                            "L", [0.0028578937041864243 , 0.012024983513057032],
                            "C", [0.0028578937041864243 , 0.012024983513057032],
                                 [0.002900809048372156 , 0.01172443109218602],
                                 [0.0027076899995236397 , 0.011423878671315004],
                            "L", [0.0020210444923102044 , 0.012089387603237968],
                            "L", [0.0007121264934367655 , 0.01080130579953931],
                            "L", [0.0007121264934367655 , 0.010522221408781899],
                            "L", [0.0004546344280170399 , 0.01028607307809182],
                            "Z"
                        ]
                    },
                    "337A": {
                        "type": "polygon",
                        "coords": [
                            [0.0027076899995236397 , 0.011423878671315004],
                            [0.0020210444923102044 , 0.012089387603237968],
                            [0.001398772001133645 , 0.011466814731462273],
                            [0.002085417508614247 , 0.010758369739432009],
                        ]
                    },
                    "337B": {
                        "type": "polygon",
                        "coords": [
                            [0.001398772001133645 , 0.011466814731462273],
                            [0.002085417508614247 , 0.010758369739432009],
                            [0.001463145017463132 , 0.010092860807549012],
                            [0.0010554492473042886 , 0.010522221408781899],
                            [0.0007121264934367655 , 0.010522221408781899],
                            [0.0007121264934367655 , 0.01080130579953931]
                        ]
                    },
                    "343-345": {
                        "type": "polygon",
                        "coords": [
                            [-0.001326352357739118 , 0.010264605048018184],
                            [-0.001326352357739118 , 0.013720957887874976],
                            [-0.004738122219397984 , 0.013720957887874976],
                            [-0.004738122219397984 , 0.010264605048018184],
                        ]
                    },
                    "338": {
                        "type": "polygon",
                        "coords": [
                            [0.000004023313519797797 , 0.008633034763381177],
                            [-0.0004895031452140652 , 0.009148267484828667],
                            [-0.0004895031452140652 , 0.009835244446785298],
                            [0.00041171908379314116 , 0.009856712476858933],
                            [0.0008194148540283179 , 0.009427351875626046],
                        ]
                    },
                    "339": {
                        "type": "curve",
                        "coords": [
                            "M", [-0.001326352357739118 , 0.009814895074478082],
                            "L", [-0.001326352357739118 , 0.00792570842911733],
                            "C", [-0.001326352357739118 , 0.00792570842911733],
                                 [-0.00111177563659418 , 0.007796900248715489],
                                 [-0.0008113682269760001 , 0.007990112519298266],
                            "C", [-0.0008113682269760001 , 0.007990112519298266],
                                 [-0.0004036724567408234 , 0.00820479281991471],
                                 [0.000004023313519797797 , 0.008634153421107628],
                            "L", [-0.0004895031452140652 , 0.009170854172628752],
                            "L", [-0.0004895031452140652 , 0.009836363104551717],
                            "Z"
                        ]
                    },
                    "340-341": {
                        "type": "polygon",
                        "coords": [
                            [-0.001326352357739118 , 0.009814895074478082],
                            [-0.001326352357739118 , 0.00792570842911733],
                            [-0.0035364925838895418 , 0.008827365691650437],
                            [-0.0035364925838895418 , 0.009836363104551717]
                        ]
                    },
                    "342": {
                        "type": "curve",
                        "coords": [
                            "M", [-0.0035364925838895418 , 0.008827365691650437],
                            "L", [-0.0035364925838895418 , 0.009836363104551717],
                            "L", [-0.004802495235536638 , 0.009836363104551717],
                            "C", [-0.004802495235536638 , 0.009836363104551717],
                                 [-0.004823952907578615 , 0.00944993856342613],
                                 [-0.004545003170969301 , 0.009342598413137894],
                            "Z"
                        ]
                    },
                    "350-352": {
                        "type": "polygon",
                        "coords": [
                            [-0.007055550795847685 , 0.008890930788556519],
                            [-0.007034093123882042 , 0.010844521524098207],
                            [-0.0059397518528327056 , 0.010844521524098207],
                            [-0.0059397518528327056 , 0.01369976952225294],
                            [-0.008686333861445397 , 0.012926920440041735],
                            [-0.008686333861445397 , 0.008890930788556519],
                        ]
                    },
                    "353-356": {
                        "type": "curve",
                        "coords": [
                            "M", [-0.007055550795847685 , 0.008890930788556519],
                            "L", [-0.007034093123882042 , 0.010844521524098207],
                            "L", [-0.0059397518528327056 , 0.010844521524098207],
                            "C", [-0.0059397518528327056 , 0.010844521524098207],
                                 [-0.005961209524836516 , 0.010329288802650716],
                                 [-0.005875378836821274 , 0.0099428642615651],
                            "C", [-0.005875378836821274 , 0.0099428642615651],
                                 [-0.0058110058207971205 , 0.009320291389789405],
                                 [-0.005617886772686493 , 0.00874065457812101],
                            "L", [-0.006411820636712981 , 0.00841863412721633],
                            "L", [-0.006755143388354116 , 0.008890930788556519],
                            "Z"
                        ]
                    },
                    "357-358": {
                        "type": "polygon",
                        "coords": [
                            [-0.008729249205198573 , 0.0029013504014896445],
                            [-0.0068624317481950576 , 0.0029228184315233108],
                            [-0.0068624317481950576 , 0.005262833708190585],
                            [-0.004373341794531706 , 0.005262833708190585],
                            [-0.005274564020332913 , 0.007688721105112428],
                            [-0.0058110058207971205 , 0.007431104744388684],
                            [-0.008729249205198573 , 0.007452572774462318],
                        ]
                    },
                    "359": {
                        "type": "polygon",
                        "coords": [
                            [-0.0068624317481950576 , 0.005262833708190585],
                            [-0.004373341794531706 , 0.005262833708190585],
                            [-0.004330426450422307 , 0.004103560084893766],
                            [-0.006840974076229413 , 0.004103560084893766],
                        ]
                    },
                    "360": {
                        "type": "polygon",
                        "coords": [
                            [-0.0068624317481950576 , 0.0029228184315233108],
                            [-0.006840974076229413 , 0.004103560084893766],
                            [-0.004330426450422307 , 0.004103560084893766],
                            [-0.004351884122477006 , 0.0029013504014896445],
                        ]
                    },
                    "361": {
                        "type": "polygon",
                        "coords": [
                            [-0.004351884122477006 , 0.0029013504014896445],
                            [-0.006218701588818631 , 0.0029013504014896445],
                            [-0.0061972439168275426 , 0.001827948898407428],
                            [-0.00469520687531403 , 0.001827948898407428],
                            [-0.004351884122477006 , 0.0026437340407259313],
                        ]
                    },
                    "362-363": {
                        "type": "polygon",
                        "coords": [
                            [-0.008729249205198573 , 0.0029013504014896445],
                            [-0.006218701588818631 , 0.0029013504014896445],
                            [-0.0061972439168275426 , 0.001827948898407428],
                            [-0.00469520687531403 , 0.001827948898407428],
                            [-0.005231648676274404 , 0.00047546300456779994],
                            [-0.00677660106031976 , 0.0007760154253988462],
                            [-0.008729249205198573 , 0.0007760154253988462],
                        ]
                    },
                    "365-366": {
                        "type": "polygon",
                        "coords": [
                            [-0.005231648676274404 , 0.00047546300456779994],
                            [-0.00677660106031976 , 0.0007760154253988462],
                            [-0.008729249205198573 , 0.0007760154253988462],
                            [-0.008750706877062439 , -0.0005120663782598456],
                            [-0.0073345005311975 , -0.0005120663782598456],
                            [-0.0068624317481950576 , -0.0014995957610675072],
                            [-0.005467683068558041 , -0.0014995957610675072],
                        ]
                    },
                    "367": {
                        "type": "polygon",
                        "coords": [
                            [-0.005467683068558041 , -0.0033000929259685567],
                            [-0.006712228044410106 , -0.0033000929259685567],
                            [-0.00673368571637575 , -0.004330558368903504],
                            [-0.007656365610224158 , -0.004330558368903504],
                            [-0.007677823282151635 , -0.003643581406946872],
                            [-0.008750706877062439 , -0.003643581406946872],
                            [-0.008750706877062439 , -0.002376967633341831],
                            [-0.007441788890885774 , -0.0023554996032881803],
                            [-0.007441788890885774 , -0.0019261390020552942],
                            [-0.005467683068558041 , -0.0019046709720016433],
                        ]
                    },
                    "368-369": {
                        "type": "polygon",
                        "coords": [
                            [-0.005467683068558041 , -0.0033000929259685567],
                            [-0.006712228044410106 , -0.0033000929259685567],
                            [-0.00673368571637575 , -0.004330558368903504],
                            [-0.007656365610224158 , -0.004330558368903504],
                            [-0.007677823282151635 , -0.0069711260664417915],
                            [-0.0057895481487933095 , -0.006992594096495442],
                            [-0.005768090476776777 , -0.007379018637601043],
                            [-0.004974156611859733 , -0.007379018637601043],
                            [-0.00499561428390171 , -0.004824323060317326],
                            [-0.005446225396528786 , -0.004824323060317326],
                        ]
                    },
                    "393": {
                        "type": "curve",
                        "coords": [
                            "M", [0.00004693865776914096 , -0.004626636158808762],
                            "L", [0.00004693865776914096 , -0.006107930233030246],
                            "L", [0.0006692111492001446 , -0.006107930233030246],
                            "L", [0.0006692111492001446 , -0.005184804940383536],
                            "C", [0.0006692111492001446 , -0.005184804940383536],
                                 [0.000540465116503004 , -0.0046695722189160635],
                                 [0.00004693865776914096 , -0.004626636158808762],
                            "Z"
                        ]
                    },
                    "392": {
                        "type": "polygon",
                        "coords": [
                            [0.00004693865776914096 , -0.006107930233030246],
                            [0.0006692111492001446 , -0.006107930233030246],
                            [0.0006692111492001446 , -0.007889776728102761],
                            [0.00004693865776914096 , -0.007889776728102761],
                        ]
                    },
                    "Yata Department Store": {
                        "type": "curve",
                        "coords": [
                            "M", [0.00004693865776914096 , -0.004626636158808762],
                            "L", [0.000025480985650830485 , -0.008984646261218643],
                            "C", [0.000025480985650830485 , -0.008984646261218643],
                                 [0.000776499509791697 , -0.008684093840367614],
                                 [0.0012700259684492268 , -0.008898774140984058],
                            "C", [0.0012700259684492268 , -0.008898774140984058],
                                 [0.00287935137627929 , -0.00930666671214331],
                                 [ 0.004467219109777377 , -0.010122451854461811],
                            "L", [0.005754679431787118 , -0.011754022139118804],
                            "L", [0.005776137103790928 , -0.013986697265477856],
                            "L", [-0.008750706877062439 , -0.013986697265477856],
                            "L", [-0.008750706877062439 , -0.012698615461819164],
                            "L", [-0.0069482624360449104 , -0.01267714743174553],
                            "L", [-0.006926804764079267 , -0.009392538832377896],
                            "L", [-0.006540566668608622 , -0.009371070802324244],
                            "L", [-0.006540566668608622 , -0.007846840667975474],
                            "L", [-0.004609376187120677 , -0.007825372637921824],
                            "L", [-0.004609376187120677 , -0.004605168128735127],
                            "Z"
                        ]
                    },
                    "Open Cafe B": {
                        "type": "curve",
                        "coords": [
                            "M", [-0.0013478100298574284 , 0.0004183509055977197],
                            "L", [-0.0013478100298574284 , 0.0016205605890018406],
                            "C", [-0.0013478100298574284 , 0.0016205605890018406],
                                 [-0.0018627941605187687 , 0.0019211130098728548],
                                 [-0.002506524323610083 , 0.0019211130098728548],
                            "L", [-0.002506524323610083 , 0.0005686271159932589],
                            "C", [-0.002506524323610083 , 0.0005686271159932589],
                                 [-0.0019271671768355334 , 0.0006759672663214645],
                                 [-0.0013478100298574284 , 0.0004183509055977197],
                            "Z"
                        ]
                    },
                };
            case 'L4':
                return {
                    "405A": {
                        "type": "curve",
                        "coords": [
                            "M", [0.0026004016390465874 , -0.0019649940330079834],
                            "L", [0.0026004016390465874 , -0.003016927506016565],
                            "L", [0.001377314329028057 , -0.0029954594759429303],
                            "C", [0.001377314329028057 , -0.0029954594759429303],
                                 [0.0014416873453575439 , -0.0024587587244218057],
                                 [0.001396760344367385 , -0.001948900181336644],
                            "Z"
                        ]
                    },
                    "405": {
                        "type": "curve",
                        "coords": [
                            "M", [0.0026004016390465874 , -0.003016927506016565],
                            "L", [0.001377314329028057 , -0.0029954594759429303],
                            "C", [0.001377314329028057 , -0.0029954594759429303],
                                 [0.001355856656897024 , -0.0036824364378995615],
                                 [0.0011412799357648083 , -0.004283541279621606],
                            "L", [0.0026004016390465874 , -0.004283541279621606],
                            "Z"
                        ]
                    },
                    "Yata Department Store": {
                        "type": "curve",
                        "coords": [
                            "M", [0.0026004016390465874 , -0.0019649940330079834],
                            "L", [0.0026004016390465874 , -0.004283541279621606],
                            "L", [0.0011412799357648083 , -0.004283541279621606],
                            "C", [0.0011412799357648083 , -0.004283541279621606],
                                 [0.0007335841655550761 , -0.005077858391886459],
                                 [-0.0001461803913083755 , -0.005271070662429267],
                            "L", [-0.003944188353272329 , -0.005271070662429267],
                            "L", [-0.003944188353272329 , -0.0058292394440240276],
                            "L", [-0.004394799466586405 , -0.006365940195545152],
                            "L", [-0.0044162571386411045 , -0.007825766239712983],
                            "L", [-0.006497651324639167 , -0.00784723426978662],
                            "L", [-0.006519108996630256 , -0.010766886358102305],
                            "L", [-0.008729249205198573 , -0.010787960786364792],
                            "L", [-0.008707791533321985 , -0.014029633325605141],
                            "L", [0.007063597422836392 , -0.014029633325605141],
                            "L", [0.0088231265196173 , -0.012312190920713565],
                            "L", [0.008801668847766156 , -0.004648104188862413],
                            "L", [0.007213801126494121 , -0.004648104188862413],
                            "L", [0.007192343454553922 , -0.0027374495134080416],
                            "L", [0.006098002183848086 , -0.0027159814833543914],
                            "L", [0.006076544511844275 , -0.004476359948373254],
                            "L", [0.004488676781832076 , -0.004476359948373254],
                            "L", [0.004445761437722678 , -0.002501301182737947],
                            "L", [0.0038020312758272517 , -0.0019431324011431885],
                            "Z"
                        ]
                    },
                    "406": {
                        "type": "curve",
                        "coords": [
                            "M", [0.0036947429154646993 , -0.0016046675220593445],
                            "L", [0.0036947429154646993 , -0.0013685191913892505],
                            "L", [0.002836436032093558 , -0.0004883299588898106],
                            "C", [0.002836436032093558 , -0.0004883299588898106],
                                 [0.0025574862948608552 , -0.00076741434968719],
                                 [0.0022570788854462307 , -0.0006815422294326191],
                            "L", [0.0019352138038878513 , -0.0007244782895599046],
                            "L", [0.0020639598365086585 , -0.0009176905601027131],
                            "L", [0.0020639598365086585 , -0.0016046675220593445],
                            "Z"
                        ]
                    },
                    "407": {
                        "type": "polygon",
                        "coords": [
                            [0.0036947429154646993 , -0.0016046675220593445],
                            [0.0036947429154646993 , -0.0013685191913892505],
                            [0.002836436032093558 , -0.0004883299588898106],
                            [0.0038878619641096604 , 0.0005636035140987873],
                            [0.0051324069431422795 , -0.0006815422294326191],
                            [0.006441324935705498 , -0.0006815422294326191],
                            [0.006462782607683864 , -0.0023131125140896107],
                            [0.0048749148786894425 , -0.00229164448403596],
                            [0.003930777308244504 , -0.0016046675220593445],
                        ]
                    },
                    "408": {
                        "type": "polygon",
                        "coords": [
                            [0.0038878619641096604 , 0.0005636035140987873],
                            [0.0051324069431422795 , -0.0006815422294326191],
                            [0.006441324935705498 , -0.0006815422294326191],
                            [0.006441324935705498 , 0.000992964115331674],
                            [0.005325525991405574 , 0.0009714960852580391],
                            [0.004810541862563512 , 0.0014652607766718617],
                        ]
                    },
                    "410": {
                        "type": "curve",
                        "coords": [
                            "M", [0.008801668847766156 , 0.002818119556433629],
                            "L", [0.005883425463835425 , 0.002818119556433629],
                            "C", [0.005883425463835425 , 0.002818119556433629],
                                 [0.005497187367588725 , 0.0021311425944769984],
                                 [0.005003660910915861 , 0.001680313963210445],
                            "L", [0.005239695303288555 , 0.001358293512305764],
                            "L", [0.0068704783751837644 , 0.001358293512305764],
                            "L", [0.007449835517874481 , 0.000800124730711005],
                            "L", [0.007471293189814681 , -0.0013681463054870948],
                            "L", [0.0088231265196173 , -0.0013681463054870948],
                            "Z"
                        ]
                    },
                    "411": {
                        "type": "curve",
                        "coords": [
                            "M", [0.008801668847766156 , 0.002818119556433629],
                            "L", [0.005883425463835425 , 0.002818119556433629],
                            "C", [0.005883425463835425 , 0.002818119556433629],
                                 [0.005990713823854478 , 0.003011331827016406],
                                 [0.005969256151837945 , 0.0032045440975592148],
                            "L", [0.006441324935705498 , 0.0032260121276328495],
                            "L", [0.006441324935705498 , 0.005201070893248172],
                            "L", [0.008780211175889568 , 0.005201070893248172],
                            "Z"
                        ]
                    },
                    "412": {
                        "type": "polygon",
                        "coords": [
                            [0.005969256151837945 , 0.0032045440975592148],
                            [0.006441324935705498 , 0.0032260121276328495],
                            [0.006441324935705498 , 0.005201070893248172],
                            [0.005969256151837945 , 0.005222538923321808],
                        ]
                    },
                    "413": {
                        "type": "polygon",
                        "coords": [
                            [0.005969256151837945 , 0.005222538923321808],
                            [0.007449835517874481 , 0.005222538923321808],
                            [0.007449835517874481 , 0.006016856035566677],
                            [0.005969256151837945 , 0.006016856035566677],
                        ]
                    },
                    "415": {
                        "type": "polygon",
                        "coords": [
                            [0.007449835517874481 , 0.006016856035566677],
                            [0.005969256151837945 , 0.006016856035566677],
                            [0.005969256151837945 , 0.00683264117788518],
                            [0.007449835517874481 , 0.00683264117788518],
                        ]
                    },
                    "417-419": {
                        "type": "polygon",
                        "coords": [
                            [0.000969618558843769 , 0.006575024817161436],
                            [0.005025118582970561 , 0.006575024817161436],
                            [0.005025118582970561 , 0.0073478738993726376],
                            [0.0041882693730281185 , 0.008185127071764775],
                            [0.001699179410688214 , 0.008185127071764775],
                            [0.000969618558843769 , 0.007369341929406304],
                        ]
                    },
                    "430-431": {
                        "type": "polygon",
                        "coords": [
                            [0.000969618558843769 , 0.006575024817161436],
                            [0.005025118582970561 , 0.006575024817161436],
                            [0.005025118582970561 , 0.005244006953355474],
                            [0.000969618558843769 , 0.005244006953355474],
                        ]
                    },
                    "420-422": {
                        "type": "polygon",
                        "coords": [
                            [0.005025118582970561 , 0.005244006953355474],
                            [0.0032226741297015356 , 0.005244006953355474],
                            [0.0032226741297015356 , 0.004084733330058655],
                            [0.005025118582970561 , 0.004084733330058655],
                        ]
                    },
                    "429": {
                        "type": "polygon",
                        "coords": [
                            [0.000969618558843769 , 0.005244006953355474],
                            [0.0029222667204523 , 0.005244006953355474],
                            [0.0029222667204523 , 0.004256477570567797],
                            [0.0026004016390465874 , 0.004256477570567797],
                            [0.0026004016390465874 , 0.003977393179770417],
                            [0.000969618558843769 , 0.003998861209804084],
                        ]
                    },
                    "428": {
                        "type": "polygon",
                        "coords": [
                            [0.0026004016390465874 , 0.003977393179770417],
                            [0.000969618558843769 , 0.003998861209804084],
                            [0.000969618558843769 , 0.0021955466846579346],
                            [0.0015060603616870308 , 0.0022170147147315693],
                            [0.0015275180338053412 , 0.002818119556433629],
                            [0.002943724392557888 , 0.002839587586507265],
                            [0.002943724392557888 , 0.00378418090918764],
                            [0.0026218593111394534 , 0.00378418090918764],
                        ]
                    },
                    "423": {
                        "type": "curve",
                        "coords": [
                            "M", [0.0032226741297015356 , 0.004084733330058655],
                            "L", [0.005025118582970561 , 0.004084733330058655],
                            "L", [0.005025118582970561 , 0.0035695006086111656],
                            "C", [0.005025118582970561 , 0.0035695006086111656],
                                 [0.004939287894815374 , 0.0030542678871237077],
                                 [0.0045745074700508735 , 0.0027966515263999625],
                            "L", [0.004402846093626001 , 0.0029683957668691363],
                            "L", [0.004402846093626001 , 0.0033118842478474524],
                            "L", [0.0032226741297015356 , 0.0033118842478474524],
                            "Z"
                        ]
                    },
                    "425": {
                        "type": "curve",
                        "coords": [
                            "M", [0.0032226741297015356 , 0.0033118842478474524],
                            "L", [0.004402846093626001 , 0.0033118842478474524],
                            "L", [0.004402846093626001 , 0.0029683957668691363],
                            "L", [0.0045745074700508735 , 0.0027966515263999625],
                            "C", [0.0045745074700508735 , 0.0027966515263999625],
                                 [0.003930777308244504 , 0.0022170147147315693],
                                 [0.0032226741297015356 , 0.002238482744805204],
                            "Z"
                        ]
                    },
                    "426-427": {
                        "type": "curve",
                        "coords": [
                            "M", [0.000969618558843769 , 0.0021955466846579346],
                            "L", [0.0015060603616870308 , 0.0022170147147315693],
                            "L", [0.0015275180338053412 , 0.002818119556433629],
                            "L", [0.0022570788854462307 , 0.002839587586507265],
                            "L", [0.0022785365575645413 , 0.0008645288208919412],
                            "L", [ 0.0029222667204523 , 0.0008645288208919412],
                            "C", [ 0.0029222667204523 , 0.0008645288208919412],
                                 [0.0022570788854462307 , -0.0000371284416811335],
                                 [0.0015060603616870308 , 0.0005210403399136254],
                            "C", [0.0015060603616870308 , 0.0005210403399136254],
                                 [0.0012056529521070176 , 0.0006927845803827992],
                                 [0.0009481608867127363 , 0.001100677151542051],
                            "Z"
                        ]
                    },
                    "416": {
                        "type": "curve",
                        "coords": [
                            "M", [0.008801668847766156 , 0.007237612809394279],
                            "L", [0.005990713823854478 , 0.007216144779320644],
                            "L", [0.005754679431787118 , 0.009040927334540427],
                            "C", [0.005754679431787118 , 0.009040927334540427],
                                 [0.005282610647347065 , 0.01037194519834639],
                                 [0.004274100061272361 , 0.0106080935289965],
                            "L", [0.00429555773332706 , 0.013720957887874976],
                            "L", [0.005346983663434829 , 0.013720957887874976],
                            "C", [0.005346983663434829 , 0.013720957887874976],
                                 [0.005218237631272022 , 0.012797832595268236],
                                 [0.0054328143515264046 , 0.01213232366334527],
                            "C", [0.0054328143515264046 , 0.01213232366334527],
                                 [ 0.0057117640877540525 , 0.010930113979941149],
                                 [0.006698816999433169 , 0.010264605048018184],
                            "L", [0.008801668847766156 , 0.010243137017984518],
                            "Z"
                        ]
                    },
                    "432": {
                        "type": "polygon",
                        "coords": [
                           [0.004274100061272361 , 0.0106080935289965],
                           [0.004274100061272361 , 0.013720957887874976],
                           [0.002214163541235054 , 0.013720957887874976],
                           [0.002214163541235054 , 0.0106080935289965],
                        ]
                    },
                    "433": {
                        "type": "polygon",
                        "coords": [
                           [0.002214163541235054 , 0.013720957887874976],
                           [0.002214163541235054 , 0.0106080935289965],
                           [-0.0014121830461996377 , 0.0106080935289965],
                           [-0.0014121830461996377 , 0.013720957887874976],
                        ]
                    },
                    "439-441": {
                        "type": "polygon",
                        "coords": [
                           [-0.0014121830461996377 , 0.0106080935289965],
                           [-0.0014121830461996377 , 0.013720957887874976],
                           [-0.004394799466586405 , 0.013720957887874976],
                           [-0.004394799466586405 , 0.0106080935289965],
                        ]
                    },
                    "441A": {
                        "type": "polygon",
                        "coords": [
                           [-0.004394799466586405 , 0.013720957887874976],
                           [-0.004394799466586405 , 0.0106080935289965],
                           [-0.005553513756636894 , 0.0106080935289965],
                           [-0.005553513756636894 , 0.011831771242514225],
                           [-0.0068624317481950576 , 0.011831771242514225],
                           [-0.0068624317481950576 , 0.013334533346789358],
                           [-0.005639344444703025 , 0.01374242591794861],
                        ]
                    },
                    "442-445": {
                        "type": "polygon",
                        "coords": [
                           [-0.005553513756636894 , 0.011831771242514225],
                           [-0.0068624317481950576 , 0.011831771242514225],
                           [-0.0068624317481950576 , 0.013334533346789358],
                           [-0.008729249205198573 , 0.012797832595268236],
                           [-0.008729249205198573 , 0.008053397951712784],
                           [-0.005574971428653427 , 0.008031929921679117],
                        ]
                    },
                    "446-448": {
                        "type": "polygon",
                        "coords": [
                           [-0.005574971428653427 , 0.00399841581832039],
                           [-0.007506161906706373 , 0.00399841581832039],
                           [-0.007506161906706373 , 0.0074762366882108475],
                           [-0.005574971428653427 , 0.0074762366882108475],
                        ]
                    },
                    "450-453 & 449": {
                        "type": "polygon",
                        "coords": [
                           [-0.005574971428653427 , 0.00399841581832039],
                           [-0.007506161906706373 , 0.00399841581832039],
                           [-0.007506161906706373 , 0.0074762366882108475],
                           [-0.008729249205198573 , 0.0074762366882108475],
                           [-0.008729249205198573 , -0.0004669344344176985],
                           [-0.006883889420160701 , -0.0004669344344176985],
                           [-0.0068624317481950576 , -0.0011109753362470445],
                           [-0.006047040212851758 , -0.0011109753362470445],
                           [-0.005553513756636894 , 0.0006279350987181688],
                        ]
                    },
                    "435-436": {
                        "type": "polygon",
                        "coords": [
                           [0.00013276934624238283 , 0.007218620327487104],
                           [-0.0026138126840998576 , 0.007218620327487104],
                           [-0.0026138126840998576 , 0.009837719994951756],
                           [0.00013276934624238283 , 0.009837719994951756],
                        ]
                    },
                    "437-438": {
                        "type": "polygon",
                        "coords": [
                           [-0.0026138126840998576 , 0.007218620327487104],
                           [-0.0026138126840998576 , 0.009837719994951756],
                           [-0.004738122219397984 , 0.009837719994951756],
                           [-0.0047166645473560075 , 0.007218620327487104],
                        ]
                    },
                    "447A": {
                        "type": "polygon",
                        "coords": [
                           [-0.0047166645473560075 , 0.007218620327487104],
                           [-0.0038154423208678003 , 0.007218620327487104],
                           [-0.0038154423208678003 , 0.0053509017121600175],
                           [-0.004738122219397984 , 0.005372369742233652],
                        ]
                    },
                    "448A": {
                        "type": "polygon",
                        "coords": [
                           [-0.0038154423208678003 , 0.0053509017121600175],
                           [-0.004738122219397984 , 0.005372369742233652],
                           [-0.0047166645473560075 , 0.002989418405419109],
                           [-0.0037939846487876562 , 0.0030108864354927434],
                        ]
                    },
                    "455-457": {
                        "type": "curve",
                        "coords": [
                            "M", [-0.004866868251675292 , -0.007441848320597356],
                            "L", [-0.008729249205198573 , -0.007441848320597356],
                            "L", [-0.008729249205198573 , -0.0019460326249243214],
                            "L", [-0.006004124868844137 , -0.0019460326249243214],
                            "C", [-0.006004124868844137 , -0.0019460326249243214],
                                 [-0.006154328572832644 , -0.002826221857443745],
                                 [-0.0058110058207971205 , -0.0035776029095813127],
                            "C", [-0.0058110058207971205 , -0.0035776029095813127],
                                 [-0.0055105984125911065 , -0.004651004412643546],
                                 [-0.004781037563494661 , -0.00518770516416467],
                            "L", [-0.0042875111063001865 , -0.005209173194218321],
                            "L", [-0.004266053434245487 , -0.005681469855578492],
                            "L", [-0.0048454105796205925 , -0.006239638637173251],
                            "L", [ -0.0048454105796205925 , -0.007420380290523721],
                            "Z"
                        ]
                    },
                    "Open Cafe C": {
                        "type": "curve",
                        "coords": [
                            "M", [-0.0009615719317914512 , 0.0003939826517429524],
                            "L", [-0.0009615719317914512 , 0.00178940460572985],
                            "C", [-0.0009615719317914512 , 0.00178940460572985],
                                 [-0.0022919476026050894 , 0.0023905094474319104],
                                 [-0.003579407928037107 , 0.00178940460572985],
                            "L", [-0.003579407928037107 , 0.00030811053148838147],
                            "C", [-0.003579407928037107 , 0.00030811053148838147],
                                 [-0.002334862946816266 , 0.0012312358241350907],
                                 [-0.0009615719317914512 , 0.0003939826517429524],
                            "Z"
                        ]
                    },
                };
            case 'L5':
                return {
                    "501": {
                        "type": "polygon",
                        "coords": [
                            [0.006162375199834073 , -0.0140305241086125],
                            [0.006140917527842985 , -0.010853255659573069],
                            [0.005754679431787118 , -0.010552703238702056],
                            [0.005776137103790928 , -0.009135813254661507],
                            [0.0088231265196173 , -0.009135813254661507],
                            [0.0088231265196173 , -0.012398953823975491],
                            [0.007192343454553922 , -0.0140305241086125],
                        ]
                    },
                    "502": {
                        "type": "polygon",
                        "coords": [
                            [0.005776137103790928 , -0.009135813254661507],
                            [0.0088231265196173 , -0.009135813254661507],
                            [0.008801668847766156 , -0.007246626609280772],
                            [0.005797594775794739 , -0.007246626609280772],
                        ]
                    },
                    "503A": {
                        "type": "polygon",
                        "coords": [
                            [0.008801668847766156 , -0.007246626609280772],
                            [0.005797594775794739 , -0.007246626609280772],
                            [0.005797594775794739 , -0.006237629196399476],
                            [0.008801668847766156 , -0.006237629196399476],
                        ]
                    },
                    "503B": {
                        "type": "polygon",
                        "coords": [
                            [0.005797594775794739 , -0.006237629196399476],
                            [0.008801668847766156 , -0.006237629196399476],
                            [0.008801668847766156 , -0.005228631783538163],
                            [0.005797594775794739 , -0.005228631783538163],
                        ]
                    },
                    "503& 505-506": {
                        "type": "polygon",
                        "coords": [
                            [0.008801668847766156 , -0.005228631783538163],
                            [0.005797594775794739 , -0.005228631783538163],
                            [0.005797594775794739 , -0.0026954042363080966],
                            [0.008801668847766156 , -0.002673936206254446],
                        ]
                    },
                    "507": {
                        "type": "polygon",
                        "coords": [
                            [0.005840510119815082 , -0.002158703484786973],
                            [0.005840510119815082 , -0.00048419714000269576],
                            [0.006140917527842985 , -0.00020511274920531622],
                            [0.006140917527842985 , 0.00041746012255039494],
                            [0.006334036575762779 , 0.0004389281526240296],
                            [0.006334036575762779 , -0.0014717265228303411],
                            [0.006183832871837883 , -0.0014931945528839923],
                            [0.006183832871837883 , -0.002158703484786973],
                        ]
                    },
                    "508-510": {
                        "type": "polygon",
                        "coords": [
                            [0.008780211175889568 , 0.0050355517759159705],
                            [0.007192343454553922 , 0.0050355517759159705],
                            [0.007192343454553922 , 0.00531463616671335],
                            [0.004510134453886776 , 0.00531463616671335],
                            [0.004510134453886776 , 0.00046286137290962875],
                            [0.00569030641573752 , 0.000441393342835994],
                            [0.0057117640877540525 , 0.0011283703047926255],
                            [0.008780211175889568 , 0.0011283703047926255],
                        ]
                    },
                    "511-512": {
                        "type": "polygon",
                        "coords": [
                            [0.004488676781832076 , 0.00531463616671335],
                            [0.002814978360000692 , 0.00531463616671335],
                            [0.002793520687907826 , 0.004305638753852038],
                            [0.0023214519017502734 , 0.004305638753852038],
                            [0.0022999942296574073 , 0.00267406846921503],
                            [0.002965182064638032 , 0.0026311324090677606],
                            [0.002965182064638032 , 0.0008063498538879444],
                            [0.002407282590159904 , 0.0007848818238143098],
                            [0.002407282590159904 , 0.00046286137290962875],
                            [0.004510134453886776 , 0.00046286137290962875],
                        ]
                    },
                    "513-516": {
                        "type": "polygon",
                        "coords": [
                            [0.008780211175889568 , 0.0050355517759159705],
                            [0.007192343454553922 , 0.0050355517759159705],
                            [0.007192343454553922 , 0.00531463616671335],
                            [0.002814978360000692 , 0.00531463616671335],
                            [0.002793520687907826 , 0.004305638753852038],
                            [0.0023214519017502734 , 0.004305638753852038],
                            [0.0022999942296574073 , 0.00267406846921503],
                            [0.0012056529521070176 , 0.00267406846921503],
                            [0.0012056529521070176 , 0.007375567052583244],
                            [0.008780211175889568 , 0.007375567052583244],
                        ]
                    },
                    "517": {
                        "type": "polygon",
                        "coords": [
                            [0.0031153857692626497 , -0.0016410055731075348],
                            [0.0031368434413555158 , -0.00048173194979073136],
                            [0.0018064677712415996 , -0.00048173194979073136],
                            [0.0012700259684492268 , -0.0009754966412045541],
                            [0.0015275180338053412 , -0.0016410055731075348],
                        ]
                    },
                    "518": {
                        "type": "polygon",
                        "coords": [
                            [0.0031153857692626497 , -0.0016410055731075348],
                            [0.0031368434413555158 , -0.00048173194979073136],
                            [0.004467219109777377 , -0.00048173194979073136],
                            [0.004853457206647466 , -0.0008896245209499833],
                            [0.004853457206647466 , -0.002027430114193152],
                            [0.003265589473861823 , -0.002027430114193152],
                            [0.003244131801781679 , -0.0016624736031611855],
                        ]
                    },
                    "519": {
                        "type": "polygon",
                        "coords": [
                            [0.004853457206647466 , -0.0035301922184882715],
                            [0.004510134453886776 , -0.0035301922184882715],
                            [0.004488676781832076 , -0.0033155119178718277],
                            [0.004166811700973419 , -0.0033155119178718277],
                            [0.004166811700973419 , -0.0023923866252451025],
                            [0.0048749148786894425 , -0.0023923866252451025],
                        ]
                    },
                    "520": {
                        "type": "polygon",
                        "coords": [
                            [0.004853457206647466 , -0.0035301922184882715],
                            [0.004510134453886776 , -0.0035301922184882715],
                            [0.004488676781832076 , -0.0033155119178718277],
                            [0.003329962490114977 , -0.0033155119178718277],
                            [0.003329962490114977 , -0.005526719014177229],
                            [0.004853457206647466 , -0.005526719014177229],
                        ]
                    },
                    "521": {
                        "type": "polygon",
                        "coords": [
                            [0.003308504818034833 , -0.00737296959943068],
                            [0.0048749148786894425 , -0.00737296959943068],
                            [0.0048749148786894425 , -0.005741399314793672],
                            [0.003308504818034833 , -0.005741399314793672],
                        ]
                    },
                    "522-523": {
                        "type": "polygon",
                        "coords": [
                            [0.003308504818034833 , -0.00737296959943068],
                            [0.0048749148786894425 , -0.00737296959943068],
                            [0.00489637255073142 , -0.009412432455246922],
                            [0.004531592125941475 , -0.00975592093622524],
                            [0.003308504818034833 , -0.009734452906171588],
                        ]
                    },
                    "525": {
                        "type": "polygon",
                        "coords": [
                            [0.003308504818034833 , -0.00737296959943068],
                            [0.003308504818034833 , -0.009734452906171588],
                            [0.0025574862948608552 , -0.009734452906171588],
                            [0.0025574862948608552 , -0.007394437629504315],
                        ]
                    },
                    "525A": {
                        "type": "polygon",
                        "coords": [
                            [0.0025574862948608552 , -0.009734452906171588],
                            [0.0025574862948608552 , -0.007394437629504315],
                            [0.001849383115452776 , -0.00737251384999249],
                            [0.001849383115452776 , -0.009733997156713414],
                        ]
                    },
                    "526": {
                        "type": "polygon",
                        "coords": [
                            [0.001849383115452776 , -0.00737251384999249],
                            [0.001849383115452776 , -0.009733997156713414],
                            [0.00041171908379314116 , -0.009733997156713414],
                            [0.0003902614116748307 , -0.007393981880046141],
                        ]
                    },
                    "527": {
                        "type": "polygon",
                        "coords": [
                            [0.00041171908379314116 , -0.009733997156713414],
                            [0.0003902614116748307 , -0.007393981880046141],
                            [-0.0010259449481463826 , -0.007393981880046141],
                            [-0.0010259449481463826 , -0.009733997156713414],
                        ]
                    },
                    "528-529": {
                        "type": "polygon",
                        "coords": [
                            [-0.0010259449481463826 , -0.007393981880046141],
                            [-0.0010259449481463826 , -0.009733997156713414],
                            [-0.002442151307318763 , -0.009755465186787049],
                            [-0.002764016388775364 , -0.009433444735862386],
                            [-0.002764016388775364 , -0.007694534300917155],
                            [-0.0024206936352131744 , -0.00737251384999249],
                        ]
                    },
                    "540-541": {
                        "type": "polygon",
                        "coords": [
                            [-0.0009401142596731407 , -0.01400816529555149],
                            [-0.0009615719317914512 , -0.010723556696183857],
                            [0.0018708407875583644 , -0.010723556696183857],
                            [0.0018708407875583644 , -0.01400816529555149],
                        ]
                    },
                    "542-543": {
                        "type": "polygon",
                        "coords": [
                            [0.0018708407875583644 , -0.010723556696183857],
                            [0.0018708407875583644 , -0.01400816529555149],
                            [0.004488676781832076 , -0.01400816529555149],
                            [0.004488676781832076 , -0.010723556696183857],
                        ]
                    },
                    "545": {
                        "type": "polygon",
                        "coords": [
                            [0.004488676781832076 , -0.010723556696183857],
                            [0.00526115297531781 , -0.010702088666110224],
                            [0.005561560383651045 , -0.011024109117034886],
                            [0.005561560383651045 , -0.012741551521926468],
                            [0.004531592125941475 , -0.012741551521926468],
                        ]
                    },
                    "536-539": {
                        "type": "polygon",
                        "coords": [
                            [-0.008707791533321985 , -0.01400816529555149],
                            [-0.008707791533321985 , -0.013149444093085718],
                            [-0.0076134502663692035 , -0.013149444093085718],
                            [-0.007591992594429004 , -0.008963178231164992],
                            [-0.00407293438567686 , -0.008963178231164992],
                            [-0.004051476713609437 , -0.010401536245259193],
                            [-0.0037939846487876562 , -0.010702088666110224],
                            [-0.0014765560625291246 , -0.010702088666110224],
                            [-0.0014765560625291246 , -0.01235512698084085],
                            [-0.0036223232721973946 , -0.01235512698084085],
                            [-0.003643780944264816 , -0.01400816529555149],
                        ]
                    },
                    "530": {
                        "type": "curve",
                        "coords": [
                            "M", [0.0032870471459546893 , -0.0073530759765816365],
                            "L", [-0.0010259449481463826 , -0.0073530759765816365],
                            "L", [-0.0010259449481463826 , -0.006086462202956611],
                            "C", [-0.0010259449481463826 , -0.006086462202956611],
                                 [-0.00016763806342668598 , -0.005936185992541088],
                                 [0.00043317675591145167 , -0.005270677060638108],
                            "L", [0.001184195279988707 , -0.00597912205264839],
                            "L", [0.003265589473861823 , -0.006000590082722025],
                            "Z"
                        ]
                    },
                    "531": {
                        "type": "curve",
                        "coords": [
                            "M", [0.00043317675591145167 , -0.005270677060638108],
                            "L", [0.001184195279988707 , -0.00597912205264839],
                            "L", [0.003265589473861823 , -0.006000590082722025],
                            "L", [0.003265589473861823 , -0.004411955858192319],
                            "L", [0.002192705869142188 , -0.004411955858192319],
                            "L", [0.001463145017463132 , -0.003746446926289338],
                            "C", [0.001463145017463132 , -0.003746446926289338],
                                 [0.0012056529521070176 , -0.004712508279043349],
                                 [0.00043317675591145167 , -0.005270677060638108],
                            "Z"
                        ]
                    },
                    "532": {
                        "type": "curve",
                        "coords": [
                            "M", [0.001463145017463132 , -0.003746446926289338],
                            "L", [0.002192705869142188 , -0.004411955858192319],
                            "L", [0.003265589473861823 , -0.004411955858192319],
                            "L", [0.003265589473861823 , -0.003038001934279056],
                            "L", [0.0016133487222404165 , -0.003038001934279056],
                            "C", [0.0016133487222404165 , -0.003038001934279056],
                                 [0.001634806394358727 , -0.0034244264753646734],
                                 [0.001463145017463132 , -0.003746446926289338],
                            "Z"
                        ]
                    },
                    "532A": {
                        "type": "curve",
                        "coords": [
                            "M", [0.0016133487222404165 , -0.003038001934279056],
                            "L", [0.003008097408823764 , -0.003038001934279056],
                            "L", [0.003008097408823764 , -0.0022866208821215044],
                            "L", [0.002643316983232319 , -0.0022866208821215044],
                            "L", [0.0024287402622527703 , -0.0020504725514514104],
                            "L", [0.0016133487222404165 , -0.0020504725514514104],
                            "C", [0.0016133487222404165 , -0.0020504725514514104],
                                 [ 0.0017420947549121127 , -0.002544237242865233],
                                 [0.0016133487222404165 , -0.003038001934279056],
                            "Z"
                        ]
                    },
                    "533-535": {
                        "type": "curve",
                        "coords": [
                            "M", [-0.004909783595746524 , -0.006045566657426616],
                            "L", [-0.004909783595746524 , -0.0064105231684585835],
                            "L", [-0.005296021692362168 , -0.0064319911985322165],
                            "L", [-0.005317479364391423 , -0.006839883769691469],
                            "L", [-0.005982667196840327 , -0.007720073002190909],
                            "L", [-0.006540566668608622 , -0.007741541032264544],
                            "L", [-0.006540566668608622 , -0.008557326174583046],
                            "L", [-0.0039012730091502086 , -0.008535858144529396],
                            "L", [-0.0039012730091502086 , -0.006067034687480267],
                            "C", [-0.0039012730091502086 , -0.006067034687480267],
                                 [-0.004394799466586405 , -0.0061743748377884895],
                                 [-0.004909783595746524 , -0.006045566657426616],
                            "Z"
                        ]
                    },
                    "Play Garden": {
                        "type": "curve",
                        "coords": [
                            "M", [-0.004909783595746524 , -0.006045566657426616],
                            "L", [-0.004909783595746524 , -0.0064105231684585835],
                            "L", [-0.005296021692362168 , -0.0064319911985322165],
                            "L", [-0.005317479364391423 , -0.006839883769691469],
                            "L", [-0.005982667196840327 , -0.007720073002190909],
                            "L", [-0.008750706877062439 , -0.007743042933847645],
                            "L", [-0.008729249205198573 , 0.012798391924131458],
                            "L", [-0.0055105984125911065 , 0.013678581156630901],
                            "L", [0.007149428110648079 , 0.013742871309432306],
                            "L", [0.008801668847766156 , 0.01196102481435979],
                            "L", [0.008780211175889568 , 0.007409802441387116],
                            "L", [0.0011627376078831187 , 0.007388334411353449],
                            "L", [0.001184195279988707 , 0.0011017854513539762],
                            "C", [0.001184195279988707 , 0.0011017854513539762],
                                 [0.0004760921001353504 , 0.0009944453010657386],
                                 [-0.00003889203071682314 , 0.00039334045932371003],
                            "C", [-0.00003889203071682314 , 0.00039334045932371003],
                                 [-0.0006397068500295164 , 0.0011649983782069384],
                                 [-0.0016696751115303078 , 0.0011237195888202935],
                            "C", [-0.0016696751115303078 , 0.0011237195888202935],
                                 [-0.0028069317329610963 , 0.0014013227938836084],
                                 [-0.003751069304640091 , 0.0007372950476947083],
                            "C", [-0.003751069304640091 , 0.0007372950476947083],
                                 [-0.005017071955943688 , 0.00005031808573807695],
                                 [-0.005296021692362168 , -0.0012592317479742656],
                            "C", [-0.005296021692362168 , -0.0012592317479742656],
                                 [-0.005703717460739901 , -0.0019462087099508811],
                                 [-0.005124360316115407 , -0.003492372981745984],
                            "C", [-0.005124360316115407 , -0.003492372981745984],
                                 [-0.005896836508825084 , -0.0036868696371072937],
                                 [-0.006047040212851758 , -0.004737052617904692],
                            "C", [-0.006047040212851758 , -0.004737052617904692],
                                 [-0.005982667196840327 , -0.0059603160137022835],
                                 [-0.004909783595746524 , -0.006045566657426616],
                            "Z"
                        ]
                    },
                };
            case 'L6':
                return {
                    "609-613": {
                        "type": "curve",
                        "coords": [
                            "M", [0.00028297305108327834 , -0.013750548934807764],
                            "L", [0.00028297305108327834 , -0.010916768966726666],
                            "L", [0.001785010099123289 , -0.010916768966726666],
                            "L", [0.001785010099123289 , -0.0071169276458915584],
                            "C", [0.001785010099123289 , -0.0071169276458915584],
                                 [0.002643316983232319 , -0.006988119465529686],
                                 [0.003008097408823764 , -0.006236738413392119],
                            "L", [0.004424303765667978 , -0.007159863706018844],
                            "L", [ 0.008737295832149113 , -0.0071813317360724955],
                            "L", [0.0087587535040257 , -0.01207604259004347],
                            "L", [0.007042139750870748 , -0.013750548934807764],
                            "Z"
                        ]
                    },
                    "615-620": {
                        "type": "curve",
                        "coords": [
                            "M", [0.003008097408823764 , -0.006236738413392119],
                            "L", [0.004424303765667978 , -0.007159863706018844],
                            "L", [ 0.008737295832149113 , -0.0071813317360724955],
                            "L", [0.008737295832149113 , -0.0024368970925570115],
                            "L", [0.0032226741297015356 , -0.0024368970925570115],
                            "L", [0.0032226741297015356 , -0.004948656609713443],
                            "C", [0.0032226741297015356 , -0.004948656609713443],
                                 [0.003265589473861823 , -0.0056141655416164235],
                                 [0.003008097408823764 , -0.006236738413392119],
                            "Z"
                        ]
                    },
                    "601-608": {
                        "type": "curve",
                        "coords": [
                            "M", [0.00028297305108327834 , -0.013750548934807764],
                            "L", [0.00028297305108327834 , -0.010916768966726666],
                            "L", [0.001785010099123289 , -0.010916768966726666],
                            "L", [0.001785010099123289 , -0.0071169276458915584],
                            "L", [-0.0013478100298574284 , -0.007138395675965193],
                            "C", [-0.0013478100298574284 , -0.007138395675965193],
                                 [-0.0029785931096913023 , -0.0071169276458915584],
                                 [-0.0037081539605052474 , -0.006880779315221464],
                            "C", [-0.0037081539605052474 , -0.006880779315221464],
                                 [-0.00407293438567686 , -0.006859311285167814],
                                 [-0.004223138090123366 , -0.006601694924424086],
                            "L", [-0.007677823282151635 , -0.006601694924424086],
                            "L", [-0.007677823282151635 , -0.012827423642181035],
                            "L", [-0.008750706877062439 , -0.012848891672234686],
                            "L", [-0.008750706877062439 , -0.013772016964861411],
                            "Z"
                        ]
                    },
                    "621-630": {
                        "type": "polygon",
                        "coords": [
                            [0.0032226741297015356 , -0.0019679667623706543],
                            [0.0032012164576213915 , 0.00047938866460484064],
                            [0.00378057360375983 , 0.0016171942578280254],
                            [0.00378057360375983 , 0.0029267440915603524],
                            [0.0012056529521070176 , 0.0029267440915603524],
                            [0.001227110624225328 , 0.007649710705042168],
                            [0.008801668847766156 , 0.007649710705042168],
                            [0.008737295832149113 , -0.000980437379562993],
                            [0.0075142085336823575 , -0.000980437379562993],
                            [0.00749275086175488 , 0.0009946213860523303],
                            [0.006076544511844275 , 0.0009946213860523303],
                            [0.006033629167849376 , -0.0019679667623706543],
                        ]
                    },
                };
        }
        return {};
    }

    function hex2a(hexx) {
        var hex = hexx.toString();
        var str = '';
        for (var i = 0;
            (i < hex.length && hex.substr(i, 2) !== '00'); i += 2)
            str += String.fromCharCode(parseInt(hex.substr(i, 2), 16));
        return str;
    }

    function showFacility(facility) {
        if ($('body').hasClass('facility-' + facility + '-none')) {
            return;
        }
        $('body').addClass('facility-' + facility + '-active');
    }

    function hideFacility(facility) {
        if ($('body').hasClass('facility-' + facility + '-none')) {
            return;
        }
        $('body').removeClass('facility-' + facility + '-active');
    }

    function toggleFacility(facility) {
        if ($('body').hasClass('facility-' + facility + '-none')) {
            return;
        }
        $('body').toggleClass('facility-' + facility + '-active');
    }

    for (var facility in availableFacilities) {
        $('.facility-' + facility + ' .popup-label').html(availableFacilities[facility].label['<?php echo $lang ?>'].replace(/\n/g, '<br>'));
        showFacility(facility);
    }

    $(document).ready(function() {
        resize();
    });
    <?php
    switch (strtoupper($floor)) {
        default:
    ?>
            changeFloor('B1');
        <?php
            break;
        case 'B1':
        case 'L1':
        case 'L2':
        case 'L3':
        case 'L4':
        case 'L5':
        case 'L6':
        ?>
            changeFloor('<?php echo strtoupper($floor) ?>');
        <?php
            break;
    }
    if (isset($selected_shop)) {
        ?>
        focusShop('<?php echo $selected_shop ?>');
    <?php
    }
    if ($is_show_menu) {
    ?>
        $('body').addClass('menu-showing');
        leaflet.invalidateSize();
    <?php
    }
    ?>
    menuSearchFloor();
    $('body').on('click', 'a[data-href-mobile="#tab_mobile_floor-plan"]', function() {
        window.dispatchEvent(new Event('resize'));
        $('#shoppings_page .floors-mobile select').selectric({
            maxHeight: 200
        });
    })
</script>
