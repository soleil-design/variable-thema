<?php
/**
 * Generic breadcrumb template part.
 *
 * @package Variable_Thema
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( is_front_page() ) {
	return;
}
?>
<nav class="vt-breadcrumb" aria-label="<?php esc_attr_e( 'Breadcrumb', 'variable-thema' ); ?>">
	<ol class="vt-breadcrumb__list">
		<li class="vt-breadcrumb__item">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'variable-thema' ); ?></a>
		</li>

		<?php if ( is_singular() ) : ?>
			<?php if ( is_singular( 'service' ) ) : ?>
				<li class="vt-breadcrumb__item">
					<a href="<?php echo esc_url( home_url( '/service/' ) ); ?>"><?php esc_html_e( 'Services', 'variable-thema' ); ?></a>
				</li>
			<?php elseif ( is_single() ) : ?>
				<li class="vt-breadcrumb__item">
					<?php $posts_url = get_permalink( get_option( 'page_for_posts' ) ); ?>
					<a href="<?php echo esc_url( $posts_url ? $posts_url : home_url( '/' ) ); ?>"><?php esc_html_e( 'Posts', 'variable-thema' ); ?></a>
				</li>
			<?php endif; ?>
			<li class="vt-breadcrumb__item" aria-current="page"><?php echo esc_html( get_the_title() ); ?></li>
		<?php elseif ( is_archive() ) : ?>
			<li class="vt-breadcrumb__item" aria-current="page"><?php echo wp_kses_post( get_the_archive_title() ); ?></li>
		<?php elseif ( is_search() ) : ?>
			<li class="vt-breadcrumb__item" aria-current="page"><?php esc_html_e( 'Search', 'variable-thema' ); ?></li>
		<?php elseif ( is_404() ) : ?>
			<li class="vt-breadcrumb__item" aria-current="page"><?php esc_html_e( 'Not Found', 'variable-thema' ); ?></li>
		<?php endif; ?>
	</ol>
</nav>
