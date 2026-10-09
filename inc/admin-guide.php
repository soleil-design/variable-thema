<?php
/**
 * Theme administration guide helpers.
 *
 * @package Variable_Thema
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the theme administration guide.
 *
 * @since 0.8.0
 * @return void
 */
function variable_thema_register_admin_guide() {
	add_theme_page(
		__( 'variable Thema Guide', 'variable-thema' ),
		__( 'variable Thema', 'variable-thema' ),
		'edit_theme_options',
		'variable-thema-guide',
		'variable_thema_render_admin_guide'
	);
}
add_action( 'admin_menu', 'variable_thema_register_admin_guide' );

/**
 * Render markup for an administration check state.
 *
 * @since 0.8.0
 * @param bool $is_complete Whether the check is complete.
 * @return string
 */
function variable_thema_render_admin_check_status( $is_complete ) {
	if ( $is_complete ) {
		return '<strong style="color:#008a20;">' . esc_html__( 'OK', 'variable-thema' ) . '</strong>';
	}

	return '<strong style="color:#b32d2e;">' . esc_html__( 'Not set', 'variable-thema' ) . '</strong>';
}

/**
 * Get administration URLs used by the theme guide.
 *
 * @since 0.8.0
 * @return array
 */
function variable_thema_get_admin_guide_links() {
	$customizer_url = admin_url( 'customize.php' );

	return array(
		'theme_settings'     => add_query_arg( 'autofocus[section]', 'variable_thema_appearance_settings', $customizer_url ),
		'header_settings'    => add_query_arg( 'autofocus[section]', 'variable_thema_header_settings', $customizer_url ),
		'footer_settings'    => add_query_arg( 'autofocus[section]', 'variable_thema_footer_settings', $customizer_url ),
		'site_identity'      => add_query_arg( 'autofocus[section]', 'title_tagline', $customizer_url ),
		'nav_menus'          => add_query_arg( 'autofocus[panel]', 'nav_menus', $customizer_url ),
		'reading_settings'   => admin_url( 'options-reading.php' ),
		'permalink_settings' => admin_url( 'options-permalink.php' ),
		'menus'              => admin_url( 'nav-menus.php' ),
		'pages'              => admin_url( 'edit.php?post_type=page' ),
		'parts'              => admin_url( 'themes.php?page=variable-thema-parts' ),
		'site_health'        => admin_url( 'site-health.php' ),
	);
}

/**
 * Get current theme configuration for the guide.
 *
 * @since 0.8.0
 * @return array
 */
function variable_thema_get_admin_guide_theme_state() {
	$front_page_id       = (int) get_option( 'page_on_front' );
	$posts_page_id       = (int) get_option( 'page_for_posts' );
	$permalink_structure = (string) get_option( 'permalink_structure' );

	return array(
		'main_color'             => variable_thema_get_theme_mod( 'main_color' ),
		'accent_color'           => variable_thema_get_theme_mod( 'accent_color' ),
		'button_color'           => variable_thema_get_theme_mod( 'button_color' ),
		'show_site_title'        => variable_thema_get_theme_mod( 'show_site_title' ),
		'show_header_menu'       => variable_thema_get_theme_mod( 'show_header_menu' ),
		'show_footer_menu'       => variable_thema_get_theme_mod( 'show_footer_menu' ),
		'front_page_id'          => $front_page_id,
		'posts_page_id'          => $posts_page_id,
		'permalink_structure'    => $permalink_structure,
		'front_page_description' => $front_page_id ? get_the_title( $front_page_id ) : __( 'Latest posts', 'variable-thema' ),
		'posts_page_description' => $posts_page_id ? get_the_title( $posts_page_id ) : __( 'Not selected', 'variable-thema' ),
	);
}

/**
 * Get postcode compatibility state for the guide.
 *
 * @since 0.8.0
 * @return array
 */
function variable_thema_get_admin_guide_postcode_state() {
	return array(
		'status'    => __( 'Optional feature / Not implemented', 'variable-thema' ),
		'condition' => __( 'Only a detection extension point is provided for data-vt-postcode markup in post content.', 'variable-thema' ),
	);
}

/**
 * Get AI Counter deprecation state for the guide.
 *
 * @since 0.8.0
 * @return array
 */
function variable_thema_get_admin_guide_ai_counter_state() {
	return array(
		'status' => __( 'Deprecated', 'variable-thema' ),
		'scope'  => __( 'The theme does not provide tracking, storage, or an administration screen.', 'variable-thema' ),
		'future' => __( 'Use a dedicated plugin if this functionality is required.', 'variable-thema' ),
	);
}

