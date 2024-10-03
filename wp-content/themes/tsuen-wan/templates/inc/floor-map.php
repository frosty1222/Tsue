<?php
    $lang = pll_current_language();
    $page_fields = get_fields(get_the_ID());

    $query_arr = array(
     
        "zoomLevel" => "2",
        "noControls" => "1",
        "lang" => $lang
    );
     
    if(!empty($page_fields["shop_numbers"])){
        $shop_numbers = $page_fields["shop_numbers"];
        $shop_number_string = "";
        if(!empty($shop_numbers)){
            foreach ($shop_numbers as $key => $shop_number){
                
                if(empty($shop_number["shop_number"])) continue;
                if($key != 0){
                    $shop_number_string .= "&";
                }
                $shop_number_string .= $shop_number["shop_number"];
            }
        }
        $query_arr["shop"] = $shop_number_string;
    }
    $query = http_build_query(
        $query_arr
    );
    $floor_plan_url = $query;
     
     
    $scrollZoom = 0;
    $menu = 0;
    $dragging = true;
    $zoomLevel = 2;
    $noControl = true;
    $floor = $page_fields['shop_phase_level'];

    if(isset($shop_number_string)) {
    $selected_shop = trim($shop_number_string);
    }
?>
<div class="floor-map-wrapper">
    <div class="fire-top"><?php ani_firework() ?></div>
    <div class="container">
        <div class="floor-map">

            <?php include( locate_template( 'map/floorplan.php', false, false ) ); ?>
        </div>
    </div>
    <div class="fire-bottom"><?php ani_firework() ?></div>
</div>