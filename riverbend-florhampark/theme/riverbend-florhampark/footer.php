<?php
/**
 * footer.php — Riverbend Florham Park
 */
$rb_assets = get_template_directory_uri() . '/assets';
?>

</main><!-- /#main-content -->

<footer class="site-footer" role="contentinfo">

    <div class="footer-inner">

        <a href="<?php echo esc_url( home_url( '/' ) ); ?>"
           class="footer-logo"
           aria-label="<?php bloginfo( 'name' ); ?> — Home">
            <img src="<?php echo esc_url( $rb_assets ); ?>/images/logos/riverbend-logo-stacked-cream.svg"
                 alt="<?php bloginfo( 'name' ); ?>" width="140" height="92">
        </a>

        <nav class="footer-nav" aria-label="<?php esc_attr_e( 'Footer navigation', 'riverbend-florhampark' ); ?>">
            <a href="<?php echo esc_url( home_url( '/availability/' ) ); ?>"><?php esc_html_e( 'Availability', 'riverbend-florhampark' ); ?></a>
            <a href="<?php echo esc_url( home_url( '/#amenities' ) ); ?>"><?php esc_html_e( 'Amenities', 'riverbend-florhampark' ); ?></a>
            <a href="<?php echo esc_url( home_url( '/#residences' ) ); ?>"><?php esc_html_e( 'Residences', 'riverbend-florhampark' ); ?></a>
            <a href="<?php echo esc_url( home_url( '/#floor-plans' ) ); ?>"><?php esc_html_e( 'Floor Plans', 'riverbend-florhampark' ); ?></a>
            <a href="<?php echo esc_url( home_url( '/gallery/' ) ); ?>"><?php esc_html_e( 'Gallery', 'riverbend-florhampark' ); ?></a>
            <a href="<?php echo esc_url( home_url( '/#neighborhood' ) ); ?>"><?php esc_html_e( 'Neighborhood', 'riverbend-florhampark' ); ?></a>
            <a href="<?php echo esc_url( home_url( '/#contact' ) ); ?>"><?php esc_html_e( 'Contact', 'riverbend-florhampark' ); ?></a>
            <a href="<?php echo esc_url( home_url( '/residents/' ) ); ?>"><?php esc_html_e( 'Residents', 'riverbend-florhampark' ); ?></a>
            <a href="https://sterlingproperties.appfolio.com/connect" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Pay Rent', 'riverbend-florhampark' ); ?></a>
            <a href="<?php echo esc_url( riverbend_apply_url() ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Apply Now', 'riverbend-florhampark' ); ?></a>
        </nav>

    </div><!-- /.footer-inner -->

    <?php /* As on the preview: no pet or smoking badge until the client confirms the policies' wording. */ ?>
    <div class="footer-badges" aria-label="<?php esc_attr_e( 'Fair housing and accessibility', 'riverbend-florhampark' ); ?>">
        <img src="<?php echo esc_url( $rb_assets ); ?>/images/badges/equal-housing.svg" alt="<?php esc_attr_e( 'Equal Housing Opportunity', 'riverbend-florhampark' ); ?>" width="26" height="25">
        <img src="<?php echo esc_url( $rb_assets ); ?>/images/badges/accessible.svg" alt="<?php esc_attr_e( 'Accessible community', 'riverbend-florhampark' ); ?>" width="22" height="25">
    </div>

    <div class="footer-bottom">
        <p class="footer-bottom__copy">
            &copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> Riverbend at Florham Park. A Sterling Properties Community. All rights reserved.
            Made with ❤️ by <a href="https://bruddasindustries.com" target="_blank" rel="noopener noreferrer">Brudda's Industries</a>.
        </p>
        <a class="footer-bottom__brand"
           href="https://sterlingpropertiesnj.com"
           target="_blank"
           rel="noopener noreferrer"
           aria-label="<?php esc_attr_e( 'A Sterling Properties community', 'riverbend-florhampark' ); ?>">
            <img class="footer-sterling-logo"
                 src="<?php echo esc_url( $rb_assets ); ?>/images/logos/sterling-logo-white.png"
                 alt="Sterling Properties">
        </a>
        <div class="footer-bottom__links">
            <?php /* No Privacy Policy link: Sterling has no published policy to point at.
                     Add one back here if/when they publish one. */ ?>
            <a href="https://sterlingproperties.appfolio.com/connect" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Resident Portal', 'riverbend-florhampark' ); ?></a>
            <a href="<?php echo esc_url( riverbend_apply_url() ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Apply Online', 'riverbend-florhampark' ); ?></a>
        </div>
    </div><!-- /.footer-bottom -->

</footer><!-- /.site-footer -->

<!-- Matterport 3D-tour lightbox (opened by any [data-mp] trigger via main.js) -->
<div class="mp-modal" id="mp-modal" role="dialog" aria-modal="true" aria-hidden="true" aria-label="<?php esc_attr_e( '3D tour', 'riverbend-florhampark' ); ?>">
    <div class="mp-modal__backdrop" data-mp-close></div>
    <div class="mp-modal__dialog">
        <button class="mp-modal__close" type="button" data-mp-close aria-label="<?php esc_attr_e( 'Close 3D tour', 'riverbend-florhampark' ); ?>">&times;</button>
        <div class="mp-modal__title" id="mp-modal-title"><?php esc_html_e( '3D Tour', 'riverbend-florhampark' ); ?></div>
        <div class="mp-modal__frame">
            <iframe id="mp-modal-iframe" title="<?php esc_attr_e( 'Matterport 3D walkthrough', 'riverbend-florhampark' ); ?>" allow="fullscreen; vr" allowfullscreen loading="lazy"></iframe>
        </div>
    </div>
</div>

<!-- Full-screen viewer for floor plans and gallery photos.
     Gallery mode (main.js adds .is-gallery) shows the caption and prev/next. -->
<div class="fp-lightbox" id="fp-lightbox" aria-hidden="true">
    <div class="fp-lightbox__backdrop" data-fpl-close></div>
    <div class="fp-lightbox__dialog" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Floor plan', 'riverbend-florhampark' ); ?>">
        <button class="fp-lightbox__close" type="button" data-fpl-close aria-label="<?php esc_attr_e( 'Close', 'riverbend-florhampark' ); ?>">&times;</button>
        <button class="fp-lightbox__nav fp-lightbox__nav--prev" type="button" data-fpl-prev aria-label="<?php esc_attr_e( 'Previous photo', 'riverbend-florhampark' ); ?>">&lsaquo;</button>
        <img id="fp-lightbox-img" src="" alt="">
        <p class="fp-lightbox__caption" id="fp-lightbox-caption" aria-live="polite"></p>
        <button class="fp-lightbox__nav fp-lightbox__nav--next" type="button" data-fpl-next aria-label="<?php esc_attr_e( 'Next photo', 'riverbend-florhampark' ); ?>">&rsaquo;</button>
    </div>
</div>

<?php wp_footer(); ?>

</body>
</html>
