<?php if ( ! defined( 'ABSPATH' ) ) { exit; }
$shop_url = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop' );
$hero_img = (int) tpg_opt( 'tpg_hero_image' );
?>
<section class="relative overflow-hidden bg-grid-faint bg-grid border-b border-tpg-line">
	<div class="absolute -top-32 -right-32 w-[40rem] h-[40rem] rounded-full bg-tpg-green/10 blur-3xl pointer-events-none"></div>
	<div class="tpg-container relative grid lg:grid-cols-2 gap-12 items-center py-16 sm:py-24">
		<div class="animate-riseIn">
			<span class="eyebrow"><?php echo esc_html( tpg_opt( 'tpg_hero_eyebrow', 'Plug Into Quality' ) ); ?></span>
			<h1 class="mt-4 text-4xl sm:text-5xl lg:text-6xl font-bold leading-[1.05]">
				<?php echo esc_html( tpg_opt( 'tpg_hero_title', 'Quality UK Used Laptops, Delivered Across Ghana.' ) ); ?>
			</h1>
			<p class="mt-5 text-tpg-muted text-base sm:text-lg max-w-xl leading-relaxed">
				<?php echo esc_html( tpg_opt( 'tpg_hero_sub' ) ); ?>
			</p>
			<div class="mt-8 flex flex-wrap gap-3">
				<a href="<?php echo esc_url( $shop_url ); ?>" class="btn-primary">Browse laptops
					<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
				</a>
				<a href="<?php echo esc_url( home_url( '/how-to-order' ) ); ?>" class="btn-ghost">How to order</a>
			</div>
			<div class="mt-10 flex flex-wrap gap-x-8 gap-y-3 text-sm text-tpg-muted">
				<span class="flex items-center gap-2"><span class="text-tpg-green">✓</span> Tested &amp; graded</span>
				<span class="flex items-center gap-2"><span class="text-tpg-green">✓</span> 1–3 month warranty</span>
				<span class="flex items-center gap-2"><span class="text-tpg-green">✓</span> Pay on delivery (Accra)</span>
			</div>
		</div>
		<div class="relative animate-riseIn" style="animation-delay:.15s">
			<div class="card overflow-hidden aspect-[5/4]">
				<?php tpg_image_or_placeholder( $hero_img, 'large', 'w-full h-full object-cover' ); ?>
			</div>
			<div class="absolute -bottom-5 -left-5 card px-5 py-4 hidden sm:flex items-center gap-3">
				<span class="text-2xl font-display font-bold text-tpg-green">195+</span>
				<span class="text-xs text-tpg-muted leading-tight">laptops in stock<br>ready to ship</span>
			</div>
		</div>
	</div>
</section>
