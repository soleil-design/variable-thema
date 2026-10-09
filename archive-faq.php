<?php
/**
 * The FAQ archive presentation template.
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

		<?php
		$faq_terms = get_terms(
			array(
				'taxonomy'   => 'faq_category',
				'hide_empty' => true,
				'orderby'    => 'term_id',
				'order'      => 'ASC',
			)
		);
		?>

		<?php if ( ! is_wp_error( $faq_terms ) && $faq_terms ) : ?>
			<div class="vt-faq-archive">
				<?php foreach ( $faq_terms as $faq_term ) : ?>
					<section class="vt-faq-archive__section">
						<h2 class="vt-faq-archive__title"><?php echo esc_html( $faq_term->name ); ?></h2>
						<?php
						get_template_part(
							'template-parts/faq-list',
							null,
							array(
								'category'    => $faq_term,
								'show_title'  => false,
								'use_excerpt' => false,
							)
						);
						?>
					</section>
				<?php endforeach; ?>
				<?php
				$uncategorized_faq = new WP_Query(
					array(
						'post_type'      => 'faq',
						'post_status'    => 'publish',
						'posts_per_page' => 1,
						'fields'         => 'ids',
						'no_found_rows'  => true,
						'tax_query'      => array(
							array(
								'taxonomy' => 'faq_category',
								'operator' => 'NOT EXISTS',
							),
						),
					)
				);
				?>
				<?php if ( $uncategorized_faq->have_posts() ) : ?>
					<section class="vt-faq-archive__section">
						<h2 class="vt-faq-archive__title"><?php echo esc_html_x( 'Other', 'FAQ archive section', 'variable-thema' ); ?></h2>
						<?php
						get_template_part(
							'template-parts/faq-list',
							null,
							array(
								'uncategorized' => true,
								'show_title'    => false,
								'use_excerpt'   => false,
							)
						);
						?>
					</section>
				<?php endif; ?>
				<?php wp_reset_postdata(); ?>
			</div>
		<?php elseif ( have_posts() ) : ?>
			<?php
			get_template_part(
				'template-parts/faq-list',
				null,
				array(
					'show_title'  => false,
					'use_excerpt' => false,
				)
			);
			?>
		<?php else : ?>
			<?php get_template_part( 'template-parts/content-none' ); ?>
		<?php endif; ?>
	</div>
</main>
<?php
get_footer();
