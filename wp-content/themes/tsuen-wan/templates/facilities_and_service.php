<?php

/**
 * Template Name: Facilities & Service
 */
?>
<?php get_header();
?>
    <?php get_template_part('template-parts/banner', '', "" ) ?>
    <div class="service">
        <div class="wrap-page">
            <div class="container">
                <div class="rightfire">
                    <?php  ani_firework() ?>
                </div>
                <div class="leftfire">
                    <?php  ani_firework() ?>
                </div>
                <?php if(get_field("description")){ ?>
                    <div class="description">
                        <?php the_field("description")?>
                    </div>
                <?php
                }?>
                <div class="list">

                    <?php 
                        if(get_field("list")){
                            $list = get_field("list"); 
                            $first_item = $list[0];

                            foreach($list as $list_item){
                                $icon = "";
                                $title = "";
                                $items = "";
                                if($list_item["icon"]){
                                    $icon = $list_item["icon"];
                                }
                                if($list_item["title"]){
                                    $title = $list_item["title"];
                                }
                                if($list_item["items"]){
                                    $items = $list_item["items"];
                                }
                                ?>
                                <div class="list-item <?php echo ($first_item == $list_item) ? "active" : "" ?>">
                                    <h2 class="title">
                                        <?php if($icon){ ?>
                                            <img src="<?php echo $icon["url"]?>" alt="<?php echo $icon["title"]?>">
                                        <?php
                                        }?>

                                        <?php 
                                            if($title){ ?>
                                                <span class="text">
                                                    <?php echo $title?>
                                                </span>
                                            <?php
                                            }
                                        ?>

                                        <div class="toggle">
                                            
                                        </div>
                                        
                                    </h2>

                                    
                                    <?php 
                                        if($items){?>
                                        <div class="content">
                                            <div class="content-container">
                                                <?php
                                                foreach($items as $item){ 
                                                    $item_name = "";
                                                    if($item["item_name"]){
                                                        $item_name = $item["item_name"]; 

                                                        ?>
                                                            <p><?php echo $item_name?></p>
                                                        <?php
                                                    }
                                                    
                                                }
                                                ?>
                                            </div>
                                            
                                        </div>
                                        <?php
                                        }
                                    ?>
                                    
                                </div>
                                <?php
                            }
                        ?>
                            
                        <?php
                        }
                    ?>
                </div>
                <?php 
                    if(get_field("while_stock_lasts")){ ?>
                        <p class="while_stock_lasts">
                            <?php the_field("while_stock_lasts")?>
                        </p>
                    <?php
                    }
                ?>
            </div>
        </div>
    </div>


<?php get_footer() ?>