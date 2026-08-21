<?php
/**
 * Plugin Name: Sahar Farahani Core
 * Description: هسته محتوایی قالب سحر فراهانی؛ مسیرهای یادگیری، نظرات هنرجویان و ابزارهای Tutor LMS.
 * Version: 1.0.1
 * Author: Ahmad Pourostami
 * Text Domain: sahar-farahani-core
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

define( 'SFCORE_VERSION', '1.0.1' );

define( 'SFCORE_PATH', plugin_dir_path( __FILE__ ) );

function sfcore_register_content_types() {
	register_post_type( 'sf_learning_path', array(
		'labels' => array( 'name' => 'مسیر یادگیری', 'singular_name' => 'مرحله مسیر', 'add_new' => 'افزودن مرحله', 'add_new_item' => 'افزودن مرحله جدید', 'edit_item' => 'ویرایش مرحله', 'new_item' => 'مرحله جدید', 'view_item' => 'مشاهده مرحله', 'search_items' => 'جستجوی مراحل', 'menu_name' => 'مسیر یادگیری' ),
		'public' => false, 'show_ui' => true, 'show_in_menu' => true, 'menu_icon' => 'dashicons-list-view', 'supports' => array( 'title', 'thumbnail', 'page-attributes' ), 'menu_position' => 25, 'show_in_rest' => true,
	) );
	register_post_type( 'sf_testimonial', array(
		'labels' => array( 'name' => 'نظرات هنرجویان', 'singular_name' => 'نظر هنرجو', 'add_new' => 'افزودن نظر', 'add_new_item' => 'افزودن نظر جدید', 'edit_item' => 'ویرایش نظر', 'new_item' => 'نظر جدید', 'view_item' => 'مشاهده نظر', 'search_items' => 'جستجوی نظرات', 'menu_name' => 'نظرات هنرجویان' ),
		'public' => false, 'show_ui' => true, 'show_in_menu' => true, 'menu_icon' => 'dashicons-format-quote', 'supports' => array( 'title', 'editor', 'thumbnail', 'page-attributes' ), 'menu_position' => 26, 'show_in_rest' => true,
	) );
}
add_action( 'init', 'sfcore_register_content_types' );

function sfcore_add_meta_boxes() {
	add_meta_box( 'sfcore_path_fields', 'تنظیمات مرحله مسیر', 'sfcore_render_path_meta', 'sf_learning_path', 'normal', 'high' );
	add_meta_box( 'sfcore_testimonial_fields', 'اطلاعات نظر هنرجو', 'sfcore_render_testimonial_meta', 'sf_testimonial', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'sfcore_add_meta_boxes' );

function sfcore_render_path_meta( $post ) {
	wp_nonce_field( 'sfcore_save_path', 'sfcore_path_nonce' );
	$text = get_post_meta( $post->ID, '_sfcore_path_text', true ); $button = get_post_meta( $post->ID, '_sfcore_path_button_text', true ); $url = get_post_meta( $post->ID, '_sfcore_path_button_url', true );
	?>
	<p><label><strong>توضیح کوتاه</strong></label><br><textarea name="sfcore_path_text" rows="3" style="width:100%"><?php echo esc_textarea( $text ); ?></textarea></p>
	<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px"><p><label><strong>متن دکمه</strong></label><br><input type="text" name="sfcore_path_button_text" value="<?php echo esc_attr( $button ); ?>" style="width:100%"></p><p><label><strong>لینک دکمه</strong></label><br><input type="url" name="sfcore_path_button_url" value="<?php echo esc_attr( $url ); ?>" style="width:100%" dir="ltr"></p></div>
	<p><strong>ترتیب نمایش:</strong> از بخش «ترتیب» در کادر «ویژگی‌های برگه» سمت چپ کنترل می‌شود. مقدار کمتر، زودتر نمایش داده می‌شود.</p>
	<?php
}

function sfcore_render_testimonial_meta( $post ) {
	wp_nonce_field( 'sfcore_save_testimonial', 'sfcore_testimonial_nonce' ); $role = get_post_meta( $post->ID, '_sfcore_testimonial_role', true );
	?>
	<p><label><strong>سمت / توضیح کوتاه</strong></label><br><input type="text" name="sfcore_testimonial_role" value="<?php echo esc_attr( $role ); ?>" style="width:100%"></p>
	<p>نام هنرجو را به عنوان <strong>عنوان نوشته</strong> وارد کنید، متن نظر را در ویرایشگر بنویسید و در صورت نیاز تصویر هنرجو را به عنوان «تصویر شاخص» انتخاب کنید.</p>
	<?php
}

function sfcore_save_meta( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) { return; }
	if ( wp_is_post_revision( $post_id ) || ! current_user_can( 'edit_post', $post_id ) ) { return; }
	if ( 'sf_learning_path' === get_post_type( $post_id ) && isset( $_POST['sfcore_path_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['sfcore_path_nonce'] ) ), 'sfcore_save_path' ) ) {
		update_post_meta( $post_id, '_sfcore_path_text', isset( $_POST['sfcore_path_text'] ) ? sanitize_textarea_field( wp_unslash( $_POST['sfcore_path_text'] ) ) : '' );
		update_post_meta( $post_id, '_sfcore_path_button_text', isset( $_POST['sfcore_path_button_text'] ) ? sanitize_text_field( wp_unslash( $_POST['sfcore_path_button_text'] ) ) : '' );
		update_post_meta( $post_id, '_sfcore_path_button_url', isset( $_POST['sfcore_path_button_url'] ) ? esc_url_raw( wp_unslash( $_POST['sfcore_path_button_url'] ) ) : '' );
	}
	if ( 'sf_testimonial' === get_post_type( $post_id ) && isset( $_POST['sfcore_testimonial_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['sfcore_testimonial_nonce'] ) ), 'sfcore_save_testimonial' ) ) {
		update_post_meta( $post_id, '_sfcore_testimonial_role', isset( $_POST['sfcore_testimonial_role'] ) ? sanitize_text_field( wp_unslash( $_POST['sfcore_testimonial_role'] ) ) : '' );
	}
}
add_action( 'save_post', 'sfcore_save_meta' );

function sfcore_get_learning_paths() { return get_posts( array( 'post_type' => 'sf_learning_path', 'post_status' => 'publish', 'posts_per_page' => -1, 'orderby' => array( 'menu_order' => 'ASC', 'date' => 'ASC' ), 'order' => 'ASC' ) ); }
function sfcore_get_testimonials( $count = 3 ) { return get_posts( array( 'post_type' => 'sf_testimonial', 'post_status' => 'publish', 'posts_per_page' => max( 1, absint( $count ) ), 'orderby' => array( 'menu_order' => 'ASC', 'date' => 'DESC' ), 'order' => 'ASC' ) ); }

function sfcore_get_latest_course_ids( $count = 5 ) {
	$count = max( 1, absint( $count ) ); $ids = array();
	if ( function_exists( 'tutor' ) ) {
		if ( class_exists( '\\Tutor\\Models\\CourseModel' ) ) {
			$courses = \Tutor\Models\CourseModel::get_courses( array(), array( 'publish' ) );
			if ( is_array( $courses ) ) { foreach ( $courses as $course ) { if ( ! empty( $course->ID ) ) { $ids[] = absint( $course->ID ); } } }
		} else {
			$ids = get_posts( array( 'post_type' => tutor()->course_post_type, 'post_status' => 'publish', 'posts_per_page' => -1, 'fields' => 'ids', 'suppress_filters' => true ) );
		}
	}
	if ( empty( $ids ) ) {
		$post_type = post_type_exists( 'tutor_course' ) ? 'tutor_course' : ( post_type_exists( 'courses' ) ? 'courses' : 'course' );
		$ids = get_posts( array( 'post_type' => $post_type, 'post_status' => 'publish', 'posts_per_page' => -1, 'fields' => 'ids', 'suppress_filters' => true ) );
	}
	$ids = array_values( array_unique( array_map( 'absint', $ids ) ) );
	usort( $ids, function( $a, $b ) { return strcmp( get_post_field( 'post_date', $b ), get_post_field( 'post_date', $a ) ); } );
	return array_slice( $ids, 0, $count );
}

function sfcore_admin_columns( $columns ) { $columns['menu_order'] = 'ترتیب'; return $columns; }
add_filter( 'manage_sf_learning_path_posts_columns', 'sfcore_admin_columns' ); add_filter( 'manage_sf_testimonial_posts_columns', 'sfcore_admin_columns' );
function sfcore_admin_column_content( $column, $post_id ) { if ( 'menu_order' === $column ) { echo esc_html( get_post_field( 'menu_order', $post_id ) ); } }
add_action( 'manage_sf_learning_path_posts_custom_column', 'sfcore_admin_column_content', 10, 2 ); add_action( 'manage_sf_testimonial_posts_custom_column', 'sfcore_admin_column_content', 10, 2 );

function sfcore_migrate_legacy_content() {
	if ( get_option( 'sfcore_legacy_migrated' ) ) { return; }
	$paths = get_option( 'sf_learning_paths', array() );
	if ( is_array( $paths ) ) {
		$order = 1;
		foreach ( $paths as $item ) {
			if ( empty( $item['title'] ) && empty( $item['image'] ) ) { continue; }
			$post_id = wp_insert_post( array( 'post_type' => 'sf_learning_path', 'post_status' => 'publish', 'post_title' => sanitize_text_field( $item['title'] ?? '' ), 'menu_order' => $order++ ) );
			if ( $post_id && ! is_wp_error( $post_id ) ) {
				if ( ! empty( $item['image'] ) ) { set_post_thumbnail( $post_id, absint( $item['image'] ) ); }
				update_post_meta( $post_id, '_sfcore_path_text', sanitize_textarea_field( $item['text'] ?? '' ) );
				update_post_meta( $post_id, '_sfcore_path_button_text', sanitize_text_field( $item['button_text'] ?? '' ) );
				update_post_meta( $post_id, '_sfcore_path_button_url', esc_url_raw( $item['button_url'] ?? '' ) );
			}
		}
	}
	for ( $i = 1; $i <= 3; $i++ ) {
		$text = get_theme_mod( 'sf_testimonial_' . $i . '_text', '' ); $name = get_theme_mod( 'sf_testimonial_' . $i . '_name', '' ); $role = get_theme_mod( 'sf_testimonial_' . $i . '_role', '' );
		if ( $text && $name ) {
			$post_id = wp_insert_post( array( 'post_type' => 'sf_testimonial', 'post_status' => 'publish', 'post_title' => sanitize_text_field( $name ), 'post_content' => wp_kses_post( $text ), 'menu_order' => $i ) );
			if ( $post_id && ! is_wp_error( $post_id ) ) { update_post_meta( $post_id, '_sfcore_testimonial_role', sanitize_text_field( $role ) ); }
		}
	}
	update_option( 'sfcore_legacy_migrated', 1, false );
}

function sfcore_activate() { sfcore_register_content_types(); sfcore_migrate_legacy_content(); flush_rewrite_rules(); }
function sfcore_deactivate() { flush_rewrite_rules(); }
register_activation_hook( __FILE__, 'sfcore_activate' ); register_deactivation_hook( __FILE__, 'sfcore_deactivate' );
