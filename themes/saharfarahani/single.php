<?php
/** Cinematic single post template. */
get_header();
while ( have_posts() ) : the_post();
    $post_id = get_the_ID();
    $author_id = (int) get_post_field( 'post_author', $post_id );
    $author_name = get_the_author_meta( 'display_name', $author_id );
    $author_bio = get_the_author_meta( 'description', $author_id );
    $author_avatar = get_avatar_url( $author_id, array( 'size' => 180 ) );
    $categories = get_the_category( $post_id );
    $reading_time = max( 1, ceil( str_word_count( wp_strip_all_tags( get_post_field( 'post_content', $post_id ) ) ) / 220 ) );
    $related = get_posts( array( 'post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => 4, 'post__not_in' => array( $post_id ), 'category__in' => wp_get_post_categories( $post_id ), 'orderby' => 'date', 'order' => 'DESC' ) );
    $popular = get_posts( array( 'post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => 4, 'post__not_in' => array( $post_id ), 'orderby' => 'date', 'order' => 'DESC' ) );
?>
<main class="sf-article-page">
    <div class="sf-container">
        <nav class="sf-article-breadcrumb" aria-label="مسیر صفحه"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">خانه</a><span>‹</span><a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ?: home_url( '/blog/' ) ); ?>">مقالات</a><span>‹</span><strong><?php the_title(); ?></strong></nav>

        <div class="sf-article-layout">
            <aside class="sf-article-sidebar">
                <section class="sf-article-sidecard sf-author-card">
                    <h3>درباره نویسنده</h3>
                    <img src="<?php echo esc_url( $author_avatar ); ?>" alt="<?php echo esc_attr( $author_name ); ?>">
                    <strong><?php echo esc_html( $author_name ); ?></strong>
                    <span>بازیگر سینما و گوینده</span>
                    <p><?php echo esc_html( $author_bio ?: 'فعال در حوزه بازیگری، گویندگی و آموزش مهارت‌های بیان و اجرا.' ); ?></p>
                    <div class="sf-author-socials"><a href="#">◎</a><a href="#">◈</a><a href="#">▣</a><a href="#">◉</a></div>
                </section>

                <section class="sf-article-sidecard sf-toc-card">
                    <h3>فهرست مطالب</h3>
                    <div class="sf-toc" id="sf-article-toc"><a href="#sf-article-content">مقدمه</a></div>
                </section>

                <section class="sf-article-sidecard sf-popular-card">
                    <h3>مطالب پربازدید</h3>
                    <?php foreach ( $popular as $item ) : ?>
                        <a class="sf-popular-item" href="<?php echo esc_url( get_permalink( $item ) ); ?>">
                            <?php echo get_the_post_thumbnail( $item, 'thumbnail' ); ?>
                            <span><strong><?php echo esc_html( get_the_title( $item ) ); ?></strong><small><?php echo esc_html( get_the_date( 'Y/m/d', $item ) ); ?></small></span>
                        </a>
                    <?php endforeach; ?>
                </section>

                <section class="sf-article-sidecard sf-article-contact-card">
                    <span>همکاری هنری</span><h3>برای پروژه بعدی آماده‌ای؟</h3><p>برای بازیگری، گویندگی یا همکاری آموزشی با من در ارتباط باشید.</p><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">تماس با من ←</a>
                </section>
            </aside>

            <article <?php post_class( 'sf-article-main' ); ?>>
                <?php if ( has_post_thumbnail() ) : ?><div class="sf-article-cover"><?php the_post_thumbnail( 'full' ); ?><?php if ( $categories ) : ?><span><?php echo esc_html( $categories[0]->name ); ?></span><?php endif; ?></div><?php endif; ?>
                <div class="sf-article-head">
                    <h1><?php the_title(); ?></h1>
                    <?php if ( has_excerpt() ) : ?><p><?php echo esc_html( get_the_excerpt() ); ?></p><?php endif; ?>
                    <div class="sf-article-meta">
                        <div><img src="<?php echo esc_url( $author_avatar ); ?>" alt=""><span><small>نویسنده</small><strong><?php echo esc_html( $author_name ); ?></strong></span></div>
                        <div><span>▣</span><small>تاریخ انتشار</small><strong><?php echo esc_html( get_the_date() ); ?></strong></div>
                        <div><span>◷</span><small>زمان مطالعه</small><strong><?php echo esc_html( $reading_time ); ?> دقیقه</strong></div>
                        <div><span>◉</span><small>دیدگاه‌ها</small><strong><?php echo esc_html( get_comments_number() ); ?></strong></div>
                    </div>
                </div>
                <div class="sf-article-content sf-entry-content" id="sf-article-content"><?php the_content(); ?></div>
                <div class="sf-article-share"><span>این مقاله را با دوستان خود به اشتراک بگذارید:</span><div><a href="#">↗</a><a href="#">in</a><a href="#">𝕏</a><a href="#">◈</a></div></div>
            </article>
        </div>

        <?php if ( $related ) : ?><section class="sf-article-related"><div class="sf-article-section-title"><span>مطالب پیشنهادی</span><h2>مقالات مرتبط</h2></div><div class="sf-article-related-grid"><?php foreach ( $related as $item ) : ?><article><a href="<?php echo esc_url( get_permalink( $item ) ); ?>" class="sf-related-media"><?php echo get_the_post_thumbnail( $item, 'medium_large' ); ?></a><div><?php $cats = get_the_category( $item->ID ); if ( $cats ) : ?><span><?php echo esc_html( $cats[0]->name ); ?></span><?php endif; ?><h3><a href="<?php echo esc_url( get_permalink( $item ) ); ?>"><?php echo esc_html( get_the_title( $item ) ); ?></a></h3><small><?php echo esc_html( get_the_date( 'Y/m/d', $item ) ); ?></small></div></article><?php endforeach; ?></div></section><?php endif; ?>
    </div>
</main>
<script>(function(){var c=document.querySelector('.sf-article-content'),t=document.getElementById('sf-article-toc');if(!c||!t)return;var hs=c.querySelectorAll('h2,h3');hs.forEach(function(h,i){if(!h.id)h.id='sf-section-'+(i+1);var a=document.createElement('a');a.href='#'+h.id;a.textContent=h.textContent;t.appendChild(a);});})();</script>
<?php endwhile; get_footer(); ?>
