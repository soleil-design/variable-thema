<?php
/**
 * Template part for single post content.
 *
 * @package Variable_Thema
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<article <?php post_class( 'vt-entry vt-entry--single' ); ?>>
	<?php if ( 'post' === get_post_type() ) : ?>
		<div class="vt-entry__meta">
			<div class="vt-entry__meta-left">
				<?php variable_thema_posted_on(); ?>
				<?php variable_thema_updated_on(); ?>
				<?php variable_thema_post_categories(); ?>
			</div>
		</div>
	<?php endif; ?>

	<?php variable_thema_post_thumbnail( 'large', 'vt-entry__thumbnail' ); ?>

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

	<footer class="vt-entry__footer">
		<?php the_post_navigation(); ?>
	</footer>
</article>
