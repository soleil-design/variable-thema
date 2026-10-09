<?php
/**
 * Conditional helpers for theme presentation.
 *
 * @package Variable_Thema
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get the current post content for feature detection.
 *
 * @since 0.8.0
 * @param int $post_id Optional post ID.
 * @return string
 */
function variable_thema_get_post_content( $post_id = 0 ) {
	static $content_cache = array();

	$post_id = $post_id ? (int) $post_id : get_queried_object_id();

	if ( ! $post_id ) {
		return '';
	}

	if ( array_key_exists( $post_id, $content_cache ) ) {
		return $content_cache[ $post_id ];
	}

	$content = get_post_field( 'post_content', $post_id );

	$content_cache[ $post_id ] = is_string( $content ) ? $content : '';

	return $content_cache[ $post_id ];
}

/**
 * Determine whether post content contains a feature marker.
 *
 * @since 0.8.0
 * @param string $needle  Marker to find.
 * @param int    $post_id Optional post ID.
 * @return bool
 */
function variable_thema_post_content_contains( $needle, $post_id = 0 ) {
	if ( ! is_string( $needle ) || '' === $needle ) {
		return false;
	}

	$content = variable_thema_get_post_content( $post_id );

	return '' !== $content && false !== strpos( $content, $needle );
}

/**
 * Determine whether the current post needs syntax highlighting.
 *
 * @since 0.8.0
 * @param int $post_id Optional post ID.
 * @return bool
 */
function variable_thema_should_load_prism( $post_id = 0 ) {
	if ( ! is_singular() ) {
		return false;
	}

	$post_id = $post_id ? (int) $post_id : get_queried_object_id();

	if ( ! $post_id ) {
		return false;
	}

	if ( has_block( 'core/code', $post_id ) || has_block( 'core/preformatted', $post_id ) ) {
		return true;
	}

	return variable_thema_post_content_contains( 'language-', $post_id )
		|| variable_thema_post_content_contains( '<pre><code', $post_id );
}

/**
 * Evaluate the historical Soleil SWD conditions.
 *
 * Retained as an unannounced compatibility fallback for installations whose
 * child theme does not provide the site-specific condition yet.
 *
 * @param int $post_id Optional post ID.
 * @return bool
 */
function variable_thema_legacy_is_swd_page( $post_id = 0 ) {
	unset( $post_id );

	return false;
}

/**
 * Determine whether the current page belongs to a site's SWD experience.
 *
 * @param int $post_id Optional post ID.
 * @return bool
 */
function variable_thema_is_swd_page( $post_id = 0 ) {
	$provider = apply_filters( 'variable_thema_swd_page_provider', null );

	if ( is_callable( $provider ) ) {
		return (bool) call_user_func( $provider, $post_id );
	}

	return variable_thema_legacy_is_swd_page( $post_id );
}

/**
 * Determine whether a post uses a specified page template.
 *
 * @since 0.8.0
 * @param string $template Template filename.
 * @param int    $post_id Optional post ID.
 * @return bool
 */
function variable_thema_is_template( $template, $post_id = 0 ) {
	if ( ! is_string( $template ) || '' === $template ) {
		return false;
	}

	$post_id = $post_id ? (int) $post_id : get_queried_object_id();

	if ( ! $post_id ) {
		return false;
	}

	return is_page_template( $template ) || get_page_template_slug( $post_id ) === $template;
}

/**
 * Filter whether postcode-address markup is present.
 *
 * @since 0.8.0
 * @param int $post_id Optional post ID.
 * @return bool
 */
function variable_thema_has_postcode_address_markup( $post_id = 0 ) {
	return (bool) apply_filters( 'variable_thema_has_postcode_address_markup', false, $post_id );
}
