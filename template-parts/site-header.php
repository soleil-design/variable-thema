<?php
/**
 * Generic site header template part.
 *
 * @package Variable_Thema
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<header class="site-header">
	<div class="site-header__inner">
		<?php if ( has_custom_logo() || variable_thema_get_theme_mod( 'show_site_title' ) ) : ?>
			<div class="site-branding">
				<?php if ( has_custom_logo() ) : ?>
					<div class="site-logo"><?php the_custom_logo(); ?></div>
				<?php elseif ( variable_thema_get_theme_mod( 'show_site_title' ) ) : ?>
					<?php if ( is_front_page() && is_home() ) : ?>
						<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></a></h1>
					<?php else : ?>
						<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></a></p>
					<?php endif; ?>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<?php
		/**
		 * Filters whether the primary navigation wrapper should be rendered.
		 *
		 * @param bool $has_primary_navigation Whether a primary menu is assigned.
		 */
		$has_primary_navigation = (bool) apply_filters(
			'variable_thema_has_primary_navigation',
			has_nav_menu( 'primary' )
		);
		?>

		<?php if ( variable_thema_get_theme_mod( 'show_header_menu' ) && $has_primary_navigation ) : ?>
			<button class="site-nav-toggle" type="button" aria-controls="site-primary-navigation" aria-expanded="false" data-vt-nav-toggle>
				<span class="site-nav-toggle__label"><?php esc_html_e( 'Menu', 'variable-thema' ); ?></span>
				<span class="site-nav-toggle__icon" aria-hidden="true">
					<span class="site-nav-toggle__line"></span>
					<span class="site-nav-toggle__line"></span>
					<span class="site-nav-toggle__line"></span>
				</span>
			</button>

			<nav id="site-primary-navigation" class="site-nav" aria-label="<?php esc_attr_e( 'Primary Menu', 'variable-thema' ); ?>" data-vt-navigation data-vt-submenu-toggle-label="<?php esc_attr_e( 'Toggle submenu', 'variable-thema' ); ?>">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'container'      => false,
						'menu_class'     => 'site-nav__list',
						'fallback_cb'    => false,
					)
				);
				?>
			</nav>
		<?php endif; ?>
	</div>
</header>
