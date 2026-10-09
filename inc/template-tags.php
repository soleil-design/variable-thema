<?php
/**
 * Reusable presentation and template tag helpers.
 *
 * @package Variable_Thema
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Sanitize the theme's fixed decorative SVG markup.
 *
 * @param string $svg SVG markup from an internal registry.
 * @return string
 */
function variable_thema_kses_svg( $svg ) {
	return wp_kses(
		$svg,
		array(
			'svg'  => array(
				'viewbox'     => true,
				'aria-hidden' => true,
				'focusable'   => true,
			),
			'path' => array(
				'd' => true,
			),
		)
	);
}

/**
 * Display the published date for a post.
 *
 * @since 0.8.0
 * @param int $post_id Optional post ID.
 * @return void
 */
function variable_thema_posted_on( $post_id = 0 ) {
	$post_id = $post_id ? (int) $post_id : get_the_ID();

	if ( ! $post_id ) {
		return;
	}

	printf(
		'<time class="vt-posted-on" datetime="%1$s">%2$s</time>',
		esc_attr( get_the_date( DATE_W3C, $post_id ) ),
		esc_html( get_the_date( '', $post_id ) )
	);
}

/**
 * Display the modified date when it differs from publication.
 *
 * @since 0.8.0
 * @param int $post_id Optional post ID.
 * @return void
 */
function variable_thema_updated_on( $post_id = 0 ) {
	$post_id = $post_id ? (int) $post_id : get_the_ID();

	if ( ! $post_id || get_post_modified_time( 'U', false, $post_id ) <= get_post_time( 'U', false, $post_id ) ) {
		return;
	}

	printf(
		'<time class="vt-updated-on" datetime="%1$s">%2$s</time>',
		esc_attr( get_the_modified_date( DATE_W3C, $post_id ) ),
		esc_html( get_the_modified_date( '', $post_id ) )
	);
}

/**
 * Display category links for a standard post.
 *
 * @since 0.8.0
 * @param int $post_id Optional post ID.
 * @return void
 */
function variable_thema_post_categories( $post_id = 0 ) {
	$post_id = $post_id ? (int) $post_id : get_the_ID();

	if ( ! $post_id || 'post' !== get_post_type( $post_id ) ) {
		return;
	}

	$categories = get_the_category_list( esc_html__( ', ', 'variable-thema' ), '', $post_id );

	if ( ! $categories ) {
		return;
	}

	echo '<span class="vt-post-categories">' . wp_kses_post( $categories ) . '</span>';
}

/**
 * Display a post thumbnail with optional linking.
 *
 * @since 0.8.0
 * @param string|int|array $size Registered image size or dimensions.
 * @param string           $class_name Wrapper class name.
 * @param bool             $linked Whether to link the image.
 * @param int              $post_id Optional post ID.
 * @return void
 */
function variable_thema_post_thumbnail( $size = 'large', $class_name = 'vt-entry__thumbnail', $linked = false, $post_id = 0 ) {
	$post_id = $post_id ? (int) $post_id : get_the_ID();

	if ( ! $post_id || ! has_post_thumbnail( $post_id ) ) {
		return;
	}

	$thumbnail = get_the_post_thumbnail( $post_id, $size );

	if ( ! $thumbnail ) {
		return;
	}

	if ( $linked ) {
		printf(
			'<a class="%1$s" href="%2$s" aria-hidden="true" tabindex="-1">%3$s</a>',
			esc_attr( $class_name ),
			esc_url( get_permalink( $post_id ) ),
			wp_kses_post( $thumbnail )
		);
		return;
	}

	printf(
		'<div class="%1$s">%2$s</div>',
		esc_attr( $class_name ),
		wp_kses_post( $thumbnail )
	);
}

/**
 * Display a trimmed post excerpt.
 *
 * @since 0.8.0
 * @param int $word_count Maximum word count.
 * @param int $post_id Optional post ID.
 * @return void
 */
