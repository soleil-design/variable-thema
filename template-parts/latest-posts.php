<?php
/**
 * Latest posts presentation template part.
 *
 * @package Variable_Thema
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$template_args = isset( $args ) && is_array( $args ) ? $args : array();
$default_args  = apply_filters(
	'variable_thema_latest_posts_default_args',
	array(
		'post_types'     => 'post',
		'posts_per_page' => 3,
		'show_title'     => true,
		'title'          => __( 'Latest Posts', 'variable-thema' ),
		'use_excerpt'    => true,
		'archive_link'   => false,
		'archive_label'  => __( 'View all posts', 'variable-thema' ),
		'tabs'           => '',
	)
);
$args          = wp_parse_args(
	$template_args,
	$default_args
);

$posts_per_page  = max( 1, (int) $args['posts_per_page'] );
$base_post_types = is_scalar( $args['post_types'] ) ? trim( (string) $args['post_types'] ) : '';
$tab_config      = array();

$normalize_post_type = static function ( $value ) {
	$value = is_scalar( $value ) ? sanitize_key( (string) $value ) : '';

	if ( 'news' === $value ) {
		$value = 'information';
	} elseif ( 'blog' === $value ) {
		$value = 'post';
	}

	return $value ? $value : '';
};

$parse_post_types = static function ( $value, $default_post_types = array() ) use ( $normalize_post_type ) {
	$value = is_scalar( $value ) ? trim( (string) $value ) : '';

	if ( '' === $value ) {
		return array_values( array_filter( array_map( $normalize_post_type, $default_post_types ) ) );
	}

	$items = preg_split( '/\s*[|,]\s*/', $value );
	$items = array_values( array_filter( array_map( $normalize_post_type, $items ) ) );

	return $items;
};

$get_post_type_object_label = static function ( $latest_post_type ) {
	$latest_post_type_object = get_post_type_object( $latest_post_type );

	if ( $latest_post_type_object && ! is_wp_error( $latest_post_type_object ) ) {
		$labels = $latest_post_type_object->labels;

		if ( isset( $labels->name ) && '' !== trim( $labels->name ) ) {
			return $labels->name;
		}
	}

	return $latest_post_type;
};

$get_post_type_archive_link = static function ( $latest_post_type ) {
	if ( 'post' === $latest_post_type ) {
		$posts_page_id = (int) get_option( 'page_for_posts' );

		return $posts_page_id ? get_permalink( $posts_page_id ) : home_url( '/' );
	}

	$link = get_post_type_archive_link( $latest_post_type );

	if ( $link && ! is_wp_error( $link ) ) {
		return $link;
	}

	return '';
};

$build_query_args = static function ( $latest_post_type ) use ( $posts_per_page ) {
	if ( class_exists( 'Soleil_Content_Core_Latest_Posts' ) ) {
		return Soleil_Content_Core_Latest_Posts::get_query_args( $latest_post_type, $posts_per_page );
	}

	return array(
		'post_type'           => $latest_post_type,
		'post_status'         => 'publish',
		'posts_per_page'      => $posts_per_page,
		'orderby'             => 'date',
		'order'               => 'DESC',
		'ignore_sticky_posts' => true,
	);
};

$resolve_custom_tabs = static function ( $tabs_string ) use ( $normalize_post_type ) {
	$tabs_string = is_scalar( $tabs_string ) ? trim( (string) $tabs_string ) : '';

	if ( '' === $tabs_string ) {
		return array();
	}

	$tabs  = array();
	$parts = preg_split( '/\s*[|,]\s*/', $tabs_string );

	foreach ( $parts as $part ) {
		if ( '' === trim( $part ) ) {
			continue;
		}

		$pair  = array_map( 'trim', explode( ':', $part, 2 ) );
		$slug  = $normalize_post_type( $pair[0] );
		$label = isset( $pair[1] ) && '' !== $pair[1] ? $pair[1] : $pair[0];

		if ( '' === $slug || ! post_type_exists( $slug ) ) {
			continue;
		}

		$tabs[] = array(
			'slug'      => $slug,
			'label'     => $label,
			'post_type' => array( $slug ),
		);
	}

	return $tabs;
};

