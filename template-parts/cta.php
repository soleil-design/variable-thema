<?php
/**
 * Generic call-to-action template part.
 *
 * @package Variable_Thema
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$template_args = isset( $args ) && is_array( $args ) ? $args : array();
$args          = wp_parse_args(
	$template_args,
	array(
		'title'       => '',
		'description' => '',
		'buttons'     => array(),
	)
);

$cta_title   = is_scalar( $args['title'] ) ? (string) $args['title'] : '';
$description = is_scalar( $args['description'] ) ? (string) $args['description'] : '';
$buttons     = is_array( $args['buttons'] ) ? $args['buttons'] : array();
$variants    = array( 'primary', 'secondary', 'tertiary' );
$buttons     = array_filter(
	array_map(
		function ( $button ) use ( $variants ) {
			if ( ! is_array( $button ) ) {
				return null;
			}

			$label   = isset( $button['label'] ) && is_scalar( $button['label'] ) ? (string) $button['label'] : '';
			$url     = isset( $button['url'] ) && is_scalar( $button['url'] ) ? (string) $button['url'] : '';
			$variant = isset( $button['variant'] ) && is_scalar( $button['variant'] ) ? (string) $button['variant'] : 'secondary';
			$icon    = isset( $button['icon'] ) && is_scalar( $button['icon'] ) ? (string) $button['icon'] : '';

			$label = trim( $label );
			$url   = trim( $url );
			$icon  = trim( $icon );

			if ( '' === $label || '' === $url ) {
				return null;
			}

			if ( ! in_array( $variant, $variants, true ) ) {
				$variant = 'secondary';
			}

			return array(
				'label'   => $label,
				'url'     => $url,
				'variant' => $variant,
				'icon'    => $icon,
			);
		},
		$buttons
	)
);

if (
	'' === trim( wp_strip_all_tags( $cta_title ) )
	&& '' === trim( wp_strip_all_tags( $description ) )
	&& empty( $buttons )
) {
	return;
}
?>
<section class="vt-cta">
	<div class="vt-cta__inner">
		<?php if ( '' !== trim( wp_strip_all_tags( $cta_title ) ) ) : ?>
			<h2 class="vt-cta__title"><?php echo esc_html( $cta_title ); ?></h2>
		<?php endif; ?>

		<?php if ( '' !== trim( wp_strip_all_tags( $description ) ) ) : ?>
			<p class="vt-cta__description vt-cta__text"><?php echo esc_html( $description ); ?></p>
		<?php endif; ?>

		<?php if ( $buttons ) : ?>
			<div class="vt-cta__actions">
				<?php foreach ( $buttons as $button ) : ?>
					<?php
					$button_class = sprintf(
						'vt-cta__button vt-cta__button--%s',
						$button['variant']
					);
					$button_icon  = function_exists( 'variable_get_cta_icon_svg' ) ? variable_get_cta_icon_svg( $button['icon'] ) : '';
					?>
					<a class="<?php echo esc_attr( $button_class ); ?>" href="<?php echo esc_url( $button['url'] ); ?>">
						<?php if ( $button_icon ) : ?>
							<span class="vt-cta__button-icon" aria-hidden="true">
								<?php
								// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Sanitized by variable_thema_kses_svg().
								echo variable_thema_kses_svg( $button_icon );
								?>
							</span>
						<?php endif; ?>
						<span class="vt-cta__button-label"><?php echo esc_html( $button['label'] ); ?></span>
					</a>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
