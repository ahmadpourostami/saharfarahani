<?php get_header(); ?>
<main class="sf-section sf-content-page"><div class="sf-container sf-narrow"><?php while ( have_posts() ) : the_post(); ?><article <?php post_class( 'sf-page-content' ); ?>><header class="sf-page-content__header"><span class="sf-eyebrow"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></span><h1><?php the_title(); ?></h1></header><div class="sf-entry-content"><?php the_content(); ?></div></article><?php endwhile; ?></div></main>
<?php get_footer(); ?>