/**
 * Get footer configuration state for the guide.
 *
 * @since 0.8.0
 * @return array
 */
function variable_thema_get_admin_guide_footer_company_state() {
	return array(
		'has_footer_logo'    => has_custom_logo(),
		'has_copyright_note' => '' !== variable_thema_get_theme_mod( 'footer_copyright_note' ),
	);
}

/**
 * Build the theme guide checklist.
 *
 * @since 0.8.0
 * @param array $state Current theme state.
 * @param array $links Administration URLs.
 * @return array
 */
function variable_thema_get_admin_guide_checklist( $state, $links ) {
	return array(
		array(
			'label'    => __( 'Logo configured', 'variable-thema' ),
			'complete' => has_custom_logo(),
			'current'  => has_custom_logo() ? __( 'Configured', 'variable-thema' ) : __( 'Not set', 'variable-thema' ),
			'url'      => $links['site_identity'],
			'action'   => __( 'Go to logo settings', 'variable-thema' ),
		),
		array(
			'label'    => __( 'Site icon configured', 'variable-thema' ),
			'complete' => (bool) get_site_icon_url(),
			'current'  => get_site_icon_url() ? __( 'Configured', 'variable-thema' ) : __( 'Not set', 'variable-thema' ),
			'url'      => $links['site_identity'],
			'action'   => __( 'Go to Site Identity', 'variable-thema' ),
		),
		array(
			'label'    => __( 'Primary Menu assigned', 'variable-thema' ),
			'complete' => has_nav_menu( 'primary' ),
			'current'  => has_nav_menu( 'primary' ) ? __( 'Assigned', 'variable-thema' ) : __( 'Not assigned', 'variable-thema' ),
			'url'      => $links['nav_menus'],
			'action'   => __( 'Go to menu settings', 'variable-thema' ),
		),
		array(
			'label'    => __( 'Footer Menu assigned', 'variable-thema' ),
			'complete' => has_nav_menu( 'footer' ),
			'current'  => has_nav_menu( 'footer' ) ? __( 'Assigned', 'variable-thema' ) : __( 'Not assigned', 'variable-thema' ),
			'url'      => $links['nav_menus'],
			'action'   => __( 'Go to menu settings', 'variable-thema' ),
		),
		array(
			'label'    => __( 'Front page configured', 'variable-thema' ),
			'complete' => 0 < $state['front_page_id'],
			'current'  => $state['front_page_description'],
			'url'      => $links['reading_settings'],
			'action'   => __( 'Go to Reading Settings', 'variable-thema' ),
		),
		array(
			'label'    => __( 'Posts page configured', 'variable-thema' ),
			'complete' => 0 < $state['posts_page_id'],
			'current'  => $state['posts_page_description'],
			'url'      => $links['reading_settings'],
			'action'   => __( 'Go to Reading Settings', 'variable-thema' ),
		),
		array(
			'label'    => __( 'Permalink structure is not Plain', 'variable-thema' ),
			'complete' => '' !== $state['permalink_structure'],
			'current'  => '' !== $state['permalink_structure'] ? $state['permalink_structure'] : __( 'Plain', 'variable-thema' ),
			'url'      => $links['permalink_settings'],
			'action'   => __( 'Go to Permalink Settings', 'variable-thema' ),
		),
		array(
			'label'    => __( 'Site title display setting', 'variable-thema' ),
			'complete' => true,
			'current'  => $state['show_site_title'] ? __( 'Show', 'variable-thema' ) : __( 'Hide', 'variable-thema' ),
			'url'      => $links['header_settings'],
			'action'   => __( 'Go to theme settings', 'variable-thema' ),
		),
		array(
			'label'    => __( 'Header menu display setting', 'variable-thema' ),
			'complete' => true,
			'current'  => $state['show_header_menu'] ? __( 'Show', 'variable-thema' ) : __( 'Hide', 'variable-thema' ),
			'url'      => $links['header_settings'],
			'action'   => __( 'Go to theme settings', 'variable-thema' ),
		),
		array(
			'label'    => __( 'Footer menu display setting', 'variable-thema' ),
			'complete' => true,
			'current'  => $state['show_footer_menu'] ? __( 'Show', 'variable-thema' ) : __( 'Hide', 'variable-thema' ),
			'url'      => $links['footer_settings'],
			'action'   => __( 'Go to theme settings', 'variable-thema' ),
		),
	);
}

/**
 * Count completed theme guide checks.
 *
 * @since 0.8.0
 * @param array $checklist Guide checklist.
 * @return int
 */
