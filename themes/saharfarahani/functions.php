<?php
/**
 * Sahar Farahani theme bootstrap.
 *
 * @package SaharFarahani
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

define( 'SF_VERSION', '1.0.5' );
define( 'SF_DIR', get_template_directory() );
define( 'SF_URI', get_template_directory_uri() );

require_once SF_DIR . '/inc/customizer.php';
require_once SF_DIR . '/inc/auth-settings.php';
if ( ! function_exists( 'sfcore_get_learning_paths' ) ) { require_once SF_DIR . '/inc/admin-homepage.php'; }
require_once SF_DIR . '/inc/template-tags.php';
require_once SF_DIR . '/inc/auth.php';
require_once SF_DIR . '/inc/course-latest-ajax.php';

function sf_setup() {
    load_theme_textdomain( 'saharfarahani', SF_DIR . '/languages' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'custom-logo', array( 'height' => 120, 'width' => 320, 'flex-height' => true, 'flex-width' => true ) );
    add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );
    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'responsive-embeds' );
    add_theme_support( 'block-template-parts' );
    register_nav_menus( array( 'primary' => __( 'منوی اصلی', 'saharfarahani' ), 'footer' => __( 'منوی فوتر', 'saharfarahani' ) ) );
}
add_action( 'after_setup_theme', 'sf_setup' );

function sf_enqueue_assets() {
    wp_enqueue_style( 'sf-style', get_stylesheet_uri(), array(), SF_VERSION );
    wp_enqueue_style( 'sf-main', SF_URI . '/assets/css/main.css', array( 'sf-style' ), SF_VERSION );
    wp_enqueue_style( 'sf-fixes', SF_URI . '/assets/css/fixes.css', array( 'sf-main' ), SF_VERSION );
    wp_enqueue_style( 'sf-auth', SF_URI . '/assets/css/auth.css', array( 'sf-fixes' ), SF_VERSION );
    wp_enqueue_script( 'sf-main', SF_URI . '/assets/js/main.js', array(), SF_VERSION, true );
    wp_enqueue_script( 'sf-auth', SF_URI . '/assets/js/auth.js', array(), SF_VERSION, true );
    if ( is_front_page() ) {
        wp_enqueue_script( 'sf-course-latest', SF_URI . '/assets/js/course-latest.js', array(), SF_VERSION, true );
        wp_localize_script( 'sf-course-latest', 'sfLatestCourses', array(
            'ajaxUrl' => admin_url( 'admin-ajax.php' ),
            'nonce'   => wp_create_nonce( 'sf_latest_courses_nonce' ),
            'count'   => max( 1, absint( get_theme_mod( 'sf_latest_course_count', 5 ) ) ),
        ) );
    }
    wp_localize_script( 'sf-auth', 'sfAuth', array( 'ajaxUrl' => admin_url( 'admin-ajax.php' ), 'nonce' => wp_create_nonce( 'sf_auth_nonce' ) ) );
}
add_action( 'wp_enqueue_scripts', 'sf_enqueue_assets' );

function sf_customizer_css() {
    $primary = get_theme_mod( 'sf_primary_color', '#7c315e' );
    $accent = get_theme_mod( 'sf_accent_color', '#c99b5d' );
    $dark = get_theme_mod( 'sf_dark_color', '#171417' );
    $surface = get_theme_mod( 'sf_surface_color', '#f7f3f0' );
    $container = absint( get_theme_mod( 'sf_container_width', 1240 ) );
    ?>
    <style id="sf-customizer-css">:root{--sf-primary:<?php echo esc_attr( $primary ); ?>;--sf-accent:<?php echo esc_attr( $accent ); ?>;--sf-dark:<?php echo esc_attr( $dark ); ?>;--sf-surface:<?php echo esc_attr( $surface ); ?>;--sf-container:<?php echo esc_attr( $container ); ?>px;}</style>
    <?php
}
add_action( 'wp_head', 'sf_customizer_css', 100 );

function sf_body_classes( $classes ) {
    if ( function_exists( 'tutor' ) ) { $classes[] = 'sf-has-tutor'; }
    if ( function_exists( 'sfcore_get_learning_paths' ) ) { $classes[] = 'sf-has-core'; }
    $classes[] = is_front_page() ? 'sf-front-page' : 'sf-inner-page';
    return $classes;
}
add_filter( 'body_class', 'sf_body_classes' );

function sf_register_image_sizes() {
    add_image_size( 'sf-course-card', 720, 480, true );
    add_image_size( 'sf-category-card', 720, 560, true );
    add_image_size( 'sf-portrait', 720, 900, true );
}
add_action( 'after_setup_theme', 'sf_register_image_sizes' );

function sf_get_course_taxonomy() {
    return taxonomy_exists( 'course-category' ) ? 'course-category' : 'category';
}
