<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package %DOMAIN_NAME%
 */

get_header();
?>

<div class="container">
  <div class="not-found">
    <h3 class="not-found__text">404</h3>
    <div class="not-found__desc">
      <p><?php _e('Sembra che ti sia perso.', '%DOMAIN_NAME%'); ?></p>
      <a href="<?= esc_url(home_url('/')); ?>" class="btn btn-primary"><?php _e('Torna alla home', '%DOMAIN_NAME%'); ?></a>
    </div>
  </div>
</div>

<?php
get_footer();
