<?php
/**
 * TechPlug GH one-click site setup (Tools > TechPlug GH Setup).
 *
 * Creates categories, pages, the primary menu, WooCommerce settings,
 * all products, and uploads images into the Media Library (products,
 * category tiles, hero and deals banners). Everything it creates is
 * normal WordPress content: editable and replaceable in the dashboard.
 * Safe to run repeatedly: existing items are skipped, not duplicated.
 *
 * @package TechPlugGH
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

add_action( 'admin_menu', function () {
	add_management_page(
		__( 'TechPlug GH Setup', 'techpluggh' ),
		__( 'TechPlug GH Setup', 'techpluggh' ),
		'manage_options',
		'tpg-setup',
		'tpg_setup_page'
	);
} );

function tpg_setup_page() {
	$report = get_transient( 'tpg_setup_report' );
	?>
	<div class="wrap">
		<h1>TechPlug GH Setup</h1>
		<p>One click creates the full store: product categories (with tile images), all pages (policies, About, Contact, Deals, How to Order), the primary menu, GHS currency, WooCommerce pages, all 31 products with stock and prices, branded product images in the Media Library, and the hero and deals banners. Re-running is safe: existing items are skipped.</p>
		<p><em>All images land in Media Library / Customizer / category settings, so you can replace any of them later.</em></p>
		<?php if ( $report ) : ?>
			<div class="notice notice-success"><p><strong>Setup finished.</strong></p><pre style="white-space:pre-wrap"><?php echo esc_html( $report ); ?></pre></div>
			<?php delete_transient( 'tpg_setup_report' ); ?>
		<?php endif; ?>
		<?php if ( ! class_exists( 'WooCommerce' ) ) : ?>
			<div class="notice notice-error"><p>WooCommerce is not active. Install and activate WooCommerce first, then run setup.</p></div>
		<?php else : ?>
			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
				<input type="hidden" name="action" value="tpg_run_setup">
				<?php wp_nonce_field( 'tpg_run_setup' ); ?>
				<p><button type="submit" class="button button-primary button-hero">Run Full Setup</button></p>
			</form>
			<hr>
			<h2>Product photos from the web</h2>
			<p>Searches openly licensed photos (CC0 / CC-BY via Openverse) for each laptop model, uploads the best match into the Media Library with attribution, and sets it as the product image. The branded card moves into the product gallery. Runs in batches of 8; click again until all products are processed. Models without a suitable openly licensed photo keep their branded card. You can replace any image per product afterwards.</p>
			<?php $off = (int) get_option( 'tpg_img_offset', 0 ); $tot = class_exists( 'WooCommerce' ) ? (int) ( wp_count_posts( 'product' )->publish ?? 0 ) : 0; ?>
			<p><em>Progress: <?php echo esc_html( $off ); ?> of <?php echo esc_html( $tot ); ?> products scanned this pass.</em></p>
			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
				<input type="hidden" name="action" value="tpg_fetch_images">
				<?php wp_nonce_field( 'tpg_fetch_images' ); ?>
				<p><button type="submit" class="button button-secondary button-hero">Fetch web images (next batch)</button></p>
			</form>
		<?php endif; ?>
	</div>
	<?php
}

add_action( 'admin_post_tpg_run_setup', 'tpg_run_setup' );
function tpg_run_setup() {
	if ( ! current_user_can( 'manage_options' ) ) { wp_die( 'Insufficient permissions.' ); }
	check_admin_referer( 'tpg_run_setup' );
	if ( ! class_exists( 'WooCommerce' ) ) { wp_die( 'WooCommerce must be active.' ); }

	@set_time_limit( 600 );
	if ( function_exists( 'wp_raise_memory_limit' ) ) { wp_raise_memory_limit( 'admin' ); }

	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	$log = array();

	/* 1. Currency + WooCommerce pages */
	if ( 'GHS' !== get_option( 'woocommerce_currency' ) ) {
		update_option( 'woocommerce_currency', 'GHS' );
		$log[] = 'Currency set to GHS.';
	}
	if ( function_exists( 'wc_get_page_id' ) && wc_get_page_id( 'shop' ) < 1 && class_exists( 'WC_Install' ) ) {
		WC_Install::create_pages();
		$log[] = 'WooCommerce core pages created (Shop, Cart, Checkout, My Account).';
	}

	/* 2. Product categories with tile images */
	$cats    = tpg_setup_data( 'categories' );
	$cat_ids = array();
	$made    = 0;
	foreach ( $cats as $c ) {
		$term = term_exists( $c['slug'], 'product_cat' );
		if ( ! $term ) {
			$term = wp_insert_term( $c['name'], 'product_cat', array( 'slug' => $c['slug'], 'description' => $c['desc'] ) );
			$made++;
		}
		if ( is_wp_error( $term ) ) { continue; }
		$tid = (int) ( is_array( $term ) ? $term['term_id'] : $term );
		$cat_ids[ $c['name'] ] = $tid;
		if ( ! get_term_meta( $tid, 'thumbnail_id', true ) ) {
			$att = tpg_setup_attach_image( $c['image'], $c['name'] );
			if ( $att ) { update_term_meta( $tid, 'thumbnail_id', $att ); }
		}
	}
	$log[] = sprintf( 'Categories: %d created, %d total (tile images set, editable under Products > Categories).', $made, count( $cat_ids ) );

	/* 3. Pages */
	$pages = tpg_setup_data( 'pages' );
	$made  = 0;
	foreach ( $pages as $p ) {
		if ( get_page_by_path( $p['slug'] ) ) { continue; }
		$id = wp_insert_post( array(
			'post_type'    => 'page',
			'post_status'  => 'publish',
			'post_title'   => $p['title'],
			'post_name'    => $p['slug'],
			'post_content' => $p['content'],
		) );
		if ( $id && ! is_wp_error( $id ) ) {
			if ( ! empty( $p['template'] ) ) { update_post_meta( $id, '_wp_page_template', $p['template'] ); }
			$made++;
		}
	}
	$log[] = sprintf( 'Pages: %d created (policies, About, Contact, Deals, How to Order).', $made );

	/* 4. Primary menu */
	if ( ! wp_get_nav_menu_object( 'Primary' ) ) {
		$menu_id = wp_create_nav_menu( 'Primary' );
		if ( ! is_wp_error( $menu_id ) ) {
			$items = array(
				'Home'         => home_url( '/' ),
				'Shop'         => function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' ),
				'Deals'        => home_url( '/deals/' ),
				'How to Order' => home_url( '/how-to-order/' ),
				'About'        => home_url( '/about/' ),
				'Contact'      => home_url( '/contact/' ),
			);
			foreach ( $items as $label => $url ) {
				wp_update_nav_menu_item( $menu_id, 0, array(
					'menu-item-title'  => $label,
					'menu-item-url'    => $url,
					'menu-item-type'   => 'custom',
					'menu-item-status' => 'publish',
				) );
			}
			$locations            = (array) get_theme_mod( 'nav_menu_locations' );
			$locations['primary'] = $menu_id;
			set_theme_mod( 'nav_menu_locations', $locations );
			$log[] = 'Primary menu created and assigned (edit under Appearance > Menus).';
		}
	} else {
		$log[] = 'Primary menu already exists, skipped.';
	}

	/* 5. Products with images */
	$products = tpg_setup_data( 'products' );
	$created  = 0;
	$skipped  = 0;
	foreach ( $products as $row ) {
		if ( wc_get_product_id_by_sku( $row['sku'] ) ) { $skipped++; continue; }
		$p = new WC_Product_Simple();
		$p->set_name( $row['name'] );
		$p->set_sku( $row['sku'] );
		$p->set_regular_price( (string) $row['price'] );
		$p->set_short_description( $row['short'] );
		$p->set_description( $row['desc'] );
		$p->set_manage_stock( true );
		$p->set_stock_quantity( (int) $row['stock'] );
		$p->set_stock_status( 'instock' );
		$p->set_weight( (string) $row['weight'] );
		$p->set_featured( ! empty( $row['featured'] ) );
		$p->set_catalog_visibility( 'visible' );
		$ids = array();
		foreach ( $row['cats'] as $cname ) {
			if ( isset( $cat_ids[ $cname ] ) ) { $ids[] = $cat_ids[ $cname ]; }
			else {
				$t = get_term_by( 'name', $cname, 'product_cat' );
				if ( $t ) { $ids[] = (int) $t->term_id; }
			}
		}
		$p->set_category_ids( $ids );
		$att = tpg_setup_attach_image( $row['image'], $row['name'] );
		if ( $att ) { $p->set_image_id( $att ); }
		$pid = $p->save();
		if ( $pid && ! empty( $row['tags'] ) ) {
			wp_set_object_terms( $pid, array_map( 'sanitize_text_field', $row['tags'] ), 'product_tag' );
		}
		$created++;
	}
	$log[] = sprintf( 'Products: %d created, %d already existed (images in Media Library, swap per product any time).', $created, $skipped );

	/* 6. Hero + deals banners via Customizer (editable in Appearance > Customize) */
	if ( ! get_theme_mod( 'tpg_hero_image' ) ) {
		$att = tpg_setup_attach_image( 'banners/hero.jpg', 'TechPlug GH hero' );
		if ( $att ) { set_theme_mod( 'tpg_hero_image', $att ); $log[] = 'Hero image set (Customize > Homepage Hero).'; }
	}
	if ( ! get_theme_mod( 'tpg_promo_image' ) ) {
		$att = tpg_setup_attach_image( 'banners/deals.jpg', 'TechPlug GH deals' );
		if ( $att ) { set_theme_mod( 'tpg_promo_image', $att ); $log[] = 'Deals image set (Customize > Promo Banner).'; }
	}

	/* 7. Permalinks */
	flush_rewrite_rules();
	$log[] = 'Rewrite rules flushed (fixes page/product 404s).';

	set_transient( 'tpg_setup_report', implode( "\n", $log ), 300 );
	wp_safe_redirect( admin_url( 'tools.php?page=tpg-setup' ) );
	exit;
}

