<?php
/**
 * Theme setup and supported WordPress features.
 *
 * @package Variable_Thema
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register theme supports, image sizes, and menu locations.
 *
 * @since 0.8.0
 * @return void
 */
function variable_thema_setup() {
	load_theme_textdomain( 'variable-thema', get_template_directory() . '/languages' );

	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 80,
			'width'       => 240,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );

	register_nav_menus(
		array(
			'primary' => __( 'Primary Menu', 'variable-thema' ),
			'footer'  => __( 'Footer Menu', 'variable-thema' ),
		)
	);
}
add_action( 'after_setup_theme', 'variable_thema_setup' );
