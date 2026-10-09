<?php
/**
 * Template part for page content.
 *
 * @package Variable_Thema
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<article <?php post_class( 'vt-entry vt-entry--page' ); ?>>
	<?php if ( ! is_page() ) : ?>
		<?php variable_thema_post_thumbnail( 'large', 'vt-entry__thumbnail' ); ?>
	<?php endif; ?>

	<div class="vt-entry__content">
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
</article>
