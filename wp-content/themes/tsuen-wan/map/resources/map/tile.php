<?php

$z = $_GET['z'];
$x = $_GET['x'];
$y = $_GET['y'];

if (!is_numeric($z) || !is_numeric($x) || !is_numeric($y)) {
    return;
}

$tile_filename = dirname(__FILE__) . '/tiles/' . $z . '/' . $x . '/' . $y . '.jpg';

if (file_exists($tile_filename)) {
    header('Content-Type: image/jpeg');
    readfile($tile_filename);
    return;
}

$center_grid_index = pow(2, $z) / 2;

$output_size = 256;

$gd_full = imagecreatefromjpeg(dirname(__FILE__) . '/full_' . $z . '.jpg');
if ($gd_full === false) {
    $gd = imagecreatetruecolor($output_size, $output_size);
    imagefill($gd, 0, 0, imagecolorallocate($gd, 255, 255, 255));
    imagestring($gd, 5, 0, 0, 'image load fail', imagecolorallocate($gd, 0, 0, 0));
    imagestring($gd, 5, 0, 16, 'full_' . $z . '.jpg', imagecolorallocate($gd, 0, 0, 0));
    ob_clean();
    header('Content-Type: image/jpeg');
    imagejpeg($gd, null, 100);
    return;
}
$full_width = imagesx($gd_full);
$full_height = imagesy($gd_full);
$center_full_x = $full_width / 2;
$center_full_y = $full_height / 2;

$gd = imagecreatetruecolor($output_size, $output_size);
imagefill($gd, 0, 0, imagecolorallocate($gd, 248, 248, 248));

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

if (!file_exists(dirname($tile_filename))) {
    mkdir(dirname($tile_filename), 0777, true);
}
imagejpeg($gd, $tile_filename, 100);

ob_clean();
header('Content-Type: image/jpeg');
imagejpeg($gd, null, 100);
