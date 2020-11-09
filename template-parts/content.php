<?php
// Exit if accessed directly.
defined('ABSPATH') || exit;
?>

<div class="posts-list__item col-md-4" role="listitem">
  <div class="entry">
    <figure class="entry__thumb">
      <a href="<?= esc_url(get_permalink()); ?>">
        <?php
          has_post_thumbnail()
            ? the_post_thumbnail('medium_large')
            : gwp_placeholder_thumbnail();
        ?>
      </a>
    </figure>
    <div class="entry__body">
      <?php the_title('<h4>', '</h4><hr />'); ?>
      <div class="wp-block-text">
        <?php the_excerpt(); ?>
      </div>
      <div class="btn-container">
        <a href="<?= esc_url(get_permalink()); ?>" class="btn btn-primary">
          <span><?php _e('Leggi tutto', '%DOMAIN_NAME%'); ?></span>
        </a>
      </div>
    </div>
  </div>
</div>
