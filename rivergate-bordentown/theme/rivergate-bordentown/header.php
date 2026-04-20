<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="site-header" id="site-header" role="banner">
    <nav class="nav-inner" aria-label="<?php esc_attr_e( 'Primary navigation', 'rivergate-bordentown' ); ?>">

        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="nav-logo" aria-label="<?php bloginfo( 'name' ); ?> — Home">
            <!-- LOGO SLOT: Replace span with <img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo.png"> -->
            <span style="font-family: var(--font-heading); font-size: 1.1rem; letter-spacing: 0.1em; color: var(--color-text); text-transform: uppercase;">
                <?php bloginfo( 'name' ); ?>
            </span>
        </a>

        <div class="nav-links" id="nav-links" role="list">
            <?php
            wp_nav_menu( [
                'theme_location' => 'primary',
                'container'      => false,
                'items_wrap'     => '%3$s',
                'walker'         => new Rivergate_Nav_Walker(),
                'fallback_cb'    => 'rivergate_nav_fallback',
            ] );
            ?>
            <a href="#contact"
               class="btn btn--nav"
               aria-label="<?php esc_attr_e( 'Schedule a tour', 'rivergate-bordentown' ); ?>">
                <?php esc_html_e( 'Schedule a Tour', 'rivergate-bordentown' ); ?>
            </a>
        </div>

        <button class="nav-toggle"
                id="nav-toggle"
                aria-controls="nav-links"
                aria-expanded="false"
                aria-label="<?php esc_attr_e( 'Toggle navigation menu', 'rivergate-bordentown' ); ?>">
            <span></span>
            <span></span>
            <span></span>
        </button>

    </nav>
</header>

<script>
(function () {
    var toggle = document.getElementById('nav-toggle');
    var links  = document.getElementById('nav-links');
    if (!toggle || !links) return;
    toggle.addEventListener('click', function () {
        var isOpen = links.classList.toggle('is-open');
        toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    });
}());
</script>

<main id="main-content" role="main">
