<?php
// Exit if accessed directly.
defined('ABSPATH') || exit;
$anchor_class = has_custom_logo() ? 'site-logo' : 'site-title';
?>

<div class="<?php echo $anchor_class; ?>">
  <?php if( has_custom_logo() ): ?>
    <?php the_custom_logo(); ?>
  <?php else: ?>
    <a href="<?= esc_url( home_url('/') ); ?>">
      <?php bloginfo('name'); ?>
    </a>
  <?php endif; ?>
</div>
