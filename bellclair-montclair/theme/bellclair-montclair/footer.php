<?php
/**
 * footer.php — Bellclair Montclair
 */
$bc_assets = get_template_directory_uri() . '/assets';
?>

</main><!-- /#main-content -->

<footer class="site-footer" role="contentinfo">

    <div class="footer-inner">

        <a href="<?php echo esc_url( home_url( '/' ) ); ?>"
           class="footer-logo"
           aria-label="<?php bloginfo( 'name' ); ?> — Home">
            <img src="<?php echo esc_url( $bc_assets ); ?>/images/logos/bellclair-logo-cream-gold.png"
                 alt="<?php bloginfo( 'name' ); ?>" width="122" height="44">
        </a>

        <nav class="footer-nav" aria-label="<?php esc_attr_e( 'Footer navigation', 'bellclair-montclair' ); ?>">
            <a href="<?php echo esc_url( home_url( '/availability/' ) ); ?>"><?php esc_html_e( 'Availability', 'bellclair-montclair' ); ?></a>
            <a href="<?php echo esc_url( home_url( '/#amenities' ) ); ?>"><?php esc_html_e( 'Amenities', 'bellclair-montclair' ); ?></a>
            <a href="<?php echo esc_url( home_url( '/#residences' ) ); ?>"><?php esc_html_e( 'Residences', 'bellclair-montclair' ); ?></a>
            <a href="<?php echo esc_url( home_url( '/#floor-plans' ) ); ?>"><?php esc_html_e( 'Floor Plans', 'bellclair-montclair' ); ?></a>
            <a href="<?php echo esc_url( home_url( '/gallery/' ) ); ?>"><?php esc_html_e( 'Gallery', 'bellclair-montclair' ); ?></a>
            <a href="<?php echo esc_url( home_url( '/#neighborhood' ) ); ?>"><?php esc_html_e( 'Neighborhood', 'bellclair-montclair' ); ?></a>
            <a href="<?php echo esc_url( home_url( '/#contact' ) ); ?>"><?php esc_html_e( 'Contact', 'bellclair-montclair' ); ?></a>
            <a href="<?php echo esc_url( home_url( '/residents/' ) ); ?>"><?php esc_html_e( 'Residents', 'bellclair-montclair' ); ?></a>
            <a href="https://sterlingproperties.appfolio.com/connect" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Pay Rent', 'bellclair-montclair' ); ?></a>
            <a href="<?php echo esc_url( bellclair_apply_url() ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Apply Now', 'bellclair-montclair' ); ?></a>
        </nav>

    </div><!-- /.footer-inner -->

    <?php /* Cats only, so no pet-friendly badge; no smoking policy confirmed yet. */ ?>
    <div class="footer-badges" aria-label="<?php esc_attr_e( 'Fair housing and accessibility', 'bellclair-montclair' ); ?>">
        <img src="<?php echo esc_url( $bc_assets ); ?>/images/badges/equal-housing.svg" alt="<?php esc_attr_e( 'Equal Housing Opportunity', 'bellclair-montclair' ); ?>" width="26" height="25">
        <img src="<?php echo esc_url( $bc_assets ); ?>/images/badges/accessible.svg" alt="<?php esc_attr_e( 'Accessible community', 'bellclair-montclair' ); ?>" width="22" height="25">
    </div>

    <div class="footer-bottom">
        <p class="footer-bottom__copy">
            &copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> Bellclair at Montclair. A Sterling Properties Community. All rights reserved.
            Made with ❤️ by <a href="https://bruddasindustries.com" target="_blank" rel="noopener noreferrer">Brudda's Industries</a>.
        </p>
        <a class="footer-bottom__brand"
           href="https://sterlingpropertiesnj.com"
           target="_blank"
           rel="noopener noreferrer"
           aria-label="<?php esc_attr_e( 'A Sterling Properties community', 'bellclair-montclair' ); ?>">
            <img class="footer-sterling-logo"
                 src="<?php echo esc_url( $bc_assets ); ?>/images/logos/sterling-logo-white.png"
                 alt="Sterling Properties">
        </a>
        <div class="footer-bottom__links">
            <?php /* No Privacy Policy link: Sterling has no published policy to point at.
                     Add one back here if/when they publish one. */ ?>
            <a href="https://sterlingproperties.appfolio.com/connect" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Resident Portal', 'bellclair-montclair' ); ?></a>
            <a href="<?php echo esc_url( bellclair_apply_url() ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Apply Online', 'bellclair-montclair' ); ?></a>
        </div>
    </div><!-- /.footer-bottom -->