$custom_tabs           = $resolve_custom_tabs( $args['tabs'] );
$configured_post_types = $parse_post_types(
	$base_post_types,
	(array) apply_filters( 'variable_thema_latest_posts_default_post_types', array( 'post' ) )
);

if ( $custom_tabs ) {
	$tab_config = $custom_tabs;
} else {
	foreach ( $configured_post_types as $latest_post_type ) {
		if ( post_type_exists( $latest_post_type ) ) {
			$tab_config[] = array(
				'slug'      => $latest_post_type,
				'label'     => $get_post_type_object_label( $latest_post_type ),
				'post_type' => array( $latest_post_type ),
			);
		}
	}

	if ( ! $tab_config ) {
		$tab_config = array(
			array(
				'slug'      => 'post',
				'label'     => __( 'Blog', 'variable-thema' ),
				'post_type' => array( 'post' ),
			),
		);
	}
}

if ( ! array_filter(
	$tab_config,
	static function ( $latest_tab ) {
		return isset( $latest_tab['slug'] ) && 'all' === $latest_tab['slug'];
	}
) && 1 < count( $tab_config ) ) {
	array_unshift(
		$tab_config,
		array(
			'slug'      => 'all',
			'label'     => __( 'All', 'variable-thema' ),
			'post_type' => $configured_post_types,
		)
	);
}

