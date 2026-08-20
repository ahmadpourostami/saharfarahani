<?php
/** Customizer settings. */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function sf_customize_register( $wp_customize ) {
	$wp_customize->add_panel( 'sf_homepage', array( 'title' => __( 'صفحه اصلی سحر فراهانی', 'saharfarahani' ), 'description' => __( 'متن‌ها، تصاویر، لینک‌ها، رنگ‌ها و تعداد آیتم‌های صفحه اصلی را کنترل کنید.', 'saharfarahani' ), 'priority' => 30 ) );

	$wp_customize->add_section( 'sf_hero', array( 'title' => __( 'هیرو و معرفی اولیه', 'saharfarahani' ), 'panel' => 'sf_homepage' ) );
	sf_add_text( $wp_customize, 'sf_hero_eyebrow', 'sf_hero', 'متن بالای عنوان', 'سحر فراهانی' );
	sf_add_text( $wp_customize, 'sf_hero_title', 'sf_hero', 'عنوان اصلی', 'هنرِ دیده شدن، شنیده شدن و خلق کردن' );
	sf_add_textarea( $wp_customize, 'sf_hero_text', 'sf_hero', 'توضیحات', 'آموزش تخصصی بازیگری، فن بیان و مهارت‌های تئاتر و سینما.' );
	sf_add_text( $wp_customize, 'sf_hero_button_text', 'sf_hero', 'متن دکمه اصلی', 'مشاهده دوره‌ها' );
	sf_add_url( $wp_customize, 'sf_hero_button_url', 'sf_hero', 'لینک دکمه اصلی', home_url( '/courses/' ) );
	sf_add_text( $wp_customize, 'sf_hero_secondary_text', 'sf_hero', 'متن دکمه دوم', 'درباره من' );
	sf_add_url( $wp_customize, 'sf_hero_secondary_url', 'sf_hero', 'لینک دکمه دوم', home_url( '/about/' ) );
	sf_add_image( $wp_customize, 'sf_hero_image', 'sf_hero', 'تصویر هیرو' );

	$wp_customize->add_section( 'sf_about', array( 'title' => __( 'بخش درباره من', 'saharfarahani' ), 'panel' => 'sf_homepage' ) );
	sf_add_text( $wp_customize, 'sf_about_kicker', 'sf_about', 'برچسب', 'درباره من' );
	sf_add_text( $wp_customize, 'sf_about_title', 'sf_about', 'عنوان', 'هنر فقط اجرا نیست؛ شناختن خودت است.' );
	sf_add_textarea( $wp_customize, 'sf_about_text', 'sf_about', 'متن', 'من سحر فراهانی هستم و در مسیر آموزش هنرهای نمایشی و سینما تلاش می‌کنم یادگیری را از تئوری به تجربه واقعی تبدیل کنم.' );
	sf_add_text( $wp_customize, 'sf_about_button_text', 'sf_about', 'متن دکمه', 'بیشتر درباره من' );
	sf_add_url( $wp_customize, 'sf_about_button_url', 'sf_about', 'لینک دکمه', home_url( '/about/' ) );
	sf_add_image( $wp_customize, 'sf_about_image', 'sf_about', 'تصویر' );

	$wp_customize->add_section( 'sf_categories', array( 'title' => __( 'دسته‌بندی دوره‌ها', 'saharfarahani' ), 'panel' => 'sf_homepage' ) );
	sf_add_text( $wp_customize, 'sf_categories_kicker', 'sf_categories', 'برچسب', 'مسیر یادگیری' );
	sf_add_text( $wp_customize, 'sf_categories_title', 'sf_categories', 'عنوان', 'چه چیزی می‌خواهید یاد بگیرید؟' );
	sf_add_textarea( $wp_customize, 'sf_categories_text', 'sf_categories', 'توضیحات', 'حوزه مورد علاقه‌تان را انتخاب کنید و دوره‌های مرتبط را ببینید.' );
	sf_add_number( $wp_customize, 'sf_category_count', 'sf_categories', 'تعداد دسته‌ها', 4, 1, 12 );
	sf_add_number( $wp_customize, 'sf_courses_per_category', 'sf_categories', 'تعداد دوره در هر دسته', 3, 1, 6 );

	$wp_customize->add_section( 'sf_latest_courses', array( 'title' => __( 'جدیدترین دوره‌ها', 'saharfarahani' ), 'panel' => 'sf_homepage' ) );
	sf_add_text( $wp_customize, 'sf_courses_kicker', 'sf_latest_courses', 'برچسب', 'دوره‌های آموزشی' );
	sf_add_text( $wp_customize, 'sf_courses_title', 'sf_latest_courses', 'عنوان', 'جدیدترین دوره‌ها' );
	sf_add_textarea( $wp_customize, 'sf_courses_text', 'sf_latest_courses', 'توضیحات', 'دوره‌های تازه منتشرشده را برای شروع یادگیری ببینید.' );
	sf_add_number( $wp_customize, 'sf_latest_course_count', 'sf_latest_courses', 'تعداد دوره', 6, 1, 12 );
	sf_add_text( $wp_customize, 'sf_courses_button_text', 'sf_latest_courses', 'متن دکمه', 'مشاهده همه دوره‌ها' );
	sf_add_url( $wp_customize, 'sf_courses_button_url', 'sf_latest_courses', 'لینک دکمه', home_url( '/courses/' ) );

	$wp_customize->add_section( 'sf_paths', array( 'title' => __( 'مسیر یادگیری بازیگری', 'saharfarahani' ), 'panel' => 'sf_homepage' ) );
	sf_add_text( $wp_customize, 'sf_paths_kicker', 'sf_paths', 'برچسب', 'مسیر پیشنهادی' );
	sf_add_text( $wp_customize, 'sf_paths_title', 'sf_paths', 'عنوان', 'اگر می‌خواهید بازیگر شوید، از اینجا شروع کنید.' );
	sf_add_textarea( $wp_customize, 'sf_paths_text', 'sf_paths', 'توضیحات', 'یک مسیر مرحله‌به‌مرحله برای ساخت مهارت‌های اصلی بازیگری.' );

	$wp_customize->add_section( 'sf_cta', array( 'title' => __( 'دعوت به اقدام پایانی', 'saharfarahani' ), 'panel' => 'sf_homepage' ) );
	sf_add_text( $wp_customize, 'sf_cta_title', 'sf_cta', 'عنوان', 'آماده‌ای صدای خودت را پیدا کنی؟' );
	sf_add_textarea( $wp_customize, 'sf_cta_text', 'sf_cta', 'توضیحات', 'دوره مناسب خودت را انتخاب کن و مسیر یادگیری را شروع کن.' );
	sf_add_text( $wp_customize, 'sf_cta_button_text', 'sf_cta', 'متن دکمه', 'شروع یادگیری' );
	sf_add_url( $wp_customize, 'sf_cta_button_url', 'sf_cta', 'لینک دکمه', home_url( '/courses/' ) );

	$wp_customize->add_section( 'sf_footer', array( 'title' => __( 'فوتر', 'saharfarahani' ), 'panel' => 'sf_homepage' ) );
	sf_add_textarea( $wp_customize, 'sf_footer_text', 'sf_footer', 'متن معرفی فوتر', 'آموزش بازیگری، فن بیان و مهارت‌های تئاتر و سینما با سحر فراهانی.' );
	sf_add_text( $wp_customize, 'sf_footer_credit', 'sf_footer', 'متن اعتبار', 'طراحی و توسعه با WordPress' );

	$wp_customize->add_section( 'sf_colors', array( 'title' => __( 'رنگ و ابعاد', 'saharfarahani' ), 'panel' => 'sf_homepage' ) );
	sf_add_color( $wp_customize, 'sf_primary_color', 'sf_colors', 'رنگ اصلی', '#7c315e' );
	sf_add_color( $wp_customize, 'sf_accent_color', 'sf_colors', 'رنگ تأکیدی', '#c99b5d' );
	sf_add_color( $wp_customize, 'sf_dark_color', 'sf_colors', 'رنگ تیره', '#171417' );
	sf_add_color( $wp_customize, 'sf_surface_color', 'sf_colors', 'رنگ سطح', '#f7f3f0' );
	sf_add_number( $wp_customize, 'sf_container_width', 'sf_colors', 'عرض محتوا (px)', 1240, 980, 1600 );
}
add_action( 'customize_register', 'sf_customize_register' );

