<?php
/**
 * Legacy FAQ post type compatibility.
 *
 * @package Variable_Thema
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Legacy compatibility wrapper for the former theme-owned FAQ registration.
 *
 * @deprecated 0.3.0 Use Soleil_Content_Core_FAQ::register_post_type().
 * @return void
 */
function variable_thema_register_faq_post_type() {
	_deprecated_function( __FUNCTION__, '0.3.0', 'Soleil_Content_Core_FAQ::register_post_type' );

	if ( class_exists( 'Soleil_Content_Core_FAQ' ) ) {
		Soleil_Content_Core_FAQ::register_post_type();
	}
}
