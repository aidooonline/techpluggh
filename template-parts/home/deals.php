<?php if ( ! defined( 'ABSPATH' ) ) { exit; }
$img = (int) tpg_opt( 'tpg_promo_image' );
?>
<section class="tpg-container py-16 sm:py-20">
	<div class="card overflow-hidden grid lg:grid-cols-2 items-stretch">
		<div class="p-8 sm:p-12 flex flex-col justify-center">
			<span class="eyebrow">Deals &amp; Offers</span>
			<h2 class="section-title mt-3">Save more on this week&rsquo;s picks.</h2>
			<p class="text-tpg-muted mt-4 max-w-md leading-relaxed">Discounted units, bulk pricing for teams and student bundles. Limited stock, updated weekly.</p>
			<a href="<?php echo esc_url( home_url( '/deals' ) ); ?>" class="btn-primary mt-7 self-start">See current deals</a>
		</div>
		<div class="relative min-h-[260px] bg-tpg-ink">
			<?php tpg_image_or_placeholder( $img, 'large', 'absolute inset-0 w-full h-full object-cover' ); ?>
			<div class="absolute inset-0 bg-gradient-to-r from-tpg-surface/80 to-transparent lg:bg-gradient-to-l"></div>
		</div>
	</div>
</section>