function variable_thema_count_completed_admin_guide_checks( $checklist ) {
	return count(
		array_filter(
			$checklist,
			function ( $item ) {
				return ! empty( $item['complete'] );
			}
		)
	);
}

/**
 * Render the theme administration guide.
 *
 * @since 0.8.0
 * @return void
 */
function variable_thema_render_admin_guide() {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}

	$theme                = wp_get_theme();
	$links                = variable_thema_get_admin_guide_links();
	$state                = variable_thema_get_admin_guide_theme_state();
	$footer_company_state = variable_thema_get_admin_guide_footer_company_state();
	$checklist            = variable_thema_get_admin_guide_checklist( $state, $links );
	$completed_checks     = variable_thema_count_completed_admin_guide_checks( $checklist );
	$total_checks         = count( $checklist );
	?>
	<div class="wrap">
		<h1><?php echo esc_html( $theme->get( 'Name' ) ); ?></h1>
		<p>
			<?php esc_html_e( 'This guide summarizes the initial setup and checks for Variable Thema. It provides guidance only and does not change site settings.', 'variable-thema' ); ?>
		</p>

		<div class="card">
			<h2><?php esc_html_e( 'Customization shortcuts', 'variable-thema' ); ?></h2>
			<p>
				<a class="button button-primary" href="<?php echo esc_url( $links['theme_settings'] ); ?>">
					<?php esc_html_e( 'Open theme settings', 'variable-thema' ); ?>
				</a>
				<a class="button" href="<?php echo esc_url( $links['header_settings'] ); ?>">
					<?php esc_html_e( 'Open header settings', 'variable-thema' ); ?>
				</a>
				<a class="button" href="<?php echo esc_url( $links['footer_settings'] ); ?>">
					<?php esc_html_e( 'Open footer settings', 'variable-thema' ); ?>
				</a>
				<a class="button" href="<?php echo esc_url( $links['site_identity'] ); ?>">
					<?php esc_html_e( 'Open logo and site icon settings', 'variable-thema' ); ?>
				</a>
				<a class="button" href="<?php echo esc_url( $links['nav_menus'] ); ?>">
					<?php esc_html_e( 'Open menu customization', 'variable-thema' ); ?>
				</a>
				<a class="button" href="<?php echo esc_url( $links['parts'] ); ?>">
					<?php esc_html_e( 'Open Parts', 'variable-thema' ); ?>
				</a>
			</p>
			<p class="description">
				<?php esc_html_e( 'The logo and site icon use standard WordPress settings. Colors and display options are managed in Variable Thema Settings.', 'variable-thema' ); ?>
			</p>
		</div>

		<div class="card">
			<h2><?php esc_html_e( 'Current theme settings', 'variable-thema' ); ?></h2>
			<table class="widefat striped">
				<tbody>
					<tr>
						<th scope="row"><?php esc_html_e( 'Logo', 'variable-thema' ); ?></th>
						<td><?php echo has_custom_logo() ? esc_html__( 'Configured', 'variable-thema' ) : esc_html__( 'Not set', 'variable-thema' ); ?></td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Site icon', 'variable-thema' ); ?></th>
						<td><?php echo get_site_icon_url() ? esc_html__( 'Configured', 'variable-thema' ) : esc_html__( 'Not set', 'variable-thema' ); ?></td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Main color', 'variable-thema' ); ?></th>
						<td><?php echo esc_html( $state['main_color'] ); ?></td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Accent color', 'variable-thema' ); ?></th>
						<td><?php echo esc_html( $state['accent_color'] ); ?></td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Button color', 'variable-thema' ); ?></th>
						<td><?php echo esc_html( $state['button_color'] ); ?></td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Show site title', 'variable-thema' ); ?></th>
						<td><?php echo $state['show_site_title'] ? esc_html__( 'Show', 'variable-thema' ) : esc_html__( 'Hide', 'variable-thema' ); ?></td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Show header menu', 'variable-thema' ); ?></th>
						<td><?php echo $state['show_header_menu'] ? esc_html__( 'Show', 'variable-thema' ) : esc_html__( 'Hide', 'variable-thema' ); ?></td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Show footer menu', 'variable-thema' ); ?></th>
						<td><?php echo $state['show_footer_menu'] ? esc_html__( 'Show', 'variable-thema' ) : esc_html__( 'Hide', 'variable-thema' ); ?></td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Primary Menu', 'variable-thema' ); ?></th>
						<td><?php echo has_nav_menu( 'primary' ) ? esc_html__( 'Assigned', 'variable-thema' ) : esc_html__( 'Not assigned', 'variable-thema' ); ?></td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Footer Menu', 'variable-thema' ); ?></th>
						<td><?php echo has_nav_menu( 'footer' ) ? esc_html__( 'Assigned', 'variable-thema' ) : esc_html__( 'Not assigned', 'variable-thema' ); ?></td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Front page', 'variable-thema' ); ?></th>
						<td><?php echo esc_html( $state['front_page_description'] ); ?></td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Posts page', 'variable-thema' ); ?></th>
						<td><?php echo esc_html( $state['posts_page_description'] ); ?></td>
					</tr>
				</tbody>
			</table>
		</div>

		<div class="card">
			<h2><?php esc_html_e( 'Initial setup checklist', 'variable-thema' ); ?></h2>
			<p>
				<?php
				printf(
					/* translators: 1: completed count, 2: total count. */
					esc_html__( 'Progress: %1$d / %2$d', 'variable-thema' ),
					(int) $completed_checks,
					(int) $total_checks
				);
				?>
			</p>
			<table class="widefat striped">
				<thead>
					<tr>
						<th scope="col"><?php esc_html_e( 'Status', 'variable-thema' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Item', 'variable-thema' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Current value', 'variable-thema' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Settings location', 'variable-thema' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ( $checklist as $item ) : ?>
						<tr>
							<td><?php echo wp_kses_post( variable_thema_render_admin_check_status( $item['complete'] ) ); ?></td>
							<th scope="row"><?php echo esc_html( $item['label'] ); ?></th>
							<td><?php echo esc_html( $item['current'] ); ?></td>
							<td>
								<a class="button" href="<?php echo esc_url( $item['url'] ); ?>">
									<?php echo esc_html( $item['action'] ); ?>
								</a>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>

		<div class="card">
			<h2><?php esc_html_e( 'Settings shortcuts', 'variable-thema' ); ?></h2>
			<p>
				<a class="button button-primary" href="<?php echo esc_url( $links['menus'] ); ?>">
					<?php esc_html_e( 'Open menu settings', 'variable-thema' ); ?>
				</a>
				<a class="button" href="<?php echo esc_url( $links['reading_settings'] ); ?>">
					<?php esc_html_e( 'Open Reading Settings', 'variable-thema' ); ?>
				</a>
				<a class="button" href="<?php echo esc_url( $links['pages'] ); ?>">
					<?php esc_html_e( 'Open Pages', 'variable-thema' ); ?>
				</a>
			</p>
			<p class="description">
				<?php esc_html_e( 'Reading Settings controls whether the front page shows the latest posts or a static page.', 'variable-thema' ); ?>
			</p>
		</div>

		<div class="card">
			<h2><?php esc_html_e( 'Footer display settings', 'variable-thema' ); ?></h2>
			<table class="widefat striped">
				<tbody>
					<tr>
						<th scope="row"><?php esc_html_e( 'Footer logo', 'variable-thema' ); ?></th>
						<td><?php echo $footer_company_state['has_footer_logo'] ? esc_html__( 'Logo configured', 'variable-thema' ) : esc_html__( 'Logo not set', 'variable-thema' ); ?></td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Copyright note', 'variable-thema' ); ?></th>
						<td><?php echo $footer_company_state['has_copyright_note'] ? esc_html__( 'Configured', 'variable-thema' ) : esc_html__( 'Not set', 'variable-thema' ); ?></td>
					</tr>
				</tbody>
			</table>
			<p>
				<a class="button" href="<?php echo esc_url( $links['footer_settings'] ); ?>">
					<?php esc_html_e( 'Open theme settings', 'variable-thema' ); ?>
				</a>
			</p>
		</div>

		<div class="card">
			<h2><?php esc_html_e( 'Recommended checks', 'variable-thema' ); ?></h2>
			<ul>
				<li><?php esc_html_e( 'Header: site title, Primary Menu, and mobile display.', 'variable-thema' ); ?></li>
				<li><?php esc_html_e( 'Footer: Footer Menu, footer logo, and copyright.', 'variable-thema' ); ?></li>
				<li><?php esc_html_e( 'Pages: page title, content width, featured image, and breadcrumb.', 'variable-thema' ); ?></li>
				<li><?php esc_html_e( 'Single posts: title, date, categories, featured image, and post navigation.', 'variable-thema' ); ?></li>
				<li><?php esc_html_e( 'Post archive: post cards, pagination, and empty results.', 'variable-thema' ); ?></li>
			</ul>
		</div>

		<?php do_action( 'variable_thema_admin_guide_site_sections', $links ); ?>
	</div>
	<?php
}
