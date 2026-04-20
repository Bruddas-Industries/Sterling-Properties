<?php
/**
 * footer.php — Sterling Properties
 *
 * Closes the <main> tag opened in header.php, then renders the site footer.
 *
 * ELEMENTOR THEME BUILDER
 * If you publish a Footer template in Elementor → Theme Builder,
 * Elementor will inject it here automatically and the fallback markup
 * below will not be visible. Keep the fallback in place so the site
 * degrades gracefully without Elementor active.
 *
 * CUSTOMIZATION POINTS (search for "CUSTOMIZE:" comments below):
 *   - Logo image src
 *   - Copyright text / year
 *   - Footer navigation (via WordPress Appearance → Menus → Footer Navigation)
 */
?>

</main><!-- /#main-content -->

<!-- =====================================================================
     SITE FOOTER (fallback — replaced by Elementor Footer template in prod)
     ===================================================================== -->
<footer class="site-footer" role="contentinfo">

    <div class="footer-inner">

        <!-- CUSTOMIZE: Replace text node with <img> once logo file exists.
             See header.php logo slot for the <img> pattern to follow.     -->
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>"
           class="footer-logo"
           aria-label="<?php bloginfo( 'name' ); ?> — Home">
            <span style="
                font-family: var(--font-heading);
                font-size: 1.1rem;
                letter-spacing: 0.14em;
                color: rgba(250,250,248,0.85);
                text-transform: uppercase;
            ">
                <?php bloginfo( 'name' ); ?>
            </span>
        </a>

        <!-- Footer navigation — populated from the 'footer' menu location.
             Assign links in Appearance → Menus → Footer Navigation.       -->
        <nav class="footer-nav" aria-label="<?php esc_attr_e( 'Footer navigation', 'sterling-properties' ); ?>">
            <?php
            wp_nav_menu( [
                'theme_location' => 'footer',
                'container'      => false,
                'items_wrap'     => '%3$s',
                'walker'         => new Sterling_Nav_Walker(),
                'fallback_cb'    => '__return_false',
            ] );
            ?>
        </nav>

    </div><!-- /.footer-inner -->

    <!-- Footer bottom bar: copyright + optional legal links -->
    <div class="footer-bottom">

        <!-- CUSTOMIZE: Update entity name and any legal page URLs -->
        <p style="color: rgba(250,250,248,0.4); font-size: var(--text-xs); letter-spacing: var(--tracking-wide); max-width: none;">
            &copy; <?php echo esc_html( gmdate( 'Y' ) ); ?>
            <?php bloginfo( 'name' ); ?>.
            <?php esc_html_e( 'All rights reserved.', 'sterling-properties' ); ?>
        </p>

        <!-- CUSTOMIZE: Add privacy policy / terms links here if required -->
        <div style="display:flex; gap: var(--space-5);">
            <a href="/privacy-policy"
               style="font-size: var(--text-xs); color: rgba(250,250,248,0.4); letter-spacing: var(--tracking-wide);">
                <?php esc_html_e( 'Privacy Policy', 'sterling-properties' ); ?>
            </a>
        </div>

    </div><!-- /.footer-bottom -->

</footer><!-- /.site-footer -->

<?php
/**
 * wp_footer() must appear immediately before </body>.
 * It outputs: enqueued footer scripts, Elementor scripts, plugin hooks.
 * DO NOT remove this.
 */
wp_footer();
?>

</body>
</html>
