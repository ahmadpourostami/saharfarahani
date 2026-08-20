<?php
/** Front page. */
get_header();
$hero_image_id = absint( sf_get_mod( 'sf_hero_image', 0 ) );
$about_image_id = absint( sf_get_mod( 'sf_about_image', 0 ) );
$hero_image = $hero_image_id ? wp_get_attachment_image_url( $hero_image_id, 'full' ) : '';
$about_image = $about_image_id ? wp_get_attachment_image_url( $about_image_id, 'sf-portrait' ) : '';
$taxonomy = sf_get_course_taxonomy();
$course_post_type = function_exists( 'tutor' ) && ! empty( tutor()->course_post_type ) ? tutor()->course_post_type : ( post_type_exists( 'tutor_course' ) ? 'tutor_course' : 'course' );
$cat_count = max( 1, absint( sf_get_mod( 'sf_category_count', 4 ) ) );
$per_category = max( 1, absint( sf_get_mod( 'sf_courses_per_category', 4 ) ) );
$latest_count = max( 1, absint( sf_get_mod( 'sf_latest_course_count', 5 ) ) );
$categories = get_terms( array( 'taxonomy' => $taxonomy, 'hide_empty' => true, 'number' => $cat_count, 'orderby' => 'count', 'order' => 'DESC' ) );
$paths = sf_get_path_items();
?>
<main>
<section class="sf-hero"><div class="sf-hero__overlay"></div><?php if ( $hero_image ) : ?><div class="sf-hero__background" style="background-image:url('<?php echo esc_url( $hero_image ); ?>');"></div><?php endif; ?><div class="sf-container sf-hero__grid"><div class="sf-hero__content"><span class="sf-eyebrow"><?php echo esc_html( sf_get_mod( 'sf_hero_eyebrow', 'سحر فراهانی' ) ); ?></span><h1><?php echo esc_html( sf_get_mod( 'sf_hero_title', 'هنرِ دیده شدن، شنیده شدن و خلق کردن' ) ); ?></h1><p><?php echo esc_html( sf_get_mod( 'sf_hero_text', 'آموزش تخصصی بازیگری، فن بیان و مهارت‌های تئاتر و سینما؛ از درون تا پشت صحنه.' ) ); ?></p><div class="sf-actions"><a class="sf-button sf-button--primary" href="<?php echo esc_url( sf_get_mod( 'sf_hero_button_url', '#' ) ); ?>"><?php echo esc_html( sf_get_mod( 'sf_hero_button_text', 'مشاهده دوره‌ها' ) ); ?> <span>←</span></a><a class="sf-button sf-button--ghost" href="<?php echo esc_url( sf_get_mod( 'sf_hero_secondary_url', '#' ) ); ?>"><?php echo esc_html( sf_get_mod( 'sf_hero_secondary_text', 'درباره سحر فراهانی' ) ); ?> <span>←</span></a></div></div></div></section>
<section class="sf-section sf-categories"><div class="sf-container"><div class="sf-section-heading sf-section-heading--center"><span class="sf-eyebrow"><?php echo esc_html( sf_get_mod( 'sf_categories_kicker', 'مسیر یادگیری' ) ); ?></span><h2><?php echo esc_html( sf_get_mod( 'sf_categories_title', 'چه چیزی می‌خواهید یاد بگیرید؟' ) ); ?></h2><p><?php echo esc_html( sf_get_mod( 'sf_categories_text', 'حوزه مورد علاقه‌تان را انتخاب کنید و دوره‌های مرتبط را ببینید.' ) ); ?></p></div><?php if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) : ?><div class="sf-category-grid"><?php foreach ( $categories as $category ) : $image = sf_category_thumbnail( $category ); $courses = new WP_Query( array( 'post_type' => $course_post_type, 'post_status' => 'publish', 'posts_per_page' => $per_category, 'tax_query' => array( array( 'taxonomy' => $taxonomy, 'field' => 'term_id', 'terms' => $category->term_id ) ), 'orderby' => 'date', 'order' => 'DESC', 'no_found_rows' => true ) ); ?><article class="sf-category-card"><a class="sf-category-card__media" href="<?php echo esc_url( get_term_link( $category ) ); ?>"><?php if ( $image ) : ?><img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $category->name ); ?>" loading="lazy"><?php else : ?><span class="sf-category-card__placeholder">SF</span><?php endif; ?><span class="sf-category-card__icon" aria-hidden="true">✦</span></a><div class="sf-category-card__body"><h3><?php echo esc_html( $category->name ); ?></h3><?php if ( $courses->have_posts() ) : ?><ul><?php while ( $courses->have_posts() ) : $courses->the_post(); ?><li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li><?php endwhile; ?></ul><?php endif; wp_reset_postdata(); ?><a class="sf-text-link" href="<?php echo esc_url( get_term_link( $category ) ); ?>"><?php echo esc_html( sf_get_mod( 'sf_category_button_text', 'مشاهده دوره‌ها' ) ); ?> <span>←</span></a></div></article><?php endforeach; ?></div><?php else : ?><div class="sf-empty">برای نمایش این بخش، حداقل یک دسته دوره در Tutor LMS بسازید.</div><?php endif; ?></div></section>
<section class="sf-section sf-latest"><div class="sf-container"><div class="sf-section-heading sf-section-heading--center"><span class="sf-eyebrow"><?php echo esc_html( sf_get_mod( 'sf_courses_kicker', 'دوره‌های آموزشی' ) ); ?></span><h2><?php echo esc_html( sf_get_mod( 'sf_courses_title', 'دوره‌های آموزشی سحر فراهانی' ) ); ?></h2><p><?php echo esc_html( sf_get_mod( 'sf_courses_text', 'جدیدترین دوره‌ها را ببینید و مسیر یادگیری خود را شروع کنید.' ) ); ?></p></div>
<?php
// Tutor LMS exposes courses as WordPress posts. Build the latest list from the native course query first.
$latest_ids = get_posts( array(
    'post_type'              => $course_post_type,
    'post_status'            => 'publish',
    'posts_per_page'         => $latest_count,
    'orderby'                => 'date',
    'order'                  => 'DESC',
    'fields'                 => 'ids',
    'ignore_sticky_posts'    => true,
    'suppress_filters'       => false,
) );

