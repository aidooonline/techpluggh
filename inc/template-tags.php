<?php
/**
 * Reusable template helpers.
 *
 * @package TechPlugGH
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

/** Get a Customizer value with default. */
function tpg_opt( $key, $default = '' ) {
	return get_theme_mod( $key, $default );
}

/**
 * Sanitised, digits-only WhatsApp number.
 * Sources: Customizer field first, then the WhatsApp gateway setting.
 * Accepts a raw number or a wa.me / WhatsApp profile link.
 */
function tpg_wa_number() {
	$raw = trim( (string) tpg_opt( 'tpg_whatsapp' ) );
	if ( '' === $raw ) {
		$gw  = get_option( 'woocommerce_tpg_whatsapp_settings', array() );
		$raw = isset( $gw['whatsapp'] ) ? trim( (string) $gw['whatsapp'] ) : '';
	}
	if ( preg_match( '~(?:wa\.me/|api\.whatsapp\.com/send[^ ]*phone=)\+?(\d+)~i', $raw, $m ) ) {
		return $m[1];
	}
	return preg_replace( '/\D+/', '', $raw );
}

/** Build a wa.me link with an optional prefilled message. */
function tpg_wa_link( $message = '' ) {
	$num = tpg_wa_number();
	if ( ! $num ) {
		return '#';
	}
	$url = 'https://wa.me/' . $num;
	if ( $message ) {
		$url .= '?text=' . rawurlencode( $message );
	}
	return esc_url( $url );
}

/**
 * Print the site logo if set, otherwise a clean text wordmark fallback.
 * Keeps the brand visible even before a logo is uploaded (no hardcoded image).
 */
function tpg_logo() {
	if ( has_custom_logo() ) {
		the_custom_logo();
		return;
	}
	printf(
		'<a href="%1$s" class="flex items-baseline gap-1 font-display font-extrabold text-xl tracking-tight text-tpg-paper">%2$s<span class="text-tpg-green">.</span><span class="sr-only">%3$s</span></a>',
		esc_url( home_url( '/' ) ),
		'TechPlug<span class="text-tpg-green">GH</span>',
		esc_html__( 'Home', 'techpluggh' )
	);
}

/**
 * Image for a slot, falling back to a branded SVG placeholder.
 * @param int    $attachment_id Customizer media id (0 if none).
 * @param string $size          Image size.
 * @param string $class         CSS classes.
 */
function tpg_image_or_placeholder( $attachment_id, $size = 'large', $class = '' ) {
	if ( $attachment_id && wp_get_attachment_image_url( (int) $attachment_id, $size ) ) {
		echo wp_get_attachment_image( (int) $attachment_id, $size, false, array( 'class' => $class, 'loading' => 'lazy' ) );
		return;
	}
	echo tpg_placeholder_svg( $class );
}

/** Inline branded placeholder (used only when no media is set; not a stored file). */
function tpg_placeholder_svg( $class = '' ) {
	return '<div class="' . esc_attr( $class ) . ' flex items-center justify-center bg-tpg-ink">'
		. '<svg viewBox="0 0 64 64" width="64" height="64" fill="none" aria-hidden="true">'
		. '<rect x="8" y="14" width="48" height="30" rx="3" stroke="#2c3a33" stroke-width="2"/>'
		. '<rect x="4" y="46" width="56" height="4" rx="2" fill="#2c3a33"/>'
		. '<path d="M26 29l5 5 8-9" stroke="#00E676" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>'
		. '</svg></div>';
}

/** Product count for a WC category term. */
function tpg_cat_count( $term ) {
	return isset( $term->count ) ? (int) $term->count : 0;
}

/** Fallback primary menu when none is assigned. */
function tpg_default_menu() {
	$shop = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop' );
	$items = array(
		__( 'Home', 'techpluggh' )        => home_url( '/' ),
		__( 'Shop', 'techpluggh' )        => $shop,
		__( 'Deals', 'techpluggh' )       => home_url( '/deals' ),
		__( 'How to Order', 'techpluggh' )=> home_url( '/how-to-order' ),
		__( 'About', 'techpluggh' )       => home_url( '/about' ),
		__( 'Contact', 'techpluggh' )     => home_url( '/contact' ),
	);
	echo '<ul class="flex flex-col lg:flex-row gap-1 lg:gap-7">';
	foreach ( $items as $label => $url ) {
		printf( '<li><a class="menu-link block py-2 lg:py-0" href="%s">%s</a></li>', esc_url( $url ), esc_html( $label ) );
	}
	echo '</ul>';
}

/** Minimal nav walker that applies brand classes to top-level links. */
class TPG_Nav_Walker extends Walker_Nav_Menu {
	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		$url     = ! empty( $item->url ) ? $item->url : '#';
		$output .= '<li class="' . esc_attr( implode( ' ', $classes ) ) . '">';
		$output .= '<a class="menu-link" href="' . esc_url( $url ) . '">' . esc_html( $item->title ) . '</a>';
	}
	public function end_el( &$output, $item, $depth = 0, $args = null ) {
		$output .= '</li>';
	}
	public function start_lvl( &$output, $depth = 0, $args = null ) { $output .= '<ul class="tpg-submenu">'; }
	public function end_lvl( &$output, $depth = 0, $args = null ) { $output .= '</ul>'; }
}
