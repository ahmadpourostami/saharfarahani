<?php
/** Custom single course template for Tutor LMS. */
defined( 'ABSPATH' ) || exit;

use Tutor\Models\EnrollmentModel;

$course_id = get_the_ID();
$is_enrolled = EnrollmentModel::is_enrolled( $course_id, get_current_user_id() );
$course_nav_item = apply_filters( 'tutor_course/single/nav_items', tutor_utils()->course_nav_items(), $course_id );
$is_public = \TUTOR\Course_List::is_public( $course_id );
$student_must_login_to_view_course = tutor_utils()->get_option( 'student_must_login_to_view_course' );
$has_video = apply_filters( 'tutor_course_has_video', tutor_utils()->has_video_in_single(), $course_id );
$author_id = (int) get_post_field( 'post_author', $course_id );
$author_name = get_the_author_meta( 'display_name', $author_id );
$author_avatar = get_avatar_url( $author_id, array( 'size' => 160 ) );
$rating = tutor_utils()->get_course_rating( $course_id );
$rating_value = is_array( $rating ) ? ( $rating['rating_avg'] ?? $rating['rating'] ?? 0 ) : 0;
$rating_count = is_array( $rating ) ? ( $rating['rating_count'] ?? $rating['count'] ?? 0 ) : 0;
$course_duration = '';
try { $course_duration = tutor_utils()->get_course_duration( $course_id, false ); } catch ( Throwable $e ) { $course_duration = ''; }
$lesson_count = 0;
try { $lesson_count = tutor_utils()->get_lesson_count_by_course( $course_id ); } catch ( Throwable $e ) { $lesson_count = 0; }

if ( ! is_user_logged_in() && ! $is_public && $student_must_login_to_view_course ) {
    get_header();
    echo '<main class="sf-course-login-required sf-section"><div class="sf-container">';
    tutor_load_template( 'login' );
    echo '</div></main>';
    get_footer();
    return;
}