// Some Tutor installations apply a filter to an unscoped course query. Since the category cards above
// already prove which courses are publicly queryable, use those courses as a reliable fallback.
if ( empty( $latest_ids ) && ! empty( $categories ) && ! is_wp_error( $categories ) ) {
    $candidate_ids = array();
    foreach ( $categories as $category ) {
        $category_ids = get_posts( array(
            'post_type'              => $course_post_type,
            'post_status'            => 'publish',
            'posts_per_page'         => -1,
            'fields'                 => 'ids',
            'orderby'                => 'date',
            'order'                  => 'DESC',
            'tax_query'              => array( array(
                'taxonomy' => $taxonomy,
                'field'    => 'term_id',
                'terms'    => $category->term_id,
            ) ),
            'suppress_filters'       => false,
        ) );
        $candidate_ids = array_merge( $candidate_ids, $category_ids );
    }
    $candidate_ids = array_values( array_unique( array_map( 'absint', $candidate_ids ) ) );
    usort( $candidate_ids, function( $a, $b ) {
        return strcmp( get_post_field( 'post_date', $b ), get_post_field( 'post_date', $a ) );
    } );
    $latest_ids = array_slice( $candidate_ids, 0, $latest_count );
}
?>
<?php if ( ! empty( $latest_ids ) ) : ?><div class="sf-course-slider"><button class="sf-slider-arrow sf-slider-arrow--prev" type="button" aria-label="قبلی">‹</button><div class="sf-course-track"><?php foreach ( $latest_ids as $course_id ) { sf_course_card( $course_id ); } ?></div><button class="sf-slider-arrow sf-slider-arrow--next" type="button" aria-label="بعدی">›</button></div><?php else : ?><div class="sf-empty">هنوز دوره‌ای منتشر نشده است.</div><?php endif; ?><div class="sf-section-action"><a class="sf-button sf-button--primary" href="<?php echo esc_url( sf_get_mod( 'sf_courses_button_url', '#' ) ); ?>"><?php echo esc_html( sf_get_mod( 'sf_courses_button_text', 'مشاهده همه دوره‌ها' ) ); ?> <span>←</span></a></div></div></section>
<section class="sf-section sf-paths"><div class="sf-container"><div class="sf-section-heading sf-section-heading--center"><span class="sf-eyebrow"><?php echo esc_html( sf_get_mod( 'sf_paths_kicker', 'مسیر پیشنهادی' ) ); ?></span><h2><?php echo esc_html( sf_get_mod( 'sf_paths_title', 'اگر می‌خواهید بازیگر شوید، از اینجا شروع کنید.' ) ); ?></h2><p><?php echo esc_html( sf_get_mod( 'sf_paths_text', 'یک مسیر مرحله‌به‌مرحله برای ساخت مهارت‌های اصلی بازیگری.' ) ); ?></p></div><div class="sf-path-slider"><button class="sf-slider-arrow sf-slider-arrow--prev" type="button" aria-label="قبلی">‹</button><div class="sf-path-track"><?php foreach ( $paths as $index => $item ) : if ( empty( $item['title'] ) && empty( $item['image'] ) ) continue; ?><div class="sf-path-step"><?php if ( ! empty( $item['image'] ) ) : ?><div class="sf-path-step__icon"><?php echo wp_get_attachment_image( absint( $item['image'] ), 'thumbnail', false, array( 'loading' => 'lazy' ) ); ?></div><?php else : ?><div class="sf-path-step__icon"><span><?php echo esc_html( sprintf( '%02d', $index ) ); ?></span></div><?php endif; ?><h3><?php echo esc_html( $item['title'] ); ?></h3></div><?php endforeach; ?></div><button class="sf-slider-arrow sf-slider-arrow--next" type="button" aria-label="بعدی">›</button></div><div class="sf-path-action"><a class="sf-button sf-button--primary" href="<?php echo esc_url( sf_get_mod( 'sf_paths_button_url', '#' ) ); ?>"><?php echo esc_html( sf_get_mod( 'sf_paths_button_text', 'شروع مسیر بازیگری' ) ); ?> <span>←</span></a></div></div></section>
<section class="sf-section sf-about"><div class="sf-container sf-about__grid"><div class="sf-about__media"><?php if ( $about_image ) : ?><img src="<?php echo esc_url( $about_image ); ?>" alt="سحر فراهانی" loading="lazy"><?php else : ?><div class="sf-image-placeholder sf-image-placeholder--portrait">تصویر سحر فراهانی</div><?php endif; ?></div><div class="sf-about__content"><span class="sf-eyebrow"><?php echo esc_html( sf_get_mod( 'sf_about_kicker', 'درباره من' ) ); ?></span><h2><?php echo esc_html( sf_get_mod( 'sf_about_title', 'من سحر فراهانی هستم.' ) ); ?></h2><p><?php echo esc_html( sf_get_mod( 'sf_about_text', 'سال‌هاست که در مسیر آموزش بازیگری، فن بیان و مهارت‌های تئاتر و سینما فعالیت می‌کنم. باور دارم که آموزش هنر زمانی ماندگار می‌شود که هنرجو بتواند آموخته‌هایش را در عمل تجربه کند.' ) ); ?></p><div class="sf-about-stats"><div><strong><?php echo esc_html( sf_get_mod( 'sf_stat_1_value', '+۱۰' ) ); ?></strong><span><?php echo esc_html( sf_get_mod( 'sf_stat_1_label', 'سال تجربه' ) ); ?></span></div><div><strong><?php echo esc_html( sf_get_mod( 'sf_stat_2_value', '+۳۰۰۰' ) ); ?></strong><span><?php echo esc_html( sf_get_mod( 'sf_stat_2_label', 'هنرجو' ) ); ?></span></div><div><strong><?php echo esc_html( sf_get_mod( 'sf_stat_3_value', '+۴۰' ) ); ?></strong><span><?php echo esc_html( sf_get_mod( 'sf_stat_3_label', 'دوره آموزشی' ) ); ?></span></div></div><a class="sf-button sf-button--primary" href="<?php echo esc_url( sf_get_mod( 'sf_about_button_url', '#' ) ); ?>"><?php echo esc_html( sf_get_mod( 'sf_about_button_text', 'داستان من' ) ); ?> <span>←</span></a></div></div></section>
<section class="sf-section sf-testimonials"><div class="sf-container"><div class="sf-section-heading sf-section-heading--center"><span class="sf-eyebrow">تجربه هنرجویان</span><h2><?php echo esc_html( sf_get_mod( 'sf_testimonials_title', 'هنرجویان درباره تجربه‌شان چه می‌گویند؟' ) ); ?></h2></div><div class="sf-testimonial-grid"><?php for ( $i = 1; $i <= 3; $i++ ) : ?><article class="sf-testimonial"><span class="sf-testimonial__quote">”</span><p><?php echo esc_html( sf_get_mod( 'sf_testimonial_' . $i . '_text', 'محتوای دوره دقیق، کاربردی و قابل اجرا بود و در مدت کوتاهی تغییر آن را در تمرین‌هایم احساس کردم.' ) ); ?></p><div><strong><?php echo esc_html( sf_get_mod( 'sf_testimonial_' . $i . '_name', 'نام هنرجو' ) ); ?></strong><span><?php echo esc_html( sf_get_mod( 'sf_testimonial_' . $i . '_role', 'هنرجو' ) ); ?></span></div></article><?php endfor; ?></div></div></section>
<section class="sf-cta"><div class="sf-container"><div class="sf-cta__inner"><span class="sf-eyebrow">شروع مسیر</span><h2><?php echo esc_html( sf_get_mod( 'sf_cta_title', 'آماده‌ای صدای خودت را پیدا کنی؟' ) ); ?></h2><p><?php echo esc_html( sf_get_mod( 'sf_cta_text', 'دوره مناسب خودت را انتخاب کن و مسیر یادگیری را شروع کن.' ) ); ?></p><a class="sf-button sf-button--light" href="<?php echo esc_url( sf_get_mod( 'sf_cta_button_url', '#' ) ); ?>"><?php echo esc_html( sf_get_mod( 'sf_cta_button_text', 'مشاهده دوره‌ها' ) ); ?> <span>←</span></a></div></div></section>
</main>
<?php get_footer(); ?>