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
 * Check whether a template candidate list resolves to a parent theme file.
 *
 * @since 1.0.0
 * @param string[] $templates Template file names in hierarchy order.
 * @return bool
 */
function variable_thema_is_parent_template( $templates ) {
	$located = locate_template( $templates );

	return '' !== $located && 0 === strpos( wp_normalize_path( $located ), trailingslashit( wp_normalize_path( get_template_directory() ) ) );
}

/**
 * Describe which parent templates render the post open in the block editor.
 *
 * The editor canvas mirrors the front-end width rules only for posts that the
 * parent theme templates render; child theme templates keep their own editor
 * styles.
 *
 * @since 1.0.0
 * @return array<string, bool|string>
 */
function variable_thema_get_editor_layout_settings() {
	$post             = get_post();
	$landing_template = 'page-templates/landing.php';
	$templates        = array();

	if ( $post && 'page' === get_option( 'show_on_front' ) && (int) get_option( 'page_on_front' ) === $post->ID ) {
		$templates[] = 'front-page.php';
	}

	$front_page_template = $templates;

	if ( $post && 'page' === $post->post_type ) {
		$templates = array_merge( $templates, array( 'page-' . $post->post_name . '.php', 'page-' . $post->ID . '.php', 'page.php' ) );
	} elseif ( $post ) {
		$templates = array_merge( $templates, array( 'single-' . $post->post_type . '-' . $post->post_name . '.php', 'single-' . $post->post_type . '.php', 'single.php' ) );
	}

	$templates[] = 'singular.php';
	$templates[] = 'index.php';

	return array(
		'landingTemplate' => $landing_template,
		'landingIsParent' => ( ! $front_page_template || '' === locate_template( $front_page_template ) ) && variable_thema_is_parent_template( array( $landing_template ) ),
		'defaultIsParent' => $post ? variable_thema_is_parent_template( $templates ) : false,
	);
}

/**
 * Enqueue block editor scripts and keep the editor stylesheet handle available.
 *
 * Child themes may list variable-thema-editor-blocks as a dependency of their
 * own editor stylesheets, so the handle stays registered on editor screens.
 *
 * @since 0.8.0
 * @return void
 */
function variable_thema_enqueue_block_editor_assets() {
	wp_register_style(
		'variable-thema-editor-blocks',
		get_template_directory_uri() . '/assets/css/blocks.css',
		array(),
		variable_thema_asset_version( 'assets/css/blocks.css' )
	);

	variable_thema_enqueue_script_file( 'variable-thema-editor-layout', 'assets/js/editor-layout.js', array( 'wp-data' ) );
	wp_add_inline_script(
		'variable-thema-editor-layout',
		'window.variableThemaEditorLayout = ' . wp_json_encode( variable_thema_get_editor_layout_settings() ) . ';',
		'before'
	);
}
add_action( 'enqueue_block_editor_assets', 'variable_thema_enqueue_block_editor_assets' );

/**
 * Enqueue block presentation and canvas width styles inside the block editor.
 *
 * Styles added on enqueue_block_assets are loaded into the iframed editor
 * canvas, where they apply to the edited content.
 *
 * @since 1.0.0
 * @return void
 */
function variable_thema_enqueue_block_editor_content_assets() {
	if ( ! is_admin() ) {
		return;
	}

	variable_thema_enqueue_style_file( 'variable-thema-editor-blocks', 'assets/css/blocks.css' );
	variable_thema_enqueue_style_file( 'variable-thema-editor-layout', 'assets/css/editor.css', array( 'variable-thema-editor-blocks' ) );
}
add_action( 'enqueue_block_assets', 'variable_thema_enqueue_block_editor_content_assets' );

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
