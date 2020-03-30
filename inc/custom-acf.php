<?php
/**
 * ACF Integrations File
 *
 * @link https://www.advancedcustomfields.com/
 *
 * @package %DOMAIN_NAME%
 */

function %DOMAIN_NAME%_acf_fields_admin_head() {
  ?>
  <style type="text/css">
    #acf-term-fields>.acf-field>.acf-label label {font-size: 13px !important}
    .form-table>tbody>.acf-field>.acf-label label {font-weight: 600 !important}
    .acf-accordion .acf-accordion-title label {font-size: 14px; font-weight: 600}
    /* Custom ACF styles here */
  </style>
  <?php
}
add_action('acf/input/admin_head', '%DOMAIN_NAME%_acf_fields_admin_head');
