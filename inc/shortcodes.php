<?php
/**
 * Theme shortcode compatibility and presentation bridges.
 *
 * @package Variable_Thema
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Normalize a boolean-like shortcode value.
 *
 * @param mixed $value Shortcode value.
 * @return bool
 */
function variable_thema_shortcode_truthy( $value ) {
	return in_array(
		strtolower( (string) $value ),
		array( '1', 'true', 'yes', 'on' ),
		true
	);
}

/**
 * Legacy compatibility wrapper for the Core-owned FAQ shortcode.
 *
 * @deprecated 0.3.0 Use Soleil_Content_Core_FAQ::render_shortcode().
 * @param array<string,mixed>|string $atts Shortcode attributes.
 * @return string
 */
function variable_thema_render_faq_list_shortcode( $atts ) {
	_deprecated_function( __FUNCTION__, '0.3.0', 'Soleil_Content_Core_FAQ::render_shortcode' );

	if ( class_exists( 'Soleil_Content_Core_FAQ' ) ) {
		return Soleil_Content_Core_FAQ::render_shortcode( $atts );
	}

	return '';
}

/**
 * Render the latest posts shortcode through the theme presentation layer.
 *
 * @param array<string,mixed>|string $atts Shortcode attributes.
 * @return string
 */
function variable_thema_render_latest_posts_shortcode( $atts ) {
	if ( class_exists( 'Soleil_Content_Core_Latest_Posts' ) ) {
		return Soleil_Content_Core_Latest_Posts::render_shortcode( $atts );
	}

	$atts = shortcode_atts(
		array(
			'post_types'     => '',
			'posts_per_page' => 3,
			'show_title'     => 'true',
			'title'          => __( 'News and Blog', 'variable-thema' ),
			'use_excerpt'    => 'true',
			'archive_link'   => 'true',
			'archive_label'  => __( 'View all news and blog posts', 'variable-thema' ),
			'tabs'           => '',
		),
		(array) $atts,
		'variable_latest_posts'
	);

	$latest_args = array(
		'post_types'     => is_scalar( $atts['post_types'] ) ? (string) $atts['post_types'] : '',
		'posts_per_page' => (int) $atts['posts_per_page'],
		'show_title'     => variable_thema_shortcode_truthy( $atts['show_title'] ),
		'title'          => is_scalar( $atts['title'] ) ? (string) $atts['title'] : '',
		'use_excerpt'    => variable_thema_shortcode_truthy( $atts['use_excerpt'] ),
		'archive_link'   => variable_thema_shortcode_truthy( $atts['archive_link'] ),
		'archive_label'  => is_scalar( $atts['archive_label'] ) ? (string) $atts['archive_label'] : __( 'View all news and blog posts', 'variable-thema' ),
		'tabs'           => is_scalar( $atts['tabs'] ) ? (string) $atts['tabs'] : '',
	);

	return variable_thema_render_latest_posts( $latest_args );
}

/**
 * Render normalized Latest Posts arguments with the theme template.
 *
 * @param array<string,mixed> $latest_args Normalized template arguments.
 * @return string
 */
function variable_thema_render_latest_posts( $latest_args ) {
	ob_start();
	get_template_part( 'template-parts/latest-posts', null, $latest_args );

	return (string) ob_get_clean();
}

/**
 * Supply the theme presentation callback to Content Core.
 *
 * @return string
 */
function variable_thema_latest_posts_renderer_provider() {
	return 'variable_thema_render_latest_posts';
}
add_filter( 'soleil_content_core_latest_posts_renderer', 'variable_thema_latest_posts_renderer_provider' );
