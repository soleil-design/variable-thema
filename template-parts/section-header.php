<?php
/**
 * Generic section header template part.
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
		'eyebrow'     => '',
		'title'       => '',
		'description' => '',
		'level'       => 2,
	)
);
$heading_tag   = 'h' . min( 6, max( 2, (int) $args['level'] ) );

if ( '' === trim( wp_strip_all_tags( $args['title'] ) ) ) {
	return;
}
?>
<header class="vt-section-header">
	<?php if ( $args['eyebrow'] ) : ?>
		<p class="vt-section-header__eyebrow"><?php echo esc_html( $args['eyebrow'] ); ?></p>
	<?php endif; ?>

	<<?php echo esc_attr( $heading_tag ); ?> class="vt-section-header__title">
		<?php echo esc_html( $args['title'] ); ?>
	</<?php echo esc_attr( $heading_tag ); ?>>

	<?php if ( $args['description'] ) : ?>
		<p class="vt-section-header__description"><?php echo esc_html( $args['description'] ); ?></p>
	<?php endif; ?>
</header>
