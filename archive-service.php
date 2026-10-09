<?php
/**
 * The service archive presentation template.
 *
 * @package Variable_Thema
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$service_taxonomy = '';
if ( taxonomy_exists( 'service_category' ) ) {
	$service_taxonomy = 'service_category';
} else {
	$service_taxonomies = get_object_taxonomies( 'service', 'objects' );

	foreach ( $service_taxonomies as $service_taxonomy_object ) {
		if ( empty( $service_taxonomy_object->public ) || 'post_format' === $service_taxonomy_object->name ) {
			continue;
		}

		$service_taxonomy = $service_taxonomy_object->name;
		break;
	}
}

$build_query_args = static function ( $extra = array() ) {
	$query_args = array(
		'post_type'      => 'service',
		'post_status'    => 'publish',
		'posts_per_page' => -1,
		'no_found_rows'  => true,
		'meta_key'       => 'service_order',
		'orderby'        => array(
			'meta_value_num' => 'ASC',
			'menu_order'     => 'ASC',
			'date'           => 'DESC',
		),
	);

	return array_merge( $query_args, $extra );
};

$featured_ids   = array();
$featured_query = new WP_Query(
	$build_query_args(
		array(
			'meta_query' => array(
				array(
					'key'     => 'service_featured',
					'value'   => '1',
					'compare' => '=',
				),
			),
		)
	)
);

if ( $featured_query->have_posts() ) {
	while ( $featured_query->have_posts() ) {
		$featured_query->the_post();
		$featured_ids[] = (int) get_the_ID();
	}
	wp_reset_postdata();
}

$all_service_query_args = $build_query_args();

if ( $featured_ids ) {
	$all_service_query_args['post__not_in'] = $featured_ids;
}

$service_terms = array();
if ( $service_taxonomy ) {
	$service_terms = get_terms(
		array(
			'taxonomy'   => $service_taxonomy,
			'hide_empty' => true,
			'orderby'    => 'term_id',
			'order'      => 'ASC',
		)
	);

	if ( is_wp_error( $service_terms ) ) {
		$service_terms = array();
	}
}

$archive_lead       = __( 'Browse services organized by goals and challenges, with the information you need easy to find.', 'variable-thema' );
$has_service_output = false;
?>
<main id="main" class="site-main">
	<div class="site-main__inner">
		<?php get_template_part( 'template-parts/breadcrumb' ); ?>

		<header class="vt-service-archive__hero">
			<p class="vt-service-archive__eyebrow"><?php esc_html_e( 'Service', 'variable-thema' ); ?></p>
			<h1 class="vt-service-archive__title"><?php esc_html_e( 'Services', 'variable-thema' ); ?></h1>
			<p class="vt-service-archive__lead"><?php echo esc_html( $archive_lead ); ?></p>
		</header>

		<?php if ( $featured_query && $featured_query->have_posts() ) : ?>
			<?php $has_service_output = true; ?>
			<section class="vt-service-archive__section vt-service-archive__section--featured">
				<h2 class="vt-service-archive__section-title"><?php esc_html_e( 'Featured service', 'variable-thema' ); ?></h2>
				<div class="vt-service-grid">
					<?php while ( $featured_query->have_posts() ) : ?>
						<?php $featured_query->the_post(); ?>
						<?php get_template_part( 'template-parts/service-card', null, array( 'terms_taxonomy' => $service_taxonomy ) ); ?>
					<?php endwhile; ?>
				</div>
			</section>
			<?php wp_reset_postdata(); ?>
		<?php endif; ?>

		<section class="vt-service-archive__section vt-service-archive__section--regular">
			<h2 class="vt-service-archive__section-title"><?php esc_html_e( 'All services', 'variable-thema' ); ?></h2>

			<?php if ( $service_taxonomy && $service_terms ) : ?>
				<?php
				$has_uncategorized   = false;
				$uncategorized_query = new WP_Query(
					$build_query_args(
						array(
							'tax_query' => array(
								array(
									'taxonomy' => $service_taxonomy,
									'operator' => 'NOT EXISTS',
								),
							),
						)
					)
				);

				if ( $uncategorized_query->have_posts() ) {
					$has_uncategorized = true;
				}
				wp_reset_postdata();
				?>

				<?php foreach ( $service_terms as $service_term ) : ?>
					<?php
					$term_query = new WP_Query(
						$build_query_args(
							array(
								'post__not_in' => $featured_ids,
								'tax_query'    => array(
									array(
										'taxonomy' => $service_taxonomy,
										'field'    => 'term_id',
										'terms'    => array( (int) $service_term->term_id ),
									),
								),
							)
						)
					);
					?>
						<?php if ( $term_query->have_posts() ) : ?>
							<?php $has_service_output = true; ?>
							<section class="vt-service-archive__group">
							<h3 class="vt-service-archive__group-title"><?php echo esc_html( $service_term->name ); ?></h3>
							<div class="vt-service-grid">
								<?php while ( $term_query->have_posts() ) : ?>
									<?php $term_query->the_post(); ?>
									<?php get_template_part( 'template-parts/service-card', null, array( 'terms_taxonomy' => $service_taxonomy ) ); ?>
								<?php endwhile; ?>
							</div>
						</section>
					<?php endif; ?>
					<?php wp_reset_postdata(); ?>
				<?php endforeach; ?>

					<?php if ( $has_uncategorized ) : ?>
						<?php
						$uncategorized_query = new WP_Query(
							$build_query_args(
								array(
									'post__not_in' => $featured_ids,
									'tax_query'    => array(
										array(
											'taxonomy' => $service_taxonomy,
											'operator' => 'NOT EXISTS',
										),
									),
								)
							)
						);
						?>
						<?php if ( $uncategorized_query->have_posts() ) : ?>
							<?php $has_service_output = true; ?>
							<section class="vt-service-archive__group">
							<h3 class="vt-service-archive__group-title"><?php echo esc_html_x( 'Other', 'service archive section', 'variable-thema' ); ?></h3>
							<div class="vt-service-grid">
								<?php while ( $uncategorized_query->have_posts() ) : ?>
									<?php $uncategorized_query->the_post(); ?>
									<?php get_template_part( 'template-parts/service-card', null, array( 'terms_taxonomy' => $service_taxonomy ) ); ?>
								<?php endwhile; ?>
							</div>
						</section>
					<?php endif; ?>
						<?php wp_reset_postdata(); ?>
				<?php endif; ?>
			<?php else : ?>
				<?php
				$regular_query = new WP_Query( $all_service_query_args );
				?>
				<?php if ( $regular_query->have_posts() ) : ?>
					<?php $has_service_output = true; ?>
					<div class="vt-service-grid">
						<?php while ( $regular_query->have_posts() ) : ?>
							<?php $regular_query->the_post(); ?>
							<?php get_template_part( 'template-parts/service-card', null, array( 'terms_taxonomy' => $service_taxonomy ) ); ?>
						<?php endwhile; ?>
					</div>
				<?php endif; ?>
				<?php wp_reset_postdata(); ?>
			<?php endif; ?>

			<?php if ( ! $has_service_output ) : ?>
				<?php get_template_part( 'template-parts/content-none' ); ?>
			<?php endif; ?>
		</section>
	</div>
</main>
<?php
get_footer();
