<?php if ( ! defined( 'ABSPATH' ) ) { exit; }
$brands = array( 'HP EliteBook', 'Dell Latitude', 'Lenovo ThinkPad', 'Intel Core i5 / i7', 'Windows 11 Pro', 'NVMe SSD', 'Backlit Keyboard', 'Thunderbolt' );
?>
<div class="border-b border-tpg-line bg-tpg-ink overflow-hidden">
	<div class="tpg-container py-4 flex flex-wrap items-center justify-center gap-x-6 gap-y-2 text-xs font-mono uppercase tracking-widest text-tpg-muted">
		<?php foreach ( $brands as $i => $b ) : ?>
			<span class="<?php echo $i % 2 ? 'text-tpg-green/80' : ''; ?>"><?php echo esc_html( $b ); ?></span>
			<?php if ( $i < count( $brands ) - 1 ) : ?><span class="text-tpg-line">•</span><?php endif; ?>
		<?php endforeach; ?>
	</div>
</div>
