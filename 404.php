<?php if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header(); ?>
<div class="tpg-container py-28 text-center">
	<span class="font-mono text-7xl font-bold text-tpg-green/30">404</span>
	<h1 class="section-title mt-4">Page not found</h1>
	<p class="text-tpg-muted mt-3">The page you are looking for has moved or never existed.</p>
	<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn-primary mt-8">Back home</a>
</div>
<?php get_footer();
