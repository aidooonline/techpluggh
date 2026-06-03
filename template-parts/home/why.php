<?php if ( ! defined( 'ABSPATH' ) ) { exit; }
$items = array(
	array( 'M12 2l2.4 7.4H22l-6 4.5 2.3 7.1L12 16.7 5.7 21l2.3-7.1-6-4.5h7.6z', 'Tested &amp; graded', 'Every laptop is inspected and graded for cosmetic and functional condition before listing.' ),
	array( 'M12 2l8 4v6c0 5-3.4 8.5-8 10-4.6-1.5-8-5-8-10V6z', 'Warranty-backed', '1 to 3 months warranty on every machine, plus a 48-hour return window on verified faults.' ),
	array( 'M3 7h18v10H3zM3 11h18', 'Flexible payment', 'Mobile Money, bank transfer, or pay on delivery within Accra. Simple and secure.' ),
	array( 'M5 12h14M13 6l6 6-6 6', 'Fast delivery', 'Same or next-day in Accra and Tema. Nationwide courier in 2 to 4 working days.' ),
);
?>
<section class="bg-tpg-ink border-y border-tpg-line">
	<div class="tpg-container py-16 sm:py-20">
		<span class="eyebrow">Why TechPlug GH</span>
		<h2 class="section-title mt-2 max-w-2xl">Quality you can trust, service that delivers.</h2>
		<div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 mt-10">
			<?php foreach ( $items as $it ) : ?>
				<div class="card p-6 hover:border-tpg-green/40 transition">
					<div class="w-11 h-11 rounded-xl bg-tpg-green/10 grid place-items-center mb-4">
						<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#00E676" stroke-width="1.8"><path d="<?php echo esc_attr( $it[0] ); ?>" stroke-linecap="round" stroke-linejoin="round"/></svg>
					</div>
					<h3 class="font-display font-semibold text-tpg-paper"><?php echo wp_kses_post( $it[1] ); ?></h3>
					<p class="text-sm text-tpg-muted mt-2 leading-relaxed"><?php echo wp_kses_post( $it[2] ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