$section_id = 'vt-latest-posts-' . wp_unique_id();
?>
<section class="vt-latest-posts" data-vt-latest-posts id="<?php echo esc_attr( $section_id ); ?>">
	<div class="vt-latest-posts__inner">
		<div class="vt-latest-posts__header">
			<div class="vt-latest-posts__title-wrap">
				<p class="vt-latest-posts__eyebrow"><?php echo esc_html( apply_filters( 'variable_thema_latest_posts_eyebrow', __( 'Latest Posts', 'variable-thema' ) ) ); ?></p>
				<?php if ( $args['show_title'] && $args['title'] ) : ?>
					<h2 class="vt-latest-posts__title"><?php echo esc_html( $args['title'] ); ?></h2>
				<?php endif; ?>
				<p class="vt-latest-posts__description"><?php echo esc_html( apply_filters( 'variable_thema_latest_posts_description', __( 'Read the latest articles from this site.', 'variable-thema' ) ) ); ?></p>
			</div>
		</div>

		<?php if ( count( $tab_config ) > 1 ) : ?>
			<div class="vt-latest-posts__tabs" role="tablist" aria-label="<?php echo esc_attr( $args['title'] ); ?>">
				<?php foreach ( $tab_config as $index => $latest_tab ) : ?>
					<?php
					$is_active     = 0 === $index;
					$tab_id        = $section_id . '-tab-' . $latest_tab['slug'];
					$panel_id      = $section_id . '-panel-' . $latest_tab['slug'];
					$archive_link  = '';
					$post_type_ids = isset( $latest_tab['post_type'] ) && is_array( $latest_tab['post_type'] ) ? array_values( array_filter( array_map( $normalize_post_type, $latest_tab['post_type'] ) ) ) : array();

					if ( $post_type_ids ) {
						$archive_link = $get_post_type_archive_link( reset( $post_type_ids ) );
					}
					?>
					<button
						type="button"
						class="vt-latest-posts__tab<?php echo $is_active ? ' is-active' : ''; ?>"
						role="tab"
						id="<?php echo esc_attr( $tab_id ); ?>"
						aria-controls="<?php echo esc_attr( $panel_id ); ?>"
						aria-selected="<?php echo $is_active ? 'true' : 'false'; ?>"
						data-vt-latest-tab="<?php echo esc_attr( $latest_tab['slug'] ); ?>"
						data-vt-latest-archive-link="<?php echo esc_url( $archive_link ); ?>"
						data-vt-latest-archive-label="<?php echo esc_attr( sprintf( /* translators: %s: Post type label. */ __( 'View all %s', 'variable-thema' ), $latest_tab['label'] ) ); ?>"
					>
						<?php echo esc_html( $latest_tab['label'] ); ?>
					</button>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

		<div class="vt-latest-posts__panels">
			<?php foreach ( $tab_config as $index => $latest_tab ) : ?>
				<?php
				$is_active     = 0 === $index;
				$tab_id        = $section_id . '-tab-' . $latest_tab['slug'];
				$panel_id      = $section_id . '-panel-' . $latest_tab['slug'];
				$post_type_ids = isset( $latest_tab['post_type'] ) && is_array( $latest_tab['post_type'] ) ? array_values( array_filter( array_map( $normalize_post_type, $latest_tab['post_type'] ) ) ) : array();
				$query_args    = array();

				if ( ! $post_type_ids ) {
					continue;
				}

				$query_args = $build_query_args( 1 === count( $post_type_ids ) ? $post_type_ids[0] : $post_type_ids );
				$query      = new WP_Query( $query_args );
				?>
				<div
					class="vt-latest-posts__panel<?php echo $is_active ? ' is-active' : ''; ?>"
					role="tabpanel"
					id="<?php echo esc_attr( $panel_id ); ?>"
					aria-labelledby="<?php echo esc_attr( $tab_id ); ?>"
					<?php echo $is_active ? '' : 'hidden'; ?>
					data-vt-latest-panel="<?php echo esc_attr( $latest_tab['slug'] ); ?>"
				>
					<div class="vt-latest-posts__slider-shell">
						<button
							type="button"
							class="vt-latest-posts__arrow vt-latest-posts__arrow--prev"
							data-vt-latest-prev
							aria-label="<?php esc_attr_e( 'Previous', 'variable-thema' ); ?>"
						>
							<span aria-hidden="true">‹</span>
						</button>

						<div class="vt-latest-posts__slider" data-vt-latest-slider>
							<div class="vt-latest-posts__track" data-vt-latest-track>
								<?php if ( $query->have_posts() ) : ?>
									<?php while ( $query->have_posts() ) : ?>
										<?php $query->the_post(); ?>
										<div class="vt-latest-posts__slide" data-vt-latest-slide>
											<?php
											get_template_part(
												'template-parts/post-card',
												null,
												array(
													'show_excerpt'   => false,
													'button_label'   => __( 'Read more', 'variable-thema' ),
													'thumbnail_size' => 'medium',
													'compact'         => true,
													'excerpt_length'  => 0,
												)
											);
											?>
										</div>
									<?php endwhile; ?>
								<?php else : ?>
									<div class="vt-latest-posts__slide vt-latest-posts__slide--empty" data-vt-latest-slide>
										<p class="vt-latest-posts__empty"><?php esc_html_e( 'No posts are currently available.', 'variable-thema' ); ?></p>
									</div>
								<?php endif; ?>
							</div>
						</div>

						<button
							type="button"
							class="vt-latest-posts__arrow vt-latest-posts__arrow--next"
							data-vt-latest-next
							aria-label="<?php esc_attr_e( 'Next', 'variable-thema' ); ?>"
						>
							<span aria-hidden="true">›</span>
						</button>
					</div>

					<div class="vt-latest-posts__dots" data-vt-latest-dots></div>
					<?php wp_reset_postdata(); ?>
				</div>
			<?php endforeach; ?>
		</div>

		<?php
		$archive_link = '';

		if ( true === $args['archive_link'] && ! empty( $tab_config ) ) {
			$first_tab = reset( $tab_config );
			if ( isset( $first_tab['post_type'] ) && is_array( $first_tab['post_type'] ) && ! empty( $first_tab['post_type'] ) ) {
				$archive_link = $get_post_type_archive_link( reset( $first_tab['post_type'] ) );
			}
		}
		?>

		<?php if ( $archive_link ) : ?>
			<p class="vt-latest-posts__archive-link">
				<a class="vt-button vt-button--text" href="<?php echo esc_url( $archive_link ); ?>" data-vt-latest-archive>
					<?php echo esc_html( $args['archive_label'] ); ?>
				</a>
			</p>
		<?php endif; ?>
	</div>
</section>
