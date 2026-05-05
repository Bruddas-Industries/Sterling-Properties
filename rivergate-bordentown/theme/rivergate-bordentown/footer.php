<?php
/**
 * footer.php — Rivergate Bordentown
 * Forked from Sterling Properties base theme.
 */
?>

</main><!-- /#main-content -->

<footer class="site-footer" role="contentinfo">

    <div class="footer-inner">

        <a href="<?php echo esc_url( home_url( '/' ) ); ?>"
           class="footer-logo"
           aria-label="<?php bloginfo( 'name' ); ?> — Home">
            <!-- LOGO SLOT: swap for <img src="assets/images/logo-white.png"> -->
            <span style="
                font-family: var(--font-heading);
                font-size: 1.1rem;
                letter-spacing: 0.14em;
                color: rgba(248,249,251,0.85);
                text-transform: uppercase;
            ">
                <?php bloginfo( 'name' ); ?>
            </span>
        </a>

        <nav class="footer-nav" aria-label="<?php esc_attr_e( 'Footer navigation', 'rivergate-bordentown' ); ?>">
            <?php
            /*
             * Footer menu — configure in WP Admin > Appearance > Menus > Footer.
             * Required items:
             *   Amenities        → /amenities/
             *   Floor Plans      → /floor-plans/
             *   Gallery          → /gallery/
             *   Location         → /location/
             *   Contact          → /contact/
             *   Residents        → /residents/
             *   Pay Rent         → https://sterlingproperties.appfolio.com/connect  (external, open in new tab)
             *   Apply Now        → https://sterlingproperties.appfolio.com/listings?filters%5Bproperty_list%5D=RIVERGATE+BORDENTOWN  (external, open in new tab)
             */
            wp_nav_menu( [
                'theme_location' => 'footer',
                'container'      => false,
                'items_wrap'     => '%3$s',
                'walker'         => new Rivergate_Nav_Walker(),
                'fallback_cb'    => '__return_false',
            ] );
            ?>
        </nav>

    </div><!-- /.footer-inner -->

    <div class="footer-bottom">
        <p style="color: rgba(248,249,251,0.4); font-size: var(--text-xs); letter-spacing: var(--tracking-wide); max-width: none;">
            &copy; <?php echo esc_html( gmdate( 'Y' ) ); ?>
            <?php bloginfo( 'name' ); ?>.
            <?php esc_html_e( 'All rights reserved.', 'rivergate-bordentown' ); ?>
        </p>
        <div style="display:flex; gap: var(--space-5);">
            <a href="/privacy-policy"
               style="font-size: var(--text-xs); color: rgba(248,249,251,0.4); letter-spacing: var(--tracking-wide);">
                <?php esc_html_e( 'Privacy Policy', 'rivergate-bordentown' ); ?>
            </a>
        </div>
    </div><!-- /.footer-bottom -->

</footer><!-- /.site-footer -->

<?php wp_footer(); ?>

</body>
</html>
