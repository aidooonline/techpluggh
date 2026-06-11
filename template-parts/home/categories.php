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
	<div class="flex gap-3 sm:gap-4 overflow-x-auto pb-2 -mx-5 px-5 sm:mx-0 sm:px-0 sm:overflow-visible [scrollbar-width:none] [-ms-overflow-style:none]">
		<?php foreach ( $cats as $cat ) :
			$thumb_id = (int) get_term_meta( $cat->term_id, 'thumbnail_id', true ); ?>
			<a href="<?php echo esc_url( get_term_link( $cat ) ); ?>" class="group shrink-0 w-24 sm:w-auto sm:flex-1 text-center">
				<div class="card aspect-square overflow-hidden rounded-2xl flex items-center justify-center group-hover:border-tpg-green/50 transition">
					<?php tpg_image_or_placeholder( $thumb_id, 'medium', 'w-full h-full object-cover group-hover:scale-105 transition duration-500' ); ?>
				</div>
				<h3 class="mt-2 text-xs sm:text-[13px] font-medium text-tpg-paper group-hover:text-tpg-green transition-colors leading-tight line-clamp-1"><?php echo esc_html( $cat->name ); ?></h3>
				<p class="text-[10px] text-tpg-muted mt-0.5"><?php echo esc_html( $cat->count ); ?> in stock</p>
			</a>
		<?php endforeach; ?>
	</div>
</section>
