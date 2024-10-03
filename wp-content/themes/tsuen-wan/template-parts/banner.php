<header class="banner-section other-page-banner">
<?php 
   if(get_field("desktop_banner")){ 
    $image = get_field("desktop_banner");
    $mobile_image = get_field("desktop_banner");
    if(get_field("mobile_banner")){ 
        $mobile_image = get_field("mobile_banner");
    }
    ?>
        <img class="desktop banner" src="<?php echo $image["url"]?>" alt="<?php echo $image["title"]?>">
        <img class="mobile banner" src="<?php echo $mobile_image["url"]?>" alt="<?php echo $mobile_image["title"]?>">
    <?php
   }
?>
<div class="container">
    <div class="banner-section_title">
        <h1 class="title-header">
            <?php 
            if(get_field("banner_title")){ 
                $title = get_field("banner_title");
                $mobile_title = get_field("banner_title");
                if(get_field("mobile_banner_title")){ 
                    $mobile_title = get_field("mobile_banner_title");
                }
                ?>
                    <span class="text-desktop"><?php echo $title ?></span>
                    <span class="text-mobile"><?php echo $mobile_title?></span>
                <?php
               }
             ?>
        </h1>
    </div>
</div>
</header>