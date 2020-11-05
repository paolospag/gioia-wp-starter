<?php
// Exit if accessed directly.
defined('ABSPATH') || exit;
?>

<button class="navbar__search" title="<?php esc_attr_e('Cerca sul sito', '%DOMAIN_NAME%'); ?>" type="button">
  <?php echo gwp_svg_icon('search'); ?>
</button>
