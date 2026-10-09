<?php
/**
 * Theme parts activation compatibility APIs.
 *
 * @package Variable_Thema
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get stored legacy part activation settings.
 *
 * @since 0.8.0
 * @return array
 */
function variable_thema_get_parts_activation_settings() {
	$settings = get_option( 'variable_thema_parts_activation', array() );

	return is_array( $settings ) ? $settings : array();
}

/**
 * Get registry entries that expose a legacy toggle key.
 *
 * @since 0.8.0
 * @return array
 */
function variable_thema_get_toggleable_parts() {
	return array_values(
		array_filter(
			variable_thema_get_parts_registry(),
			function ( $part ) {
				return ! empty( $part['future_toggle_key'] );
			}
		)
	);
}

/**
 * Sanitize legacy part activation settings.
 *
 * @since 0.8.0
 * @param mixed $settings Submitted settings.
 * @return array
 */
function variable_thema_sanitize_parts_activation_settings( $settings ) {
	$settings         = is_array( $settings ) ? $settings : array();
	$sanitized        = array();
	$toggleable_parts = variable_thema_get_toggleable_parts();
	$enabled_part_ids = array();

	if ( isset( $settings['enabled_parts'] ) && is_array( $settings['enabled_parts'] ) ) {
		$enabled_part_ids = array_map( 'sanitize_key', wp_unslash( $settings['enabled_parts'] ) );
	}

	foreach ( $toggleable_parts as $part ) {
		if ( empty( $part['id'] ) ) {
			continue;
		}

		$part_id               = (string) $part['id'];
		$sanitized[ $part_id ] = in_array( $part_id, $enabled_part_ids, true );
	}

	return $sanitized;
}

/**
 * Retain the disabled legacy settings update API.
 *
 * @since 0.8.0
 * @param mixed $settings Unused settings value.
 * @return false
 */
function variable_thema_update_parts_activation_settings( $settings ) {
	unset( $settings );

	// Deprecated: legacy values remain readable, but the theme no longer saves them.
	return false;
}

/**
 * Get one theme part registry entry.
 *
 * @since 0.8.0
 * @param string $part_id Part identifier.
 * @return array|null
 */
function variable_thema_get_part_registry_item( $part_id ) {
	if ( ! is_string( $part_id ) || '' === $part_id ) {
		return null;
	}

	foreach ( variable_thema_get_parts_registry() as $part ) {
		if ( isset( $part['id'] ) && $part_id === $part['id'] ) {
			return $part;
		}
	}

	return null;
}

/**
 * Determine whether a registered theme part is enabled.
 *
 * @since 0.8.0
 * @param string $part_id Part identifier.
 * @return bool
 */
function variable_thema_is_part_enabled( $part_id ) {
	$part = variable_thema_get_part_registry_item( $part_id );

	if ( ! $part ) {
		return false;
	}

	if ( empty( $part['future_toggle_key'] ) ) {
		return true;
	}

	$settings = variable_thema_get_parts_activation_settings();

	if ( array_key_exists( $part_id, $settings ) ) {
		return (bool) $settings[ $part_id ];
	}

	return false;
}

/**
 * Get normalized activation state for a theme part.
 *
 * @since 0.8.0
 * @param string $part_id Part identifier.
 * @return array
 */
function variable_thema_get_part_activation_state( $part_id ) {
	$part = variable_thema_get_part_registry_item( $part_id );

	if ( ! $part ) {
		return array(
			'exists'     => false,
			'enabled'    => false,
			'toggleable' => false,
			'is_saved'   => false,
			'part_id'    => $part_id,
			'toggle_key' => '',
			'status'     => '',
		);
	}

	$settings      = variable_thema_get_parts_activation_settings();
	$toggle_key    = isset( $part['future_toggle_key'] ) ? (string) $part['future_toggle_key'] : '';
	$is_toggleable = '' !== $toggle_key;
	$is_saved      = array_key_exists( $part_id, $settings );

	return array(
		'exists'     => true,
		'enabled'    => variable_thema_is_part_enabled( $part_id ),
		'toggleable' => $is_toggleable,
		'is_saved'   => $is_saved,
		'part_id'    => $part_id,
		'toggle_key' => $toggle_key,
		'status'     => isset( $part['status'] ) ? (string) $part['status'] : '',
	);
}
