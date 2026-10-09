<?php
/**
 * Generic site footer template part.
 *
 * @package Variable_Thema
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'variable_thema_get_footer_link_icon_svg' ) ) {
	/**
	 * Get SVG markup for a footer link icon.
	 *
	 * @since 0.8.0
	 * @param string $icon Footer icon key.
	 * @return string
	 */
	function variable_thema_get_footer_link_icon_svg( $icon ) {
		$icons = array(
			'email'     => '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M3.75 5h16.5A1.75 1.75 0 0 1 22 6.75v10.5A1.75 1.75 0 0 1 20.25 19H3.75A1.75 1.75 0 0 1 2 17.25V6.75A1.75 1.75 0 0 1 3.75 5Zm.75 2.6v8.9h15V7.6l-7.03 5.27a.78.78 0 0 1-.94 0L4.5 7.6Zm1.6-1.1 5.9 4.43 5.9-4.43H6.1Z"/></svg>',
			'whatsapp'  => '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M12.04 3a8.66 8.66 0 0 0-7.35 13.25L3.7 21l4.88-1.28A8.66 8.66 0 1 0 12.04 3Zm0 1.58a7.08 7.08 0 0 1 0 14.16 7 7 0 0 1-3.58-.98l-.35-.2-2.32.61.62-2.25-.23-.37a7.08 7.08 0 0 1 5.86-10.97Zm-3.1 3.75c-.15 0-.4.06-.61.29-.21.23-.8.78-.8 1.9 0 1.11.82 2.19.93 2.34.11.15 1.58 2.53 3.9 3.45 1.93.76 2.33.61 2.75.57.42-.04 1.36-.56 1.55-1.1.19-.54.19-1 .13-1.1-.06-.1-.21-.16-.45-.28-.24-.12-1.39-.69-1.61-.77-.21-.08-.37-.12-.53.12-.15.24-.61.77-.75.92-.14.16-.28.18-.52.06-.24-.12-1.01-.37-1.92-1.18-.71-.63-1.19-1.41-1.33-1.65-.14-.24-.02-.37.1-.49.11-.11.24-.28.36-.42.12-.14.16-.24.24-.4.08-.16.04-.3-.02-.42-.06-.12-.53-1.28-.72-1.75-.19-.46-.38-.39-.53-.4h-.44Z"/></svg>',
			'x'         => '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M14.43 10.16 21.07 3h-2.35l-5.34 5.76L9.12 3H3.1l6.97 9.42L2.93 21h2.35l5.85-7.08L15.87 21h6.03l-7.47-10.84Zm-2.14 2.58-1.05-1.48L6.94 4.7h1.33l4.14 5.95 1.04 1.48 4.63 7.16h-1.33l-4.46-6.55Z"/></svg>',
			'instagram' => '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M7.5 2h9A5.5 5.5 0 0 1 22 7.5v9a5.5 5.5 0 0 1-5.5 5.5h-9A5.5 5.5 0 0 1 2 16.5v-9A5.5 5.5 0 0 1 7.5 2Zm0 1.8a3.7 3.7 0 0 0-3.7 3.7v9a3.7 3.7 0 0 0 3.7 3.7h9a3.7 3.7 0 0 0 3.7-3.7v-9a3.7 3.7 0 0 0-3.7-3.7h-9Zm4.5 3.4a4.8 4.8 0 1 1 0 9.6 4.8 4.8 0 0 1 0-9.6Zm0 1.8a3 3 0 1 0 0 6 3 3 0 0 0 0-6Zm5.05-2.35a1.15 1.15 0 1 1 0 2.3 1.15 1.15 0 0 1 0-2.3Z"/></svg>',
			'facebook'  => '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M14.1 8.05V6.62c0-.69.45-.85.78-.85h2V2.16L14.13 2c-3.05 0-3.74 2.28-3.74 3.74v2.31H8v3.76h2.39V22h4.12V11.81h2.78l.37-3.76H14.1Z"/></svg>',
			'youtube'   => '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M21.58 7.2a2.72 2.72 0 0 0-1.92-1.92C17.97 4.83 12 4.83 12 4.83s-5.97 0-7.66.45A2.72 2.72 0 0 0 2.42 7.2 28.3 28.3 0 0 0 2 12a28.3 28.3 0 0 0 .42 4.8 2.72 2.72 0 0 0 1.92 1.92c1.69.45 7.66.45 7.66.45s5.97 0 7.66-.45a2.72 2.72 0 0 0 1.92-1.92A28.3 28.3 0 0 0 22 12a28.3 28.3 0 0 0-.42-4.8ZM10 15.2V8.8l5.2 3.2L10 15.2Z"/></svg>',
			'linkedin'  => '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M5.34 7.67H1.8V22h3.54V7.67ZM3.56 2A2.06 2.06 0 1 0 3.5 6.12h.03A2.06 2.06 0 1 0 3.56 2Zm18.63 11.78c0-4.4-2.35-6.45-5.48-6.45a4.72 4.72 0 0 0-4.27 2.35h.03v-2H8.93S8.98 9 8.93 22h3.54v-8c0-.43.03-.86.16-1.16.35-.86 1.13-1.75 2.45-1.75 1.73 0 2.42 1.32 2.42 3.25V22h3.54v-8.22Z"/></svg>',
		);

		return isset( $icons[ $icon ] ) ? $icons[ $icon ] : '';
	}
}

