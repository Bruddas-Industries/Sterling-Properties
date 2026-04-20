<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php
    /**
     * wp_head() outputs: title tag, enqueued stylesheets, scripts, SEO plugin
     * meta tags, and any other hooks attached to wp_head.
     * DO NOT remove this.
     */
    wp_head();
    ?>
</head>

<body <?php body_class(); ?>>

<?php
/**
 * wp_body_open() fires immediately after <body>.
 * Required by WordPress 5.2+ and used by plugins (e.g. Google Tag Manager).
 */
wp_body_open();
?>

<?php
/**
 * HEADER
 * ------
 * This file outputs a minimal semantic header shell.
 * The visual design (logo, nav links, CTA button) is controlled by the
 * Elementor Header template in the Elementor Template Kit.
 *
 * If you are NOT using Elementor's Theme Builder for the header,
 * the fallback markup below renders a plain sticky nav with the
 * WordPress menu assigned to the 'primary' location.
 *
 * TO USE ELEMENTOR HEADER:
 *   1. Import the template kit.
 *   2. In Elementor → Theme Builder → Header, publish the header template
 *      and set it to display site-wide.
 *   3. Elementor will inject the header before this file renders,
 *      so the fallback below will not appear.
 *
 * LOGO: Replace the text "Sterling Properties" with an <img> tag once
 *       the logo file is available.
 */
?>

<header class="site-header" id="site-header" role="banner">
    <nav class="nav-inner" aria-label="<?php esc_attr_e( 'Primary navigation', 'sterling-properties' ); ?>">

        <!-- ============================================================
             LOGO SLOT
             Replace the text span below with an <img> tag pointing to
             your logo file at: assets/images/logo.svg
             ============================================================ -->
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="nav-logo" aria-label="<?php bloginfo( 'name' ); ?> — Home">
            <span style="font-family: var(--font-heading); font-size: 1.25rem; letter-spacing: 0.08em; color: var(--color-text);">
                <?php bloginfo( 'name' ); ?>
            </span>
        </a>

        <!-- ============================================================
             PRIMARY NAV LINKS
             Populated by the WordPress menu assigned to 'primary'.
             To edit: Appearance → Menus → Primary Navigation.
             ============================================================ -->
        <div class="nav-links" id="nav-links" role="list">
            <?php
            wp_nav_menu( [
                'theme_location' => 'primary',
                'container'      => false,
                'items_wrap'     => '%3$s',   // No wrapping <ul> — list items rendered directly.
                'walker'         => new Sterling_Nav_Walker(),
                'fallback_cb'    => 'sterling_nav_fallback',
            ] );
            ?>

            <!-- CTA Button — the last item in the nav -->
            <!-- CUSTOMIZATION: Change the label and href per site -->
            <a href="#contact"
               class="btn btn--nav"
               aria-label="<?php esc_attr_e( 'Schedule a tour', 'sterling-properties' ); ?>">
                <?php esc_html_e( 'Schedule a Tour', 'sterling-properties' ); ?>
            </a>
        </div>

        <!-- ============================================================
             MOBILE HAMBURGER TOGGLE
             Toggles .is-open on #nav-links via inline JS below.
             Swap for a more robust solution if mobile menu grows complex.
             ============================================================ -->
        <button class="nav-toggle"
                id="nav-toggle"
                aria-controls="nav-links"
                aria-expanded="false"
                aria-label="<?php esc_attr_e( 'Toggle navigation menu', 'sterling-properties' ); ?>">
            <span></span>
            <span></span>
            <span></span>
        </button>

    </nav>
</header>

<script>
// Inline toggle script — no jQuery dependency.
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

<?php
// Walker and fallback function are defined in functions.php.
// Main content area opens here — Elementor injects page content between header.php and footer.php.
?>
<main id="main-content" role="main">
