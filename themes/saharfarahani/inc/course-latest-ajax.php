<?php
/** AJAX fallback for latest Tutor LMS courses. */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function sf_ajax_latest_courses() {
	$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
	if ( ! wp_verify_nonce( $nonce, 'sf_latest_courses_nonce' ) ) {
		wp_send_json_error( array( 'message' => 'درخواست نامعتبر است.' ), 403 );
	}

	$count = isset( $_POST['count'] ) ? max( 1, min( 12, absint( $_POST['count'] ) ) ) : 5;
	$taxonomy = sf_get_course_taxonomy();
	$post_type = function_exists( 'tutor' ) && ! empty( tutor()->course_post_type ) ? tutor()->course_post_type : ( post_type_exists( 'tutor_course' ) ? 'tutor_course' : 'course' );
	$ids = array();

	// Use the exact same taxonomy-scoped query that is already working on the homepage cards.
	$terms = get_terms( array(
		'taxonomy'   => $taxonomy,
		'hide_empty' => true,
		'number'     => 50,
		'orderby'    => 'count',
		'order'      => 'DESC',
	) );

	if ( ! is_wp_error( $terms ) ) {
		foreach ( $terms as $term ) {
			$term_ids = get_posts( array(
				'post_type'      => $post_type,
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				'fields'         => 'ids',
				'orderby'        => 'date',
				'order'          => 'DESC',
				'tax_query'      => array( array(
					'taxonomy' => $taxonomy,
					'field'    => 'term_id',
					'terms'    => $term->term_id,
				) ),
				'no_found_rows'  => true,
			) );
			$ids = array_merge( $ids, $term_ids );
		}
	}

	$ids = array_values( array_unique( array_map( 'absint', $ids ) ) );
	usort( $ids, function ( $a, $b ) {
		return strcmp( get_post_field( 'post_date', $b ), get_post_field( 'post_date', $a ) );
	} );
	$ids = array_slice( $ids, 0, $count );

	if ( empty( $ids ) ) {
		wp_send_json_success( array( 'html' => '', 'count' => 0 ) );
	}

	ob_start();
	foreach ( $ids as $course_id ) {
		sf_course_card( $course_id );
	}
	$html = ob_get_clean();
	wp_send_json_success( array( 'html' => $html, 'count' => count( $ids ) ) );
}
add_action( 'wp_ajax_sf_latest_courses', 'sf_ajax_latest_courses' );
add_action( 'wp_ajax_nopriv_sf_latest_courses', 'sf_ajax_latest_courses' );
