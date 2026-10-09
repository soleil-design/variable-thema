<?php
/**
 * FAQ list presentation template part.
 *
 * @package Variable_Thema
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$template_args = isset( $args ) ? $args : array();

$args = wp_parse_args(
	$template_args,
	array(
		'category'              => '',
		'posts_per_page'        => -1,
		'show_title'            => false,
		'title'                 => __( 'Frequently Asked Questions', 'variable-thema' ),
		'use_excerpt'           => false,
		'uncategorized'         => false,
		'exclude_uncategorized' => false,
		'orderby'               => array(
			'menu_order' => 'ASC',
			'date'       => 'DESC',
		),
		'order'                 => '',
		'archive_link'          => '',
		'archive_label'         => __( 'View all frequently asked questions', 'variable-thema' ),
		'post__in'              => array(),
		'meta_query'            => array(),
	)
);

$tax_query = array();

$query_args = array(
	'post_type'      => 'faq',
	'post_status'    => 'publish',
	'posts_per_page' => (int) $args['posts_per_page'],
	'orderby'        => $args['orderby'],
	'no_found_rows'  => true,
);

if ( $args['order'] ) {
	$query_args['order'] = $args['order'];

	if ( is_array( $query_args['orderby'] ) && array_key_exists( 'order', $template_args ) ) {
		$faq_order = strtoupper( $args['order'] );

		foreach ( $query_args['orderby'] as $orderby_key => $orderby_order ) {
			$query_args['orderby'][ $orderby_key ] = $faq_order;
		}
	}
}

if ( $args['post__in'] ) {
	$query_args['post__in'] = array_map( 'absint', (array) $args['post__in'] );

	if ( ! $args['orderby'] || 'post__in' === $args['orderby'] ) {
		$query_args['orderby'] = 'post__in';
	}
}

if ( $args['meta_query'] ) {
	$query_args['meta_query'] = $args['meta_query'];
}

if ( $args['uncategorized'] ) {
	$tax_query[] = array(
		'taxonomy' => 'faq_category',
		'operator' => 'NOT EXISTS',
	);
} elseif ( $args['category'] ) {
	$category = $args['category'];
	$field    = 'slug';
	$terms    = $category;

	if ( $category instanceof WP_Term ) {
		$field = 'term_id';
		$terms = array( (int) $category->term_id );
	} elseif ( is_numeric( $category ) ) {
		$field = 'term_id';
		$terms = array( (int) $category );
	}

	$tax_query[] = array(
		'taxonomy' => 'faq_category',
		'field'    => $field,
		'terms'    => $terms,
	);
}

if ( $args['exclude_uncategorized'] ) {
	$tax_query[] = array(
		'taxonomy' => 'faq_category',
		'operator' => 'EXISTS',
	);
}

if ( $tax_query ) {
	if ( 1 < count( $tax_query ) ) {
		$tax_query['relation'] = 'AND';
	}

	$query_args['tax_query'] = $tax_query;
}

$faq_query = new WP_Query( $query_args );

if ( ! $faq_query->have_posts() ) {
	return;
}

$archive_link = $args['archive_link'];

if ( true === $archive_link ) {
	$archive_link = get_post_type_archive_link( 'faq' );
}
?>
<section class="wp-block-group vt-faq vt-faq--dynamic">
	<?php if ( $args['show_title'] && $args['title'] ) : ?>
		<h2 class="wp-block-heading vt-faq__title"><?php echo esc_html( $args['title'] ); ?></h2>
	<?php endif; ?>

	<div class="wp-block-group vt-faq__list">
		<?php while ( $faq_query->have_posts() ) : ?>
			<?php $faq_query->the_post(); ?>
			<article <?php post_class( 'wp-block-group vt-faq__item' ); ?>>
				<p class="vt-faq__question"><?php the_title(); ?></p>
				<div class="vt-faq__answer">
					<?php
					if ( $args['use_excerpt'] && has_excerpt() ) {
						echo wp_kses_post( wpautop( get_the_excerpt() ) );
					} else {
						the_content();
					}
					?>
				</div>
			</article>
		<?php endwhile; ?>
	</div>

	<?php if ( $archive_link ) : ?>
		<p class="vt-faq__archive-link">
			<a href="<?php echo esc_url( $archive_link ); ?>"><?php echo esc_html( $args['archive_label'] ); ?></a>
		</p>
	<?php endif; ?>
</section>
<?php
wp_reset_postdata();
