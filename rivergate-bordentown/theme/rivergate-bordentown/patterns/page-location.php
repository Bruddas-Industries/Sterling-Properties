<?php
/**
 * Title: Rivergate Page — Location
 * Slug: rivergate-bordentown/page-location
 * Categories: rivergate
 * Description: Location page — hero, transit strip, transit feature, neighborhood explorer, CTA.
 * Inserter: true
 */
$img = get_template_directory_uri() . '/assets/images';
?>
<!-- wp:rivergate/page-hero {"align":"full","lock":{"move":true,"remove":true},"eyebrow":"500 Bluff View Circle · Bordentown, NJ","heading":"Location & Neighborhood","imageUrl":"<?php echo esc_url( $img ); ?>/aerials/aerial-2.jpg"} /-->

<!-- wp:group {"align":"full","className":"transit-strip","lock":{"move":true,"remove":true}} -->
<div class="wp-block-group alignfull transit-strip">
<!-- wp:group {"className":"container"} -->
<div class="wp-block-group container">
<!-- wp:group --><div class="wp-block-group">
<!-- wp:paragraph {"className":"transit-stat__number"} --><p class="transit-stat__number">Steps</p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"transit-stat__label"} --><p class="transit-stat__label">to the River Line Light Rail</p><!-- /wp:paragraph -->
</div><!-- /wp:group -->
<!-- wp:group --><div class="wp-block-group">
<!-- wp:paragraph {"className":"transit-stat__number"} --><p class="transit-stat__number">~45 min</p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"transit-stat__label"} --><p class="transit-stat__label">to Philadelphia by rail</p><!-- /wp:paragraph -->
</div><!-- /wp:group -->
<!-- wp:group --><div class="wp-block-group">
<!-- wp:paragraph {"className":"transit-stat__number"} --><p class="transit-stat__number">~75 min</p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"transit-stat__label"} --><p class="transit-stat__label">to New York Penn Station</p><!-- /wp:paragraph -->
</div><!-- /wp:group -->
<!-- wp:group --><div class="wp-block-group">
<!-- wp:paragraph {"className":"transit-stat__number"} --><p class="transit-stat__number">5 min</p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"transit-stat__label"} --><p class="transit-stat__label">to NJ Turnpike Exit 7</p><!-- /wp:paragraph -->
</div><!-- /wp:group -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->

<!-- wp:group {"align":"full","className":"feature feature--reverse feature--alt","lock":{"move":true,"remove":true}} -->
<div class="wp-block-group alignfull feature feature--reverse feature--alt">
<!-- wp:group {"className":"feature__image"} -->
<div class="wp-block-group feature__image">
<!-- wp:image {"linkDestination":"none"} -->
<figure class="wp-block-image"><img src="<?php echo esc_url( $img ); ?>/aerials/aerial-3.jpg" alt="River Line Light Rail near Rivergate"/></figure>
<!-- /wp:image -->
</div>
<!-- /wp:group -->
<!-- wp:group {"className":"feature__body"} -->
<div class="wp-block-group feature__body">
<!-- wp:paragraph {"className":"eyebrow"} --><p class="eyebrow">Transit-Oriented Living</p><!-- /wp:paragraph -->
<!-- wp:heading --><h2 class="wp-block-heading">The Best-Connected Address in Bordentown</h2><!-- /wp:heading -->
<!-- wp:separator {"className":"divider"} --><hr class="wp-block-separator has-alpha-channel-opacity divider"/><!-- /wp:separator -->
<!-- wp:paragraph --><p>Rivergate Bordentown sits directly on the River Line Light Rail corridor — giving you access to Trenton Transit Center (Amtrak + NJ Transit) in under 10 minutes. From there, New York and Philadelphia are less than 90 minutes away.</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p>For drivers, I-295, the NJ Turnpike (Exit 7), and I-195 are all within minutes — connecting you to the Shore, Princeton, and beyond.</p><!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","align":"full","lock":{"move":true,"remove":true}} -->
<section class="wp-block-group alignfull">
<!-- wp:group {"className":"container"} -->
<div class="wp-block-group container">
<!-- wp:group {"className":"section-intro"} -->
<div class="wp-block-group section-intro">
<!-- wp:paragraph {"className":"eyebrow"} --><p class="eyebrow">Your Neighborhood</p><!-- /wp:paragraph -->
<!-- wp:heading --><h2 class="wp-block-heading">Everything Within Reach</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>From Farnsworth Avenue's boutiques and restaurants to regional malls, parks, and world-class cultural attractions — Bordentown delivers an exceptional quality of life.</p><!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:rivergate/neighborhood-explorer {"lock":{"move":true,"remove":true}} /-->
</div>
<!-- /wp:group -->
</section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","align":"full","className":"cta-section","lock":{"move":true,"remove":true}} -->
<section class="wp-block-group alignfull cta-section">
<!-- wp:group {"className":"container"} -->
<div class="wp-block-group container">
<!-- wp:paragraph {"className":"eyebrow eyebrow--light"} --><p class="eyebrow eyebrow--light">Come See It for Yourself</p><!-- /wp:paragraph -->
<!-- wp:separator {"className":"divider"} --><hr class="wp-block-separator has-alpha-channel-opacity divider"/><!-- /wp:separator -->
<!-- wp:heading --><h2 class="wp-block-heading">Schedule a Private Tour</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>Our leasing team will show you the waterfront, the amenities, and your future home.</p><!-- /wp:paragraph -->
<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons">
<!-- wp:button {"className":"is-style-rivergate-primary"} --><div class="wp-block-button is-style-rivergate-primary"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Schedule a Tour</a></div><!-- /wp:button -->
<!-- wp:button {"className":"is-style-rivergate-ghost"} --><div class="wp-block-button is-style-rivergate-ghost"><a class="wp-block-button__link wp-element-button" href="tel:6092980303">Call 609.298.0303</a></div><!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
</section>
<!-- /wp:group -->
