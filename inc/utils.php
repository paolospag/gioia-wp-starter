<?php
if ( !defined('ABSPATH') ) {
  exit;
}

/**
 * %THEME_NAME% Theme utils.
 *
 * @var GWP_DARK_BRIGHTNESS
 * @var GWP_LIGHT_BRIGHTNESS
 * @package %DOMAIN_NAME%
 */
define('GWP_DARK_BRIGHTNESS', -0.133);
define('GWP_LIGHT_BRIGHTNESS', 0.296);

/**
 * Create inline style for theme admin/bar styles.
 *
 * @return string $admin_styles [HTML output].
 */
function gwp_create_admin_styles() {
  $admin_styles = 'html {
    margin-bottom: 32px !important;
  }
  body {
    margin-top: -32px !important;
  }
  @media (max-width: 782px) {
    html {
      margin-bottom: 46px !important;
    }
    body {
      margin-top: -46px !important;
    }
    #wpadminbar {
      position: fixed !important;
    }
  }
  #wpadminbar {
    top: auto !important;
    bottom: 0 !important;
  }
  #wpadminbar .menupop .ab-sub-wrapper,
  #wpadminbar .shortlink-input {
    bottom: 32px !important;
  }';
  return $admin_styles;
}

/**
 * Create inline style for theme admin/login styles.
 *
 * @return string $login_styles [HTML output].
 */
function gwp_create_login_styles($logo_id) {
  $color_scheme = gwp_color_scheme();
  $bg_color = $color_scheme['light']['color'];
  $btn_color = $color_scheme['text_alt']['color'];
  $btn_light_color = gwp_color_brightness($btn_color, GWP_LIGHT_BRIGHTNESS);
  $login_styles = "body {
    background: $bg_color;
  }
  .wp-core-ui .button {
    background: $btn_color;
    border-color: $btn_color;
  }
  .wp-core-ui .button-primary.focus,
  .wp-core-ui .button-primary.hover,
  .wp-core-ui .button-primary:focus,
  .wp-core-ui .button-primary:hover {
    background: $btn_light_color;
    border-color: $btn_light_color;
  }
  .wp-core-ui .button-primary.focus,
  .wp-core-ui .button-primary:focus {
    box-shadow: 0 0 0 1px #fff, 0 0 0 3px $btn_light_color;
  }
  .wp-core-ui .button-secondary {
    color: $btn_color;
    border-color: transparent !important;
    box-shadow: none !important;
  }
  .wp-core-ui .button-secondary:hover,
  .wp-core-ui .button-secondary:focus {
    color: $btn_light_color;
  }
  input[type=checkbox]:focus, input[type=color]:focus, input[type=date]:focus, input[type=datetime-local]:focus, input[type=datetime]:focus, input[type=email]:focus, input[type=month]:focus, input[type=number]:focus, input[type=password]:focus, input[type=radio]:focus, input[type=search]:focus, input[type=tel]:focus, input[type=text]:focus, input[type=time]:focus, input[type=url]:focus, input[type=week]:focus, select:focus, textarea:focus {
    border-color: $btn_light_color;
    box-shadow: 0 0 0 1px $btn_light_color;
  }
  input[type=checkbox]:checked::before {
    content: url('data:image/svg+xml;base64,". base64_encode('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M14.83 4.89l1.34.94-5.81 8.38H9.02L5.78 9.67l1.34-1.25 2.57 2.4z" fill="'.$btn_color.'" /></svg>') ."');
  }";
  if ($logo_id) {
    $logo_url = wp_get_attachment_url($logo_id);
    $logo_path = wp_get_original_image_path($logo_id);
    list($width, $height) = getimagesize($logo_path);
    $login_styles .= "#login h1 a, .login h1 a {
      background-image: url('$logo_url');
      width: ${width}px;
      height: ${height}px;
      background-size: ${width}px ${height}px;
    }";
  }
  return $login_styles;
}

/**
 * Create inline style for theme root.
 *
 * @return string $root_styles [HTML output].
 */
