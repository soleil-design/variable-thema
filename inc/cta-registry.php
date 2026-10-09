<?php
/**
 * Generic call-to-action registry and compatibility APIs.
 *
 * @package Variable_Thema
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Return the pre-provider CTA definitions for backward compatibility.
 *
 * This fallback intentionally remains available for parent-only and legacy
 * child-theme installations. Site-specific providers should use the
 * variable_thema_cta_registry_provider filter instead.
 *
 * @return array
 */
function variable_thema_get_legacy_cta_registry() {
	return array();
}

/**
 * Return the CTA registry supplied by the active site layer.
 *
 * The established variable_cta_registry filter always runs last so existing
 * integrations can continue to alter the final registry.
 *
 * @return array
 */
function variable_get_cta_registry() {
	$provider = apply_filters( 'variable_thema_cta_registry_provider', null );
	$registry = is_callable( $provider ) ? call_user_func( $provider ) : null;

	if ( ! is_array( $registry ) ) {
		$registry = variable_thema_get_legacy_cta_registry();
	}

	return apply_filters( 'variable_cta_registry', $registry );
}

/**
 * Get normalized arguments for a CTA registry entry.
 *
 * @since 0.8.0
 * @param string $slug CTA registry slug.
 * @return array
 */
function variable_get_cta_args( $slug = 'default' ) {
	$registry = variable_get_cta_registry();
	$slug     = is_scalar( $slug ) ? (string) $slug : 'default';

	if ( ! is_array( $registry ) ) {
		$registry = array();
	}

	if ( isset( $registry[ $slug ] ) && is_array( $registry[ $slug ] ) ) {
		return wp_parse_args(
			$registry[ $slug ],
			array(
				'title'       => '',
				'description' => '',
				'buttons'     => array(),
			)
		);
	}

	if ( isset( $registry['default'] ) && is_array( $registry['default'] ) ) {
		return wp_parse_args(
			$registry['default'],
			array(
				'title'       => '',
				'description' => '',
				'buttons'     => array(),
			)
		);
	}

	return array(
		'title'       => '',
		'description' => '',
		'buttons'     => array(),
	);
}

/**
 * Get SVG markup for a registered CTA icon.
 *
 * @since 0.8.0
 * @param string $icon Icon identifier.
 * @return string
 */
function variable_get_cta_icon_svg( $icon ) {
	$icon = is_scalar( $icon ) ? (string) $icon : '';

	$icons = array(
		'mail' => '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M3.75 5h16.5A1.75 1.75 0 0 1 22 6.75v10.5A1.75 1.75 0 0 1 20.25 19H3.75A1.75 1.75 0 0 1 2 17.25V6.75A1.75 1.75 0 0 1 3.75 5Zm.75 2.6v8.9h15V7.6l-7.03 5.27a.78.78 0 0 1-.94 0L4.5 7.6Zm1.6-1.1 5.9 4.43 5.9-4.43H6.1Z"/></svg>',
		'grid' => '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M4 4h6.5v6.5H4V4Zm9.5 0H20v6.5h-6.5V4ZM4 13.5h6.5V20H4v-6.5Zm9.5 0H20V20h-6.5v-6.5ZM5.6 5.6v3.3h3.3V5.6H5.6Zm9.5 0v3.3h3.3V5.6h-3.3Zm-9.5 9.5v3.3h3.3v-3.3H5.6Zm9.5 0v3.3h3.3v-3.3h-3.3Z"/></svg>',
		'scan' => '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M4.5 4h4v1.7H5.7v2.8H4V5.7C4 4.76 4.76 4 5.7 4Zm11 0h2.8c.94 0 1.7.76 1.7 1.7v2.8h-1.7V5.7h-2.8V4ZM4 15.5h1.7v2.8h2.8V20H5.7A1.7 1.7 0 0 1 4 18.3v-2.8Zm14.3 0H20v2.8c0 .94-.76 1.7-1.7 1.7h-2.8v-1.7h2.8v-2.8ZM7 11.15h10v1.7H7v-1.7Zm2.2-3.2h5.6v1.7H9.2v-1.7Zm0 6.4h5.6v1.7H9.2v-1.7Z"/></svg>',
	);

	return isset( $icons[ $icon ] ) ? $icons[ $icon ] : '';
}