function variable_thema_excerpt( $word_count = 28, $post_id = 0 ) {
	$post_id = $post_id ? (int) $post_id : get_the_ID();

	if ( ! $post_id ) {
		return;
	}

	$excerpt = has_excerpt( $post_id )
		? get_the_excerpt( $post_id )
		: wp_trim_words( wp_strip_all_tags( strip_shortcodes( get_post_field( 'post_content', $post_id ) ) ), $word_count );

	if ( '' === trim( $excerpt ) ) {
		return;
	}

	echo '<p>' . esc_html( $excerpt ) . '</p>';
}

/**
 * Get a normalized Service field without requiring ACF.
 *
 * Soleil Content Core is the preferred data owner. The post meta fallback keeps
 * the public theme usable when Core is unavailable.
 *
 * @param string           $field_name Service field name.
 * @param int|WP_Post|null $post       Post ID or object. Defaults to the current post.
 * @param mixed            $fallback   Value returned for an invalid post or field.
 * @return mixed
 */
function variable_thema_get_service_field( $field_name, $post = null, $fallback = '' ) {
	$field_name = is_string( $field_name ) ? $field_name : '';
	$post       = get_post( $post );

	if ( ! $post instanceof WP_Post || 'service' !== $post->post_type || 0 !== strpos( $field_name, 'service_' ) ) {
		return $fallback;
	}

	if ( function_exists( 'soleil_content_core_get_normalized_service_field' ) ) {
		return soleil_content_core_get_normalized_service_field( $post, $field_name, $fallback );
	}

	if ( ! metadata_exists( 'post', $post->ID, $field_name ) ) {
		return $fallback;
	}

	$value = get_post_meta( $post->ID, $field_name, true );

	if ( 'service_featured' === $field_name ) {
		return '1' === (string) $value;
	}

	if ( 'service_order' === $field_name ) {
		return is_numeric( $value ) ? (int) $value : 0;
	}

	$relationship_types = array(
		'service_related'     => 'service',
		'service_related_faq' => 'faq',
	);

	if ( isset( $relationship_types[ $field_name ] ) ) {
		$related_id   = is_scalar( $value ) ? absint( $value ) : 0;
		$related_post = $related_id ? get_post( $related_id ) : null;

		return $related_post instanceof WP_Post && $relationship_types[ $field_name ] === $related_post->post_type ? $related_id : 0;
	}

	return is_scalar( $value ) ? (string) $value : $fallback;
}

/**
 * Format a service price for display.
 *
 * @since 0.8.0
 * @param mixed $price Stored service price.
 * @return string
 */
function variable_thema_format_service_price( $price ) {
	if ( null === $price ) {
		return '';
	}

	if ( is_string( $price ) ) {
		$price = trim( $price );
	}

	if ( '' === $price ) {
		return '';
	}

	if ( is_numeric( $price ) ) {
		return sprintf(
			/* translators: %s: Formatted service price. */
			__( '¥%s (tax included)', 'variable-thema' ),
			number_format( (float) $price )
		);
	}

	return (string) $price;
}

/**
 * Get a Dashicon class for a service icon key.
 *
 * @since 0.8.0
 * @param string $icon Service icon key.
 * @return string
 */
function variable_thema_get_service_dashicon_class( $icon ) {
	$map = array(
		'scan'    => 'dashicons-search',
		'shield'  => 'dashicons-shield',
		'repair'  => 'dashicons-admin-tools',
		'support' => 'dashicons-sos',
		'ai'      => 'dashicons-superhero',
		'service' => 'dashicons-hammer',
		'mail'    => 'dashicons-email-alt',
		'setting' => 'dashicons-admin-generic',
		'report'  => 'dashicons-chart-area',
	);

	return isset( $map[ $icon ] ) ? $map[ $icon ] : 'dashicons-admin-generic';
}

/**
 * Display a normalized service icon.
 *
 * @since 0.8.0
 * @param mixed  $icon Image or icon value.
 * @param string $class_name Additional class name.
 * @return void
 */
