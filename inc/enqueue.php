<?php
/**
 * Frontend and editor asset loading.
 *
 * @package Variable_Thema
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get a cache version for a theme asset.
 *
 * @since 0.8.0
 * @param string $relative_path Theme-relative asset path.
 * @return int|string
 */
function variable_thema_asset_version( $relative_path ) {
	$theme_version = wp_get_theme()->get( 'Version' );
	$asset_path    = get_template_directory() . '/' . ltrim( $relative_path, '/' );

	return file_exists( $asset_path ) ? filemtime( $asset_path ) : $theme_version;
}

/**
 * Enqueue a stylesheet from the parent theme.
 *
 * @since 0.8.0
 * @param string   $handle Stylesheet handle.
 * @param string   $relative_path Theme-relative path.
 * @param string[] $deps Dependency handles.
 * @return void
 */
function variable_thema_enqueue_style_file( $handle, $relative_path, $deps = array() ) {
	wp_enqueue_style(
		$handle,
		get_template_directory_uri() . '/' . ltrim( $relative_path, '/' ),
		$deps,
		variable_thema_asset_version( $relative_path )
	);
}

/**
 * Enqueue a script from the parent theme.
 *
 * @since 0.8.0
 * @param string   $handle Script handle.
 * @param string   $relative_path Theme-relative path.
 * @param string[] $deps Dependency handles.
 * @param bool     $in_footer Whether to load in the footer.
 * @return void
 */
function variable_thema_enqueue_script_file( $handle, $relative_path, $deps = array(), $in_footer = true ) {
	wp_enqueue_script(
		$handle,
		get_template_directory_uri() . '/' . ltrim( $relative_path, '/' ),
		$deps,
		variable_thema_asset_version( $relative_path ),
		$in_footer
	);
}

/**
 * Enqueue front-end theme assets.
 *
 * @since 0.8.0
 * @return void
 */
function variable_thema_enqueue_assets() {
	variable_thema_enqueue_style_file( 'variable-thema-base', 'assets/css/base.css' );
	variable_thema_enqueue_style_file( 'variable-thema-layout', 'assets/css/layout.css', array( 'variable-thema-base' ) );
	variable_thema_enqueue_style_file( 'variable-thema-components', 'assets/css/components.css', array( 'variable-thema-layout' ) );
	variable_thema_enqueue_style_file( 'variable-thema-blocks', 'assets/css/blocks.css', array( 'variable-thema-components' ) );
	variable_thema_enqueue_style_file( 'variable-thema-pages', 'assets/css/pages.css', array( 'variable-thema-blocks' ) );

	variable_thema_enqueue_script_file( 'variable-thema-main', 'assets/js/main.js' );
	variable_thema_enqueue_script_file( 'variable-thema-navigation', 'assets/js/navigation.js', array( 'variable-thema-main' ) );
	variable_thema_enqueue_script_file( 'variable-thema-latest-posts-slider', 'assets/js/features/latest-posts-slider.js', array( 'variable-thema-main' ) );
	variable_thema_enqueue_conditional_assets();

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'variable_thema_enqueue_assets' );

/**
 * Enqueue Dashicons on the front end.
 *
 * @since 0.8.0
 * @return void
 */
function variable_thema_enqueue_dashicons_frontend() {
	wp_enqueue_style( 'dashicons' );
}
add_action( 'wp_enqueue_scripts', 'variable_thema_enqueue_dashicons_frontend' );

/**
 * Enqueue block editor presentation styles.
 *
 * @since 0.8.0
 * @return void
 */
function variable_thema_enqueue_block_editor_assets() {
	variable_thema_enqueue_style_file( 'variable-thema-editor-blocks', 'assets/css/blocks.css' );
}
add_action( 'enqueue_block_editor_assets', 'variable_thema_enqueue_block_editor_assets' );

/**
 * Provide a callback for conditional presentation assets.
 *
 * @since 0.8.0
 * @return void
 */
function variable_thema_enqueue_conditional_assets() {
	// Reserved for implemented, presentation-owned conditional assets.
}

/**
 * Retain the deprecated postcode asset callback as a no-op.
 *
 * @since 0.8.0
 * @return void
 */
function variable_thema_enqueue_postcode_address_assets() {
	// Deprecated no-op retained for callers of the former gateway stub.
}
