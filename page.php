<?php if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
while ( have_posts() ) : the_post(); ?>
<article class="tpg-container py-14 max-w-3xl">
	<header class="mb-8"><h1 class="section-title"><?php the_title(); ?></h1></header>
	<div class="tpg-prose max-w-none text-tpg-paper/90"><?php the_content(); ?></div>
</article>
<?php endwhile; get_footer();
