<?php
/**
 * The theme footer.
 *
 * This is the template that displays the closing of the #main-content and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package %DOMAIN_NAME%
 */
$copyright_text = get_theme_mod('copyright_text', '&copy; GWP Theme');
?>

  </main><!-- #main-content -->
  <footer id="footer">
    <div class="footer__top">
      <!-- Footer top content here. -->
    </div>
    <div class="footer__bottom">
      <p><?php echo nl2br($copyright_text); ?></p>
    </div>
  </footer>

  <?php wp_footer(); ?>

</body>
</html>