$site_profile          = function_exists( 'variable_get_site_profile' ) ? variable_get_site_profile() : array();
$company_name          = isset( $site_profile['company_name'] ) ? $site_profile['company_name'] : '';
$tagline               = isset( $site_profile['tagline'] ) ? $site_profile['tagline'] : '';
$description           = isset( $site_profile['description'] ) ? $site_profile['description'] : '';
$postal_code           = isset( $site_profile['postal_code'] ) ? $site_profile['postal_code'] : '';
$address               = isset( $site_profile['address'] ) ? $site_profile['address'] : '';
$email                 = isset( $site_profile['email'] ) ? $site_profile['email'] : '';
$whatsapp_url          = isset( $site_profile['whatsapp_url'] ) ? $site_profile['whatsapp_url'] : '';
$footer_logo_url       = isset( $site_profile['footer_logo_url'] ) ? $site_profile['footer_logo_url'] : '';
$email_href            = $email ? 'mailto:' . $email : '';
$footer_copyright_note = variable_thema_get_theme_mod( 'footer_copyright_note' );
$social_links          = array(
	'x_url'         => array(
		'url'        => isset( $site_profile['x_url'] ) ? $site_profile['x_url'] : '',
		'icon'       => 'x',
		'label'      => __( 'X', 'variable-thema' ),
		'aria_label' => __( 'View X', 'variable-thema' ),
	),
	'instagram_url' => array(
		'url'        => isset( $site_profile['instagram_url'] ) ? $site_profile['instagram_url'] : '',
		'icon'       => 'instagram',
		'label'      => __( 'Instagram', 'variable-thema' ),
		'aria_label' => __( 'View Instagram', 'variable-thema' ),
	),
	'facebook_url'  => array(
		'url'        => isset( $site_profile['facebook_url'] ) ? $site_profile['facebook_url'] : '',
		'icon'       => 'facebook',
		'label'      => __( 'Facebook', 'variable-thema' ),
		'aria_label' => __( 'View Facebook', 'variable-thema' ),
	),
	'youtube_url'   => array(
		'url'        => isset( $site_profile['youtube_url'] ) ? $site_profile['youtube_url'] : '',
		'icon'       => 'youtube',
		'label'      => __( 'YouTube', 'variable-thema' ),
		'aria_label' => __( 'View YouTube', 'variable-thema' ),
	),
	'linkedin_url'  => array(
		'url'        => isset( $site_profile['linkedin_url'] ) ? $site_profile['linkedin_url'] : '',
		'icon'       => 'linkedin',
		'label'      => __( 'LinkedIn', 'variable-thema' ),
		'aria_label' => __( 'View LinkedIn', 'variable-thema' ),
	),
);
$social_links          = array_filter(
	$social_links,
	function ( $social_link ) {
		return ! empty( $social_link['url'] );
	}
);
$has_company_info      = (
	'' !== $footer_logo_url
	|| '' !== $company_name
	|| '' !== $tagline
	|| '' !== $description
	|| '' !== $postal_code
	|| '' !== $address
	|| '' !== $email
	|| '' !== $whatsapp_url
	|| ! empty( $social_links )
);
?>
<footer class="site-footer">
	<div class="site-footer__inner">
		<?php if ( $has_company_info ) : ?>
			<div class="site-footer__company">
				<?php if ( $footer_logo_url ) : ?>
					<div class="site-footer__logo">
						<img src="<?php echo esc_url( $footer_logo_url ); ?>" alt="<?php echo esc_attr( $company_name ? $company_name : get_bloginfo( 'name' ) ); ?>">
					</div>
				<?php endif; ?>

				<?php if ( ! $footer_logo_url && $company_name ) : ?>
					<p class="site-footer__company-name"><?php echo esc_html( $company_name ); ?></p>
				<?php endif; ?>

				<?php if ( $tagline ) : ?>
					<p class="site-footer__company-tagline"><?php echo esc_html( $tagline ); ?></p>
				<?php endif; ?>

				<?php if ( $description ) : ?>
					<p class="site-footer__company-description"><?php echo nl2br( esc_html( $description ) ); ?></p>
				<?php endif; ?>

				<?php if ( $postal_code || $address ) : ?>
					<ul class="site-footer__company-list">
						<li class="site-footer__company-item">
							<?php if ( $postal_code ) : ?>
								<span class="site-footer__postal-code"><?php echo esc_html( $postal_code ); ?></span>
							<?php endif; ?>
							<?php if ( $postal_code && $address ) : ?>
								<br>
							<?php endif; ?>
							<?php if ( $address ) : ?>
								<span class="site-footer__address"><?php echo nl2br( esc_html( $address ) ); ?></span>
							<?php endif; ?>
						</li>
					</ul>
				<?php endif; ?>

				<?php if ( $email || $whatsapp_url ) : ?>
					<div class="footer-contact-links" aria-label="<?php esc_attr_e( 'Contact links', 'variable-thema' ); ?>">
						<?php if ( $email ) : ?>
							<a class="footer-contact-links__item" href="<?php echo esc_url( $email_href ); ?>" aria-label="<?php esc_attr_e( 'Contact by email', 'variable-thema' ); ?>">
								<span class="footer-contact-links__icon">
									<?php
									// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Sanitized by variable_thema_kses_svg().
									echo variable_thema_kses_svg( variable_thema_get_footer_link_icon_svg( 'email' ) );
									?>
								</span>
								<span class="footer-contact-links__label"><?php echo esc_html( antispambot( $email ) ); ?></span>
							</a>
						<?php endif; ?>
						<?php if ( $whatsapp_url ) : ?>
							<a class="footer-contact-links__item" href="<?php echo esc_url( $whatsapp_url ); ?>" aria-label="<?php esc_attr_e( 'Contact via WhatsApp', 'variable-thema' ); ?>" target="_blank" rel="noopener noreferrer">
								<span class="footer-contact-links__icon">
									<?php
									// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Sanitized by variable_thema_kses_svg().
									echo variable_thema_kses_svg( variable_thema_get_footer_link_icon_svg( 'whatsapp' ) );
									?>
								</span>
								<span class="footer-contact-links__label"><?php esc_html_e( 'WhatsApp', 'variable-thema' ); ?></span>
							</a>
						<?php endif; ?>
					</div>
				<?php endif; ?>

				<?php if ( $social_links ) : ?>
					<div class="footer-social-links" aria-label="<?php esc_attr_e( 'Social links', 'variable-thema' ); ?>">
						<?php foreach ( $social_links as $social_link ) : ?>
							<a class="footer-social-links__item" href="<?php echo esc_url( $social_link['url'] ); ?>" aria-label="<?php echo esc_attr( $social_link['aria_label'] ); ?>" target="_blank" rel="noopener noreferrer">
								<span class="footer-social-links__icon">
									<?php
									// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Sanitized by variable_thema_kses_svg().
									echo variable_thema_kses_svg( variable_thema_get_footer_link_icon_svg( $social_link['icon'] ) );
									?>
								</span>
								<span class="footer-social-links__label"><?php echo esc_html( $social_link['label'] ); ?></span>
							</a>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<?php if ( variable_thema_get_theme_mod( 'show_footer_menu' ) && has_nav_menu( 'footer' ) ) : ?>
			<nav class="footer-nav" aria-label="<?php esc_attr_e( 'Footer Menu', 'variable-thema' ); ?>">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'footer',
						'container'      => false,
						'menu_class'     => 'footer-nav__list',
						'fallback_cb'    => false,
						'depth'          => 1,
					)
				);
				?>
			</nav>
		<?php endif; ?>

		<p class="site-footer__copyright">&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php echo esc_html( get_bloginfo( 'name' ) ); ?></p>
		<?php if ( $footer_copyright_note ) : ?>
			<p class="site-footer__copyright-note"><?php echo esc_html( $footer_copyright_note ); ?></p>
		<?php endif; ?>
	</div>
</footer>
