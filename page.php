<?php
/**
 * The page template.
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
		<?php if ( have_posts() ) : ?>
			<?php while ( have_posts() ) : ?>
				<?php the_post(); ?>
				<?php if ( ! is_front_page() ) : ?>
					<?php get_template_part( 'template-parts/page-header' ); ?>
				<?php endif; ?>
				<?php get_template_part( 'template-parts/breadcrumb' ); ?>
				<?php if ( is_front_page() ) : ?>
					<?php get_template_part( 'template-parts/front-hero' ); ?>
				<?php endif; ?>
				<?php get_template_part( 'template-parts/content-page' ); ?>
				<?php if ( comments_open() || get_comments_number() ) : ?>
					<?php comments_template(); ?>
				<?php endif; ?>
			<?php endwhile; ?>
		<?php else : ?>
			<?php get_template_part( 'template-parts/content-none' ); ?>
		<?php endif; ?>

		<?php
		if ( is_front_page() ) {
			get_template_part(
				'template-parts/cta',
				null,
				variable_get_cta_args( 'default' )
			);
		}
		?>
	</div>
</main>
<?php
get_footer();