function gwp_create_root_styles() {
  $color_scheme = gwp_color_scheme();
  $root_styles = ':root {';
  foreach($color_scheme as $name => $value) {
    $option_name = $name.'_color';
    $slug_color = str_replace('_', '-', $name);
    $hex_color = get_theme_mod($option_name, $value['color']);
    $root_styles .= '--'.$slug_color.'-color: '.$hex_color.';';
    if ( in_array($name, ['primary', 'secondary', 'alt']) ) {
      $root_styles .= '--'.$slug_color.'-dark-color: '.gwp_color_brightness($hex_color, GWP_DARK_BRIGHTNESS).';';
      $root_styles .= '--'.$slug_color.'-light-color: '.gwp_color_brightness($hex_color, GWP_LIGHT_BRIGHTNESS).';';
    }
  }
  $root_styles .= '}';
  return $root_styles;
}

/**
 * Create inline style for theme font-sizes & colors.
 *
 * @return string $base_styles [HTML output].
 */
function gwp_create_base_styles() {
  $color_scheme = gwp_color_scheme();
  $font_sizes = gwp_create_block_font_sizes();
  $base_styles = '';
  $not_text_colors = array();
  foreach($font_sizes as $font_size) {
    $base_styles .= ".has-$font_size[slug]-font-size {font-size: $font_size[size]px}\n";
  }
  foreach($color_scheme as $name => $value) {
    $slug = str_replace('_', '-', $name);
    if ($name !== 'text') {
      $not_text_colors[] = ":not(.has-$slug-color)";
      $base_styles .= "\n.has-$slug-color {color: var(--$slug-color) !important}";
    }
    $base_styles .= "\n.has-$slug-background-color {background-color: var(--$slug-color) !important}";
    if ( in_array($name, ['primary', 'secondary', 'alt']) ) {
      $base_styles .= "\n.has-$slug-light-color {color: var(--$slug-light-color) !important}";
      $base_styles .= "\n.has-$slug-light-background-color {background-color: var(--$slug-light-color) !important}";
    }
  }
  $not_text_colors = implode('', $not_text_colors);
  $base_styles .= "\n.has-text-color$not_text_colors {color: var(--text-color)}";
  return $base_styles;
}

function gwp_color_scheme() {
  $color_scheme = array(
    'primary' => [
      'color' => '#e64e28',
      'label' => 'Primario'
    ],
    'secondary' => [
      'color' => '#2f2c60',
      'label' => 'Secondario'
    ],
    'alt' => [
      'color' => '#ecf3f9',
      'label' => 'Alternativo'
    ],
    'light' => [
      'color' => '#f2f2f2',
      'label' => 'Chiaro'
    ],
    'border' => [
      'color' => '#dfe5e8',
      'label' => 'Bordo'
    ],
    'text' => [
      'color' => '#212121',
      'label' => 'Testo'
    ],
    'text_alt' => [
      'color' => '#363636',
      'label' => 'Testo alternativo'
    ],
    'text_light' => [
      'color' => '#5f717f',
      'label' => 'Testo chiaro'
    ],
    'white' => [
      'color' => '#ffffff',
      'label' => 'Bianco'
    ],
  );
  return $color_scheme;
}

function gwp_color_brightness($hex, $percent) {
  $hex = ltrim($hex, '#');
  if (strlen($hex) == 3) {
    $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
  }
  $hex = array_map('hexdec', str_split($hex, 2));
  foreach ($hex as & $color) {
    $adjustable_limit = $percent < 0 ? $color : 255 - $color;
    $adjust_amount = ceil($adjustable_limit * $percent);

    $color = str_pad(dechex($color + $adjust_amount), 2, '0', STR_PAD_LEFT);
  }
  return '#'.implode($hex);
}

function gwp_create_block_color_palettes() {
  $color_palettes = array();
  $color_scheme = gwp_color_scheme();
  foreach($color_scheme as $name => $value) {
    $option_name = $name.'_color';
    $slug_color = str_replace('_', '-', $name);
    $hex_color = get_theme_mod($option_name, $value['color']);
    if ( in_array($name, ['primary', 'secondary', 'alt']) ) {
      $light_hex_color = gwp_color_brightness($hex_color, GWP_LIGHT_BRIGHTNESS);
      $color_palettes[] = array(
        'color' => $light_hex_color,
        'slug'  => $slug_color.'-light',
        'name'  => __("$value[label] chiaro", '%DOMAIN_NAME%')
      );
    }
    $color_palettes[] = array(
      'color' => $hex_color,
      'slug'  => $slug_color,
      'name'  => __($value['label'], '%DOMAIN_NAME%')
    );
  }
  return $color_palettes;
}

