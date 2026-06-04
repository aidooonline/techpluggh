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
/**
 * TEMPORARY deploy diagnostic + permission auto-repair (admins only).
 * Remove after deploy is verified working.
 *
 * Git clones can land with 700/600 permissions: PHP (owner) reads them fine,
 * but the webserver's static handler cannot, and WP's rewrite !-f check then
 * routes asset requests into WordPress, producing 404s for files that exist.
 * This repairs the theme tree to 755 (dirs) / 644 (files) and reports state.
 */
add_action( 'admin_notices', 'tpg_deploy_diagnostic' );
function tpg_perms( $path ) {
	return file_exists( $path ) ? substr( sprintf( '%o', fileperms( $path ) ), -4 ) : 'n/a';
}
function tpg_fix_perms() {
	$dir   = get_template_directory();
	$fixed = 0;
	@chmod( $dir, 0755 ) && $fixed++;
	$it = new RecursiveIteratorIterator(
		new RecursiveDirectoryIterator( $dir, FilesystemIterator::SKIP_DOTS ),
		RecursiveIteratorIterator::SELF_FIRST
	);
	foreach ( $it as $item ) {
		$p = $item->getPathname();
		if ( false !== strpos( $p, DIRECTORY_SEPARATOR . '.git' ) ) { continue; }
		$want = $item->isDir() ? 0755 : 0644;
		if ( ( fileperms( $p ) & 0777 ) !== $want ) {
			if ( @chmod( $p, $want ) ) { $fixed++; }
		}
	}
	return $fixed;
}
function tpg_deploy_diagnostic() {
	if ( ! current_user_can( 'manage_options' ) ) { return; }
	$dir    = get_template_directory();
	$before = array(
		'theme dir'           => tpg_perms( $dir ),
		'assets dir'          => tpg_perms( $dir . '/assets' ),
		'assets/css dir'      => tpg_perms( $dir . '/assets/css' ),
		'style.css'           => tpg_perms( $dir . '/style.css' ),
		'assets/css/main.css' => tpg_perms( $dir . '/assets/css/main.css' ),
		'assets/js/main.js'   => tpg_perms( $dir . '/assets/js/main.js' ),
	);
	$fixed = tpg_fix_perms();
	$lines   = array();
	$lines[] = 'Theme dir: ' . $dir;
	$lines[] = 'Permissions BEFORE repair:';
	foreach ( $before as $k => $v ) { $lines[] = '  ' . $k . ': ' . $v; }
	$lines[] = 'Permissions AFTER repair:';
	$lines[] = '  theme dir: ' . tpg_perms( $dir ) . ' | main.css: ' . tpg_perms( $dir . '/assets/css/main.css' ) . ' | style.css: ' . tpg_perms( $dir . '/style.css' );
	$lines[] = 'Items repaired this load: ' . (int) $fixed;
	echo '<div class="notice notice-warning"><p><strong>TechPlug GH deploy diagnostic (perms auto-repair)</strong></p><pre style="white-space:pre-wrap">' . esc_html( implode( "\n", $lines ) ) . '</pre></div>';
}
