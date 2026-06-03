<?php if ( ! defined( 'ABSPATH' ) ) { exit; }
$steps = array(
	array( '01', 'Browse &amp; choose', 'Explore graded laptops by brand, budget or use case. Each listing shows full specs and condition.' ),
	array( '02', 'Place your order', 'Order on the site or tap &ldquo;Order on WhatsApp&rdquo;. We confirm stock and delivery details.' ),
	array( '03', 'Pay your way', 'MoMo, bank transfer, or pay on delivery in Accra. First-time orders are paid before dispatch.' ),
	array( '04', 'Get it delivered', 'Same/next-day in Accra &amp; Tema, 2 to 4 days nationwide. Pickup in Accra also available.' ),
);
?>
<section class="bg-tpg-ink border-y border-tpg-line">
	<div class="tpg-container py-16 sm:py-20">
		<div class="text-center max-w-2xl mx-auto">
			<span class="eyebrow">Simple process</span>
			<h2 class="section-title mt-2">How to order</h2>
		</div>
		<div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 mt-10">
			<?php foreach ( $steps as $s ) : ?>
				<div class="card p-6 relative">
					<span class="font-mono text-tpg-green/40 text-3xl font-bold"><?php echo esc_html( $s[0] ); ?></span>
					<h3 class="font-display font-semibold text-tpg-paper mt-3"><?php echo wp_kses_post( $s[1] ); ?></h3>
					<p class="text-sm text-tpg-muted mt-2 leading-relaxed"><?php echo wp_kses_post( $s[2] ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