function gwp_create_block_font_sizes() {
  return array(
    [
      'size' => 13,
      'slug' => 'small',
      'name' => __('Piccolo', '%DOMAIN_NAME%'),
    ],
    [
      'size' => 18,
      'slug' => 'medium',
      'name' => __('Medio', '%DOMAIN_NAME%'),
    ],
    [
      'size' => 24,
      'slug' => 'large',
      'name' => __('Grande', '%DOMAIN_NAME%'),
    ],
    [
      'size' => 34,
      'slug' => 'extra',
      'name' => __('Extra', '%DOMAIN_NAME%'),
    ],
  );
}

function gwp_social_networks($options = false) {
  $social_networks = array(
    'facebook'  => 'Facebook',
    'instagram' => 'Instagram',
    'linkedin'  => 'LinkedIn',
    'twitter'   => 'Twitter',
    'youtube'   => 'YouTube',
  );
  if ($options) {
    foreach($social_networks as $key => $name) {
      if ( $social_url = get_theme_mod($key, false) )
        $social_networks[$key] = array(
          'url'  => $social_url,
          'name' => $name
        );
      else
        unset($social_networks[$key]);
    }
  }
  return $social_networks;
}

function gwp_placeholder_thumbnail($type = 'post') {
  $placeholder_src = get_template_directory_uri() .'/assets/img/post-placeholder.jpg';
  echo '<img src="'. esc_url($placeholder_src) .'" width="330" height="360" alt="'. esc_attr__('Immagine segnaposto', '%DOMAIN_NAME%') .'" />';
}

