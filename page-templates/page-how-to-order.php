<?php
/**
 * Template Name: How to Order
 * @package TechPlugGH
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
$steps = array(
	array( '01', 'Browse and choose', 'Explore graded laptops by brand, budget or use case. Each listing shows full specs, condition grade and price in GHS.' ),
	array( '02', 'Add to cart or WhatsApp', 'Add items to your cart and check out on the site, or tap "Order on WhatsApp" on any product to order directly.' ),
	array( '03', 'Confirm details', 'We confirm availability, your delivery zone (Accra, Tema or nationwide) and the total including delivery fee.' ),
	array( '04', 'Make payment', 'Pay by Mobile Money, bank transfer, or pay on delivery within Accra. First-time customers pay before dispatch.' ),
	array( '05', 'Delivery or pickup', 'Same/next-day in Accra and Tema, 2 to 4 working days nationwide. Or pick up at our Accra meeting point.' ),
);
?>
<section class="bg-grid-faint bg-grid border-b border-tpg-line">
	<div class="tpg-container py-16 text-center">
		<span class="eyebrow">Simple &amp; secure</span>
		<h1 class="section-title mt-3">How to order from TechPlug GH</h1>
		<p class="text-tpg-muted mt-4 max-w-xl mx-auto">From browsing to delivery in five easy steps.</p>
	</div>
</section>
<div class="tpg-container py-16 max-w-3xl">
	<ol class="space-y-4">
		<?php foreach ( $steps as $s ) : ?>
			<li class="card p-6 flex gap-5">
				<span class="font-mono text-tpg-green text-2xl font-bold shrink-0"><?php echo esc_html( $s[0] ); ?></span>
				<div>
					<h3 class="font-display font-semibold text-tpg-paper"><?php echo esc_html( $s[1] ); ?></h3>
					<p class="text-sm text-tpg-muted mt-1.5 leading-relaxed"><?php echo esc_html( $s[2] ); ?></p>
				</div>
			</li>
		<?php endforeach; ?>
	</ol>
	<div class="card p-6 mt-8">
		<?php while ( have_posts() ) : the_post(); ?>
			<div class="tpg-prose max-w-none text-tpg-paper/90"><?php the_content(); ?></div>
		<?php endwhile; ?>
	</div>
	<div class="mt-8 flex flex-wrap gap-3">
		<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="btn-primary">Start shopping</a>
		<?php if ( tpg_wa_number() ) : ?><a href="<?php echo tpg_wa_link( 'Hi TechPlug GH, I would like to place an order.' ); ?>" target="_blank" rel="noopener" class="btn-wa">Order on WhatsApp</a><?php endif; ?>
	</div>
</div>
<?php get_footer();
