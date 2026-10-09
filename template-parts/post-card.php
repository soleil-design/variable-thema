<?php
/**
 * Generic post card template part.
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
		'show_excerpt'   => true,
		'button_label'   => __( 'Read more', 'variable-thema' ),
		'thumbnail_size' => 'medium_large',
		'compact'        => false,
		'excerpt_length' => 0,
	)
);

$post_card_class = 'vt-card vt-post-card';

if ( $args['compact'] ) {
	$post_card_class .= ' vt-post-card--compact';
}
?>
<article <?php post_class( $post_card_class ); ?>>
	<?php variable_thema_post_thumbnail( $args['thumbnail_size'], 'vt-post-card__thumbnail', true ); ?>

	<div class="vt-post-card__body">
		<?php if ( $args['compact'] ) : ?>
			<h3 class="vt-post-card__title">
				<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
			</h3>
			<p class="vt-post-card__meta">
				<span class="vt-post-card__date">
					<?php variable_thema_posted_on(); ?>
				</span>
				<?php if ( 'post' === get_post_type() ) : ?>
					<span class="vt-post-card__categories">
						<?php variable_thema_post_categories(); ?>
					</span>
				<?php else : ?>
					<?php
					$card_post_type_object = get_post_type_object( get_post_type() );
					$post_type_label       = $card_post_type_object && ! is_wp_error( $card_post_type_object ) && isset( $card_post_type_object->labels->singular_name ) && '' !== trim( $card_post_type_object->labels->singular_name ) ? $card_post_type_object->labels->singular_name : get_post_type();
					?>
					<span class="vt-post-card__categories"><?php echo esc_html( $post_type_label ); ?></span>
				<?php endif; ?>
			</p>
		<?php else : ?>
			<header class="vt-post-card__header">
				<h2 class="vt-post-card__title">
					<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
				</h2>
				<?php if ( 'post' === get_post_type() ) : ?>
					<p class="vt-post-card__meta">
						<?php variable_thema_posted_on(); ?>
						<?php variable_thema_post_categories(); ?>
					</p>
				<?php endif; ?>
			</header>

			<?php if ( $args['show_excerpt'] ) : ?>
				<div class="vt-post-card__excerpt">
					<?php
					if ( $args['excerpt_length'] > 0 && function_exists( 'variable_thema_excerpt' ) ) {
						$excerpt = wp_trim_words( get_the_excerpt(), (int) $args['excerpt_length'] );
						echo '<p>' . esc_html( $excerpt ) . '</p>';
					} else {
						variable_thema_excerpt();
					}
					?>
				</div>
			<?php endif; ?>

			<a class="vt-button vt-button--text vt-post-card__button" href="<?php the_permalink(); ?>">
				<?php echo esc_html( $args['button_label'] ); ?>
			</a>
		<?php endif; ?>
	</div>
</article>