get_header();
?>
<main class="sf-course-single">
    <div class="sf-container">
        <nav class="sf-course-breadcrumb" aria-label="مسیر صفحه">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>">خانه</a><span>‹</span>
            <a href="<?php echo esc_url( get_post_type_archive_link( 'courses' ) ); ?>">دوره‌ها</a><span>‹</span>
            <strong><?php the_title(); ?></strong>
        </nav>

        <section class="sf-course-hero-card">
            <div class="sf-course-purchase">
                <div class="sf-course-purchase__media">
                    <?php if ( $has_video ) : ?><div class="sf-course-preview-badge">پیش‌نمایش دوره</div><?php tutor_course_video(); ?>
                    <?php elseif ( has_post_thumbnail() ) : the_post_thumbnail( 'large', array( 'class' => 'sf-course-cover' ) );
                    else : ?><div class="sf-course-cover sf-course-cover--empty">سحر فراهانی</div><?php endif; ?>
                </div>
                <div class="sf-course-purchase__body" id="sf-course-enroll">
                    <?php /* Tutor LMS's official Course Entry Box owns the course price and purchase state. */ ?>
                    <div class="sf-course-enroll-box"><?php tutor_load_template( 'single.course.course-entry-box' ); ?></div>
                    <div class="sf-course-benefits">
                        <div><span>∞</span> دسترسی دانشجو به محتوای دوره</div>
                        <div><span>♧</span> آپدیت‌های رایگان</div>
                        <div><span>✦</span> پشتیبانی و پاسخ‌گویی</div>
                        <div><span>✓</span> گواهینامه پایان دوره</div>
                    </div>
                </div>
            </div>

            <div class="sf-course-intro">
                <?php $terms = get_the_terms( $course_id, 'course-category' ); if ( $terms && ! is_wp_error( $terms ) ) : ?>
                    <div class="sf-course-labels"><span><?php echo esc_html( $terms[0]->name ); ?></span><span class="is-light">همه سطوح</span></div>
                <?php endif; ?>
                <div class="sf-course-lead"><?php tutor_load_template( 'single.course.lead-info' ); ?></div>
                <div class="sf-course-rating-row">
                    <div class="sf-course-rating"><strong><?php echo esc_html( number_format_i18n( (float) $rating_value, 1 ) ); ?></strong><span class="sf-stars">★★★★★</span><small>(<?php echo esc_html( number_format_i18n( (int) $rating_count ) ); ?> نظر)</small></div>
                    <div class="sf-course-students"><span>♙</span> دانشجویان دوره</div>
                </div>
                <div class="sf-course-stats">
                    <div><span class="sf-stat-icon">◷</span><strong><?php echo esc_html( $course_duration ?: '—' ); ?></strong><small>زمان دوره</small></div>
                    <div><span class="sf-stat-icon">▣</span><strong><?php echo esc_html( $lesson_count ?: '—' ); ?></strong><small>جلسه و محتوا</small></div>
                    <div><span class="sf-stat-icon">◫</span><strong><?php echo esc_html( $is_enrolled ? 'فعال' : 'در دسترس' ); ?></strong><small>وضعیت دسترسی</small></div>
                    <div><span class="sf-stat-icon">▤</span><strong><?php echo $has_video ? 'ویدئویی' : 'آموزشی'; ?></strong><small>نوع دوره</small></div>
                </div>
                <div class="sf-course-instructor">
                    <img src="<?php echo esc_url( $author_avatar ); ?>" alt="<?php echo esc_attr( $author_name ); ?>">
                    <div><small>مدرس:</small><strong><?php echo esc_html( $author_name ); ?></strong><p>مدرس تخصصی بازیگری، فن بیان و مهارت‌های اجرا</p></div>
                </div>

                <div class="sf-course-curriculum sf-course-curriculum--below-instructor">
                    <div class="sf-course-sidebar-card">
                        <div class="sf-sidebar-heading"><h2>سرفصل‌های دوره</h2><span><?php echo esc_html( $course_duration ?: 'برنامه آموزشی' ); ?></span></div>
                        <?php tutor_course_topics(); ?>
                    </div>
                </div>

                <div class="sf-course-additional-info">
                    <div class="sf-course-info-card">
                        <h2>اطلاعات تکمیلی دوره</h2>
                        <div class="sf-course-info-grid">
                            <?php tutor_course_benefits_html(); ?>
                            <?php tutor_course_requirements_html(); ?>
                            <?php tutor_course_target_audience_html(); ?>
                            <?php tutor_course_material_includes_html(); ?>
                            <?php tutor_course_tags_html(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="sf-course-tabs-section">
            <?php if ( is_array( $course_nav_item ) && count( $course_nav_item ) > 1 ) : ?>
                <div class="sf-course-tabs" role="tablist">
                    <?php foreach ( $course_nav_item as $key => $subpage ) : ?><a href="#sf-course-tab-<?php echo esc_attr( $key ); ?>" class="<?php echo 'info' === $key ? 'is-active' : ''; ?>"><?php echo esc_html( $subpage['title'] ?? $key ); ?></a><?php endforeach; ?>
                </div>
            <?php endif; ?>
            <div class="sf-course-tabs-grid">
                <div class="sf-course-main-content">
                    <?php foreach ( $course_nav_item as $key => $subpage ) : ?>
                        <section id="sf-course-tab-<?php echo esc_attr( $key ); ?>" class="sf-course-tab-panel <?php echo 'info' === $key ? 'is-active' : ''; ?>">
                            <?php
                            do_action( 'tutor_course/single/tab/' . $key . '/before' );
                            $method = $subpage['method'];
                            if ( is_string( $method ) && is_callable( $method ) ) { $method(); }
                            elseif ( is_array( $method ) && isset( $method[0], $method[1] ) && is_callable( $method ) ) { call_user_func( $method, $course_id ); }
                            do_action( 'tutor_course/single/tab/' . $key . '/after' );
                            ?>
                        </section>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <section class="sf-course-related">
            <div class="sf-section-heading"><span class="sf-eyebrow">پیشنهاد ما</span><h2>دوره‌های مرتبط</h2><p>برای تکمیل مسیر یادگیری خود، این دوره‌ها را هم ببینید.</p></div>
            <div class="sf-related-grid">
                <?php
                $related = new WP_Query( array( 'post_type' => 'courses', 'post_status' => 'publish', 'posts_per_page' => 3, 'post__not_in' => array( $course_id ), 'orderby' => 'date', 'order' => 'DESC', 'no_found_rows' => true ) );
                if ( $related->have_posts() ) : while ( $related->have_posts() ) : $related->the_post(); sf_course_card( get_the_ID() ); endwhile; wp_reset_postdata();
                else : echo '<p class="sf-empty">دوره مرتبط دیگری منتشر نشده است.</p>'; endif;
                ?>
            </div>
        </section>

        <section class="sf-course-final-cta">
            <div><span class="sf-eyebrow">شروع مسیر حرفه‌ای</span><h2>آمادگی برای ورود به دنیای بازیگری؟</h2><p>همین حالا مسیر یادگیری خود را شروع کنید و قدم‌به‌قدم مهارت‌های حرفه‌ای خود را توسعه دهید.</p></div>
            <a class="sf-button sf-button--primary" href="#sf-course-enroll">ثبت‌نام در دوره <span>←</span></a>
        </section>
    </div>
</main>
<?php get_footer(); ?>
