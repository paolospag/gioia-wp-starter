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
  <?php if( have_posts() ): ?>
    <div class="posts-list">
      <?php if( is_search() ): ?>
        <h3><?php printf( esc_html__('Risultati di ricerca per: %s', '%DOMAIN_NAME%'), '<em>'.get_search_query().'</em>' ); ?></h3>
      <?php endif; ?>
      <div class="row" role="list">
        <?php
          while( have_posts() ): the_post();
            get_template_part('template-parts/content');
          endwhile;
        ?>
      </div>
      <?php
        the_posts_pagination([
          'type' => 'list',
          'prev_text' => gwp_svg_icon('left'),
          'next_text' => gwp_svg_icon('right')
        ]);
      ?>
    </div>
  <?php else: ?>
    <p><?php _e('Nessun articolo trovato.', '%DOMAIN_NAME%'); ?></p>
  <?php endif; ?>
</div>

<?php
get_footer();
