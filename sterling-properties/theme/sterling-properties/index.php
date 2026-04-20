<?php
/**
 * index.php — Sterling Properties
 *
 * WordPress requires this file to exist for every theme.
 * In normal operation this file is never rendered because:
 *   - The home page uses an Elementor full-page template.
 *   - Individual pages use the Default or Full Width page template.
 *
 * This fallback renders if no other template matches — e.g. if Elementor
 * is deactivated or a page has no template assigned.
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
            <span class="eyebrow"><?php esc_html_e( '404', 'sterling-properties' ); ?></span>
            <h1 style="margin-bottom: var(--space-5);">
                <?php esc_html_e( 'Page Not Found', 'sterling-properties' ); ?>
            </h1>
            <p style="margin-inline: auto; margin-bottom: var(--space-7);">
                <?php esc_html_e( 'The page you\'re looking for doesn\'t exist or has been moved.', 'sterling-properties' ); ?>
            </p>
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn--primary">
                <?php esc_html_e( 'Return Home', 'sterling-properties' ); ?>
            </a>
        </div>

    <?php endif; ?>

</section>

<?php get_footer(); ?>
