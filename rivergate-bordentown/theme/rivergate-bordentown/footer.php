<?php
/**
 * footer.php — Rivergate Bordentown
 */
?>

</main><!-- /#main-content -->

<footer class="site-footer" role="contentinfo">

    <div class="footer-inner">

        <a href="<?php echo esc_url( home_url( '/' ) ); ?>"
           class="footer-logo"
           aria-label="<?php bloginfo( 'name' ); ?> — Home">
            <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/logos/rivergate-logo-white.png"
                 alt="<?php bloginfo( 'name' ); ?>" height="44" style="opacity:0.85;">
        </a>

        <nav class="footer-nav" aria-label="<?php esc_attr_e( 'Footer navigation', 'rivergate-bordentown' ); ?>">
            <a href="<?php echo esc_url( home_url( '/availability/' ) ); ?>"><?php esc_html_e( 'Availability', 'rivergate-bordentown' ); ?></a>
            <a href="<?php echo esc_url( home_url( '/#amenities' ) ); ?>"><?php esc_html_e( 'Amenities', 'rivergate-bordentown' ); ?></a>
            <a href="<?php echo esc_url( home_url( '/#residences' ) ); ?>"><?php esc_html_e( 'Residences', 'rivergate-bordentown' ); ?></a>
            <a href="<?php echo esc_url( home_url( '/#floor-plans' ) ); ?>"><?php esc_html_e( 'Floor Plans', 'rivergate-bordentown' ); ?></a>
            <a href="<?php echo esc_url( home_url( '/gallery/' ) ); ?>"><?php esc_html_e( 'Gallery', 'rivergate-bordentown' ); ?></a>
            <a href="<?php echo esc_url( home_url( '/#neighborhood' ) ); ?>"><?php esc_html_e( 'Neighborhood', 'rivergate-bordentown' ); ?></a>
            <a href="<?php echo esc_url( home_url( '/#contact' ) ); ?>"><?php esc_html_e( 'Contact', 'rivergate-bordentown' ); ?></a>
            <a href="<?php echo esc_url( home_url( '/residents/' ) ); ?>"><?php esc_html_e( 'Residents', 'rivergate-bordentown' ); ?></a>
            <a href="https://sterlingproperties.appfolio.com/connect" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Pay Rent', 'rivergate-bordentown' ); ?></a>
            <a href="https://sterlingproperties.appfolio.com/listings?filters%5Bproperty_list%5D=RIVERGATE+BORDENTOWN" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Apply Now', 'rivergate-bordentown' ); ?></a>
        </nav>

    </div><!-- /.footer-inner -->

    <div class="footer-badges" aria-label="<?php esc_attr_e( 'Fair housing and community policies', 'rivergate-bordentown' ); ?>">
        <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/badges/EH_LightBlue.svg" alt="<?php esc_attr_e( 'Equal Housing Opportunity', 'rivergate-bordentown' ); ?>" width="26" height="25">
        <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/badges/HC_LightBlue.svg" alt="<?php esc_attr_e( 'ADA / wheelchair accessible', 'rivergate-bordentown' ); ?>" width="22" height="25">
        <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/badges/Pets_white.svg" alt="<?php esc_attr_e( 'Pet-friendly community', 'rivergate-bordentown' ); ?>" width="34" height="30">
        <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/badges/SmokingProhibited_white.svg" alt="<?php esc_attr_e( 'Non-smoking community', 'rivergate-bordentown' ); ?>" width="30" height="30">
    </div>

    <div class="footer-bottom">
        <p class="footer-bottom__copy">
            &copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> Rivergate Bordentown. A Sterling Properties Community. All rights reserved.
            Made with ❤️ by <a href="https://bruddasindustries.com" target="_blank" rel="noopener noreferrer">Brudda's Industries</a>.
        </p>
        <a class="footer-bottom__brand"
           href="https://sterlingpropertiesnj.com"
           target="_blank"
           rel="noopener noreferrer"
           aria-label="<?php esc_attr_e( 'A Sterling Properties community', 'rivergate-bordentown' ); ?>">
            <img class="footer-sterling-logo"
                 src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/logos/sterling-logo-white.png"
                 alt="Sterling Properties">
        </a>
        <div class="footer-bottom__links">
            <?php /* No Privacy Policy link: Sterling has no published policy to point at.
                     Add one back here if/when they publish one. */ ?>
            <a href="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/pdf/RivergateBrochure.pdf" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Brochure', 'rivergate-bordentown' ); ?></a>
            <a href="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/pdf/Rivergate-Rental-Application.pdf" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Rental Application', 'rivergate-bordentown' ); ?></a>
        </div>
    </div><!-- /.footer-bottom -->

</footer><!-- /.site-footer -->

<!-- Matterport virtual-tour lightbox (opened by any [data-mp] trigger via main.js) -->
<div class="mp-modal" id="mp-modal" role="dialog" aria-modal="true" aria-hidden="true" aria-label="<?php esc_attr_e( 'Virtual tour', 'rivergate-bordentown' ); ?>">
    <div class="mp-modal__backdrop" data-mp-close></div>
    <div class="mp-modal__dialog">
        <button class="mp-modal__close" type="button" data-mp-close aria-label="<?php esc_attr_e( 'Close virtual tour', 'rivergate-bordentown' ); ?>">&times;</button>
        <div class="mp-modal__title" id="mp-modal-title"><?php esc_html_e( 'Virtual Tour', 'rivergate-bordentown' ); ?></div>
        <div class="mp-modal__frame">
            <iframe id="mp-modal-iframe" title="<?php esc_attr_e( 'Matterport 3D virtual tour', 'rivergate-bordentown' ); ?>" allow="fullscreen; vr" allowfullscreen loading="lazy"></iframe>
        </div>
    </div>
</div>

<?php wp_footer(); ?>

</body>
</html>
