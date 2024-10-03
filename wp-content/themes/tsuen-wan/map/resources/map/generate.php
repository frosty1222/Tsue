<?php
$base_level = 15;
$start_level = 15;
$end_level = 17;
$bound_x = 1529;
$bound_y = 824.2587;

ini_set('memory_limit', '2048M');
set_time_limit(3600);

function rrmdir($src) {
    if(!file_exists($src)){
        return;
    }
    $dir = opendir($src);
    while (false !== ( $file = readdir($dir))) {
        if (( $file != '.' ) && ( $file != '..' )) {
            $full = $src . '/' . $file;
            if (is_dir($full)) {
                rrmdir($full);
            } else {
                unlink($full);
            }
        }
    }
    closedir($dir);
    rmdir($src);
}

rrmdir(dirname(__FILE__) . '/tiles');

for ($z = $start_level; $z <= $end_level; $z++) {

    $center_grid_index = pow(2, $z) / 2;

    $output_size = 256;

    $center_bound_x = $bound_x * pow(2, $z - $base_level) / 2;
    $center_bound_y = $bound_y * pow(2, $z - $base_level) / 2;

    $half_width_indexes = ceil($center_bound_x / $output_size);
    $half_height_indexes = ceil($center_bound_y / $output_size);

    for ($x = $center_grid_index - $half_width_indexes; $x < $center_grid_index + $half_width_indexes; $x++) {
        for ($y = $center_grid_index - $half_height_indexes; $y < $center_grid_index + $half_height_indexes; $y++) {
            echo 'z: ' . $z . ', x: ' . $x . ', y: ' . $y . '<br>';

            $gd_full = imagecreatefromjpeg(dirname(__FILE__) . '/full_' . $z . '.jpg');
            $full_width = imagesx($gd_full);
            $full_height = imagesy($gd_full);
            $center_full_x = $full_width / 2;
            $center_full_y = $full_height / 2;

            $gd = imagecreatetruecolor($output_size, $output_size);
            imagefill($gd, 0, 0, imagecolorallocate($gd, 255, 255, 255));

            $dst_x = 0;
            $dst_y = 0;
            $copy_x = $center_full_x + ($output_size * ($x - $center_grid_index));
            $copy_y = $center_full_y + ($output_size * ($y - $center_grid_index));
            $copy_width = $output_size;
            $copy_height = $output_size;
            if ($copy_x + $copy_width > 0 && $copy_y + $copy_height > 0 && $copy_x < $full_width && $copy_y < $full_width) {
                if ($copy_x < 0) {
                    $copy_width += $copy_x;
                    $dst_x = $copy_x * -1;
                    $copy_x = 0;
                }
                if ($copy_y < 0) {
                    $copy_height += $copy_y;
                    $dst_y = $copy_y * -1;
                    $copy_y = 0;
                }
                if ($copy_x + $copy_width > $full_width) {
                    $copy_width = $full_width - $copy_x;
                }
                if ($copy_y + $copy_height > $full_height) {
                    $copy_height = $full_height - $copy_y;
                }
                imagecopy($gd, $gd_full, $dst_x, $dst_y, $copy_x, $copy_y, $copy_width, $copy_height);
            }
            if (!file_exists(dirname(__FILE__) . '/tiles/' . $z . '/' . $x)) {
                mkdir(dirname(__FILE__) . '/tiles/' . $z . '/' . $x, 0777, true);
            }
            imagejpeg($gd, dirname(__FILE__) . '/tiles/' . $z . '/' . $x . '/' . $y . '.jpg', 100);
        }
    }
}
?>
done.