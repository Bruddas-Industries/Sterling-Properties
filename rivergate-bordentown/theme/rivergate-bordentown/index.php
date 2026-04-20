<?php
/**
 * index.php — Rivergate Bordentown
 * Fallback template. In normal operation the home page uses an Elementor
 * full-page template; this file only renders if Elementor is inactive or
 * no template is assigned.
 */

get_header();
?>

<section class="container" style="padding-block: var(--space-10); min-height: 60vh;">

    <?php if ( have_posts() ) : ?>

        <?php while ( have_posts() ) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?> style="max-width: 720px;">
                <header style="margin-bottom: var(--space-7);">
                    <h1><?php the_title(); ?></h1>
                </header>
                <div class="entry-content" style="line-height: 1.8; color: var(--color-text-muted);">
                    <?php the_content(); ?>
                </div>
            </article>
        <?php endwhile; ?>

    <?php else : ?>

        <div style="text-align: center; padding-block: var(--space-10);">
            <span class="eyebrow"><?php esc_html_e( '404', 'rivergate-bordentown' ); ?></span>
            <h1 style="margin-bottom: var(--space-5);">
                <?php esc_html_e( 'Page Not Found', 'rivergate-bordentown' ); ?>
            </h1>
            <p style="margin-inline: auto; margin-bottom: var(--space-7);">
                <?php esc_html_e( "The page you're looking for doesn't exist or has been moved.", 'rivergate-bordentown' ); ?>
            </p>
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn--primary">
                <?php esc_html_e( 'Return Home', 'rivergate-bordentown' ); ?>
            </a>
        </div>

    <?php endif; ?>

</section>

<?php get_footer(); ?>
