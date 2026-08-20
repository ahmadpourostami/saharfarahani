<?php
/** Phone OTP authentication and FarazSMS integration. */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function sf_auth_normalize_mobile( $mobile ) {
    $mobile = preg_replace( '/[^0-9+]/', '', (string) $mobile );
    $mobile = preg_replace( '/^\+98/', '0', $mobile );
    $mobile = preg_replace( '/^0098/', '0', $mobile );
    return preg_match( '/^09\d{9}$/', $mobile ) ? $mobile : '';
}

function sf_auth_api_key() {
    if ( defined( 'SF_FARAZSMS_API_KEY' ) && SF_FARAZSMS_API_KEY ) {
        return SF_FARAZSMS_API_KEY;
    }
    return trim( (string) get_theme_mod( 'sf_farazsms_api_key', '' ) );
}

function sf_farazsms_send_pattern( $mobile, $code ) {
    $api_key = sf_auth_api_key();
    $pattern = trim( (string) get_theme_mod( 'sf_farazsms_pattern', '' ) );
    $line = trim( (string) get_theme_mod( 'sf_farazsms_line', '90008361' ) );

    if ( ! $api_key || ! $pattern ) {
        return new WP_Error( 'sf_sms_config', __( 'تنظیمات پیامک هنوز کامل نشده است. کلید API و کد پترن را در سفارشی‌سازی وارد کنید.', 'saharfarahani' ) );
    }

    $response = wp_remote_post( 'https://api.iranpayamak.com/ws/v1/sms/pattern', array(
        'timeout' => 15,
        'headers' => array(
            'Accept' => 'application/json',
            'Api-Key' => $api_key,
            'Content-Type' => 'application/json',
        ),
        'body' => wp_json_encode( array(
            'code' => $pattern,
            'attributes' => array( 'code' => $code ),
            'recipient' => $mobile,
            'line_number' => $line,
            'number_format' => 'english',
        ) ),
    ) );

    if ( is_wp_error( $response ) ) {
        return $response;
    }

    $status = wp_remote_retrieve_response_code( $response );
    $body = json_decode( wp_remote_retrieve_body( $response ), true );
    if ( $status < 200 || $status >= 300 || ( isset( $body['status'] ) && 'success' !== $body['status'] ) ) {
        return new WP_Error( 'sf_sms_failed', __( 'ارسال کد تایید انجام نشد. تنظیمات سرویس پیامکی را بررسی کنید.', 'saharfarahani' ), $body );
    }

    return true;
}

function sf_auth_rate_key( $mobile ) {
    return 'sf_otp_rate_' . md5( $mobile . '|' . ( isset( $_SERVER['REMOTE_ADDR'] ) ? $_SERVER['REMOTE_ADDR'] : '' ) );
}

function sf_auth_user_by_mobile( $mobile ) {
    $users = get_users( array(
        'meta_key' => '_sf_mobile',
        'meta_value' => $mobile,
        'number' => 1,
        'count_total' => false,
        'fields' => 'all',
    ) );
    return ! empty( $users ) ? $users[0] : false;
}

function sf_ajax_request_otp() {
    check_ajax_referer( 'sf_auth_nonce', 'nonce' );
    $mobile = sf_auth_normalize_mobile( wp_unslash( $_POST['mobile'] ?? '' ) );
    if ( ! $mobile ) {
        wp_send_json_error( array( 'message' => __( 'شماره موبایل معتبر وارد کنید.', 'saharfarahani' ) ), 422 );
    }

    if ( get_transient( sf_auth_rate_key( $mobile ) ) ) {
        wp_send_json_error( array( 'message' => __( 'لطفاً کمی صبر کنید و دوباره تلاش کنید.', 'saharfarahani' ) ), 429 );
    }

    $code = (string) wp_rand( 100000, 999999 );
    $sent = sf_farazsms_send_pattern( $mobile, $code );
    if ( is_wp_error( $sent ) ) {
        wp_send_json_error( array( 'message' => $sent->get_error_message() ), 500 );
    }

    set_transient( 'sf_otp_' . md5( $mobile ), wp_hash_password( $code ), 5 * MINUTE_IN_SECONDS );
    set_transient( sf_auth_rate_key( $mobile ), 1, 60 );
    wp_send_json_success( array( 'message' => __( 'کد تایید ارسال شد.', 'saharfarahani' ) ) );
}
add_action( 'wp_ajax_nopriv_sf_request_otp', 'sf_ajax_request_otp' );
add_action( 'wp_ajax_sf_request_otp', 'sf_ajax_request_otp' );

