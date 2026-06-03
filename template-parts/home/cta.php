<?php if ( ! defined( 'ABSPATH' ) ) { exit; }
$shop_url = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop' );
?>
<section class="tpg-container py-20">
	<div class="relative card overflow-hidden p-10 sm:p-16 text-center">
		<div class="absolute -top-24 left-1/2 -translate-x-1/2 w-[36rem] h-[36rem] rounded-full bg-tpg-green/10 blur-3xl pointer-events-none"></div>
		<div class="relative">
			<h2 class="section-title max-w-2xl mx-auto">Ready to plug into quality?</h2>
			<p class="text-tpg-muted mt-4 max-w-lg mx-auto">Find your next laptop today. New stock added every week.</p>
			<div class="mt-8 flex flex-wrap gap-3 justify-center">
				<a href="<?php echo esc_url( $shop_url ); ?>" class="btn-primary">Shop laptops</a>
				<?php if ( tpg_wa_number() ) : ?>
				<a href="<?php echo tpg_wa_link( 'Hi TechPlug GH, I would like help choosing a laptop.' ); ?>" target="_blank" rel="noopener" class="btn-wa">Chat on WhatsApp</a>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
