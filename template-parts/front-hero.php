<?php
/**
 * Front-page hero template part.
 *
 * @package Variable_Thema
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$front_page_id = (int) get_option( 'page_on_front' );
$hero_title    = $front_page_id ? get_the_title( $front_page_id ) : get_bloginfo( 'name' );
$description   = $front_page_id && has_excerpt( $front_page_id )
	? get_the_excerpt( $front_page_id )
	: get_bloginfo( 'description' );
?>
<section class="vt-hero vt-front-hero" aria-labelledby="vt-front-hero-title">
	<div class="vt-hero__content">
		<p class="vt-hero__label"><?php esc_html_e( 'Welcome', 'variable-thema' ); ?></p>
		<h1 id="vt-front-hero-title" class="vt-hero__title"><?php echo esc_html( $hero_title ); ?></h1>
		<?php if ( '' !== trim( (string) $description ) ) : ?>
			<p class="vt-hero__text"><?php echo esc_html( $description ); ?></p>
		<?php endif; ?>
	</div>
</section>
