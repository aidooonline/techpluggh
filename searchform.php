<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<form role="search" method="get" class="flex gap-2" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<input type="search" class="input" name="s" placeholder="<?php esc_attr_e( 'Search laptops...', 'techpluggh' ); ?>" value="<?php echo get_search_query(); ?>">
	<button type="submit" class="btn-primary px-5"><?php esc_html_e( 'Search', 'techpluggh' ); ?></button>
</form>
