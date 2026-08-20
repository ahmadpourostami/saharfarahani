<?php
/** Template helpers. */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function sf_get_mod( $key, $default = '' ) { return get_theme_mod( $key, $default ); }

function sf_course_price( $course_id ) {
	$price = apply_filters( 'get_tutor_course_price', null, $course_id );
	return $price ? wp_kses_post( $price ) : esc_html__( 'رایگان', 'saharfarahani' );
}

function sf_course_duration( $course_id ) {
	if ( function_exists( 'tutor_utils' ) ) {
		$duration = tutor_utils()->get_course_duration( $course_id );
		if ( is_string( $duration ) && $duration ) { return $duration; }
	}
	return '';
}

function sf_course_card( $course_id, $class = '' ) {
	$title = get_the_title( $course_id );
	$url = get_permalink( $course_id );
	$thumb = get_the_post_thumbnail_url( $course_id, 'sf-course-card' );
	$taxonomy = sf_get_course_taxonomy();
	$terms = get_the_terms( $course_id, $taxonomy );
	$term_name = ( $terms && ! is_wp_error( $terms ) ) ? $terms[0]->name : '';
	?>
	<article class="sf-course-card <?php echo esc_attr( $class ); ?>">
		<a class="sf-course-card__media" href="<?php echo esc_url( $url ); ?>" aria-label="<?php echo esc_attr( $title ); ?>">
			<?php if ( $thumb ) : ?><img src="<?php echo esc_url( $thumb ); ?>" alt="<?php echo esc_attr( $title ); ?>" loading="lazy"><?php else : ?><span class="sf-course-card__placeholder" aria-hidden="true">SF</span><?php endif; ?>
			<?php if ( $term_name ) : ?><span class="sf-course-card__tag"><?php echo esc_html( $term_name ); ?></span><?php endif; ?>
		</a>
		<div class="sf-course-card__body">
			<h3><a href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( $title ); ?></a></h3>
			<div class="sf-course-card__meta"><span><?php echo sf_course_price( $course_id ); ?></span><?php $duration = sf_course_duration( $course_id ); ?><?php if ( $duration ) : ?><span><?php echo esc_html( $duration ); ?></span><?php endif; ?></div>
		</div>
	</article>
	<?php
}

function sf_category_thumbnail( $term ) {
	$thumbnail_id = absint( get_term_meta( $term->term_id, 'thumbnail_id', true ) );
	return $thumbnail_id ? wp_get_attachment_image_url( $thumbnail_id, 'sf-category-card' ) : '';
}

function sf_get_path_items() {
	$defaults = array();
	for ( $i = 1; $i <= 8; $i++ ) { $defaults[ $i ] = array( 'image' => 0, 'title' => '', 'text' => '', 'button_text' => '', 'button_url' => '' ); }
	$saved = get_option( 'sf_learning_paths', array() );
	return wp_parse_args( is_array( $saved ) ? $saved : array(), $defaults );
}