</footer><!-- /.site-footer -->

<!-- Matterport 3D-tour lightbox (opened by any [data-mp] trigger via main.js) -->
<div class="mp-modal" id="mp-modal" role="dialog" aria-modal="true" aria-hidden="true" aria-label="<?php esc_attr_e( '3D tour', 'bellclair-montclair' ); ?>">
    <div class="mp-modal__backdrop" data-mp-close></div>
    <div class="mp-modal__dialog">
        <button class="mp-modal__close" type="button" data-mp-close aria-label="<?php esc_attr_e( 'Close 3D tour', 'bellclair-montclair' ); ?>">&times;</button>
        <div class="mp-modal__title" id="mp-modal-title"><?php esc_html_e( '3D Tour', 'bellclair-montclair' ); ?></div>
        <div class="mp-modal__frame">
            <iframe id="mp-modal-iframe" title="<?php esc_attr_e( 'Matterport 3D walkthrough', 'bellclair-montclair' ); ?>" allow="fullscreen; vr" allowfullscreen loading="lazy"></iframe>
        </div>
    </div>
</div>

<!-- Film lightbox: the full VTC film with sound, opened by any [data-film]
     trigger (hero button, film bands, amenities panel). The coming-soon card only
     shows if the file fails to load. Encodes: assets/video/README.txt. -->
<div class="mp-modal mp-modal--film" id="film-modal" role="dialog" aria-modal="true" aria-hidden="true" aria-label="<?php esc_attr_e( 'The Bellclair film', 'bellclair-montclair' ); ?>">
    <div class="mp-modal__backdrop" data-film-close></div>
    <div class="mp-modal__dialog">
        <button class="mp-modal__close" type="button" data-film-close aria-label="<?php esc_attr_e( 'Close film', 'bellclair-montclair' ); ?>">&times;</button>
        <div class="mp-modal__title"><?php esc_html_e( 'The Bellclair Film', 'bellclair-montclair' ); ?></div>
        <div class="mp-modal__frame">
            <video controls playsinline preload="none" poster="<?php echo esc_url( $bc_assets ); ?>/images/film/film-poster.jpg">
                <source src="<?php echo esc_url( bellclair_film_url() ); ?>" type="video/mp4">
            </video>
            <div class="film-modal__fallback" style="background-image: url('<?php echo esc_url( $bc_assets ); ?>/images/aerials/aerial-nyc-skyline.jpg');">
                <span class="eyebrow"><?php esc_html_e( 'Premiering Soon', 'bellclair-montclair' ); ?></span>
                <p><?php esc_html_e( 'The new Bellclair film will play here.', 'bellclair-montclair' ); ?></p>
            </div>
        </div>
    </div>
</div>

<!-- Full-screen viewer for floor plans and gallery photos.
     Gallery mode (main.js adds .is-gallery) shows the caption and prev/next. -->
<div class="fp-lightbox" id="fp-lightbox" aria-hidden="true">
    <div class="fp-lightbox__backdrop" data-fpl-close></div>
    <div class="fp-lightbox__dialog" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Floor plan', 'bellclair-montclair' ); ?>">
        <button class="fp-lightbox__close" type="button" data-fpl-close aria-label="<?php esc_attr_e( 'Close', 'bellclair-montclair' ); ?>">&times;</button>
        <button class="fp-lightbox__nav fp-lightbox__nav--prev" type="button" data-fpl-prev aria-label="<?php esc_attr_e( 'Previous photo', 'bellclair-montclair' ); ?>">&lsaquo;</button>
        <img id="fp-lightbox-img" src="" alt="">
        <p class="fp-lightbox__caption" id="fp-lightbox-caption" aria-live="polite"></p>
        <button class="fp-lightbox__nav fp-lightbox__nav--next" type="button" data-fpl-next aria-label="<?php esc_attr_e( 'Next photo', 'bellclair-montclair' ); ?>">&rsaquo;</button>
    </div>
</div>

<?php wp_footer(); ?>

</body>
</html>