/** Load a bundled JSON data file. */
function tpg_setup_data( $name ) {
	$file = get_template_directory() . '/inc/setup-data/' . $name . '.json';
	if ( ! file_exists( $file ) ) { return array(); }
	$data = json_decode( (string) file_get_contents( $file ), true );
	return is_array( $data ) ? $data : array();
}

/**
 * Upload a bundled setup image into the Media Library (deduplicated).
 * Returns attachment ID, or 0 on failure.
 */
function tpg_setup_attach_image( $rel, $title ) {
	$src = get_template_directory() . '/assets/setup/' . $rel;
	if ( ! file_exists( $src ) ) { return 0; }

	$existing = get_posts( array(
		'post_type'   => 'attachment',
		'numberposts' => 1,
		'fields'      => 'ids',
		'meta_key'    => '_tpg_setup_image',
		'meta_value'  => $rel,
	) );
	if ( $existing ) { return (int) $existing[0]; }

	$tmp = wp_tempnam( basename( $src ) );
	if ( ! $tmp || ! copy( $src, $tmp ) ) { return 0; }

	$file_array = array( 'name' => basename( $src ), 'tmp_name' => $tmp );
	$id         = media_handle_sideload( $file_array, 0, $title );
	if ( is_wp_error( $id ) ) {
		@unlink( $tmp );
		return 0;
	}
	update_post_meta( $id, '_tpg_setup_image', $rel );
	return (int) $id;
}

