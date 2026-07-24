<?php
/**
 * Template Name: Rivergate Full Width
 * Full-width page — no sidebar, full container width. For generic WordPress pages.
 */
get_header();
?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'container' ); ?> style="padding-block:var(--space-10);">
    <header style="margin-bottom:var(--space-8);">
        <h1><?php the_title(); ?></h1>
    </header>
    <div class="entry-content" style="line-height:1.8;color:var(--color-text-muted);">
        <?php the_content(); ?>
    </div>
</article>

<?php endwhile; endif; ?>

<?php get_footer(); ?>
