<?php
/** Global header. */
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<header class="sf-header <?php echo is_front_page() ? 'sf-header--hero' : 'sf-header--inner'; ?>">
    <div class="sf-container sf-header__inner">
        <div class="sf-brand">
            <?php if ( has_custom_logo() ) : the_custom_logo(); else : ?>
                <a class="sf-brand__text" href="<?php echo esc_url( home_url( '/' ) ); ?>">
                    <span class="sf-brand__name"><?php bloginfo( 'name' ); ?></span>
                    <span class="sf-brand__tagline"><?php bloginfo( 'description' ); ?></span>
                </a>
            <?php endif; ?>
        </div>

        <button class="sf-menu-toggle" type="button" aria-expanded="false" aria-controls="sf-primary-menu">
            <span></span><span></span><span></span>
            <span class="screen-reader-text"><?php esc_html_e( 'باز کردن منو', 'saharfarahani' ); ?></span>
        </button>

        <nav class="sf-nav" id="sf-primary-menu" aria-label="<?php esc_attr_e( 'منوی اصلی', 'saharfarahani' ); ?>">
            <?php wp_nav_menu( array( 'theme_location' => 'primary', 'container' => false, 'menu_class' => 'sf-menu', 'fallback_cb' => 'sf_menu_fallback' ) ); ?>
        </nav>

        <div class="sf-header__actions">
            <?php if ( is_user_logged_in() ) : ?>
                <?php $profile_url = function_exists( 'tutor_dashboard_url' ) ? tutor_dashboard_url() : home_url( '/profile/' ); ?>
                <a class="sf-header__profile" href="<?php echo esc_url( $profile_url ); ?>">پروفایل</a>
                <a class="sf-header__logout" href="<?php echo esc_url( wp_logout_url( home_url( '/' ) ) ); ?>">خروج</a>
            <?php else : ?>
                <button class="sf-header__login" type="button" data-sf-auth-open>ورود / ثبت‌نام</button>
                <a class="sf-header__cta" href="<?php echo esc_url( sf_get_mod( 'sf_hero_button_url', home_url( '/courses/' ) ) ); ?>"><?php echo esc_html( sf_get_mod( 'sf_hero_button_text', 'مشاهده دوره‌ها' ) ); ?></a>
            <?php endif; ?>
        </div>
    </div>
</header>
<?php sf_auth_modal_markup(); ?>