function variable_thema_render_service_icon( $icon, $class_name = '' ) {
	if ( empty( $icon ) ) {
		return;
	}

	$class_name = is_scalar( $class_name ) ? trim( (string) $class_name ) : '';
	$classes    = array_filter( array( 'dashicons' ) );
	$image_url  = '';
	$image_id   = 0;
	$value      = '';
	$is_image   = false;

	if ( is_array( $icon ) ) {
		$image_keys = array( 'ID', 'id', 'attachment_id', 'url', 'sizes', 'mime_type' );
		foreach ( $image_keys as $image_key ) {
			if ( array_key_exists( $image_key, $icon ) ) {
				$is_image = true;
				break;
			}
		}

		if ( ! $is_image ) {
			foreach ( array( 'value', 'selected', 'label' ) as $value_key ) {
				if ( isset( $icon[ $value_key ] ) && is_scalar( $icon[ $value_key ] ) && '' !== trim( (string) $icon[ $value_key ] ) ) {
					$value = sanitize_key( (string) $icon[ $value_key ] );
					break;
				}
			}

			if ( '' === $value ) {
				foreach ( $icon as $item ) {
					if ( is_scalar( $item ) && '' !== trim( (string) $item ) ) {
						$value = sanitize_key( (string) $item );
						break;
					}
				}
			}
		}

		if ( $is_image ) {
			foreach ( array( 'ID', 'id', 'attachment_id' ) as $key ) {
				if ( ! empty( $icon[ $key ] ) && is_numeric( $icon[ $key ] ) ) {
					$image_id = (int) $icon[ $key ];
					break;
				}
			}

			if ( ! $image_id ) {
				if ( ! empty( $icon['url'] ) && is_scalar( $icon['url'] ) ) {
					$image_url = (string) $icon['url'];
				} elseif ( ! empty( $icon['sizes'] ) && is_array( $icon['sizes'] ) && ! empty( $icon['sizes']['full'] ) ) {
					$image_url = (string) $icon['sizes']['full'];
				}
			}
		}
	} elseif ( is_numeric( $icon ) ) {
		$is_image = true;
		$image_id = (int) $icon;
	} elseif ( is_scalar( $icon ) ) {
		$icon = trim( (string) $icon );
		if ( '' !== $icon ) {
			if ( filter_var( $icon, FILTER_VALIDATE_URL ) ) {
				$is_image  = true;
				$image_url = $icon;
			} else {
				$value = sanitize_key( $icon );
			}
		}
	}

	if ( '' !== $value ) {
		$classes[] = variable_thema_get_service_dashicon_class( $value );
	}

	if ( $class_name ) {
		$classes[] = $class_name;
	}

	if ( ! $is_image ) {
		$classes = array_unique( array_filter( $classes ) );
		printf(
			'<span class="%s" aria-hidden="true"></span>',
			esc_attr( implode( ' ', $classes ) )
		);
		return;
	}

	if ( $image_id ) {
		$attachment_html = wp_get_attachment_image(
			$image_id,
			'thumbnail',
			false,
			array(
				'class' => $class_name ? $class_name : 'vt-service-hero__icon-image',
				'alt'   => '',
			)
		);

		if ( $attachment_html ) {
			echo wp_kses_post( $attachment_html );
			return;
		}

		$image_url = wp_get_attachment_image_url( $image_id, 'full' );
		if ( ! $image_url ) {
			$image_url = wp_get_attachment_image_url( $image_id, 'thumbnail' );
		}
	}

	if ( $image_url ) {
		?>
		<img class="<?php echo esc_attr( $class_name ? $class_name : 'vt-service-hero__icon-image' ); ?>" src="<?php echo esc_url( $image_url ); ?>" alt="">
		<?php
		return;
	}

	$classes[] = 'dashicons-admin-generic';
	$classes   = array_unique( array_filter( $classes ) );
	printf(
		'<span class="%s" aria-hidden="true"></span>',
		esc_attr( implode( ' ', $classes ) )
	);
}
