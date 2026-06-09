<?php
/**
 * WooCommerce integration & tweaks.
 *
 * @package TechPlugGH
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

/** Remove default WooCommerce wrappers; add our own. */
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

add_action( 'woocommerce_before_main_content', function () {
	echo '<div class="tpg-container py-10 sm:py-14"><div class="woocommerce-page-inner">';
}, 10 );
add_action( 'woocommerce_after_main_content', function () {
	echo '</div></div>';
}, 10 );

/** No WooCommerce sidebar - full-width shop. */
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

/** Products per row / per page. */
add_filter( 'loop_shop_columns', function () { return 3; } );
add_filter( 'loop_shop_per_page', function () { return 12; } );

/** Replace "Add to cart" text on archives. */
add_filter( 'woocommerce_product_add_to_cart_text', function ( $text ) {
	return is_shop() || is_product_category() ? __( 'View laptop', 'techpluggh' ) : $text;
}, 10 );

/** Force "View laptop" archive button to link to product (not ajax add). */
add_filter( 'woocommerce_loop_add_to_cart_link', function ( $html, $product ) {
	if ( is_shop() || is_product_category() || is_product_taxonomy() ) {
		return sprintf(
			'<a href="%s" class="btn-ghost w-full mt-2 text-xs">%s</a>',
			esc_url( $product->get_permalink() ),
			esc_html__( 'View details', 'techpluggh' )
		);
	}
	return $html;
}, 10, 2 );

/** Cart count fragment for header bubble. */
add_filter( 'woocommerce_add_to_cart_fragments', function ( $fragments ) {
	ob_start();
	$count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
	?>
	<span class="tpg-cart-count" data-count="<?php echo esc_attr( $count ); ?>"><?php echo esc_html( $count ); ?></span>
	<?php
	$fragments['span.tpg-cart-count'] = ob_get_clean();
	return $fragments;
} );

/**
 * WhatsApp-first purchase flow on the single product page.
 * Replaces the add-to-cart form with stock info and a direct
 * Buy on WhatsApp button (creates a tracked pending order,
 * then redirects to WhatsApp with the device details).
 */
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
add_action( 'woocommerce_single_product_summary', function () {
	global $product;
	if ( ! $product ) { return; }
	echo wp_kses_post( wc_get_stock_html( $product ) );
	if ( ! function_exists( 'tpg_wa_number' ) || '' === tpg_wa_number() || ! function_exists( 'tpg_wa_buy_url' ) ) { return; }
	if ( ! $product->is_in_stock() ) { return; }
	printf(
		'<a href="%s" class="btn-wa w-full mt-6 !text-base !py-4"><svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M.057 24l1.687-6.163a11.867 11.867 0 01-1.587-5.946C.16 5.335 5.495 0 12.05 0a11.82 11.82 0 018.413 3.488 11.82 11.82 0 013.48 8.414c-.003 6.557-5.338 11.892-11.893 11.892a11.9 11.9 0 01-5.688-1.448L.057 24zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884a9.86 9.86 0 001.51 5.26l-.999 3.648 3.978-1.045z"/></svg>%s</a>',
		esc_url( tpg_wa_buy_url( $product->get_id() ) ),
		esc_html__( 'Buy Now', 'techpluggh' )
	);
	echo '<p class="text-sm text-tpg-muted mt-3">' . esc_html__( 'Tap to send this laptop to us on WhatsApp. Pay by MoMo, bank transfer, or on delivery within Accra.', 'techpluggh' ) . '</p>';
}, 30 );

/** Move sale flash & rating styling handled in CSS; reposition not required. */

/** Breadcrumb defaults. */
add_filter( 'woocommerce_breadcrumb_defaults', function ( $defaults ) {
	$defaults['delimiter']   = ' <span class="text-tpg-muted">/</span> ';
	$defaults['wrap_before'] = '<nav class="tpg-breadcrumb font-mono text-xs uppercase tracking-widest text-tpg-muted mb-6">';
	$defaults['wrap_after']  = '</nav>';
	return $defaults;
} );

/** Currency symbol spacing already handled by WC GHS setting. */

/** Default catalog ordering: alphabetical title sort groups products by brand and model. */
add_filter( 'woocommerce_default_catalog_orderby', function () { return 'title'; } );

/** Related products: show 4, matching the shop grid. */
add_filter( 'woocommerce_output_related_products_args', function ( $args ) {
	$args['posts_per_page'] = 4;
	$args['columns']        = 4;
	return $args;
} );
