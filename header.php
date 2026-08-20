<?php
/** Header. */
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<header class="sf-header">
	<div class="sf-container sf-header__inner">
		<div class="sf-brand">
			<?php if ( has_custom_logo() ) : the_custom_logo(); else : ?>
				<a class="sf-brand__text" href="<?php echo esc_url( home_url( '/' ) ); ?>">
					<span class="sf-brand__name"><?php bloginfo( 'name' ); ?></span>
					<span class="sf-brand__tagline"><?php bloginfo( 'description' ); ?></span>
				</a>
			<?php endif; ?>
		</div>
		<button class="sf-menu-toggle" type="button" aria-expanded="false" aria-controls="sf-primary-menu"><span></span><span></span><span></span><span class="screen-reader-text"><?php esc_html_e( 'باز کردن منو', 'saharfarahani' ); ?></span></button>
		<nav class="sf-nav" id="sf-primary-menu" aria-label="<?php esc_attr_e( 'منوی اصلی', 'saharfarahani' ); ?>">
			<?php wp_nav_menu( array( 'theme_location' => 'primary', 'container' => false, 'menu_class' => 'sf-menu', 'fallback_cb' => 'sf_menu_fallback' ) ); ?>
		</nav>
		<a class="sf-header__cta" href="<?php echo esc_url( sf_get_mod( 'sf_hero_button_url', home_url( '/courses/' ) ) ); ?>"><?php echo esc_html( sf_get_mod( 'sf_hero_button_text', 'مشاهده دوره‌ها' ) ); ?></a>
	</div>
</header>
