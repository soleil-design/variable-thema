<?php
/**
 * Legacy FAQ taxonomy compatibility.
 *
 * @package Variable_Thema
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Legacy compatibility wrapper for the former theme-owned taxonomy.
 *
 * @deprecated 0.3.0 Use Soleil_Content_Core_FAQ::register_taxonomy().
 * @return void
 */
function variable_thema_register_faq_category_taxonomy() {
	_deprecated_function( __FUNCTION__, '0.3.0', 'Soleil_Content_Core_FAQ::register_taxonomy' );

	if ( class_exists( 'Soleil_Content_Core_FAQ' ) ) {
		Soleil_Content_Core_FAQ::register_taxonomy();
	}
}

/**
 * Legacy compatibility wrapper for the one-time Core FAQ migration.
 *
 * @deprecated 0.3.0 Use Soleil_Content_Core_FAQ::maybe_migrate_initial_terms().
 * @return void
 */
function variable_thema_register_default_faq_categories() {
	_deprecated_function( __FUNCTION__, '0.3.0', 'Soleil_Content_Core_FAQ::maybe_migrate_initial_terms' );

	if ( class_exists( 'Soleil_Content_Core_FAQ' ) ) {
		Soleil_Content_Core_FAQ::maybe_migrate_initial_terms();
	}
}
