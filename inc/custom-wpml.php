<?php
if ( !defined('ABSPATH') ) {
  exit;
}

/**
 * Returns the translated object ID (post_type or term) or original if missing
 *
 * @param $object_id integer|string|array The ID/s of the objects to check and return
 * @param $type the object type: post, page, {custom post type name}, nav_menu, nav_menu_item, category, tag etc.
 * @return string or array of object ids
 */
function wpml_sync($object_id, $type) {
  $current_language = apply_filters( 'wpml_current_language', NULL );
  if( is_array( $object_id ) ) {
    $translated_object_ids = array();
    foreach ( $object_id as $id ) {
      $translated_object_ids[] = apply_filters( 'wpml_object_id', $id, $type, true, $current_language );
    }
    return $translated_object_ids;
  } elseif( is_string( $object_id ) ) {
    $is_comma_separated = strpos( $object_id,"," );
    if( $is_comma_separated !== FALSE ) {
      $object_id     = explode( ',', $object_id );
      $translated_object_ids = array();
      foreach ( $object_id as $id ) {
        $translated_object_ids[] = apply_filters ( 'wpml_object_id', $id, $type, true, $current_language );
      }
      return implode ( ',', $translated_object_ids );
    } else {
      return apply_filters( 'wpml_object_id', intval( $object_id ), $type, true, $current_language );
    }
  } else {
    return apply_filters( 'wpml_object_id', $object_id, $type, true, $current_language );
  }
}

// Render custom WPML language switcher
function wpml_language_dropdown() {
  $languages = icl_get_languages('skip_missing=0');
  if(!empty($languages)) {
    foreach($languages as $l) {
      if ($l['active']) {
        $label = substr($l['native_name'], 0, 3);
        echo '<span role="button">'.strtoupper($label).'</span>';
      }
    }
    echo '<ul class="lang-menu__dropdown">';
    foreach($languages as $l) {
      if (!$l['active']) {
        $label = substr($l['native_name'], 0, 3);
        echo '<li><a href="'.$l['url'].'">'.strtoupper($label).'</a></li>';
      }
    }
    echo '</ul>';
  }
}
