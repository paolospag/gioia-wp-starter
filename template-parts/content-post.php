<?php
// Exit if accessed directly.
defined('ABSPATH') || exit;
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('col-sm-9 col-xl-8'); ?>>
  <?php if( has_post_thumbnail() ): ?>
    <figure class="post__image">
      <?php the_post_thumbnail('large'); ?>
    </figure>
  <?php endif; ?>
  <div class="post__meta">
    <?php
      the_title('<h1 class="h2">', '</h1><hr />');
      the_date('', '<span class="date">', '</span>');
    ?>
  </div>
  <div class="post__content">
    <?php the_content(); ?>
  </div>
  <?php
    get_template_part('template-parts/partials/post', 'share');
  ?>
</article>
