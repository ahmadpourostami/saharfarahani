<?php
/** FarazSMS settings. */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function sf_register_farazsms_settings( $wp_customize ) {
    $wp_customize->add_section( 'sf_farazsms', array(
        'title' => __( 'ورود و ثبت‌نام پیامکی', 'saharfarahani' ),
        'description' => __( 'برای ورود با شماره موبایل، یک پترن تایید در فراز اس ام اس بسازید که متغیر آن code باشد. کلید API فقط در سمت سرور استفاده می‌شود.', 'saharfarahani' ),
        'priority' => 35,
    ) );
    $wp_customize->add_setting( 'sf_farazsms_api_key', array( 'default' => '', 'sanitize_callback' => 'sanitize_text_field', 'transport' => 'refresh' ) );
    $wp_customize->add_control( 'sf_farazsms_api_key', array( 'label' => __( 'کلید API فراز اس ام اس', 'saharfarahani' ), 'section' => 'sf_farazsms', 'type' => 'password', 'description' => __( 'کلید را از بخش خدمات وب‌سرویس / کلید دسترسی پنل فراز اس ام اس دریافت کنید.', 'saharfarahani' ) ) );
    $wp_customize->add_setting( 'sf_farazsms_pattern', array( 'default' => '', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'sf_farazsms_pattern', array( 'label' => __( 'کد پترن OTP', 'saharfarahani' ), 'section' => 'sf_farazsms', 'type' => 'text', 'description' => __( 'پترن تاییدشده‌ای که متغیر آن code است.', 'saharfarahani' ) ) );
    $wp_customize->add_setting( 'sf_farazsms_line', array( 'default' => '90008361', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'sf_farazsms_line', array( 'label' => __( 'شماره خط ارسال', 'saharfarahani' ), 'section' => 'sf_farazsms', 'type' => 'text' ) );
}
add_action( 'customize_register', 'sf_register_farazsms_settings', 20 );
