<?php
/**
 * The template for displaying search form.
 *
 * @package %DOMAIN_NAME%
 */
$has_dismiss = isset($args['has_dismiss']) && $args['has_dismiss'];
?>
<form class="search-form" method="get" action="<?= home_url(); ?>" autocomplete="off" role="search">
  <label for="search" class="sr-only"><?= _x('Cerca per parole chiave', 'label', '%DOMAIN_NAME%'); ?></label>
  <div class="search-form__wrap">
    <input class="search-form__control" id="search" type="search" name="s" placeholder="<?= esc_attr_x('Inserisci un termine e premi invio&hellip;', 'placeholder', '%DOMAIN_NAME%'); ?>">
  </div>
  <?php if( $has_dismiss ): ?>
    <button class="btn btn-search dismiss" title="<?= esc_attr_x('Chiudi', 'submit button', '%DOMAIN_NAME%'); ?>" type="submit">
      <?php echo gwp_svg_icon('dismiss'); ?>
    </button>
  <?php else: ?>
    <button class="btn btn-search" title="<?= esc_attr_x('Cerca', 'submit button', '%DOMAIN_NAME%'); ?>" type="submit">
      <?php echo gwp_svg_icon('search'); ?>
    </button>
  <?php endif; ?>
</form>
