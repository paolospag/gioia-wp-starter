<?php
/**
 * The template for displaying single post.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package %DOMAIN_NAME%
 */

get_header();
?>

<div class="container">
  <section class="row">
    <?php
      while( have_posts() ): the_post();
        $post_type = get_post_type();
        get_template_part('template-parts/content', $post_type);
      endwhile;
      get_sidebar();
    ?>
  </section>
</div>

<?php
get_footer();