function sf_ajax_verify_otp() {
    check_ajax_referer( 'sf_auth_nonce', 'nonce' );
    $mobile = sf_auth_normalize_mobile( wp_unslash( $_POST['mobile'] ?? '' ) );
    $code = preg_replace( '/\D/', '', (string) wp_unslash( $_POST['code'] ?? '' ) );
    if ( ! $mobile || 6 !== strlen( $code ) ) {
        wp_send_json_error( array( 'message' => __( 'شماره موبایل یا کد تایید معتبر نیست.', 'saharfarahani' ) ), 422 );
    }

    $stored = get_transient( 'sf_otp_' . md5( $mobile ) );
    if ( ! $stored || ! wp_check_password( $code, $stored ) ) {
        wp_send_json_error( array( 'message' => __( 'کد تایید اشتباه یا منقضی شده است.', 'saharfarahani' ) ), 422 );
    }
    delete_transient( 'sf_otp_' . md5( $mobile ) );

    $user = sf_auth_user_by_mobile( $mobile );
    if ( ! $user ) {
        $username = 'user_' . substr( $mobile, 1 );
        $base = $username;
        $i = 1;
        while ( username_exists( $username ) ) { $username = $base . '_' . $i++; }
        $user_id = wp_insert_user( array(
            'user_login' => $username,
            'user_pass' => wp_generate_password( 32, true, true ),
            'display_name' => $mobile,
            'role' => 'subscriber',
        ) );
        if ( is_wp_error( $user_id ) ) {
            wp_send_json_error( array( 'message' => __( 'ساخت حساب کاربری انجام نشد.', 'saharfarahani' ) ), 500 );
        }
        update_user_meta( $user_id, '_sf_mobile', $mobile );
        $user = get_user_by( 'id', $user_id );
    }

    wp_set_current_user( $user->ID );
    wp_set_auth_cookie( $user->ID, true );
    wp_send_json_success( array(
        'message' => __( 'با موفقیت وارد شدید.', 'saharfarahani' ),
        'redirect' => function_exists( 'tutor_dashboard_url' ) ? tutor_dashboard_url() : home_url( '/profile/' ),
    ) );
}
add_action( 'wp_ajax_nopriv_sf_verify_otp', 'sf_ajax_verify_otp' );
add_action( 'wp_ajax_sf_verify_otp', 'sf_ajax_verify_otp' );

function sf_auth_modal_markup() {
    if ( is_user_logged_in() ) { return; }
    ?>
    <div class="sf-auth-modal" id="sf-auth-modal" aria-hidden="true">
        <div class="sf-auth-modal__backdrop" data-sf-auth-close></div>
        <div class="sf-auth-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="sf-auth-title">
            <button class="sf-auth-modal__close" type="button" data-sf-auth-close aria-label="بستن">×</button>
            <div class="sf-auth-modal__icon">✦</div>
            <h2 id="sf-auth-title">ورود یا ثبت‌نام</h2>
            <p class="sf-auth-modal__hint">شماره موبایل خود را وارد کنید تا کد تایید برایتان ارسال شود.</p>
            <form id="sf-auth-form">
                <div class="sf-auth-step is-active" data-step="mobile">
                    <label for="sf-auth-mobile">شماره موبایل</label>
                    <input id="sf-auth-mobile" name="mobile" type="tel" inputmode="numeric" autocomplete="tel" placeholder="0912 123 4567" maxlength="11" required>
                    <button class="sf-button sf-button--primary sf-auth-submit" type="submit">دریافت کد تایید</button>
                </div>
                <div class="sf-auth-step" data-step="code">
                    <label for="sf-auth-code">کد تایید</label>
                    <input id="sf-auth-code" name="code" type="text" inputmode="numeric" autocomplete="one-time-code" placeholder="کد ۶ رقمی" maxlength="6">
                    <button class="sf-button sf-button--primary sf-auth-submit" type="submit">تایید و ورود</button>
                    <button class="sf-auth-back" type="button" id="sf-auth-back">تغییر شماره موبایل</button>
                </div>
                <div class="sf-auth-message" role="alert" aria-live="polite"></div>
            </form>
        </div>
    </div>
    <?php
}
