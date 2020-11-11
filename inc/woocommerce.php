<?php
if ( !defined('ABSPATH') ) {
  exit;
}

/**
 * WooCommerce theme support & setup.
 *
 * @link https://docs.woocommerce.com/document/third-party-custom-theme-compatibility/
 * @link https://github.com/woocommerce/woocommerce/wiki/Enabling-product-gallery-features-(zoom,-swipe,-lightbox)
 */
function %DOMAIN_NAME%_woocommerce_setup() {
	add_theme_support('woocommerce');
	// add_theme_support('wc-product-gallery-zoom');
	// add_theme_support('wc-product-gallery-lightbox');
	// add_theme_support('wc-product-gallery-slider');
}
add_action('after_setup_theme', '%DOMAIN_NAME%_woocommerce_setup');

/**
 * Enqueue WooCommerce scripts & styles.
 */
function %DOMAIN_NAME%_woocommerce_scripts() {
	wp_enqueue_style( '%DOMAIN_NAME%-woocommerce-style', get_template_directory_uri() .'/assets/css/woocommerce.css' );
	$font_path = WC()->plugin_url() .'/assets/fonts/';
	$inline_font = '@font-face {
		font-family: "star";
		src: url("'. $font_path .'star.eot");
		src: url("'. $font_path .'star.eot?#iefix") format("embedded-opentype"),
			url("'. $font_path .'star.woff") format("woff"),
			url("'. $font_path .'star.ttf") format("truetype"),
			url("'. $font_path .'star.svg#star") format("svg");
		font-weight: normal;
		font-style: normal;
	}';
	wp_add_inline_style( '%DOMAIN_NAME%-woocommerce-style', $inline_font );
}
add_action('wp_enqueue_scripts', '%DOMAIN_NAME%_woocommerce_scripts');

// Add body class if WooCommerce page is detected.
function %DOMAIN_NAME%_woocommerce_body_classes($classes) {
	if ( is_cart() || is_checkout() || is_woocommerce() ) {
		$classes[] = 'is-woocommerce';
	}
	return $classes;
}
add_filter('body_class', '%DOMAIN_NAME%_woocommerce_body_classes');

/**
 * Remove default WooCommerce wrapper,
 * then wrap the content in theme markup.
 *
 * @return void
 */
remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
if ( !function_exists('%DOMAIN_NAME%_woocommerce_wrapper_before') ) {
	function %DOMAIN_NAME%_woocommerce_wrapper_before() {
		echo '<div class="container">';
	}
}
if ( !function_exists('%DOMAIN_NAME%_woocommerce_wrapper_after') ) {
	function %DOMAIN_NAME%_woocommerce_wrapper_after() {
		echo '</div>';
	}
}
add_action('woocommerce_before_main_content', '%DOMAIN_NAME%_woocommerce_wrapper_before');
add_action('woocommerce_after_main_content', '%DOMAIN_NAME%_woocommerce_wrapper_after');

/**
 * Cart button.
 *
 * Show a custom button to link the cart,
 * including the number of items present and the total.
 *
 * @return void
 */
if ( !function_exists('%DOMAIN_NAME%_woocommerce_cart_button') ) {
	function %DOMAIN_NAME%_woocommerce_cart_button() {
	?>
		<a class="navbar__cart" href="<?php echo esc_url(wc_get_cart_url()); ?>" title="<?php esc_attr_e('Vai al carrello', '%DOMAIN_NAME%'); ?>" role="button">
			<?php
				$item_count = WC()->cart->get_cart_contents_count();
				$item_count_text = sprintf(
					/* translators: number of items in the mini cart. */
					_n('%d elemento', '%d elementi', $item_count, '%DOMAIN_NAME%'),
					WC()->cart->get_cart_contents_count()
				);
			?>
			<?php echo gwp_svg_icon('cart'); ?>
			<span class="sr-only amount"><?php echo wp_kses_data( WC()->cart->get_cart_subtotal() ); ?></span>
			<span class="count">
				<?php if($item_count): ?><span class="badge"><?php echo $item_count; ?></span><?php endif; ?>
				<span class="sr-only"><?php echo esc_html($item_count_text); ?></span>
			</span>
		</a>
	<?php
	}
}

/**
 * Cart fragments.
 *
 * Ensure cart contents update on AJAX operations/refresh.
 *
 * @param array $fragments
 * @return array Fragments to refresh via AJAX.
 */
if ( !function_exists('%DOMAIN_NAME%_woocommerce_cart_fragment') ) {
	function %DOMAIN_NAME%_woocommerce_cart_fragment() {
		ob_start();
		%DOMAIN_NAME%_woocommerce_cart_button();
		$fragments['a.navbar__cart'] = ob_get_clean();
		return $fragments;
	}
}
add_filter('woocommerce_add_to_cart_fragments', '%DOMAIN_NAME%_woocommerce_cart_fragment');

/**
 * Products loop columns.
 *
 * @return int
 */
if ( !function_exists('%DOMAIN_NAME%_woocommerce_loop_columns') ) {
	function %DOMAIN_NAME%_woocommerce_loop_columns() {
		return get_theme_mod('woo_loop_columns', 3);
	}
}
add_filter('loop_shop_columns', '%DOMAIN_NAME%_woocommerce_loop_columns', 20);

/**
 * Hide category product(s) count in archives.
 */
add_filter('woocommerce_subcategory_count_html', '__return_false');

/**
 * Hook into other functionalities here.
 *
 * @link https://docs.woocommerce.com/document/introduction-to-hooks-actions-and-filters/
 */