/* =========================================================
   Web product images (Openverse, CC0/CC-BY only)
   Fetched server-side into the Media Library with attribution,
   set as featured image; the branded card moves to the gallery.
   ========================================================= */

add_action( 'admin_post_tpg_fetch_images', 'tpg_fetch_images' );
function tpg_fetch_images() {
	if ( ! current_user_can( 'manage_options' ) ) { wp_die( 'Insufficient permissions.' ); }
	check_admin_referer( 'tpg_fetch_images' );
	if ( ! class_exists( 'WooCommerce' ) ) { wp_die( 'WooCommerce must be active.' ); }

	@set_time_limit( 300 );
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	$batch    = 8;
	$offset   = (int) get_option( 'tpg_img_offset', 0 );
	$products = wc_get_products( array(
		'limit'   => $batch,
		'offset'  => $offset,
		'orderby' => 'ID',
		'order'   => 'ASC',
		'status'  => 'publish',
	) );

	$log = array();
	foreach ( $products as $product ) {
		$pid  = $product->get_id();
		$name = $product->get_name();
		if ( get_post_meta( $pid, '_tpg_web_image', true ) ) {
			$log[] = $name . ': web image already set, skipped';
			continue;
		}
		$model = tpg_image_model( $name );
		$att   = tpg_fetch_openverse_image( $model );
		if ( $att ) {
			$old = (int) $product->get_image_id();
			if ( $old ) {
				$gallery   = $product->get_gallery_image_ids();
				$gallery[] = $old;
				$product->set_gallery_image_ids( array_values( array_unique( $gallery ) ) );
			}
			$product->set_image_id( $att );
			$product->save();
			update_post_meta( $pid, '_tpg_web_image', $att );
			$log[] = $name . ': image updated (branded card kept in gallery)';
		} else {
			$log[] = $name . ': no suitable openly licensed image found, branded card kept';
		}
	}

	$offset += count( $products );
	$counts  = wp_count_posts( 'product' );
	$total   = isset( $counts->publish ) ? (int) $counts->publish : 0;
	if ( $offset >= $total || empty( $products ) ) {
		$offset = 0;
		$log[]  = 'All products processed. The next run starts from the beginning (already-updated products are skipped).';
	} else {
		$log[] = sprintf( 'Processed up to product %d of %d. Run again for the next batch.', $offset, $total );
	}
	update_option( 'tpg_img_offset', $offset );

	set_transient( 'tpg_setup_report', implode( "\n", $log ), 300 );
	wp_safe_redirect( admin_url( 'tools.php?page=tpg-setup' ) );
	exit;
}

