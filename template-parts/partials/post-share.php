<?php
// Exit if accessed directly.
defined('ABSPATH') || exit;
$post_url = get_permalink();
$post_title = get_the_title();
?>

<div class="entry-share">
  <span class="sr-only"><?php _e('Condividi', '%DOMAIN_NAME%'); ?></span>
  <ul class="social-bar">
    <li><a href="<?= esc_url('https://www.facebook.com/sharer/sharer.php?u='.$post_url); ?>" target="_blank"><?= gwp_svg_icon('facebook'); ?></a></li>
    <li><a href="<?= esc_url('https://twitter.com/home?status='.$post_url); ?>" title="<?= esc_attr__('Condividi su Twitter', '%DOMAIN_NAME%'); ?>" target="_blank"><?= gwp_svg_icon('twitter'); ?></a></li>
    <li><a href="<?= esc_url('https://www.linkedin.com/shareArticle?mini=true&url='.$post_url.'/&title='.esc_attr($post_title)); ?>" target="_blank"><?= gwp_svg_icon('linkedin'); ?></a></li>
    <li><a href="<?= esc_url('mailto:?&subject='.esc_attr($post_title).'&body='.$post_url); ?>" title="<?= esc_attr__('Condividi via E-mail', '%DOMAIN_NAME%'); ?>" target="_blank"><?= gwp_svg_icon('email'); ?></a></li>
    <li><a href="https://wa.me/?text=<?= esc_attr($post_title.' - '.$post_url); ?>" data-action="share/whatsapp/share" title="<?= esc_attr__('Condividi su WhatsApp', '%DOMAIN_NAME%'); ?>" target="_blank"><?= gwp_svg_icon('whatsapp'); ?></a></li>
  </ul>
</div>