function sf_add_text( $c, $id, $section, $label, $default ) { $c->add_setting( $id, array( 'default' => $default, 'sanitize_callback' => 'sanitize_text_field' ) ); $c->add_control( $id, array( 'label' => $label, 'section' => $section, 'type' => 'text' ) ); }
function sf_add_textarea( $c, $id, $section, $label, $default ) { $c->add_setting( $id, array( 'default' => $default, 'sanitize_callback' => 'sanitize_textarea_field' ) ); $c->add_control( $id, array( 'label' => $label, 'section' => $section, 'type' => 'textarea' ) ); }
function sf_add_url( $c, $id, $section, $label, $default ) { $c->add_setting( $id, array( 'default' => $default, 'sanitize_callback' => 'esc_url_raw' ) ); $c->add_control( $id, array( 'label' => $label, 'section' => $section, 'type' => 'url' ) ); }
function sf_add_image( $c, $id, $section, $label ) { $c->add_setting( $id, array( 'default' => 0, 'sanitize_callback' => 'absint' ) ); $c->add_control( new WP_Customize_Media_Control( $c, $id, array( 'label' => $label, 'section' => $section, 'mime_type' => 'image' ) ) ); }
function sf_add_number( $c, $id, $section, $label, $default, $min, $max ) { $c->add_setting( $id, array( 'default' => $default, 'sanitize_callback' => 'absint' ) ); $c->add_control( $id, array( 'label' => $label, 'section' => $section, 'type' => 'number', 'input_attrs' => array( 'min' => $min, 'max' => $max ) ) ); }
function sf_add_color( $c, $id, $section, $label, $default ) { $c->add_setting( $id, array( 'default' => $default, 'sanitize_callback' => 'sanitize_hex_color' ) ); $c->add_control( new WP_Customize_Color_Control( $c, $id, array( 'label' => $label, 'section' => $section ) ) ); }