/** Model search phrase from a product name, e.g. "HP EliteBook 840 G7 laptop". */
function tpg_image_model( $name ) {
	$model = trim( explode( ' - ', $name )[0] );
	$model = preg_replace( '/\((Touch|Non-touch)\)/i', '', $model );
	return trim( preg_replace( '/\s+/', ' ', $model ) ) . ' laptop';
}

/**
 * Query Openverse for an openly licensed photo and sideload the best match.
 * Only commercial-safe licenses (CC0, CC-BY). Attribution is saved in the
 * attachment caption and description. Returns attachment ID or 0.
 */
function tpg_fetch_openverse_image( $query ) {
	$url = 'https://api.openverse.org/v1/images/?' . http_build_query( array(
		'q'         => $query,
		'license'   => 'cc0,by',
		'per_page'  => 10,
		'extension' => 'jpg,png',
	) );
	$res = wp_remote_get( $url, array(
		'timeout' => 25,
		'headers' => array( 'User-Agent' => 'TechPlugGH/1.0 (WordPress; +https://techpluggh.com)' ),
	) );
	if ( is_wp_error( $res ) || 200 !== wp_remote_retrieve_response_code( $res ) ) { return 0; }
	$data = json_decode( wp_remote_retrieve_body( $res ), true );
	if ( empty( $data['results'] ) || ! is_array( $data['results'] ) ) { return 0; }

	/* Prefer results whose title mentions the model number token. */
	$token   = '';
	if ( preg_match( '/\b([A-Z]?\d{3,4}[A-Za-z]?(?:\s?G\d)?|L1\d|T1\d|L390)\b/i', $query, $m ) ) { $token = strtolower( $m[1] ); }
	$results = $data['results'];
	usort( $results, function ( $a, $b ) use ( $token ) {
		$score = function ( $r ) use ( $token ) {
			$t = strtolower( (string) ( $r['title'] ?? '' ) );
			$s = 0;
			if ( $token && false !== strpos( $t, $token ) ) { $s += 2; }
			if ( false !== strpos( $t, 'laptop' ) || false !== strpos( $t, 'notebook' ) || false !== strpos( $t, 'thinkpad' ) || false !== strpos( $t, 'elitebook' ) || false !== strpos( $t, 'latitude' ) ) { $s += 1; }
			return $s;
		};
		return $score( $b ) <=> $score( $a );
	} );

	foreach ( $results as $r ) {
		if ( empty( $r['url'] ) || (int) ( $r['width'] ?? 0 ) < 500 ) { continue; }
		$tmp = download_url( $r['url'], 60 );
		if ( is_wp_error( $tmp ) ) { continue; }
		$ext  = strtolower( pathinfo( wp_parse_url( $r['url'], PHP_URL_PATH ), PATHINFO_EXTENSION ) );
		$ext  = in_array( $ext, array( 'jpg', 'jpeg', 'png' ), true ) ? $ext : 'jpg';
		$file = array(
			'name'     => sanitize_title( $query ) . '-' . wp_generate_password( 6, false, false ) . '.' . $ext,
			'tmp_name' => $tmp,
		);
		$att = media_handle_sideload( $file, 0, $query );
		if ( is_wp_error( $att ) ) {
			@unlink( $tmp );
			continue;
		}
		$credit = 'Photo: ' . ( $r['creator'] ?? 'unknown' ) . ' (' . strtoupper( (string) ( $r['license'] ?? '' ) ) . ' ' . ( $r['license_version'] ?? '' ) . '). Source: ' . ( $r['foreign_landing_url'] ?? $r['url'] );
		wp_update_post( array(
			'ID'           => $att,
			'post_excerpt' => $credit,
			'post_content' => $credit,
		) );
		update_post_meta( $att, '_tpg_image_source', esc_url_raw( (string) ( $r['foreign_landing_url'] ?? '' ) ) );
		return (int) $att;
	}
	return 0;
}
