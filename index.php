<?php
/**
 * The fallback template.
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
				<?php if ( is_singular() ) : ?>
					<?php get_template_part( 'template-parts/page-header' ); ?>
					<?php get_template_part( 'template-parts/content-single' ); ?>
				<?php else : ?>
					<?php get_template_part( 'template-parts/post-card' ); ?>
				<?php endif; ?>
			<?php endwhile; ?>

			<?php the_posts_navigation(); ?>
		<?php else : ?>
			<?php get_template_part( 'template-parts/content-none' ); ?>
		<?php endif; ?>
	</div>
</main>
<?php
get_footer();
