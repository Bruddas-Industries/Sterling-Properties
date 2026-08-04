<?php
/**
 * Title: Rivergate Page — Gallery
 * Slug: rivergate-bordentown/page-gallery
 * Categories: rivergate
 * Description: Photo gallery page — hero, intro, media-library gallery (client-managed), CTA.
 * Inserter: true
 */
$img = get_template_directory_uri() . '/assets/images';
?>
<!-- wp:rivergate/page-hero {"align":"full","lock":{"move":true,"remove":true},"eyebrow":"Rivergate Bordentown","heading":"Photo Gallery","imageUrl":"<?php echo esc_url( $img ); ?>/exterior/exterior-1.png"} /-->

<!-- wp:group {"tagName":"section","align":"full","lock":{"move":true,"remove":true}} -->
<section class="wp-block-group alignfull">
<!-- wp:group {"className":"container"} -->
<div class="wp-block-group container">
<!-- wp:group {"className":"section-intro"} -->
<div class="wp-block-group section-intro">
<!-- wp:paragraph {"className":"eyebrow"} --><p class="eyebrow">Exterior &amp; Community</p><!-- /wp:paragraph -->
<!-- wp:heading --><h2 class="wp-block-heading">The Grounds &amp; Building</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>Situated on the banks of the Delaware River, Rivergate's waterfront campus is as impressive from the outside as it is within. Replace these placeholders with your own photos from the Media Library.</p><!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:gallery {"columns":3,"linkTo":"none"} -->
<figure class="wp-block-gallery has-nested-images columns-3 is-cropped">
<!-- wp:image {"linkDestination":"none"} --><figure class="wp-block-image"><img src="<?php echo esc_url( $img ); ?>/exterior/exterior-1.png" alt="Rivergate Bordentown building exterior"/></figure><!-- /wp:image -->
<!-- wp:image {"linkDestination":"none"} --><figure class="wp-block-image"><img src="<?php echo esc_url( $img ); ?>/aerials/aerial-1.jpg" alt="Rivergate Bordentown aerial view"/></figure><!-- /wp:image -->
<!-- wp:image {"linkDestination":"none"} --><figure class="wp-block-image"><img src="<?php echo esc_url( $img ); ?>/clubhouse/clubhouse-1.jpg" alt="Rivergate Bordentown clubhouse"/></figure><!-- /wp:image -->
<!-- wp:image {"linkDestination":"none"} --><figure class="wp-block-image"><img src="<?php echo esc_url( $img ); ?>/amenities/amenity-1.jpg" alt="Rivergate resort-style pool"/></figure><!-- /wp:image -->
<!-- wp:image {"linkDestination":"none"} --><figure class="wp-block-image"><img src="<?php echo esc_url( $img ); ?>/units/living-room-1.jpg" alt="Rivergate residence living room"/></figure><!-- /wp:image -->
<!-- wp:image {"linkDestination":"none"} --><figure class="wp-block-image"><img src="<?php echo esc_url( $img ); ?>/units/bedroom-1.jpg" alt="Rivergate residence bedroom"/></figure><!-- /wp:image -->
<!-- wp:image {"linkDestination":"none"} --><figure class="wp-block-image"><img src="<?php echo esc_url( $img ); ?>/exterior/exterior-3.jpg" alt="Rivergate building from the waterfront"/></figure><!-- /wp:image -->
<!-- wp:image {"linkDestination":"none"} --><figure class="wp-block-image"><img src="<?php echo esc_url( $img ); ?>/aerials/aerial-2.jpg" alt="Rivergate aerial showing the Delaware River"/></figure><!-- /wp:image -->
<!-- wp:image {"linkDestination":"none"} --><figure class="wp-block-image"><img src="<?php echo esc_url( $img ); ?>/units/dining-room-1.jpg" alt="Rivergate residence dining area"/></figure><!-- /wp:image -->
</figure>
<!-- /wp:gallery -->
</div>
<!-- /wp:group -->
</section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","align":"full","className":"cta-section","lock":{"move":true,"remove":true}} -->
<section class="wp-block-group alignfull cta-section">
<!-- wp:group {"className":"container"} -->
<div class="wp-block-group container">
<!-- wp:paragraph {"className":"eyebrow eyebrow--light"} --><p class="eyebrow eyebrow--light">Ready to See It in Person?</p><!-- /wp:paragraph -->
<!-- wp:separator {"className":"divider"} --><hr class="wp-block-separator has-alpha-channel-opacity divider"/><!-- /wp:separator -->
<!-- wp:heading --><h2 class="wp-block-heading">Schedule a Private Tour</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>Photos only tell part of the story. Come see the waterfront views, the amenities, and your future home.</p><!-- /wp:paragraph -->
<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons">
<!-- wp:button {"className":"is-style-rivergate-primary"} --><div class="wp-block-button is-style-rivergate-primary"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Schedule a Tour</a></div><!-- /wp:button -->
<!-- wp:button {"className":"is-style-rivergate-ghost"} --><div class="wp-block-button is-style-rivergate-ghost"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/floor-plans/' ) ); ?>">View Floor Plans</a></div><!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
</section>
<!-- /wp:group -->
