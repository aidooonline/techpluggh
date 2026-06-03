<?php
/**
 * TechPlug GH theme functions.
 *
 * @package TechPlugGH
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

define( 'TPG_VERSION', '1.0.0' );
define( 'TPG_DIR', get_template_directory() );
define( 'TPG_URI', get_template_directory_uri() );

/**
 * Theme setup.
 */
function tpg_setup() {
	load_theme_textdomain( 'techpluggh', TPG_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );

	add_theme_support( 'custom-logo', array(
		'height'      => 80,
		'width'       => 240,
		'flex-height' => true,
		'flex-width'  => true,
	) );

	// WooCommerce.
	add_theme_support( 'woocommerce', array(
		'thumbnail_image_width' => 700,
		'single_image_width'    => 1200,
		'product_grid'          => array( 'default_columns' => 4 ),
	) );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );

	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'techpluggh' ),
		'footer'  => __( 'Footer Menu', 'techpluggh' ),
	) );
}
add_action( 'after_setup_theme', 'tpg_setup' );

/**
 * Assets.
 */
function tpg_assets() {
	wp_enqueue_style( 'techpluggh-main', TPG_URI . '/assets/css/main.css', array(), TPG_VERSION );
	// Keep theme header style.css present for WP validation.
	wp_enqueue_style( 'techpluggh-style', get_stylesheet_uri(), array( 'techpluggh-main' ), TPG_VERSION );

	wp_enqueue_script( 'techpluggh-main', TPG_URI . '/assets/js/main.js', array(), TPG_VERSION, true );
	wp_localize_script( 'techpluggh-main', 'TPG', array(
		'ajaxUrl' => admin_url( 'admin-ajax.php' ),
	) );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'tpg_assets' );

/**
 * Widget areas (footer).
 */
function tpg_widgets() {
	register_sidebar( array(
		'name'          => __( 'Footer About', 'techpluggh' ),
		'id'            => 'footer-about',
		'before_widget' => '<div class="mb-6">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="text-sm font-display font-semibold text-tpg-paper mb-3">',
		'after_title'   => '</h4>',
	) );
}
add_action( 'widgets_init', 'tpg_widgets' );

/** Includes. */
require TPG_DIR . '/inc/customizer.php';
require TPG_DIR . '/inc/template-tags.php';
if ( class_exists( 'WooCommerce' ) ) {
	require TPG_DIR . '/inc/woocommerce.php';
}

/** Excerpt tweaks. */
add_filter( 'excerpt_more', function () { return '&hellip;'; } );
add_filter( 'excerpt_length', function () { return 24; } );

/** Body classes. */
add_filter( 'body_class', function ( $classes ) {
	$classes[] = 'tpg-theme';
	return $classes;
} );
