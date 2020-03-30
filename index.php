<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme.
 * It is used to display a page when nothing more specific matches a query.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package %DOMAIN_NAME%
 */

get_header();
?>

<div class="container">
  <?php
    if( have_posts() ):
      echo '<div class="posts-list" role="list">';
      while( have_posts() ): the_post();
        get_template_part('template-parts/content');
      endwhile;
      echo '</div>';
    else:
      echo '<p>'. __('Nessun articolo trovato.', '%DOMAIN_NAME%') .'</p>';
    endif;
  ?>
</div>

<?php
get_footer();
