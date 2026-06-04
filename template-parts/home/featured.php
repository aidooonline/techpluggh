<?php if ( ! defined( 'ABSPATH' ) ) { exit; }
if ( ! function_exists( 'wc_get_products' ) ) { return; }

// Featured products via product_visibility taxonomy.
$args = array(
	'post_type'      => 'product',
	'post_status'    => 'publish',
	'posts_per_page' => 6,
	'tax_query'      => array(
		array( 'taxonomy' => 'product_visibility', 'field' => 'name', 'terms' => 'featured' ),
	),
);
$q = new WP_Query( $args );
if ( ! $q->have_posts() ) {
	// Fallback: most recent products.
	wp_reset_postdata();
	$q = new WP_Query( array( 'post_type' => 'product', 'post_status' => 'publish', 'posts_per_page' => 6, 'orderby' => 'date', 'order' => 'DESC' ) );
}
if ( ! $q->have_posts() ) { wp_reset_postdata(); return; }
?>
<section class="tpg-container py-16 sm:py-20 border-t border-tpg-line">
	<div class="flex items-end justify-between mb-8 gap-4">
		<div>
			<span class="eyebrow">Hand-picked</span>
			<h2 class="section-title mt-2">Featured laptops</h2>
		</div>
		<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="menu-link hidden sm:block">Shop all &rarr;</a>
	</div>
	<ul class="products">
		<?php
		while ( $q->have_posts() ) {
			$q->the_post();
			wc_get_template_part( 'content', 'product' );
		}
		wp_reset_postdata();
		?>
	</ul>
</section>
