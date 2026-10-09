<?php
/**
 * The single service presentation template.
 *
 * @package Variable_Thema
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$get_service_field = static function ( $field_name ) {
	return variable_thema_get_service_field( $field_name, get_the_ID() );
};

$normalize_posts = static function ( $posts ) {
	$ids = array();

	foreach ( (array) $posts as $post_item ) {
		if ( $post_item instanceof WP_Post ) {
			$ids[] = (int) $post_item->ID;
		} elseif ( is_numeric( $post_item ) ) {
			$ids[] = (int) $post_item;
		}
	}

	return array_values( array_unique( array_filter( $ids ) ) );
};
?>
<main id="main" class="site-main">
	<div class="site-main__inner">
		<?php get_template_part( 'template-parts/breadcrumb' ); ?>

		<?php if ( have_posts() ) : ?>
			<?php while ( have_posts() ) : ?>
				<?php
				the_post();

				$service_subtitle    = $get_service_field( 'service_subtitle' );
				$service_summary     = $get_service_field( 'service_summary' );
				$service_catchcopy   = $get_service_field( 'service_catchcopy' );
				$service_price       = $get_service_field( 'service_price' );
				$service_duration    = $get_service_field( 'service_duration' );
				$service_scope       = $get_service_field( 'service_scope' );
				$service_icon        = $get_service_field( 'service_icon' );
				$service_featured    = $get_service_field( 'service_featured' );
				$service_related     = $get_service_field( 'service_related' );
				$service_related_faq = $get_service_field( 'service_related_faq' );
				$related_service_ids = $normalize_posts( $service_related );
				$related_faq_ids     = $normalize_posts( $service_related_faq );
				$service_terms       = array();
				$service_taxonomies  = get_object_taxonomies( get_post_type(), 'objects' );

				$service_price = variable_thema_format_service_price( $service_price );

				foreach ( $service_taxonomies as $service_taxonomy_object ) {
					if ( empty( $service_taxonomy_object->public ) || 'post_format' === $service_taxonomy_object->name ) {
						continue;
					}

					$terms = get_the_terms( get_the_ID(), $service_taxonomy_object->name );

					if ( empty( $terms ) || is_wp_error( $terms ) ) {
						continue;
					}

					foreach ( $terms as $service_term ) {
						$service_terms[] = $service_term;
					}
				}
				?>
				<article <?php post_class( 'vt-service-single' ); ?>>
					<header class="vt-service-hero">
						<div class="vt-service-hero__content">
							<?php if ( $service_featured ) : ?>
								<p class="vt-service-hero__badge"><?php esc_html_e( 'Featured service', 'variable-thema' ); ?></p>
							<?php endif; ?>

							<div class="vt-service-hero__heading">
								<?php if ( $service_icon ) : ?>
									<div class="vt-service-hero__icon" aria-hidden="true">
										<?php variable_thema_render_service_icon( $service_icon, 'vt-service-hero__icon-image' ); ?>
									</div>
								<?php endif; ?>

								<div>
									<?php if ( $service_subtitle ) : ?>
										<p class="vt-service-hero__subtitle"><?php echo esc_html( $service_subtitle ); ?></p>
									<?php endif; ?>
									<h1 class="vt-service-hero__title"><?php the_title(); ?></h1>
								</div>
							</div>

							<?php if ( $service_catchcopy ) : ?>
								<p class="vt-service-hero__catchcopy"><?php echo esc_html( $service_catchcopy ); ?></p>
							<?php endif; ?>

							<?php if ( $service_terms ) : ?>
								<div class="vt-service-meta" aria-label="<?php esc_attr_e( 'Service categories', 'variable-thema' ); ?>">
									<?php foreach ( $service_terms as $service_term ) : ?>
										<?php $term_link = get_term_link( $service_term ); ?>
										<?php if ( is_wp_error( $term_link ) ) : ?>
											<span class="vt-service-meta__item"><?php echo esc_html( $service_term->name ); ?></span>
										<?php else : ?>
											<a class="vt-service-meta__item" href="<?php echo esc_url( $term_link ); ?>"><?php echo esc_html( $service_term->name ); ?></a>
										<?php endif; ?>
									<?php endforeach; ?>
								</div>
							<?php endif; ?>
						</div>

						<?php if ( has_post_thumbnail() ) : ?>
							<figure class="vt-service-hero__media">
								<?php the_post_thumbnail( 'large', array( 'class' => 'vt-service-hero__image' ) ); ?>
							</figure>
						<?php endif; ?>
					</header>

					<div class="vt-service-body">
					<?php if ( $service_summary || $service_price || $service_duration || $service_scope ) : ?>
						<section class="vt-service-summary" aria-label="<?php esc_attr_e( 'Service overview', 'variable-thema' ); ?>">
							<?php if ( $service_summary ) : ?>
								<div class="vt-service-summary__lead">
									<?php echo wp_kses_post( wpautop( $service_summary ) ); ?>
								</div>
							<?php endif; ?>

							<?php if ( $service_price || $service_duration || $service_scope ) : ?>
								<dl class="vt-service-summary__list">
									<?php if ( $service_price ) : ?>
										<div class="vt-service-summary__row vt-service-price">
											<dt><?php esc_html_e( 'Price', 'variable-thema' ); ?></dt>
											<dd><?php echo esc_html( $service_price ); ?></dd>
										</div>
									<?php endif; ?>

									<?php if ( $service_duration ) : ?>
										<div class="vt-service-summary__row">
											<dt><?php esc_html_e( 'Duration', 'variable-thema' ); ?></dt>
											<dd><?php echo esc_html( $service_duration ); ?></dd>
										</div>
									<?php endif; ?>

									<?php if ( $service_scope ) : ?>
										<div class="vt-service-summary__row">
											<dt><?php esc_html_e( 'Coverage', 'variable-thema' ); ?></dt>
											<dd>
												<?php if ( is_array( $service_scope ) ) : ?>
													<ul class="vt-service-summary__scope-list">
														<?php foreach ( $service_scope as $scope_item ) : ?>
															<?php if ( is_scalar( $scope_item ) && '' !== trim( (string) $scope_item ) ) : ?>
																<li><?php echo esc_html( (string) $scope_item ); ?></li>
															<?php endif; ?>
														<?php endforeach; ?>
													</ul>
												<?php elseif ( is_scalar( $service_scope ) ) : ?>
													<?php echo wp_kses_post( nl2br( esc_html( (string) $service_scope ) ) ); ?>
												<?php endif; ?>
											</dd>
										</div>
									<?php endif; ?>
								</dl>
							<?php endif; ?>
						</section>
					<?php endif; ?>

					<div class="vt-entry__content vt-service-single__content">
						<?php
						the_content();

						wp_link_pages(
							array(
								'before' => '<nav class="vt-page-links" aria-label="' . esc_attr__( 'Page links', 'variable-thema' ) . '">',
								'after'  => '</nav>',
							)
						);
						?>
					</div>

					<?php if ( $related_faq_ids ) : ?>
						<section class="vt-service-single__faq">
							<?php
							get_template_part(
								'template-parts/faq-list',
								null,
								array(
									'post__in'       => $related_faq_ids,
									'posts_per_page' => -1,
									'show_title'     => true,
									'title'          => __( 'Frequently Asked Questions', 'variable-thema' ),
									'orderby'        => 'post__in',
								)
							);
							?>
						</section>
					<?php endif; ?>

					<?php if ( $related_service_ids ) : ?>
						<?php
						$related_services = new WP_Query(
							array(
								'post_type'      => 'service',
								'post_status'    => 'publish',
								'post__in'       => $related_service_ids,
								'orderby'        => 'post__in',
								'posts_per_page' => -1,
								'no_found_rows'  => true,
							)
						);
						?>
						<?php if ( $related_services->have_posts() ) : ?>
						<section class="vt-service-related">
							<h2 class="vt-service-related__title"><?php esc_html_e( 'Related services', 'variable-thema' ); ?></h2>
							<div class="vt-service-related__grid">
								<?php while ( $related_services->have_posts() ) : ?>
									<?php
									$related_services->the_post();
									$related_summary = $get_service_field( 'service_summary' );
									$related_price   = variable_thema_format_service_price( $get_service_field( 'service_price' ) );
									?>
									<article <?php post_class( 'vt-service-related__card' ); ?>>
										<h3 class="vt-service-related__card-title">
											<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
										</h3>

										<?php if ( $related_summary ) : ?>
											<div class="vt-service-related__excerpt">
												<?php echo wp_kses_post( wpautop( $related_summary ) ); ?>
											</div>
										<?php elseif ( has_excerpt() ) : ?>
											<p class="vt-service-related__excerpt"><?php echo esc_html( get_the_excerpt() ); ?></p>
										<?php endif; ?>

										<?php if ( $related_price ) : ?>
											<p class="vt-service-related__price"><?php echo esc_html( $related_price ); ?></p>
										<?php endif; ?>

										<a class="vt-service-related__link" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Learn more', 'variable-thema' ); ?></a>
									</article>
								<?php endwhile; ?>
								<?php wp_reset_postdata(); ?>
							</div>
						</section>
						<?php else : ?>
							<?php wp_reset_postdata(); ?>
						<?php endif; ?>
					<?php endif; ?>
					</div>

				</article>
			<?php endwhile; ?>
		<?php else : ?>
			<?php get_template_part( 'template-parts/content-none' ); ?>
		<?php endif; ?>
	</div>
</main>
<?php
get_footer();
