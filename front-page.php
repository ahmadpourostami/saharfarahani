<?php
/** Front page. */
get_header();
$hero_image_id = absint( sf_get_mod( 'sf_hero_image', 0 ) );
$about_image_id = absint( sf_get_mod( 'sf_about_image', 0 ) );
$hero_image = $hero_image_id ? wp_get_attachment_image_url( $hero_image_id, 'large' ) : '';
$about_image = $about_image_id ? wp_get_attachment_image_url( $about_image_id, 'sf-portrait' ) : '';
$taxonomy = sf_get_course_taxonomy();
$cat_count = max( 1, absint( sf_get_mod( 'sf_category_count', 4 ) ) );
$per_category = max( 1, absint( sf_get_mod( 'sf_courses_per_category', 3 ) ) );
$latest_count = max( 1, absint( sf_get_mod( 'sf_latest_course_count', 6 ) ) );
$categories = get_terms( array( 'taxonomy' => $taxonomy, 'hide_empty' => true, 'number' => $cat_count, 'orderby' => 'count', 'order' => 'DESC' ) );
$paths = sf_get_path_items();
?>
<main>
	<section class="sf-hero">
		<div class="sf-container sf-hero__grid">
			<div class="sf-hero__content">
				<span class="sf-eyebrow"><?php echo esc_html( sf_get_mod( 'sf_hero_eyebrow', 'سحر فراهانی' ) ); ?></span>
				<h1><?php echo esc_html( sf_get_mod( 'sf_hero_title', 'هنرِ دیده شدن، شنیده شدن و خلق کردن' ) ); ?></h1>
				<p><?php echo esc_html( sf_get_mod( 'sf_hero_text', 'آموزش تخصصی بازیگری، فن بیان و مهارت‌های تئاتر و سینما.' ) ); ?></p>
				<div class="sf-actions">
					<a class="sf-button sf-button--primary" href="<?php echo esc_url( sf_get_mod( 'sf_hero_button_url', '#' ) ); ?>"><?php echo esc_html( sf_get_mod( 'sf_hero_button_text', 'مشاهده دوره‌ها' ) ); ?></a>
					<a class="sf-button sf-button--ghost" href="<?php echo esc_url( sf_get_mod( 'sf_hero_secondary_url', '#' ) ); ?>"><?php echo esc_html( sf_get_mod( 'sf_hero_secondary_text', 'درباره من' ) ); ?></a>
				</div>
			</div>
			<div class="sf-hero__visual">
				<div class="sf-hero__frame">
					<?php if ( $hero_image ) : ?><img src="<?php echo esc_url( $hero_image ); ?>" alt="<?php echo esc_attr( sf_get_mod( 'sf_hero_eyebrow', 'سحر فراهانی' ) ); ?>"><?php else : ?><div class="sf-image-placeholder">تصویر سحر فراهانی</div><?php endif; ?>
				</div>
				<div class="sf-hero__note">ACT · CREATE · PERFORM</div>
			</div>
		</div>
	</section>

	<section class="sf-section sf-about">
		<div class="sf-container sf-about__grid">
			<div class="sf-about__media"><?php if ( $about_image ) : ?><img src="<?php echo esc_url( $about_image ); ?>" alt="سحر فراهانی" loading="lazy"><?php else : ?><div class="sf-image-placeholder sf-image-placeholder--portrait">تصویر سحر فراهانی</div><?php endif; ?></div>
			<div class="sf-about__content">
				<span class="sf-eyebrow"><?php echo esc_html( sf_get_mod( 'sf_about_kicker', 'درباره من' ) ); ?></span>
				<h2><?php echo esc_html( sf_get_mod( 'sf_about_title', 'هنر فقط اجرا نیست؛ شناختن خودت است.' ) ); ?></h2>
				<p><?php echo esc_html( sf_get_mod( 'sf_about_text', 'من سحر فراهانی هستم و در مسیر آموزش هنرهای نمایشی و سینما تلاش می‌کنم یادگیری را از تئوری به تجربه واقعی تبدیل کنم.' ) ); ?></p>
				<a class="sf-text-link" href="<?php echo esc_url( sf_get_mod( 'sf_about_button_url', '#' ) ); ?>"><?php echo esc_html( sf_get_mod( 'sf_about_button_text', 'بیشتر درباره من' ) ); ?> <span>←</span></a>
			</div>
		</div>
	</section>

	<section class="sf-section sf-categories">
		<div class="sf-container">
			<div class="sf-section-heading">
				<div><span class="sf-eyebrow"><?php echo esc_html( sf_get_mod( 'sf_categories_kicker', 'مسیر یادگیری' ) ); ?></span><h2><?php echo esc_html( sf_get_mod( 'sf_categories_title', 'چه چیزی می‌خواهید یاد بگیرید؟' ) ); ?></h2></div>
				<p><?php echo esc_html( sf_get_mod( 'sf_categories_text', 'حوزه مورد علاقه‌تان را انتخاب کنید و دوره‌های مرتبط را ببینید.' ) ); ?></p>
			</div>
			<?php if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) : ?>
				<div class="sf-category-list">
				<?php foreach ( $categories as $category ) : $image = sf_category_thumbnail( $category ); $courses = new WP_Query( array( 'post_type' => 'tutor_course', 'post_status' => 'publish', 'posts_per_page' => $per_category, 'tax_query' => array( array( 'taxonomy' => $taxonomy, 'field' => 'term_id', 'terms' => $category->term_id ) ), 'no_found_rows' => true ) ); ?>
					<div class="sf-category-block">
						<a class="sf-category-block__head" href="<?php echo esc_url( get_term_link( $category ) ); ?>">
							<div class="sf-category-block__image"><?php if ( $image ) : ?><img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $category->name ); ?>" loading="lazy"><?php else : ?><span>SF</span><?php endif; ?></div>
							<div><h3><?php echo esc_html( $category->name ); ?></h3><span><?php echo esc_html( $category->count ); ?> دوره</span></div>
							<span class="sf-circle-arrow">←</span>
						</a>
						<?php if ( $courses->have_posts() ) : ?><div class="sf-mini-courses"><?php while ( $courses->have_posts() ) : $courses->the_post(); ?><a href="<?php the_permalink(); ?>" class="sf-mini-course"><span class="sf-mini-course__thumb"><?php the_post_thumbnail( 'thumbnail' ); ?></span><span><?php the_title(); ?></span></a><?php endwhile; wp_reset_postdata(); ?></div><?php endif; ?>
					</div>
				<?php endforeach; ?>
				</div>
			<?php else : ?><div class="sf-empty">برای نمایش این بخش، حداقل یک دسته دوره در Tutor LMS بسازید.</div><?php endif; ?>
		</div>
	</section>

	<section class="sf-section sf-latest">
		<div class="sf-container">
			<div class="sf-section-heading">
				<div><span class="sf-eyebrow"><?php echo esc_html( sf_get_mod( 'sf_courses_kicker', 'دوره‌های آموزشی' ) ); ?></span><h2><?php echo esc_html( sf_get_mod( 'sf_courses_title', 'جدیدترین دوره‌ها' ) ); ?></h2></div>
				<p><?php echo esc_html( sf_get_mod( 'sf_courses_text', 'دوره‌های تازه منتشرشده را برای شروع یادگیری ببینید.' ) ); ?></p>
			</div>
			<?php $latest = new WP_Query( array( 'post_type' => 'tutor_course', 'post_status' => 'publish', 'posts_per_page' => $latest_count, 'orderby' => 'date', 'order' => 'DESC', 'no_found_rows' => true ) ); ?>
			<?php if ( $latest->have_posts() ) : ?><div class="sf-course-grid"><?php while ( $latest->have_posts() ) : $latest->the_post(); sf_course_card( get_the_ID() ); endwhile; wp_reset_postdata(); ?></div><?php else : ?><div class="sf-empty">هنوز دوره‌ای منتشر نشده است.</div><?php endif; ?>
			<div class="sf-section-action"><a class="sf-button sf-button--outline" href="<?php echo esc_url( sf_get_mod( 'sf_courses_button_url', '#' ) ); ?>"><?php echo esc_html( sf_get_mod( 'sf_courses_button_text', 'مشاهده همه دوره‌ها' ) ); ?></a></div>
		</div>
	</section>

	<section class="sf-section sf-paths">
		<div class="sf-container">
			<div class="sf-section-heading sf-section-heading--center"><div><span class="sf-eyebrow"><?php echo esc_html( sf_get_mod( 'sf_paths_kicker', 'مسیر پیشنهادی' ) ); ?></span><h2><?php echo esc_html( sf_get_mod( 'sf_paths_title', 'اگر می‌خواهید بازیگر شوید، از اینجا شروع کنید.' ) ); ?></h2></div><p><?php echo esc_html( sf_get_mod( 'sf_paths_text', 'یک مسیر مرحله‌به‌مرحله برای ساخت مهارت‌های اصلی بازیگری.' ) ); ?></p></div>
			<div class="sf-path-grid">
				<?php foreach ( $paths as $index => $item ) : if ( empty( $item['title'] ) && empty( $item['image'] ) ) { continue; } ?>
					<div class="sf-path-card"><div class="sf-path-card__number"><?php echo esc_html( sprintf( '%02d', $index ) ); ?></div><?php if ( ! empty( $item['image'] ) ) : ?><div class="sf-path-card__image"><?php echo wp_get_attachment_image( absint( $item['image'] ), 'thumbnail', false, array( 'loading' => 'lazy' ) ); ?></div><?php endif; ?><h3><?php echo esc_html( $item['title'] ); ?></h3><?php if ( ! empty( $item['text'] ) ) : ?><p><?php echo esc_html( $item['text'] ); ?></p><?php endif; ?><?php if ( ! empty( $item['button_url'] ) ) : ?><a class="sf-text-link" href="<?php echo esc_url( $item['button_url'] ); ?>"><?php echo esc_html( $item['button_text'] ?: 'مشاهده مرحله' ); ?> <span>←</span></a><?php endif; ?></div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<section class="sf-cta"><div class="sf-container"><div class="sf-cta__inner"><span class="sf-eyebrow">شروع مسیر</span><h2><?php echo esc_html( sf_get_mod( 'sf_cta_title', 'آماده‌ای صدای خودت را پیدا کنی؟' ) ); ?></h2><p><?php echo esc_html( sf_get_mod( 'sf_cta_text', 'دوره مناسب خودت را انتخاب کن و مسیر یادگیری را شروع کن.' ) ); ?></p><a class="sf-button sf-button--light" href="<?php echo esc_url( sf_get_mod( 'sf_cta_button_url', '#' ) ); ?>"><?php echo esc_html( sf_get_mod( 'sf_cta_button_text', 'شروع یادگیری' ) ); ?></a></div></div></section>
</main>
<?php get_footer(); ?>
