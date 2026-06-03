<?php
/**
 * Customizer settings - every image/contact value is editable here.
 * Nothing media-related is hardcoded in templates.
 *
 * @package TechPlugGH
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

function tpg_customize_register( $wp_customize ) {

	/* ---------------------------------------------------------------------
	 * PANEL: TechPlug GH
	 * ------------------------------------------------------------------- */
	$wp_customize->add_panel( 'tpg_panel', array(
		'title'    => __( 'TechPlug GH Settings', 'techpluggh' ),
		'priority' => 20,
	) );

	/* ===== Section: Contact / Social ===== */
	$wp_customize->add_section( 'tpg_contact', array(
		'title' => __( 'Contact & Social', 'techpluggh' ),
		'panel' => 'tpg_panel',
	) );

	$contact_fields = array(
		'tpg_phone'      => array( 'label' => 'Phone number', 'default' => '' ),
		'tpg_whatsapp'   => array( 'label' => 'WhatsApp number (intl, e.g. 233XXXXXXXXX)', 'default' => '' ),
		'tpg_email'      => array( 'label' => 'Email', 'default' => 'info@techpluggh.com' ),
		'tpg_address'    => array( 'label' => 'Location text', 'default' => 'Accra, Ghana' ),
		'tpg_pickup'     => array( 'label' => 'Pickup point note', 'default' => 'Pickup available - meeting point in Accra (confirmed on order).' ),
		'tpg_fb'         => array( 'label' => 'Facebook URL', 'default' => '' ),
		'tpg_ig'         => array( 'label' => 'Instagram URL', 'default' => '' ),
		'tpg_tiktok'     => array( 'label' => 'TikTok URL', 'default' => '' ),
		'tpg_x'          => array( 'label' => 'X (Twitter) URL', 'default' => '' ),
	);
	foreach ( $contact_fields as $id => $f ) {
		$wp_customize->add_setting( $id, array( 'default' => $f['default'], 'sanitize_callback' => 'sanitize_text_field' ) );
		$wp_customize->add_control( $id, array( 'label' => $f['label'], 'section' => 'tpg_contact', 'type' => 'text' ) );
	}

	/* ===== Section: Hero ===== */
	$wp_customize->add_section( 'tpg_hero', array(
		'title' => __( 'Homepage Hero', 'techpluggh' ),
		'panel' => 'tpg_panel',
	) );

	$wp_customize->add_setting( 'tpg_hero_eyebrow', array( 'default' => 'Plug Into Quality', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'tpg_hero_eyebrow', array( 'label' => 'Eyebrow', 'section' => 'tpg_hero', 'type' => 'text' ) );

	$wp_customize->add_setting( 'tpg_hero_title', array( 'default' => 'Quality UK Used Laptops, Delivered Across Ghana.', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'tpg_hero_title', array( 'label' => 'Headline', 'section' => 'tpg_hero', 'type' => 'text' ) );

	$wp_customize->add_setting( 'tpg_hero_sub', array( 'default' => 'Tested, graded and warranty-backed business laptops for students, professionals and teams. Pay by MoMo, bank transfer or on delivery in Accra.', 'sanitize_callback' => 'sanitize_textarea_field' ) );
	$wp_customize->add_control( 'tpg_hero_sub', array( 'label' => 'Subtext', 'section' => 'tpg_hero', 'type' => 'textarea' ) );

	$wp_customize->add_setting( 'tpg_hero_image', array( 'sanitize_callback' => 'absint' ) );
	$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'tpg_hero_image', array(
		'label'     => __( 'Hero image (editable - leave empty for gradient only)', 'techpluggh' ),
		'section'   => 'tpg_hero',
		'mime_type' => 'image',
	) ) );

	/* ===== Section: Promo / Deals banner ===== */
	$wp_customize->add_section( 'tpg_promo', array(
		'title' => __( 'Promo Banner', 'techpluggh' ),
		'panel' => 'tpg_panel',
	) );

	$wp_customize->add_setting( 'tpg_promo_on', array( 'default' => true, 'sanitize_callback' => 'wp_validate_boolean' ) );
	$wp_customize->add_control( 'tpg_promo_on', array( 'label' => 'Show top promo bar', 'section' => 'tpg_promo', 'type' => 'checkbox' ) );

	$wp_customize->add_setting( 'tpg_promo_text', array( 'default' => 'Free delivery within Accra on orders above GHS 3,000 · Pay on delivery available', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'tpg_promo_text', array( 'label' => 'Promo bar text', 'section' => 'tpg_promo', 'type' => 'text' ) );

	$wp_customize->add_setting( 'tpg_promo_image', array( 'sanitize_callback' => 'absint' ) );
	$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'tpg_promo_image', array(
		'label'     => __( 'Deals section image (editable)', 'techpluggh' ),
		'section'   => 'tpg_promo',
		'mime_type' => 'image',
	) ) );
}
add_action( 'customize_register', 'tpg_customize_register' );
