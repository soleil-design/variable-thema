<?php
/**
 * The template for missing content.
 *
 * @package Variable_Thema
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>
<main id="main" class="site-main">
	<div class="site-main__inner">
		<?php get_template_part( 'template-parts/breadcrumb' ); ?>
		<?php get_template_part( 'template-parts/page-header' ); ?>

		<section class="vt-entry vt-entry--404">
			<div class="vt-entry__content">
				<p><?php esc_html_e( 'The page you are looking for could not be found.', 'variable-thema' ); ?></p>
				<p><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Return to home', 'variable-thema' ); ?></a></p>
			</div>
		</section>
	</div>
</main>
<?php
get_footer();
