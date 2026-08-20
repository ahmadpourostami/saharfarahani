<?php
/** Tutor LMS course archive. */
get_header();
?>
<main class="sf-section sf-content-page"><div class="sf-container">
<header class="sf-page-content__header sf-page-content__header--split"><div><span class="sf-eyebrow"><?php echo esc_html( sf_get_mod( 'sf_courses_kicker', 'دوره‌های آموزشی' ) ); ?></span><h1><?php post_type_archive_title(); ?></h1></div><p><?php echo esc_html( sf_get_mod( 'sf_courses_text', 'دوره‌های تازه منتشرشده را برای شروع یادگیری ببینید.' ) ); ?></p></header>
<?php if ( have_posts() ) : ?><div class="sf-course-grid"><?php while ( have_posts() ) : the_post(); sf_course_card( get_the_ID() ); endwhile; ?></div><?php the_posts_pagination( array( 'mid_size' => 1 ) ); ?><?php else : ?><div class="sf-empty">دوره‌ای برای نمایش وجود ندارد.</div><?php endif; ?>
</div></main>
<?php get_footer(); ?>
