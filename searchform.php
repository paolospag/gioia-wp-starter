<?php
/**
 * The template for displaying search form.
 *
 * @package %DOMAIN_NAME%
 */
$has_dismiss = get_query_var('has_dismiss', false);
?>
<form class="search-form" method="get" action="<?= home_url(); ?>" role="search">
  <label for="search" class="sr-only"><?= _x('Cerca per parole chiave', 'label', '%DOMAIN_NAME%'); ?></label>
  <div class="form-control-wrap">
    <input class="form-control" id="search" type="search" name="s" placeholder="<?= esc_attr_x('Inserisci un termine e premi invio&hellip;', 'placeholder', '%DOMAIN_NAME%'); ?>">
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
