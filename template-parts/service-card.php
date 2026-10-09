<?php
/**
 * Generic service card template part.
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
		'show_terms'     => true,
		'terms_taxonomy' => 'service_category',
		'link_label'     => __( 'Learn more', 'variable-thema' ),
	)
);

$show_terms     = (bool) $args['show_terms'];
$terms_taxonomy = is_scalar( $args['terms_taxonomy'] ) ? (string) $args['terms_taxonomy'] : '';
$link_label     = is_scalar( $args['link_label'] ) ? trim( (string) $args['link_label'] ) : '';

if ( '' === $link_label ) {
	$link_label = __( 'Learn more', 'variable-thema' );
}

$get_service_field = static function ( $field_name ) {
	return variable_thema_get_service_field( $field_name, get_the_ID() );
};

$service_icon     = $get_service_field( 'service_icon' );
$service_summary  = $get_service_field( 'service_summary' );
$service_price    = variable_thema_format_service_price( $get_service_field( 'service_price' ) );
$service_terms    = array();
$service_taxonomy = $terms_taxonomy && taxonomy_exists( $terms_taxonomy ) ? $terms_taxonomy : '';

if ( $service_taxonomy ) {
	$service_terms = get_the_terms( get_the_ID(), $service_taxonomy );
	$service_terms = is_wp_error( $service_terms ) || ! is_array( $service_terms ) ? array() : $service_terms;
} else {
	$taxonomies = get_object_taxonomies( get_post_type(), 'objects' );

	foreach ( $taxonomies as $service_taxonomy_object ) {
		if ( empty( $service_taxonomy_object->public ) || 'post_format' === $service_taxonomy_object->name ) {
			continue;
		}

		$terms = get_the_terms( get_the_ID(), $service_taxonomy_object->name );

		if ( empty( $terms ) || is_wp_error( $terms ) ) {
			continue;
		}

		$service_terms = array_merge( $service_terms, $terms );
	}
}

?>
<article <?php post_class( 'vt-service-card' ); ?>>
	<?php if ( $service_icon ) : ?>
		<div class="vt-service-card__icon" aria-hidden="true">
			<?php variable_thema_render_service_icon( $service_icon, 'vt-service-card__icon-image' ); ?>
		</div>
	<?php endif; ?>

	<div class="vt-service-card__body">
		<header class="vt-service-card__header">
			<h3 class="vt-service-card__title">
				<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
			</h3>

			<?php if ( $show_terms && $service_terms ) : ?>
				<div class="vt-service-card__terms">
					<?php foreach ( $service_terms as $service_term ) : ?>
						<?php $term_link = get_term_link( $service_term ); ?>
						<?php if ( is_wp_error( $term_link ) ) : ?>
							<span class="vt-service-card__term"><?php echo esc_html( $service_term->name ); ?></span>
						<?php else : ?>
							<a class="vt-service-card__term" href="<?php echo esc_url( $term_link ); ?>"><?php echo esc_html( $service_term->name ); ?></a>
						<?php endif; ?>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</header>

			<?php if ( $service_summary || has_excerpt() ) : ?>
				<div class="vt-service-card__summary">
					<?php
					if ( $service_summary ) {
						echo wp_kses_post( wpautop( $service_summary ) );
					} elseif ( has_excerpt() ) {
						echo wp_kses_post( wpautop( get_the_excerpt() ) );
					}
					?>
				</div>
			<?php endif; ?>

		<?php if ( $service_price ) : ?>
			<p class="vt-service-card__price"><?php echo esc_html( $service_price ); ?></p>
		<?php endif; ?>

		<a class="vt-service-card__link" href="<?php the_permalink(); ?>"><?php echo esc_html( $link_label ); ?></a>
	</div>
</article>
