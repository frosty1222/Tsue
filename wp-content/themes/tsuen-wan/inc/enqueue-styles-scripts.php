<?php
function addThemeScripts()
{
  wp_enqueue_style('general', get_stylesheet_directory_uri() . '/style.css');

  $css_files = glob(get_template_directory() . '/assets/css/*.css');
  foreach ($css_files as $file) {
    $file_url = get_template_directory_uri() . '/assets/css/' . basename($file);
    wp_enqueue_style(basename($file, '.css'), $file_url, [], file_exists($file) ? filemtime($file) : time());
  }

  $js_files = glob(get_template_directory() . '/assets/js/*.js');


  foreach ($js_files as $file) {
    $file_url = get_template_directory_uri() . '/assets/js/' . basename($file);
    $version = file_exists($file) ? filemtime($file) : time();
    wp_enqueue_script(basename($file, '.js'), $file_url, ['jquery'], $version, true);
  }
  $translation_array = array(
    'previousText' => __('PREV', 'tsuen'),
    'nextText' => __('NEXT', 'tsuen')
  );
  wp_localize_script('nearby', 'scriptData', $translation_array);
  wp_localize_script(
    'shoppings',
    'wpData',
    array(
      'baseUrl' => esc_url(home_url()),
      'adminUrl' => admin_url() . 'admin-ajax.php',
    )
  );
}

add_action('wp_enqueue_scripts', 'addThemeScripts');


