<?php
/**
 * Footer.
 * @package TechPlugGH
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
$shop_url = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop' );
?>
</main>

<footer class="border-t border-tpg-line bg-tpg-ink mt-20">
	<div class="tpg-container py-14 grid gap-10 md:grid-cols-2 lg:grid-cols-4">

		<div>
			<?php tpg_logo(); ?>
			<p class="mt-4 text-sm text-tpg-muted max-w-xs leading-relaxed">
				<?php esc_html_e( 'Quality UK used laptops for students, professionals and businesses across Ghana. Tested, graded and warranty-backed.', 'techpluggh' ); ?>
			</p>
			<div class="flex gap-3 mt-5">
				<?php
				$socials = array(
					'tpg_fb'     => 'Facebook',
					'tpg_ig'     => 'Instagram',
					'tpg_tiktok' => 'TikTok',
					'tpg_x'      => 'X',
				);
				foreach ( $socials as $key => $name ) {
					$url = tpg_opt( $key );
					if ( $url ) {
						printf( '<a href="%s" target="_blank" rel="noopener" class="chip hover:border-tpg-green hover:text-tpg-green">%s</a>', esc_url( $url ), esc_html( $name ) );
					}
				}
				?>
			</div>
		</div>

		<div>
			<h4 class="text-sm font-display font-semibold text-tpg-paper mb-4"><?php esc_html_e( 'Shop', 'techpluggh' ); ?></h4>
			<ul class="space-y-2.5 text-sm text-tpg-muted">
				<li><a class="hover:text-tpg-green" href="<?php echo esc_url( $shop_url ); ?>"><?php esc_html_e( 'All laptops', 'techpluggh' ); ?></a></li>
				<?php
				if ( taxonomy_exists( 'product_cat' ) ) {
					$cats = get_terms( array( 'taxonomy' => 'product_cat', 'hide_empty' => true, 'number' => 6 ) );
					if ( ! is_wp_error( $cats ) ) {
						foreach ( $cats as $cat ) {
							printf( '<li><a class="hover:text-tpg-green" href="%s">%s</a></li>', esc_url( get_term_link( $cat ) ), esc_html( $cat->name ) );
						}
					}
				}
				?>
				<li><a class="hover:text-tpg-green" href="<?php echo esc_url( home_url( '/deals' ) ); ?>"><?php esc_html_e( 'Deals & Offers', 'techpluggh' ); ?></a></li>
			</ul>
		</div>

		<div>
			<h4 class="text-sm font-display font-semibold text-tpg-paper mb-4"><?php esc_html_e( 'Help', 'techpluggh' ); ?></h4>
			<ul class="space-y-2.5 text-sm text-tpg-muted">
				<li><a class="hover:text-tpg-green" href="<?php echo esc_url( home_url( '/how-to-order' ) ); ?>"><?php esc_html_e( 'How to Order', 'techpluggh' ); ?></a></li>
				<li><a class="hover:text-tpg-green" href="<?php echo esc_url( home_url( '/warranty-policy' ) ); ?>"><?php esc_html_e( 'Warranty Policy', 'techpluggh' ); ?></a></li>
				<li><a class="hover:text-tpg-green" href="<?php echo esc_url( home_url( '/return-policy' ) ); ?>"><?php esc_html_e( 'Return Policy', 'techpluggh' ); ?></a></li>
				<li><a class="hover:text-tpg-green" href="<?php echo esc_url( home_url( '/delivery-policy' ) ); ?>"><?php esc_html_e( 'Delivery Policy', 'techpluggh' ); ?></a></li>
				<li><a class="hover:text-tpg-green" href="<?php echo esc_url( home_url( '/privacy-policy' ) ); ?>"><?php esc_html_e( 'Privacy Policy', 'techpluggh' ); ?></a></li>
				<li><a class="hover:text-tpg-green" href="<?php echo esc_url( home_url( '/terms' ) ); ?>"><?php esc_html_e( 'Terms & Conditions', 'techpluggh' ); ?></a></li>
			</ul>
		</div>

		<div>
			<h4 class="text-sm font-display font-semibold text-tpg-paper mb-4"><?php esc_html_e( 'Contact', 'techpluggh' ); ?></h4>
			<ul class="space-y-2.5 text-sm text-tpg-muted">
				<?php if ( tpg_opt( 'tpg_phone' ) ) : ?><li>📞 <a class="hover:text-tpg-green" href="tel:<?php echo esc_attr( tpg_opt( 'tpg_phone' ) ); ?>"><?php echo esc_html( tpg_opt( 'tpg_phone' ) ); ?></a></li><?php endif; ?>
				<?php if ( tpg_wa_number() ) : ?><li>💬 <a class="hover:text-tpg-green" target="_blank" rel="noopener" href="<?php echo tpg_wa_link( __( 'Hi TechPlug GH!', 'techpluggh' ) ); ?>"><?php esc_html_e( 'WhatsApp us', 'techpluggh' ); ?></a></li><?php endif; ?>
				<?php if ( tpg_opt( 'tpg_email' ) ) : ?><li>✉️ <a class="hover:text-tpg-green" href="mailto:<?php echo esc_attr( tpg_opt( 'tpg_email' ) ); ?>"><?php echo esc_html( tpg_opt( 'tpg_email' ) ); ?></a></li><?php endif; ?>
				<?php if ( tpg_opt( 'tpg_address' ) ) : ?><li>📍 <?php echo esc_html( tpg_opt( 'tpg_address' ) ); ?></li><?php endif; ?>
			</ul>
			<div class="flex flex-wrap gap-2 mt-5">
				<span class="chip">MoMo</span>
				<span class="chip">Bank Transfer</span>
				<span class="chip">Pay on Delivery</span>
			</div>
		</div>
	</div>

	<div class="border-t border-tpg-line">
		<div class="tpg-container py-5 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-tpg-muted">
			<p>&copy; <?php echo esc_html( date( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>. <?php esc_html_e( 'Plug Into Quality.', 'techpluggh' ); ?></p>
			<p class="font-mono uppercase tracking-widest"><?php esc_html_e( 'Accra · Tema · Nationwide courier', 'techpluggh' ); ?></p>
		</div>
	</div>
</footer>

<?php if ( tpg_wa_number() ) : ?>
<a href="<?php echo tpg_wa_link( __( 'Hi TechPlug GH, I have a question.', 'techpluggh' ) ); ?>" target="_blank" rel="noopener"
	class="fixed bottom-5 right-5 z-40 btn-wa shadow-lg rounded-full w-14 h-14 !p-0 grid place-items-center" aria-label="WhatsApp">
	<svg width="26" height="26" viewBox="0 0 24 24" fill="currentColor"><path d="M.057 24l1.687-6.163a11.867 11.867 0 01-1.587-5.946C.16 5.335 5.495 0 12.05 0a11.82 11.82 0 018.413 3.488 11.82 11.82 0 013.48 8.414c-.003 6.557-5.338 11.892-11.893 11.892a11.9 11.9 0 01-5.688-1.448L.057 24zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884a9.86 9.86 0 001.51 5.26l-.999 3.648 3.978-1.045z"/></svg>
</a>
<?php endif; ?>

<?php wp_footer(); ?>
</body>
</html>
