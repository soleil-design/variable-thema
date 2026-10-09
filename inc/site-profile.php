<?php
/**
 * Site Profile display compatibility.
 *
 * @package Variable_Thema
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get the Site Profile from Soleil Content Core.
 *
 * @deprecated 0.4.0 Use soleil_content_core_get_site_profile().
 *
 * @param string|null $key     Optional field key.
 * @param mixed       $default Value returned for an absent or empty field.
 * @return array<string,string>|mixed
 */
function variable_get_site_profile( $key = null, $default = '' ) {
	if ( function_exists( 'soleil_content_core_get_site_profile' ) ) {
		return soleil_content_core_get_site_profile( $key, $default );
	}

	return null === $key ? array() : $default;
}
