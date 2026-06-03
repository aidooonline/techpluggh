<?php
/**
 * Front page (homepage).
 * @package TechPlugGH
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();

get_template_part( 'template-parts/home/hero' );
get_template_part( 'template-parts/home/marquee' );
get_template_part( 'template-parts/home/categories' );
get_template_part( 'template-parts/home/featured' );
get_template_part( 'template-parts/home/why' );
get_template_part( 'template-parts/home/deals' );
get_template_part( 'template-parts/home/how' );
get_template_part( 'template-parts/home/cta' );

get_footer();
