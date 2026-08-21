<?php
/**
 * Title: Rivergate Page — Gallery
 * Slug: rivergate-bordentown/page-gallery
 * Categories: rivergate
 * Description: Photo gallery page — hero, two mosaic galleries, interior note, CTA.
 * Inserter: true
 *
 * Tiles use the .gallery-item / --wide / --tall classes from global.css section 19,
 * matching the approved preview. Each tile is a core Image block, so the client can
 * still swap a photo or edit its caption in the editor.
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
<!-- wp:paragraph --><p>Situated on the banks of the Delaware River, Rivergate's waterfront campus is as impressive from the outside as it is within.</p><!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:group {"className":"gallery-grid"} -->
<div class="wp-block-group gallery-grid">
<!-- wp:image {"className":"gallery-item gallery-item\u002d\u002dwide","linkDestination":"none"} -->
<figure class="wp-block-image gallery-item gallery-item--wide"><img src="<?php echo esc_url( $img ); ?>/exterior/exterior-1.png" alt="Rivergate Bordentown waterfront exterior"/><figcaption class="gallery-item__caption">Waterfront Exterior</figcaption></figure>
<!-- /wp:image -->
<!-- wp:image {"className":"gallery-item","linkDestination":"none"} -->
<figure class="wp-block-image gallery-item"><img src="<?php echo esc_url( $img ); ?>/exterior/exterior-2.png" alt="Rivergate building view"/><figcaption class="gallery-item__caption">Building View</figcaption></figure>
<!-- /wp:image -->
<!-- wp:image {"className":"gallery-item","linkDestination":"none"} -->
<figure class="wp-block-image gallery-item"><img src="<?php echo esc_url( $img ); ?>/exterior/exterior-4.jpg" alt="Rivergate Bordentown building exterior"/><figcaption class="gallery-item__caption">Community Exterior</figcaption></figure>
<!-- /wp:image -->
<!-- wp:image {"className":"gallery-item","linkDestination":"none"} -->
<figure class="wp-block-image gallery-item"><img src="<?php echo esc_url( $img ); ?>/exterior/exterior-5.jpg" alt="Rivergate grounds and exterior"/><figcaption class="gallery-item__caption">Grounds &amp; Landscaping</figcaption></figure>
<!-- /wp:image -->
<!-- wp:image {"className":"gallery-item","linkDestination":"none"} -->
<figure class="wp-block-image gallery-item"><img src="<?php echo esc_url( $img ); ?>/aerials/aerial-4.jpg" alt="Rivergate community view"/><figcaption class="gallery-item__caption">Community View</figcaption></figure>
<!-- /wp:image -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
</section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","align":"full","className":"section\u002d\u002dalt","lock":{"move":true,"remove":true}} -->
<section class="wp-block-group alignfull section--alt">
<!-- wp:group {"className":"container"} -->
<div class="wp-block-group container">
<!-- wp:group {"className":"section-intro"} -->
<div class="wp-block-group section-intro">
<!-- wp:paragraph {"className":"eyebrow"} --><p class="eyebrow">Amenity Spaces</p><!-- /wp:paragraph -->
<!-- wp:heading --><h2 class="wp-block-heading">Clubhouse, Pool &amp; More</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>From the resort-style pool to the outdoor BBQ area, Rivergate's amenity spaces are designed for relaxation and community.</p><!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:group {"className":"gallery-grid"} -->
<div class="wp-block-group gallery-grid">
<!-- wp:image {"className":"gallery-item gallery-item\u002d\u002dtall","linkDestination":"none"} -->
<figure class="wp-block-image gallery-item gallery-item--tall"><img src="<?php echo esc_url( $img ); ?>/clubhouse/clubhouse-1.jpg" alt="Rivergate clubhouse and pool"/><figcaption class="gallery-item__caption">Clubhouse &amp; Pool</figcaption></figure>
<!-- /wp:image -->
<!-- wp:image {"className":"gallery-item gallery-item\u002d\u002dwide","linkDestination":"none"} -->
<figure class="wp-block-image gallery-item gallery-item--wide"><img src="<?php echo esc_url( $img ); ?>/amenities/amenity-3.jpg" alt="Outdoor BBQ entertaining area"/><figcaption class="gallery-item__caption">Outdoor BBQ Area</figcaption></figure>
<!-- /wp:image -->
<!-- wp:image {"className":"gallery-item","linkDestination":"none"} -->
<figure class="wp-block-image gallery-item"><img src="<?php echo esc_url( $img ); ?>/amenities/amenity-4.jpg" alt="Rivergate amenity space"/><figcaption class="gallery-item__caption">Amenity Space</figcaption></figure>
<!-- /wp:image -->
<!-- wp:image {"className":"gallery-item","linkDestination":"none"} -->
<figure class="wp-block-image gallery-item"><img src="<?php echo esc_url( $img ); ?>/amenities/amenity-5.jpg" alt="Rivergate amenity detail"/><figcaption class="gallery-item__caption">Amenity Detail</figcaption></figure>
<!-- /wp:image -->
<!-- wp:image {"className":"gallery-item","linkDestination":"none"} -->
<figure class="wp-block-image gallery-item"><img src="<?php echo esc_url( $img ); ?>/amenities/amenity-1.jpg" alt="Pool and outdoor living at Rivergate"/><figcaption class="gallery-item__caption">Pool Deck</figcaption></figure>
<!-- /wp:image -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
</section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","align":"full","lock":{"move":true,"remove":true}} -->
<section class="wp-block-group alignfull">
<!-- wp:group {"className":"container"} -->
<div class="wp-block-group container">
<!-- wp:group {"className":"section-intro"} -->
<div class="wp-block-group section-intro">
<!-- wp:paragraph {"className":"eyebrow"} --><p class="eyebrow">Interior Photography</p><!-- /wp:paragraph -->
<!-- wp:heading --><h2 class="wp-block-heading">Schedule a Private Tour</h2><!-- /wp:heading -->
<!-- wp:separator {"className":"divider"} --><hr class="wp-block-separator has-alpha-channel-opacity divider"/><!-- /wp:separator -->
<!-- wp:paragraph --><p>Interior and model unit photography is best experienced in person. Contact our leasing team to schedule a walkthrough.</p><!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons">
<!-- wp:button {"className":"is-style-rivergate-primary"} --><div class="wp-block-button is-style-rivergate-primary"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/#contact' ) ); ?>">Schedule a Tour</a></div><!-- /wp:button -->
<!-- wp:button {"className":"is-style-rivergate-secondary"} --><div class="wp-block-button is-style-rivergate-secondary"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/floor-plans/' ) ); ?>">View Floor Plans</a></div><!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
</section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","align":"full","className":"cta\u002d\u002dsection","lock":{"move":true,"remove":true}} -->
<section class="wp-block-group alignfull cta-section">
<!-- wp:group {"className":"container"} -->
<div class="wp-block-group container">
<!-- wp:paragraph {"className":"eyebrow eyebrow\u002d\u002dlight"} --><p class="eyebrow eyebrow--light">Experience It Firsthand</p><!-- /wp:paragraph -->
<!-- wp:separator {"className":"divider"} --><hr class="wp-block-separator has-alpha-channel-opacity divider"/><!-- /wp:separator -->
<!-- wp:heading --><h2 class="wp-block-heading">See Rivergate in Person</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>Photos can only tell part of the story. Come see the waterfront setting, resort amenities, and designer interiors for yourself.</p><!-- /wp:paragraph -->
<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons">
<!-- wp:button {"className":"is-style-rivergate-primary"} --><div class="wp-block-button is-style-rivergate-primary"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/#contact' ) ); ?>">Schedule a Tour</a></div><!-- /wp:button -->
<!-- wp:button {"className":"is-style-rivergate-ghost"} --><div class="wp-block-button is-style-rivergate-ghost"><a class="wp-block-button__link wp-element-button" href="tel:6092980303">Call 609.298.0303</a></div><!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
</section>
<!-- /wp:group -->
