<?php if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
while ( have_posts() ) : the_post(); ?>
<article class="tpg-container py-14 max-w-3xl">
	<header class="mb-8">
		<span class="font-mono text-xs uppercase tracking-widest text-tpg-green"><?php echo esc_html( get_the_date() ); ?></span>
		<h1 class="section-title mt-3"><?php the_title(); ?></h1>
	</header>
	<?php if ( has_post_thumbnail() ) : ?><div class="card overflow-hidden mb-8"><?php the_post_thumbnail( 'large', array( 'class' => 'w-full' ) ); ?></div><?php endif; ?>
	<div class="tpg-prose max-w-none text-tpg-paper/90"><?php the_content(); ?></div>
</article>
<?php endwhile; get_footer();