function gwp_svg_icon($name) {
  $svg;
  switch($name) {
    case 'facebook':
      $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M17.988.16v3.535h-2.105c-.766 0-1.285.16-1.551.485-.27.32-.402.8-.402 1.445v2.531h3.922l-.52 3.965H13.93v10.164H9.832V12.121H6.414V8.156h3.418V5.238c0-1.664.461-2.949 1.39-3.867C12.153.457 13.388 0 14.935 0c1.312 0 2.328.055 3.054.16zm0 0"/></svg>';
      break;
    case 'instagram':
      $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M15.43 12c0-.945-.336-1.754-1.004-2.426A3.318 3.318 0 0012 8.57c-.945 0-1.754.336-2.426 1.004A3.318 3.318 0 008.57 12c0 .945.336 1.754 1.004 2.426A3.318 3.318 0 0012 15.43c.945 0 1.754-.336 2.426-1.004A3.318 3.318 0 0015.43 12zm1.847 0c0 1.465-.515 2.71-1.539 3.738-1.027 1.024-2.273 1.54-3.738 1.54-1.465 0-2.71-.516-3.738-1.54-1.024-1.027-1.54-2.273-1.54-3.738 0-1.465.516-2.71 1.54-3.738 1.027-1.024 2.273-1.54 3.738-1.54 1.465 0 2.71.516 3.738 1.54 1.024 1.027 1.54 2.273 1.54 3.738zm1.446-5.492c0 .34-.121.629-.36.87a1.186 1.186 0 01-.87.364c-.34 0-.63-.12-.872-.363a1.186 1.186 0 01-.363-.871c0-.34.12-.63.363-.871.242-.239.531-.36.871-.36.34 0 .63.121.871.36.239.242.36.531.36.87zM12 3.562l-1.023-.007a83.13 83.13 0 00-1.415 0c-.32.004-.753.02-1.292.043-.54.02-1 .066-1.38.132-.378.067-.699.149-.956.247a3.505 3.505 0 00-1.957 1.957c-.098.257-.18.578-.247.957-.066.379-.113.84-.132 1.379-.024.539-.04.972-.043 1.293-.004.32-.004.792 0 1.414L3.562 12l-.007 1.023a83.13 83.13 0 000 1.415c.004.32.02.753.043 1.292.02.54.066 1 .132 1.38.067.378.149.699.247.956a3.505 3.505 0 001.957 1.957c.257.098.578.18.957.247.379.066.84.113 1.379.132.539.024.972.04 1.293.043.32.004.792.004 1.414 0L12 20.437l1.023.008a83.13 83.13 0 001.415 0c.32-.004.753-.02 1.292-.043.54-.02 1-.066 1.38-.132.378-.067.699-.149.956-.247a3.505 3.505 0 001.957-1.957c.098-.257.18-.578.247-.957.066-.379.113-.84.132-1.379.024-.539.04-.972.043-1.293.004-.32.004-.792 0-1.414L20.437 12l.008-1.023a83.13 83.13 0 000-1.415 44.79 44.79 0 00-.043-1.292c-.02-.54-.066-1-.132-1.38a5.361 5.361 0 00-.247-.956 3.505 3.505 0 00-1.957-1.957 5.361 5.361 0 00-.957-.247c-.379-.066-.84-.113-1.379-.132a44.79 44.79 0 00-1.293-.043 83.13 83.13 0 00-1.414 0L12 3.562zM22.285 12c0 2.043-.023 3.46-.066 4.246-.09 1.856-.645 3.293-1.66 4.313-1.02 1.015-2.457 1.57-4.313 1.66-.785.043-2.203.066-4.246.066-2.043 0-3.46-.023-4.246-.066-1.856-.09-3.293-.645-4.313-1.66-1.015-1.02-1.57-2.457-1.66-4.313-.043-.785-.066-2.203-.066-4.246 0-2.043.023-3.46.066-4.246.09-1.856.645-3.293 1.66-4.313 1.02-1.015 2.457-1.57 4.313-1.66.785-.043 2.203-.066 4.246-.066 2.043 0 3.46.023 4.246.066 1.856.09 3.293.645 4.313 1.66 1.015 1.02 1.57 2.457 1.66 4.313.043.785.066 2.203.066 4.246zm0 0"/></svg>';
      break;
    case 'youtube':
      $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M9.523 15.105l6.48-3.347-6.48-3.387zM12 3.562c1.5 0 2.95.02 4.348.06 1.394.042 2.422.085 3.07.128l.98.055c.008 0 .082.004.227.02.145.01.246.027.309.038.062.016.168.035.312.063.149.023.277.062.383.105.11.043.234.102.375.176.144.07.281.156.414.258.137.105.266.223.39.355.055.055.122.137.208.25.086.11.214.371.386.782.176.41.293.863.356 1.355.074.57.129 1.18.168 1.828.039.645.066 1.152.074 1.52v2.355c.008 1.297-.07 2.59-.242 3.883a5.396 5.396 0 01-.332 1.336c-.164.394-.305.672-.43.82l-.187.23c-.125.134-.254.25-.391.356-.133.102-.27.188-.414.254-.14.066-.266.121-.375.168a1.98 1.98 0 01-.383.105l-.32.06a3.064 3.064 0 01-.309.042l-.219.02c-2.242.168-5.043.253-8.398.253a273.03 273.03 0 01-4.816-.085c-1.36-.043-2.254-.075-2.684-.102l-.656-.055-.48-.05a6.096 6.096 0 01-.731-.137 3.727 3.727 0 01-.684-.281 2.668 2.668 0 01-.758-.547 2.583 2.583 0 01-.207-.25c-.086-.11-.214-.371-.386-.782a5.041 5.041 0 01-.356-1.355c-.074-.57-.129-1.18-.168-1.828A42.81 42.81 0 010 13.445V11.09C-.008 9.793.07 8.5.242 7.207c.063-.492.172-.937.332-1.336.164-.394.305-.672.43-.82l.187-.23c.125-.133.254-.25.391-.356a2.43 2.43 0 01.414-.258c.14-.074.266-.133.375-.176.106-.043.234-.082.383-.105.144-.028.25-.047.312-.063.063-.011.164-.027.309-.039a3.58 3.58 0 01.227-.02c2.242-.16 5.043-.241 8.398-.241zm0 0"/></svg>';
      break;
    case 'linkedin':
      $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M6.387 8.371v13.274H1.969V8.37zm.281-4.098c.012.653-.215 1.196-.676 1.633-.457.438-1.062.657-1.812.657h-.028c-.734 0-1.32-.22-1.77-.657-.445-.437-.667-.98-.667-1.633 0-.66.23-1.207.687-1.64.461-.434 1.063-.653 1.805-.653.738 0 1.332.22 1.781.653.446.433.672.98.68 1.64zm15.617 9.762v7.61H17.88v-7.102c0-.938-.18-1.672-.543-2.203-.36-.531-.926-.797-1.691-.797-.563 0-1.036.156-1.415.465a2.847 2.847 0 00-.851 1.144c-.098.266-.149.63-.149 1.086v7.407H8.824c.02-3.563.028-6.454.028-8.668 0-2.215-.004-3.536-.012-3.965l-.016-.64h4.406V10.3h-.023c.176-.29.36-.54.547-.75.187-.215.441-.45.758-.7.316-.25.703-.44 1.164-.581.46-.137.972-.207 1.535-.207 1.527 0 2.754.507 3.684 1.519.925 1.016 1.39 2.5 1.39 4.453zm0 0"/></svg>';
      break;
    case 'twitter':
      $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M22.555 5.465a9.016 9.016 0 01-2.172 2.234c.012.125.015.313.015.563 0 1.164-.171 2.32-.511 3.476a12.353 12.353 0 01-1.547 3.328 13.111 13.111 0 01-2.469 2.82c-.957.817-2.11 1.47-3.457 1.954-1.348.488-2.789.73-4.324.73-2.422 0-4.633-.644-6.645-1.941.313.035.66.055 1.047.055 2.008 0 3.797-.618 5.371-1.848a4.258 4.258 0 01-2.52-.863 4.245 4.245 0 01-1.527-2.137c.297.043.567.066.817.066.387 0 .765-.05 1.14-.148a4.249 4.249 0 01-2.484-1.492c-.656-.79-.984-1.707-.984-2.754v-.051c.605.336 1.257.52 1.953.547a4.298 4.298 0 01-1.406-1.54 4.212 4.212 0 01-.52-2.062c0-.785.195-1.515.586-2.183a12.312 12.312 0 003.945 3.195 12.1 12.1 0 004.977 1.332c-.07-.34-.11-.672-.11-.992 0-1.195.422-2.215 1.266-3.059.844-.843 1.867-1.265 3.063-1.265 1.25 0 2.304.453 3.16 1.363a8.532 8.532 0 002.746-1.043c-.332 1.027-.965 1.82-1.902 2.383a8.694 8.694 0 002.492-.668zm0 0"/></svg>';
      break;
    case 'whatsapp':
      $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M14.906 13.043c.117 0 .551.2 1.305.59.754.394 1.156.629 1.2.71.019.044.026.114.026.2 0 .297-.074.637-.226 1.02-.145.347-.461.64-.953.878-.488.235-.945.352-1.363.352-.512 0-1.36-.273-2.547-.828a7.672 7.672 0 01-2.278-1.582c-.64-.653-1.3-1.477-1.98-2.477-.645-.957-.961-1.82-.953-2.597v-.11c.027-.812.36-1.515.992-2.113.215-.2.445-.297.695-.297.055 0 .137.008.242.02.11.015.192.023.254.023.172 0 .29.027.356.086s.136.18.207.367c.074.18.219.57.441 1.18.227.605.336.941.336 1.004 0 .187-.152.445-.46.77-.31.327-.462.534-.462.624 0 .063.02.13.067.2.3.652.758 1.261 1.363 1.835.5.473 1.176.922 2.023 1.352.11.063.207.094.297.094.133 0 .375-.215.723-.649.348-.433.578-.652.695-.652zm-2.719 7.102a8.209 8.209 0 003.262-.672 8.464 8.464 0 002.684-1.793 8.513 8.513 0 001.797-2.688 8.191 8.191 0 00.668-3.262 8.186 8.186 0 00-.668-3.257 8.513 8.513 0 00-1.797-2.688 8.464 8.464 0 00-2.684-1.793 8.142 8.142 0 00-3.261-.672 8.142 8.142 0 00-3.262.672 8.464 8.464 0 00-2.684 1.793 8.513 8.513 0 00-1.797 2.688 8.186 8.186 0 00-.668 3.257 8.19 8.19 0 001.606 4.93l-1.059 3.121 3.242-1.031a8.233 8.233 0 004.622 1.395zm0-18.512c1.368 0 2.672.27 3.918.804 1.247.536 2.32 1.254 3.22 2.157.902.902 1.62 1.976 2.155 3.222.54 1.243.805 2.551.805 3.914a9.757 9.757 0 01-.805 3.918c-.535 1.247-1.253 2.32-2.156 3.223-.898.902-1.972 1.621-3.219 2.156a9.824 9.824 0 01-3.918.805 9.925 9.925 0 01-4.886-1.262l-5.586 1.797 1.82-5.426c-.965-1.59-1.445-3.324-1.445-5.21 0-1.364.265-2.672.805-3.915.535-1.246 1.253-2.32 2.156-3.222.898-.903 1.972-1.621 3.219-2.157a9.824 9.824 0 013.918-.804zm0 0"/></svg>';
      break;
    case 'skype':
      $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M17.426 14.238a2.944 2.944 0 00-.914-2.145 4.016 4.016 0 00-.977-.655 7.749 7.749 0 00-1.105-.458 15.647 15.647 0 00-1.172-.304l-1.39-.324c-.27-.063-.466-.11-.59-.141a4.491 4.491 0 01-.47-.152 1.373 1.373 0 01-.402-.215 1.123 1.123 0 01-.222-.281.797.797 0 01-.098-.403c0-.687.64-1.031 1.926-1.031.386 0 .73.055 1.031.16.305.11.547.234.727.383.175.148.347.297.507.45.16.151.34.28.536.386.195.11.41.16.644.16.418 0 .754-.14 1.008-.426a1.48 1.48 0 00.383-1.031c0-.492-.25-.938-.75-1.332-.5-.399-1.133-.7-1.903-.906a9.47 9.47 0 00-2.437-.309c-.606 0-1.195.07-1.766.207a6.227 6.227 0 00-1.601.633 3.212 3.212 0 00-1.192 1.164c-.3.496-.449 1.07-.449 1.719 0 .547.086 1.023.254 1.43.172.406.422.742.75 1.007.332.27.687.485 1.07.653.387.164.844.308 1.383.433l1.953.485c.805.195 1.305.355 1.5.48.285.18.43.445.43.805 0 .347-.18.636-.535.863-.36.227-.828.34-1.407.34-.457 0-.863-.07-1.226-.215a2.692 2.692 0 01-.871-.516 9.883 9.883 0 01-.61-.601 3.23 3.23 0 00-.617-.516 1.326 1.326 0 00-.722-.215c-.446 0-.782.137-1.012.403-.227.27-.34.601-.34 1.004 0 .824.543 1.527 1.633 2.109 1.09.586 2.39.879 3.898.879.653 0 1.278-.082 1.875-.25a6.075 6.075 0 001.64-.715c.497-.313.892-.73 1.184-1.254a3.49 3.49 0 00.446-1.758zm4.86 2.907c0 1.418-.5 2.628-1.509 3.632-1.004 1.008-2.215 1.508-3.632 1.508a5.009 5.009 0 01-3.137-1.07A10.01 10.01 0 0112 21.43a9.27 9.27 0 01-3.664-.746 9.374 9.374 0 01-3.012-2.008 9.374 9.374 0 01-2.008-3.012A9.27 9.27 0 012.57 12c0-.652.075-1.32.215-2.008a5.009 5.009 0 01-1.07-3.137c0-1.418.5-2.628 1.508-3.632 1.004-1.008 2.215-1.508 3.632-1.508 1.165 0 2.207.355 3.137 1.07A10.01 10.01 0 0112 2.57a9.27 9.27 0 013.664.746 9.374 9.374 0 013.012 2.008 9.374 9.374 0 012.008 3.012A9.27 9.27 0 0121.43 12c0 .652-.075 1.32-.215 2.008a5.009 5.009 0 011.07 3.137zm0 0"/></svg>';
      break;
    case 'refresh':
      $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M19 8l-4 4h3c0 3.31-2.69 6-6 6-1.01 0-1.97-.25-2.8-.7l-1.46 1.46C8.97 19.54 10.43 20 12 20c4.42 0 8-3.58 8-8h3l-4-4zM6 12c0-3.31 2.69-6 6-6 1.01 0 1.97.25 2.8.7l1.46-1.46C15.03 4.46 13.57 4 12 4c-4.42 0-8 3.58-8 8H1l4 4 4-4H6z"/><path d="M0 0h24v24H0z" fill="none"/></svg>';
      break;
    case 'search':
      $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>';
      break;
    case 'calendar':
      $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M0 0h24v24H0z" fill="none"/><path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM7 10h5v5H7z"/></svg>';
      break;
    case 'phone':
      $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M0 0h24v24H0z" fill="none"/><path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg>';
      break;
    case 'phone-call':
      $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M0 0h24v24H0z" fill="none"/><path d="M20 15.5c-1.25 0-2.45-.2-3.57-.57-.35-.11-.74-.03-1.02.24l-2.2 2.2c-2.83-1.44-5.15-3.75-6.59-6.59l2.2-2.21c.28-.26.36-.65.25-1C8.7 6.45 8.5 5.25 8.5 4c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1 0 9.39 7.61 17 17 17 .55 0 1-.45 1-1v-3.5c0-.55-.45-1-1-1zM19 12h2c0-4.97-4.03-9-9-9v2c3.87 0 7 3.13 7 7zm-4 0h2c0-2.76-2.24-5-5-5v2c1.66 0 3 1.34 3 3z"/></svg>';
      break;
    case 'email':
      $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/><path d="M0 0h24v24H0z" fill="none"/></svg>';
      break;
    case 'address':
      $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M0 0h24v24H0z" fill="none"/><path d="M8.17 5.7L1 10.48V21h5v-8h4v8h5V10.25z"/><path d="M17 7h2v2h-2z" fill="none"/><path d="M10 3v1.51l2 1.33L13.73 7H15v.85l2 1.34V11h2v2h-2v2h2v2h-2v4h6V3H10zm9 6h-2V7h2v2z"/></svg>';
      break;
    case 'place':
      $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/><path d="M0 0h24v24H0z" fill="none"/></svg>';
      break;
    case 'globe':
      $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M0 0h24v24H0z" fill="none"/><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/></svg>';
      break;
    case 'secure':
      $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M0 0h24v24H0z" fill="none"/><path d="M19 1H5c-1.1 0-1.99.9-1.99 2L3 15.93c0 .69.35 1.3.88 1.66L12 23l8.11-5.41c.53-.36.88-.97.88-1.66L21 3c0-1.1-.9-2-2-2zm-9 15l-5-5 1.41-1.41L10 13.17l7.59-7.59L19 7l-9 9z"/></svg>';
      break;
    case 'account':
      $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M0 0h24v24H0z" fill="none"/><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/></svg>';
      break;
    case 'download':
      $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M20 12l-1.41-1.41L13 16.17V4h-2v12.17l-5.58-5.59L4 12l8 8 8-8z"/></svg>';
      break;
    case 'dismiss':
      $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/><path d="M0 0h24v24H0z" fill="none"/></svg>';
      break;
    case 'clock':
      $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8z"/><path d="M0 0h24v24H0z" fill="none"/><path d="M12.5 7H11v6l5.25 3.15.75-1.23-4.5-2.67z"/></svg>';
      break;
    case 'tag':
      $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M0 0h24v24H0z" fill="none"/><path d="M17.63 5.84C17.27 5.33 16.67 5 16 5L5 5.01C3.9 5.01 3 5.9 3 7v10c0 1.1.9 1.99 2 1.99L16 19c.67 0 1.27-.33 1.63-.84L22 12l-4.37-6.16z"/></svg>';
      break;
    case 'lock':
      $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><g fill="none"><path d="M0 0h24v24H0V0z"/><path d="M0 0h24v24H0V0z" opacity=".87"/></g><path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zM9 6c0-1.66 1.34-3 3-3s3 1.34 3 3v2H9V6zm9 14H6V10h12v10zm-6-3c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2z"/></svg>';
      break;
    case 'lock-o':
      $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M0 0h24v24H0z" fill="none"/><path d="M12 17c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm6-9h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6h1.9c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm0 12H6V10h12v10z"/></svg>';
      break;
    case 'cart':
      $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12.9-1.63h7.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.08-.14.12-.31.12-.48 0-.55-.45-1-1-1H5.21l-.94-2H1zm16 16c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"/><path d="M0 0h24v24H0z" fill="none"/></svg>';
      break;
    case 'plus':
      $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/><path d="M0 0h24v24H0z" fill="none"/></svg>';
      break;
    case 'up':
      $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M0 0h24v24H0z" fill="none"/><path d="M12 8l-6 6 1.41 1.41L12 10.83l4.59 4.58L18 14z"/></svg>';
      break;
    case 'left':
      $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/><path d="M0 0h24v24H0z" fill="none"/></svg>';
      break;
    case 'right':
      $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/><path d="M0 0h24v24H0z" fill="none"/></svg>';
      break;
    case 'down':
      $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M0 0h24v24H0z" fill="none"/><path d="M16.59 8.59L12 13.17 7.41 8.59 6 10l6 6 6-6z"/></svg>';
      break;
    default:
      $svg = '';
  }
  return str_replace('<svg ', '<svg class="icon icon-'.$name.'" aria-hidden="true" ', $svg);
}
