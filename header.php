<?php
/**
 * Header.
 * @package TechPlugGH
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
$cart_count = ( function_exists( 'WC' ) && WC()->cart ) ? WC()->cart->get_cart_contents_count() : 0;
$cart_url   = function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : home_url( '/cart' );
$shop_url   = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop' );
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-50 focus:bg-tpg-green focus:text-tpg-black focus:px-3 focus:py-2 focus:rounded" href="#main"><?php esc_html_e( 'Skip to content', 'techpluggh' ); ?></a>

<?php if ( tpg_opt( 'tpg_promo_on', true ) && tpg_opt( 'tpg_promo_text' ) ) : ?>
<div class="bg-tpg-green text-tpg-black text-center text-[12px] sm:text-xs font-medium tracking-wide py-2 px-4">
	<?php echo esc_html( tpg_opt( 'tpg_promo_text' ) ); ?>
</div>
<?php endif; ?>

<header id="site-header" class="sticky top-0 z-40 border-b border-tpg-line bg-tpg-black/85 backdrop-blur-md">
	<div class="tpg-container flex items-center justify-between gap-4 h-16">

		<div class="flex items-center gap-3">
			<button id="tpg-menu-toggle" class="lg:hidden text-tpg-paper p-2 -ml-2" aria-label="<?php esc_attr_e( 'Open menu', 'techpluggh' ); ?>" aria-expanded="false">
				<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 6h16M4 12h16M4 18h16" stroke-linecap="round"/></svg>
			</button>
			<?php tpg_logo(); ?>
		</div>

		<nav class="hidden lg:flex items-center gap-7" aria-label="<?php esc_attr_e( 'Primary', 'techpluggh' ); ?>">
			<?php
			wp_nav_menu( array(
				'theme_location' => 'primary',
				'container'      => false,
				'menu_class'     => 'flex items-center gap-7',
				'fallback_cb'    => 'tpg_default_menu',
				'depth'          => 2,
				'walker'         => new TPG_Nav_Walker(),
			) );
			?>
		</nav>

		<div class="flex items-center gap-2 sm:gap-3">
			<button id="tpg-search-toggle" class="text-tpg-paper/80 hover:text-tpg-green p-2" aria-label="<?php esc_attr_e( 'Search', 'techpluggh' ); ?>">
				<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3" stroke-linecap="round"/></svg>
			</button>
			<a href="<?php echo esc_url( $shop_url ); ?>" class="hidden sm:inline-flex btn-primary text-xs px-5 py-2.5"><?php esc_html_e( 'Shop laptops', 'techpluggh' ); ?></a>
		</div>
	</div>

	<!-- Search drawer -->
	<div id="tpg-search-drawer" class="hidden border-t border-tpg-line bg-tpg-ink">
		<div class="tpg-container py-4">
			<?php if ( function_exists( 'get_product_search_form' ) ) { get_product_search_form(); } else { get_search_form(); } ?>
		</div>
	</div>
</header>

<!-- Mobile menu -->
<div id="tpg-mobile-menu" class="fixed inset-0 z-50 hidden">
	<div class="absolute inset-0 bg-black/70" data-close></div>
	<div class="absolute left-0 top-0 h-full w-[82%] max-w-xs bg-tpg-ink border-r border-tpg-line p-6 overflow-y-auto translate-x-[-100%] transition-transform duration-300" id="tpg-mobile-panel">
		<div class="flex items-center justify-between mb-8">
			<?php tpg_logo(); ?>
			<button data-close class="text-tpg-paper p-2" aria-label="<?php esc_attr_e( 'Close', 'techpluggh' ); ?>">
				<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 6l12 12M18 6L6 18" stroke-linecap="round"/></svg>
			</button>
		</div>
		<?php
		wp_nav_menu( array(
			'theme_location' => 'primary',
			'container'      => false,
			'menu_class'     => 'flex flex-col gap-1 text-base',
			'fallback_cb'    => 'tpg_default_menu',
			'depth'          => 2,
		) );
		?>
		<a href="<?php echo esc_url( $shop_url ); ?>" class="btn-primary w-full mt-8"><?php esc_html_e( 'Shop laptops', 'techpluggh' ); ?></a>
	</div>
</div>

<main id="main" class="min-h-[60vh]">
