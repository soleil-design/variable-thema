<?php
/**
 * Theme Customizer settings and output.
 *
 * @package Variable_Thema
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get default theme appearance settings.
 *
 * @since 0.8.0
 * @return array
 */
function variable_thema_get_theme_defaults() {
	return array(
		'main_color'               => '#1f2933',
		'accent_color'             => '#0f766e',
		'button_color'             => '#1f2933',
		'show_site_title'          => true,
		'show_header_menu'         => true,
		'show_footer_menu'         => true,
		'show_footer_company_info' => false,
		'footer_company_name'      => '',
		'footer_company_address'   => '',
		'footer_company_phone'     => '',
		'footer_business_hours'    => '',
		'footer_copyright_note'    => '',
	);
}

/**
 * Get a theme modification with its registered default.
 *
 * @since 0.8.0
 * @param string $key Theme modification key.
 * @return mixed
 */
function variable_thema_get_theme_mod( $key ) {
	$defaults = variable_thema_get_theme_defaults();

	if ( ! array_key_exists( $key, $defaults ) ) {
		return null;
	}

	return get_theme_mod( 'variable_thema_' . $key, $defaults[ $key ] );
}

/**
 * Sanitize a checkbox value.
 *
 * @since 0.8.0
 * @param mixed $checked Submitted checkbox value.
 * @return bool
 */
function variable_thema_sanitize_checkbox( $checked ) {
	return (bool) $checked;
}

/**
 * Register settings and controls with the Customizer.
 *
 * @since 0.8.0
 * @param WP_Customize_Manager $wp_customize Customizer manager.
 * @return void
 */
function variable_thema_customize_register( $wp_customize ) {
	$defaults = variable_thema_get_theme_defaults();

	$wp_customize->add_section(
		'variable_thema_appearance_settings',
		array(
			'title'       => __( 'variable Thema Appearance', 'variable-thema' ),
			'description' => __( 'Basic color settings for variable Thema.', 'variable-thema' ),
			'priority'    => 30,
		)
	);

	$wp_customize->add_section(
		'variable_thema_header_settings',
		array(
			'title'       => __( 'variable Thema Header', 'variable-thema' ),
			'description' => __( 'Header display settings for variable Thema.', 'variable-thema' ),
			'priority'    => 31,
		)
	);

	$wp_customize->add_section(
		'variable_thema_footer_settings',
		array(
			'title'       => __( 'variable Thema Footer', 'variable-thema' ),
			'description' => __( 'Footer display settings for variable Thema.', 'variable-thema' ),
			'priority'    => 32,
		)
	);

	$color_settings = array(
		'main_color'   => __( 'Main color', 'variable-thema' ),
		'accent_color' => __( 'Accent color', 'variable-thema' ),
		'button_color' => __( 'Button color', 'variable-thema' ),
	);

	foreach ( $color_settings as $key => $label ) {
		$setting_id = 'variable_thema_' . $key;

		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => $defaults[ $key ],
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'refresh',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'   => $label,
					'section' => 'variable_thema_appearance_settings',
				)
			)
		);
	}

	$checkbox_settings = array(
		'show_site_title'  => array(
			'label'   => __( 'Show site title', 'variable-thema' ),
			'section' => 'variable_thema_header_settings',
		),
		'show_header_menu' => array(
			'label'   => __( 'Show header menu', 'variable-thema' ),
			'section' => 'variable_thema_header_settings',
		),
		'show_footer_menu' => array(
			'label'   => __( 'Show footer menu', 'variable-thema' ),
			'section' => 'variable_thema_footer_settings',
		),
	);

	foreach ( $checkbox_settings as $key => $control ) {
		$setting_id = 'variable_thema_' . $key;

		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => $defaults[ $key ],
				'sanitize_callback' => 'variable_thema_sanitize_checkbox',
				'transport'         => 'refresh',
			)
		);

		$wp_customize->add_control(
			$setting_id,
			array(
				'label'   => $control['label'],
				'section' => $control['section'],
				'type'    => 'checkbox',
			)
		);
	}

	$text_settings = array(
		'footer_copyright_note' => __( 'Footer copyright note', 'variable-thema' ),
	);

	foreach ( $text_settings as $key => $label ) {
		$setting_id = 'variable_thema_' . $key;

		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => $defaults[ $key ],
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'refresh',
			)
		);

		$wp_customize->add_control(
			$setting_id,
			array(
				'label'   => $label,
				'section' => 'variable_thema_footer_settings',
				'type'    => 'text',
			)
		);
	}
}
add_action( 'customize_register', 'variable_thema_customize_register' );

/**
 * Add Customizer-derived front-end styles.
 *
 * @since 0.8.0
 * @return void
 */
function variable_thema_add_customizer_inline_styles() {
	$main_color   = sanitize_hex_color( variable_thema_get_theme_mod( 'main_color' ) );
	$accent_color = sanitize_hex_color( variable_thema_get_theme_mod( 'accent_color' ) );
	$button_color = sanitize_hex_color( variable_thema_get_theme_mod( 'button_color' ) );

	$css = sprintf(
		':root{--vt-color-main:%1$s;--vt-color-accent:%2$s;--vt-color-button:%3$s;}',
		$main_color ? $main_color : '#1f2933',
		$accent_color ? $accent_color : '#0f766e',
		$button_color ? $button_color : '#1f2933'
	);

	wp_add_inline_style( 'variable-thema-base', $css );
}
add_action( 'wp_enqueue_scripts', 'variable_thema_add_customizer_inline_styles', 20 );
