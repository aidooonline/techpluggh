<?php if ( ! defined( 'ABSPATH' ) ) { exit; }
$shop_url = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop' );
$hero_img = (int) tpg_opt( 'tpg_hero_image' );
?>
<section class="relative overflow-hidden bg-grid-faint bg-grid border-b border-tpg-line">
	<div class="absolute -top-32 -right-32 w-[40rem] h-[40rem] rounded-full bg-tpg-green/10 blur-3xl pointer-events-none"></div>
	<div class="tpg-container relative grid lg:grid-cols-2 gap-12 items-center py-16 sm:py-24">
		<div class="animate-riseIn">
			<span class="eyebrow"><?php echo esc_html( tpg_opt( 'tpg_hero_eyebrow', 'Plug Into Quality' ) ); ?></span>
			<h1 class="mt-4 text-4xl sm:text-5xl lg:text-5xl font-bold leading-[1.08]">
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
			<?php
			// Featured products for the hero slider.
			$slides = array();
			if ( function_exists( 'wc_get_products' ) ) {
				$ids = get_posts( array(
					'post_type'      => 'product',
					'post_status'    => 'publish',
					'posts_per_page' => 8,
					'fields'         => 'ids',
					'tax_query'      => array( array( 'taxonomy' => 'product_visibility', 'field' => 'name', 'terms' => 'featured' ) ),
				) );
				if ( ! $ids ) {
					$ids = get_posts( array( 'post_type' => 'product', 'post_status' => 'publish', 'posts_per_page' => 8, 'fields' => 'ids', 'orderby' => 'date', 'order' => 'DESC' ) );
				}
				foreach ( $ids as $pid ) {
					$p = wc_get_product( $pid );
					if ( ! $p ) { continue; }
					$img = get_the_post_thumbnail_url( $pid, 'large' );
					$slides[] = array(
						'title' => $p->get_name(),
						'url'   => get_permalink( $pid ),
						'img'   => $img ? $img : '',
						'price' => $p->get_price_html(),
					);
				}
			}
			?>
			<div class="card overflow-hidden aspect-[5/4] relative flex items-center justify-center">
				<!-- Faint laptop backdrop -->
				<svg viewBox="0 0 64 64" class="absolute w-2/3 h-2/3 opacity-[0.06]" fill="none" aria-hidden="true">
					<rect x="8" y="14" width="48" height="30" rx="3" stroke="#9AA8A1" stroke-width="2"/>
					<rect x="4" y="46" width="56" height="4" rx="2" fill="#9AA8A1"/>
				</svg>

				<?php if ( $slides ) : ?>
					<div class="tpg-hero-slider relative w-[360px] max-w-[90%]" data-interval="4500">
						<div class="relative rounded-xl overflow-hidden border border-tpg-line bg-tpg-black aspect-[16/10] shadow-card">
							<?php foreach ( $slides as $i => $s ) : ?>
								<a href="<?php echo esc_url( $s['url'] ); ?>"
									class="tpg-hero-slide absolute inset-0 opacity-0 transition-opacity duration-700<?php echo 0 === $i ? ' is-active !opacity-100' : ''; ?>"
									data-index="<?php echo (int) $i; ?>"
									data-title="<?php echo esc_attr( $s['title'] ); ?>"
									data-url="<?php echo esc_url( $s['url'] ); ?>">
									<?php if ( $s['img'] ) : ?>
										<img src="<?php echo esc_url( $s['img'] ); ?>" alt="<?php echo esc_attr( $s['title'] ); ?>" class="w-full h-full object-contain p-3" loading="lazy">
									<?php else : ?>
										<?php echo tpg_placeholder_svg( 'w-full h-full' ); ?>
									<?php endif; ?>
								</a>
							<?php endforeach; ?>
							<div class="tpg-hero-dots absolute top-2 right-2 z-10 flex gap-1">
								<?php foreach ( $slides as $i => $s ) : ?>
									<button type="button" class="tpg-hero-dot h-1 rounded-full transition-all duration-300<?php echo 0 === $i ? ' w-4 bg-tpg-green' : ' w-1 bg-tpg-paper/40'; ?>" data-index="<?php echo (int) $i; ?>" aria-label="<?php echo esc_attr( sprintf( 'Slide %d', $i + 1 ) ); ?>"></button>
								<?php endforeach; ?>
							</div>
						</div>
						<!-- Caption outside the slider image -->
						<a href="<?php echo esc_url( $slides[0]['url'] ); ?>" class="tpg-hero-caption mt-3 flex items-center justify-between gap-3 group">
							<span class="tpg-hero-caption-title min-w-0 font-display text-sm font-semibold text-tpg-paper leading-snug line-clamp-1 group-hover:text-tpg-green transition-colors"><?php echo esc_html( $slides[0]['title'] ); ?></span>
							<span class="shrink-0 inline-flex items-center gap-1 text-xs font-semibold text-tpg-green whitespace-nowrap">Read more
								<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
							</span>
						</a>
					</div>
				<?php else : ?>
					<?php tpg_image_or_placeholder( $hero_img, 'large', 'w-full h-full object-cover' ); ?>
				<?php endif; ?>
			</div>
			<div class="absolute -bottom-5 -left-5 card px-5 py-4 hidden sm:flex items-center gap-3">
				<span class="text-2xl font-display font-bold text-tpg-green">195+</span>
				<span class="text-xs text-tpg-muted leading-tight">laptops in stock<br>ready to ship</span>
			</div>
		</div>
	</div>
</section>
