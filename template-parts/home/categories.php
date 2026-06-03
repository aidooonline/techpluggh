<?php if ( ! defined( 'ABSPATH' ) ) { exit; }
if ( ! taxonomy_exists( 'product_cat' ) ) { return; }
$cats = get_terms( array( 'taxonomy' => 'product_cat', 'hide_empty' => true, 'number' => 8, 'orderby' => 'count', 'order' => 'DESC' ) );
if ( is_wp_error( $cats ) || ! $cats ) { return; }
?>
<section class="tpg-container py-16 sm:py-20">
	<div class="flex items-end justify-between mb-8 gap-4">
		<div>
			<span class="eyebrow">Browse by</span>
			<h2 class="section-title mt-2">Shop by category</h2>
		</div>
		<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="menu-link hidden sm:block">View all &rarr;</a>
	</div>
	<div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
		<?php foreach ( $cats as $cat ) :
			$thumb_id = (int) get_term_meta( $cat->term_id, 'thumbnail_id', true ); ?>
			<a href="<?php echo esc_url( get_term_link( $cat ) ); ?>" class="card group relative overflow-hidden aspect-[4/5] flex flex-col justify-end hover:border-tpg-green/50 transition">
				<div class="absolute inset-0">
					<?php tpg_image_or_placeholder( $thumb_id, 'large', 'w-full h-full object-cover opacity-60 group-hover:opacity-80 group-hover:scale-105 transition duration-500' ); ?>
				</div>
				<div class="absolute inset-0 bg-gradient-to-t from-tpg-black via-tpg-black/40 to-transparent"></div>
				<div class="relative p-4">
					<h3 class="font-display font-semibold text-tpg-paper"><?php echo esc_html( $cat->name ); ?></h3>
					<p class="text-xs text-tpg-muted mt-0.5"><?php echo esc_html( $cat->count ); ?> in stock</p>
				</div>
			</a>
		<?php endforeach; ?>
	</div>
</section>
