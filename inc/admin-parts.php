<?php
/**
 * Theme parts administration screens.
 *
 * @package Variable_Thema
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the theme parts administration page.
 *
 * @since 0.8.0
 * @return void
 */
function variable_thema_register_admin_parts_page() {
	add_theme_page(
		__( 'variable Thema Parts', 'variable-thema' ),
		__( 'Parts', 'variable-thema' ),
		'edit_theme_options',
		'variable-thema-parts',
		'variable_thema_render_admin_parts_page'
	);
}
add_action( 'admin_menu', 'variable_thema_register_admin_parts_page' );

/**
 * Retain the disabled parts-settings save callback.
 *
 * @since 0.8.0
 * @return false
 */
function variable_thema_handle_admin_parts_settings_save() {
	// Deprecated: the non-functional gateway settings are no longer writable.
	return false;
}

/**
 * Get the administration label for a part activation state.
 *
 * @since 0.8.0
 * @param string $part_id Part identifier.
 * @return string
 */
function variable_thema_get_admin_part_activation_label( $part_id ) {
	$activation_state = variable_thema_get_part_activation_state( $part_id );

	if ( empty( $activation_state['exists'] ) ) {
		return __( 'Not registered', 'variable-thema' );
	}

	if ( empty( $activation_state['toggleable'] ) ) {
		return __( 'Standard part', 'variable-thema' );
	}

	return ! empty( $activation_state['enabled'] )
		? __( 'Enabled', 'variable-thema' )
		: __( 'Disabled', 'variable-thema' );
}

/**
 * Render the theme parts administration page.
 *
 * @since 0.8.0
 * @return void
 */
function variable_thema_render_admin_parts_page() {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}

	$parts = variable_thema_get_parts_registry();
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Variable Thema Parts', 'variable-thema' ); ?></h1>
		<p>
			<?php esc_html_e( 'This is a list of theme UI components. Stored legacy gateway values remain for compatibility, but settings that are not connected to runtime features are no longer saved.', 'variable-thema' ); ?>
		</p>

		<table class="widefat striped">
			<thead>
				<tr>
					<th scope="col"><?php esc_html_e( 'Part', 'variable-thema' ); ?></th>
					<th scope="col"><?php esc_html_e( 'Type', 'variable-thema' ); ?></th>
					<th scope="col"><?php esc_html_e( 'Status', 'variable-thema' ); ?></th>
					<th scope="col"><?php esc_html_e( 'Activation', 'variable-thema' ); ?></th>
					<th scope="col"><?php esc_html_e( 'Summary', 'variable-thema' ); ?></th>
					<th scope="col"><?php esc_html_e( 'Related files / Usage', 'variable-thema' ); ?></th>
					<th scope="col"><?php esc_html_e( 'Notes', 'variable-thema' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ( $parts as $part ) : ?>
					<tr>
						<th scope="row">
							<?php echo esc_html( $part['name'] ); ?><br>
							<code><?php echo esc_html( $part['id'] ); ?></code>
						</th>
						<td><?php echo esc_html( $part['category'] ); ?></td>
						<td><?php echo esc_html( variable_thema_get_part_status_label( $part['status'] ) ); ?></td>
						<td>
							<?php echo esc_html( variable_thema_get_admin_part_activation_label( $part['id'] ) ); ?>
						</td>
						<td><?php echo esc_html( $part['summary'] ); ?></td>
						<td>
							<?php if ( $part['template_part'] ) : ?>
								<div><?php esc_html_e( 'Template:', 'variable-thema' ); ?> <code><?php echo esc_html( $part['template_part'] ); ?></code></div>
							<?php endif; ?>
							<?php if ( $part['css'] ) : ?>
								<div><?php esc_html_e( 'CSS:', 'variable-thema' ); ?> <code><?php echo esc_html( $part['css'] ); ?></code></div>
							<?php endif; ?>
							<?php if ( $part['js'] ) : ?>
								<div><?php esc_html_e( 'JS:', 'variable-thema' ); ?> <code><?php echo esc_html( $part['js'] ); ?></code></div>
							<?php endif; ?>
							<div><?php esc_html_e( 'Usage:', 'variable-thema' ); ?> <code><?php echo esc_html( $part['usage'] ); ?></code></div>
							<?php if ( $part['future_toggle_key'] ) : ?>
								<div><?php esc_html_e( 'Future toggle:', 'variable-thema' ); ?> <code><?php echo esc_html( $part['future_toggle_key'] ); ?></code></div>
							<?php endif; ?>
						</td>
						<td><?php echo esc_html( $part['notes'] ); ?></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
	<?php
}

/**
 * Render the deprecated parts settings notice.
 *
 * @since 0.8.0
 * @return void
 */
function variable_thema_render_admin_parts_settings_page() {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}

	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'variable Thema Parts Settings', 'variable-thema' ); ?></h1>
		<div class="notice notice-info inline">
			<p><?php esc_html_e( 'This settings screen is deprecated. Existing option values remain readable for compatibility, but new values are not saved.', 'variable-thema' ); ?></p>
		</div>
		<p><a class="button" href="<?php echo esc_url( admin_url( 'themes.php?page=variable-thema-parts' ) ); ?>"><?php esc_html_e( 'Return to Parts', 'variable-thema' ); ?></a></p>
	</div>
	<?php
}
