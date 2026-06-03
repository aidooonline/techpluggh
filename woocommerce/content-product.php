<?php
/**
 * Product card (loop) - overrides woocommerce/content-product.php.
 * @package TechPlugGH
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
global $product;
if ( empty( $product ) || ! $product->is_visible() ) { return; }
?>
<li <?php wc_product_class( '', $product ); ?>>
	<div class="tpg-product">
		<a href="<?php the_permalink(); ?>" class="tpg-product__media">
			<?php
			if ( $product->is_on_sale() ) {
				echo '<span class="absolute top-3 left-3 z-10 chip-green !bg-tpg-green !text-tpg-black font-bold">SALE</span>';
			}
			if ( ! $product->is_in_stock() ) {
				echo '<span class="absolute top-3 right-3 z-10 chip">Out of stock</span>';
			}
			if ( has_post_thumbnail() ) {
				echo get_the_post_thumbnail( $product->get_id(), 'woocommerce_thumbnail' );
			} else {
				echo tpg_placeholder_svg( 'w-full h-full' );
			}
			?>
		</a>
		<div class="tpg-product__body">
			<?php
			$cats = wc_get_product_category_list( $product->get_id() );
			if ( $cats ) {
				echo '<span class="font-mono text-[10px] uppercase tracking-widest text-tpg-muted line-clamp-1">' . wp_strip_all_tags( $cats ) . '</span>';
			}
			?>
			<a href="<?php the_permalink(); ?>"><h3 class="tpg-product__title"><?php echo esc_html( $product->get_name() ); ?></h3></a>
			<div class="mt-auto pt-2">
				<?php echo wp_kses_post( $product->get_price_html() ); ?>
				<a href="<?php the_permalink(); ?>" class="btn-ghost w-full mt-3 text-xs">View details</a>
			</div>
		</div>
	</div>
</li>
