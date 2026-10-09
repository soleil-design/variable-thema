<?php
/**
 * Theme bootstrap and shared hooks.
 *
 * @package Variable_Thema
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once get_template_directory() . '/inc/setup.php';
require_once get_template_directory() . '/inc/template-tags.php';
require_once get_template_directory() . '/inc/conditionals.php';
require_once get_template_directory() . '/inc/customizer.php';
require_once get_template_directory() . '/inc/enqueue.php';
require_once get_template_directory() . '/inc/block-patterns.php';
require_once get_template_directory() . '/inc/post-types/faq.php';
require_once get_template_directory() . '/inc/taxonomies/faq-category.php';
require_once get_template_directory() . '/inc/parts-registry.php';
require_once get_template_directory() . '/inc/parts-activation.php';
require_once get_template_directory() . '/inc/cta-registry.php';
require_once get_template_directory() . '/inc/shortcodes.php';
require_once get_template_directory() . '/inc/admin-guide.php';
require_once get_template_directory() . '/inc/admin-parts.php';
require_once get_template_directory() . '/inc/site-profile.php';

/**
 * Apply the theme's custom labels to supported post type archives.
 *
 * @param string $title Archive title.
 * @return string
 */
function variable_thema_filter_archive_title( $title ) {
	if ( is_post_type_archive( 'faq' ) ) {
		return __( 'Frequently Asked Questions', 'variable-thema' );
	}

	if ( is_post_type_archive( 'service' ) ) {
		return __( 'Services', 'variable-thema' );
	}

	return $title;
}
add_filter( 'get_the_archive_title', 'variable_thema_filter_archive_title' );

/**
 * Remove the standard archive prefix while retaining the standard title API.
 *
 * @return string
 */
function variable_thema_filter_archive_title_prefix() {
	return '';
}
add_filter( 'get_the_archive_title_prefix', 'variable_thema_filter_archive_title_prefix' );
