<?php
/**
 * Allow SVG uploads for administrator users.
 *
 * @param array $upload_mimes Allowed mime types.
 *
 * @return mixed
 */
add_filter(
	'upload_mimes',
	function ( $upload_mimes ) {
		if ( ! current_user_can( 'administrator' ) ) {
			return $upload_mimes;
		}

		$upload_mimes['svg']  = 'image/svg+xml';
		$upload_mimes['svgz'] = 'image/svg+xml';

		return $upload_mimes;
	}
);

add_filter(
	'wp_check_filetype_and_ext',
	function ( $wp_check_filetype_and_ext, $file, $filename, $mimes, $real_mime ) {

		if ( ! $wp_check_filetype_and_ext['type'] ) {

			$check_filetype  = wp_check_filetype( $filename, $mimes );
			$ext             = $check_filetype['ext'];
			$type            = $check_filetype['type'];
			$proper_filename = $filename;

			if ( $type && 0 === strpos( $type, 'image/' ) && 'svg' !== $ext ) {
				$ext  = false;
				$type = false;
			}

			$wp_check_filetype_and_ext = compact( 'ext', 'type', 'proper_filename' );
		}

		return $wp_check_filetype_and_ext;

	},
	10,
	5
);
add_theme_support( 'post-thumbnails' );

function tsuen_wp_corenavi($custom_query = null, $paged = null) {
    global $wp_query;
    if($custom_query) $main_query = $custom_query;
    else $main_query = $wp_query;
    $paged = ($paged) ? $paged : get_query_var('paged');
    $big = 999999999;
    $total = isset($main_query->max_num_pages)?$main_query->max_num_pages:'';
    if($total > 1) echo '<div class="pagenavi">';
    echo paginate_links( array(
        'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
        'format' => '?paged=%#%',
        'current' => max( 1, $paged ),
        'total' => $total,
        'mid_size' => '10',
        'prev_text'    => __('Prev','tsuen'),
        'next_text'    => __('Next','tsuen'),
    ) );
    if($total > 1) echo '</div>';
}
function ani_firework($type = null){
	if($type == 2 &&  get_field('firework_2','options') ){
		$image_url = get_field('firework_2','options');
		$ext = pathinfo($image_url, PATHINFO_EXTENSION);
		if($ext=='svg'){							
			$file = file_get_contents($image_url, true);
			echo '<div class="ani-firework no">';
			echo  $file ;
			echo '</div>';
		}
	}elseif( get_field('firework','options') ){
		$image_url = get_field('firework','options');
		$ext = pathinfo($image_url, PATHINFO_EXTENSION);
		if($ext=='svg'){							
			$file = file_get_contents($image_url, true);
			echo '<div class="ani-firework no">';
			echo  $file ;
			echo '</div>';
		}
	}
}