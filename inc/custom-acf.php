<?php
if ( !defined('ABSPATH') ) {
  exit;
}

/**
 * ACF Theme Integrations.
 *
 * @link https://www.advancedcustomfields.com/
 *
 * @package %DOMAIN_NAME%
 */

function %DOMAIN_NAME%_acf_fields_admin_head() {
  ?>
  <style type="text/css">
    .acf-date-picker input.hasDatepicker {cursor: pointer;}
    #acf-term-fields>.acf-field>.acf-label label {font-size: 13px !important}
    .form-table>tbody>.acf-field>.acf-label label {font-weight: 600 !important}
    .acf-accordion .acf-accordion-title label {font-size: 14px !important; font-weight: 600}
    .acf-field.editor-max-height > .acf-input .mce-edit-area > iframe,
    .acf-field.editor-max-height > .acf-input .wp-editor-container textarea {height: 220px !important}
    .acf-field-gallery.gallery-max-height > .acf-input > .acf-gallery {height: 266px !important}
    .acf-field.field-message pre {display: inline; padding: 1px 2px; background-color: #f4f4f4}
    .acf-field.columns-layout .acf-flexible-content .values {display: flex; flex-wrap: wrap}
    .acf-field.columns-layout .acf-flexible-content .values > .layout {flex: 0 0 33.333%; max-width: calc(33.333% - 2px); margin: 0 !important}
    .acf-bgimage .acf-image-uploader.has-value .image-wrap {max-width: inherit !important; width: 100% !important;}
    .acf-bgimage .acf-image-uploader.has-value .image-wrap img {max-height: 380px !important; width: 100% !important; object-fit: cover}
    /* Custom ACF styles here */
  </style>
  <?php
}
add_action('acf/input/admin_head', '%DOMAIN_NAME%_acf_fields_admin_head');

function %DOMAIN_NAME%_acf_google_map_key() {
  $google_api_key = get_theme_mod('gmap_api_key', '');
  acf_update_setting('google_api_key', $google_api_key);
}
add_filter('acf/init', '%DOMAIN_NAME%_acf_google_map_key');
