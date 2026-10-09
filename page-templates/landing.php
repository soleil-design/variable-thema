<?php
/**
 * Template Name: Landing page
 * Template Post Type: page
 *
 * Page template that lets full-width blocks such as the Hero / Main Visual
 * pattern sit directly below the site header. The page title is kept for
 * screen readers; other content keeps the regular content width.
 *
 * @package Variable_Thema
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>
<main id="main" class="site-main vt-landing">
	<div class="site-main__inner">
		<?php if ( have_posts() ) : ?>
			<?php while ( have_posts() ) : ?>
				<?php the_post(); ?>
				<?php if ( is_front_page() ) : ?>
					<?php get_template_part( 'template-parts/front-hero' ); ?>
				<?php else : ?>
					<h1 class="screen-reader-text"><?php the_title(); ?></h1>
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
