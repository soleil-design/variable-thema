<?php
/**
 * The generic search results template.
 *
 * Shows the search heading, the standard search form, and the results or the
 * empty state. Skins can restyle it through the .vt-search-form wrapper.
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

		<div class="vt-search-form">
			<?php get_search_form(); ?>
		</div>

		<?php if ( have_posts() ) : ?>
			<div class="vt-post-list">
				<?php while ( have_posts() ) : ?>
					<?php the_post(); ?>
					<?php get_template_part( 'template-parts/post-card' ); ?>
				<?php endwhile; ?>
			</div>

			<?php the_posts_navigation(); ?>
		<?php else : ?>
			<?php get_template_part( 'template-parts/content-none' ); ?>
		<?php endif; ?>
	</div>
</main>
<?php
get_footer();
