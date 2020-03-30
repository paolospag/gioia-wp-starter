<div class="posts-list__item row" role="listitem">
  <div class="col-sm-3">
    <figure>
      <?php
        if( has_post_thumbnail() ):
          the_post_thumbnail(null, 'medium');
        else:
          gwp_placeholder_thumbnail();
        endif;
      ?>
    </figure>
  </div>
  <div class="col-sm-9">
    <?php
      the_title('<h3 class="post-title">', '</h3>');
      the_excerpt();
    ?>
  </div>
</div>
