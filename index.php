<?php get_header(); ?>
<main class="sf-section sf-content-page"><div class="sf-container">
<?php if ( have_posts() ) : ?><div class="sf-post-grid"><?php while ( have_posts() ) : the_post(); ?><article class="sf-post-card"><a href="<?php the_permalink(); ?>" class="sf-post-card__media"><?php the_post_thumbnail( 'large' ); ?></a><div class="sf-post-card__body"><span class="sf-eyebrow"><?php echo esc_html( get_the_date() ); ?></span><h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2><p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 24 ) ); ?></p></div></article><?php endwhile; ?></div><?php the_posts_pagination(); ?><?php else : ?><div class="sf-empty">محتوایی برای نمایش وجود ندارد.</div><?php endif; ?>
</div></main>
<?php get_footer(); ?>
