<?php
/**
 * Context-aware page header template part.
 *
 * @package Variable_Thema
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$page_header_title = '';
$description       = '';

if ( is_home() && ! is_front_page() ) {
	$posts_page_id     = (int) get_option( 'page_for_posts' );
	$page_header_title = $posts_page_id ? get_the_title( $posts_page_id ) : __( 'Posts', 'variable-thema' );
} elseif ( is_archive() ) {
	$page_header_title = get_the_archive_title();
	$description       = get_the_archive_description();
} elseif ( is_search() ) {
	$page_header_title = sprintf(
		/* translators: %s: search query. */
		__( 'Search results for: %s', 'variable-thema' ),
		get_search_query()
	);
} elseif ( is_404() ) {
	$page_header_title = __( 'Page not found', 'variable-thema' );
} elseif ( is_singular() ) {
	$page_header_title = get_the_title();
}

if ( '' === trim( wp_strip_all_tags( $page_header_title ) ) ) {
	return;
}

if ( is_page() && ! is_front_page() ) :
	$thumbnail = '';

	if ( has_post_thumbnail() ) {
		$thumbnail = get_the_post_thumbnail(
			get_the_ID(),
			'full',
			array(
				'class'           => 'vt-page-header__image',
				'alt'             => '',
				'aria-hidden'     => 'true',
				'data-decorative' => 'true',
			)
		);
	}
	?>
	<header class="vt-page-hero vt-page-header vt-page-header--hero">
		<?php if ( $thumbnail ) : ?>
			<figure class="vt-page-header__media" aria-hidden="true">
				<?php echo $thumbnail; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</figure>
		<?php endif; ?>

		<div class="vt-page-header__overlay" aria-hidden="true"></div>

		<div class="vt-page-header__inner">
			<div class="vt-page-header__content">
				<h1 class="vt-page-hero__title vt-page-header__title"><?php echo esc_html( $page_header_title ); ?></h1>
			</div>
		</div>
	</header>
	<?php
	return;
endif;
?>
<header class="vt-page-hero vt-page-header">
	<?php if ( is_singular() ) : ?>
		<h1 class="vt-page-hero__title vt-page-header__title"><?php echo esc_html( $page_header_title ); ?></h1>
	<?php else : ?>
		<h1 class="vt-page-hero__title vt-page-header__title"><?php echo wp_kses_post( $page_header_title ); ?></h1>
	<?php endif; ?>

	<?php if ( $description ) : ?>
		<div class="vt-page-hero__description vt-page-header__description">
			<?php echo wp_kses_post( wpautop( $description ) ); ?>
		</div>
	<?php endif; ?>
</header>
