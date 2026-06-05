<?php
/**
 * Product card (loop) - overrides woocommerce/content-product.php.
 * Shows all key info: category, full title, spec line, stock, price.
 *
 * @package TechPlugGH
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
global $product;
if ( empty( $product ) || ! $product->is_visible() ) { return; }

/* Primary category label: prefer the brand category, else first term. */
$cat_label = '';
$terms     = get_the_terms( $product->get_id(), 'product_cat' );
if ( $terms && ! is_wp_error( $terms ) ) {
	foreach ( $terms as $t ) {
		if ( false !== strpos( $t->name, 'HP' ) || false !== strpos( $t->name, 'Dell' ) || false !== strpos( $t->name, 'Lenovo' ) || false !== strpos( $t->name, 'MacBook' ) ) {
			$cat_label = $t->name;
			break;
		}
	}
	if ( ! $cat_label ) { $cat_label = $terms[0]->name; }
}

/* Spec line from the short description, plain text. */
$spec = trim( wp_strip_all_tags( $product->get_short_description() ) );
?>
<li <?php wc_product_class( '', $product ); ?>>
	<div class="tpg-product">
		<a href="<?php the_permalink(); ?>" class="tpg-product__media">
			<?php
			if ( $product->is_on_sale() ) {
				echo '<span class="absolute top-3 left-3 z-10 chip-green !bg-tpg-green !text-tpg-black font-bold">SALE</span>';
			}
			if ( has_post_thumbnail() ) {
				echo get_the_post_thumbnail( $product->get_id(), 'woocommerce_thumbnail' );
			} else {
				echo tpg_placeholder_svg( 'w-full h-full' );
			}
			?>
		</a>
		<div class="tpg-product__body">
			<?php if ( $cat_label ) : ?>
				<span class="font-mono text-[11px] uppercase tracking-widest text-tpg-muted"><?php echo esc_html( $cat_label ); ?></span>
			<?php endif; ?>

			<a href="<?php the_permalink(); ?>"><h3 class="tpg-product__title"><?php echo esc_html( $product->get_name() ); ?></h3></a>

			<?php if ( $spec ) : ?>
				<p class="tpg-product__spec"><?php echo esc_html( $spec ); ?></p>
			<?php endif; ?>

			<div class="mt-auto pt-2 flex flex-col gap-2">
				<div class="flex items-center justify-between gap-2 flex-wrap">
					<?php echo wp_kses_post( $product->get_price_html() ); ?>
					<?php
					if ( $product->is_in_stock() ) {
						$qty = $product->get_stock_quantity();
						echo '<span class="tpg-product__stock">In stock' . ( $qty ? ' · ' . (int) $qty . ' available' : '' ) . '</span>';
					} else {
						echo '<span class="tpg-product__stock is-out">Out of stock</span>';
					}
					?>
				</div>
				<a href="<?php the_permalink(); ?>" class="btn-ghost w-full text-xs !py-2.5"><?php esc_html_e( 'View details', 'techpluggh' ); ?></a>
				<?php if ( function_exists( 'tpg_wa_number' ) && '' !== tpg_wa_number() && function_exists( 'tpg_wa_buy_url' ) && $product->is_in_stock() ) : ?>
					<a href="<?php echo esc_url( tpg_wa_buy_url( $product->get_id() ) ); ?>" class="btn-wa w-full text-xs !py-2.5"><?php esc_html_e( 'Buy on WhatsApp', 'techpluggh' ); ?></a>
				<?php endif; ?>
			</div>
		</div>
	</div>
</li>
