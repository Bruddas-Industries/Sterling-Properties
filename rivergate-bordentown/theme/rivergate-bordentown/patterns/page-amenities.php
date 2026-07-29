<?php
/**
 * Title: Rivergate Page — Amenities
 * Slug: rivergate-bordentown/page-amenities
 * Categories: rivergate
 * Description: Amenities page — hero, clubhouse + pool features, amenity cards, in-unit finishes, CTA.
 * Inserter: true
 */
$img = get_template_directory_uri() . '/assets/images';
?>
<!-- wp:rivergate/page-hero {"align":"full","lock":{"move":true,"remove":true},"eyebrow":"Community & Apartment","heading":"Amenities","imageUrl":"<?php echo esc_url( $img ); ?>/clubhouse/clubhouse-1.jpg"} /-->

<!-- wp:group {"align":"full","className":"feature feature--reverse feature--alt","lock":{"move":true,"remove":true}} -->
<div class="wp-block-group alignfull feature feature--reverse feature--alt">
<!-- wp:group {"className":"feature__image"} -->
<div class="wp-block-group feature__image">
<!-- wp:image {"linkDestination":"none"} -->
<figure class="wp-block-image"><img src="<?php echo esc_url( $img ); ?>/clubhouse/clubhouse-1.jpg" alt="Rivergate Bordentown clubhouse"/></figure>
<!-- /wp:image -->
</div>
<!-- /wp:group -->
<!-- wp:group {"className":"feature__body"} -->
<div class="wp-block-group feature__body">
<!-- wp:paragraph {"className":"eyebrow"} --><p class="eyebrow">Community Hub</p><!-- /wp:paragraph -->
<!-- wp:heading --><h2 class="wp-block-heading">The Rivergate Clubhouse</h2><!-- /wp:heading -->
<!-- wp:separator {"className":"divider"} --><hr class="wp-block-separator has-alpha-channel-opacity divider"/><!-- /wp:separator -->
<!-- wp:paragraph --><p>The Rivergate Clubhouse serves as the beating heart of the community — an elegantly appointed gathering space for residents to socialize, relax, and connect.</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p>Adjacent to the fitness studio and overlooking the landscaped grounds and heated pool, the clubhouse places every cornerstone amenity within steps of one another.</p><!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->

<!-- wp:group {"align":"full","className":"feature","lock":{"move":true,"remove":true}} -->
<div class="wp-block-group alignfull feature">
<!-- wp:group {"className":"feature__image"} -->
<div class="wp-block-group feature__image">
<!-- wp:image {"linkDestination":"none"} -->
<figure class="wp-block-image"><img src="<?php echo esc_url( $img ); ?>/amenities/amenity-1.jpg" alt="Rivergate resort-style heated pool"/></figure>
<!-- /wp:image -->
</div>
<!-- /wp:group -->
<!-- wp:group {"className":"feature__body"} -->
<div class="wp-block-group feature__body">
<!-- wp:paragraph {"className":"eyebrow"} --><p class="eyebrow">Outdoor Living</p><!-- /wp:paragraph -->
<!-- wp:heading --><h2 class="wp-block-heading">Resort-Style Pool &amp; Grounds</h2><!-- /wp:heading -->
<!-- wp:separator {"className":"divider"} --><hr class="wp-block-separator has-alpha-channel-opacity divider"/><!-- /wp:separator -->
<!-- wp:paragraph --><p>The resort-style heated outdoor swimming pool and sun deck offer a private retreat that rivals any five-star resort. Professionally landscaped and irrigated grounds surround the property year-round.</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p>A dedicated outdoor BBQ and entertaining area sits just off the pool — perfect for weekend gatherings and casual evenings with neighbors.</p><!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","align":"full","className":"section--alt","lock":{"move":true,"remove":true}} -->
<section class="wp-block-group alignfull section--alt">
<!-- wp:group {"className":"container"} -->
<div class="wp-block-group container">
<!-- wp:group {"className":"section-intro"} -->
<div class="wp-block-group section-intro">
<!-- wp:paragraph {"className":"eyebrow"} --><p class="eyebrow">What's Included</p><!-- /wp:paragraph -->
<!-- wp:heading --><h2 class="wp-block-heading">Everything You Need, Right Here</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>From secured access to riverside transit, Rivergate delivers an elevated living experience designed for how you actually live.</p><!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:rivergate/amenity-cards {"lock":{"move":true,"remove":true}} /-->
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
<!-- wp:paragraph {"className":"eyebrow"} --><p class="eyebrow">Inside Every Residence</p><!-- /wp:paragraph -->
<!-- wp:heading --><h2 class="wp-block-heading">Crafted for Elevated Living</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>Every home at Rivergate is finished with premium materials and modern conveniences — from the kitchen to the balcony.</p><!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:list {"className":"feature-list"} -->
<ul class="wp-block-list feature-list">
<!-- wp:list-item --><li>Quartz countertops</li><!-- /wp:list-item -->
<!-- wp:list-item --><li>Stainless steel appliances</li><!-- /wp:list-item -->
<!-- wp:list-item --><li>Luxury vinyl plank flooring</li><!-- /wp:list-item -->
<!-- wp:list-item --><li>Floor-to-ceiling windows</li><!-- /wp:list-item -->
<!-- wp:list-item --><li>Private balcony with river or courtyard views</li><!-- /wp:list-item -->
<!-- wp:list-item --><li>In-unit full-size washer and dryer</li><!-- /wp:list-item -->
<!-- wp:list-item --><li>Keyless entry system</li><!-- /wp:list-item -->
<!-- wp:list-item --><li>Smart thermostat</li><!-- /wp:list-item -->
<!-- wp:list-item --><li>Walk-in closets</li><!-- /wp:list-item -->
<!-- wp:list-item --><li>Spa-inspired bathrooms with tile surround</li><!-- /wp:list-item -->
<!-- wp:list-item --><li>Tile kitchen backsplash</li><!-- /wp:list-item -->
<!-- wp:list-item --><li>USB charging outlets</li><!-- /wp:list-item -->
</ul>
<!-- /wp:list -->
</div>
<!-- /wp:group -->
</section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","align":"full","className":"cta-section","lock":{"move":true,"remove":true}} -->
<section class="wp-block-group alignfull cta-section">
<!-- wp:group {"className":"container"} -->
<div class="wp-block-group container">
<!-- wp:paragraph {"className":"eyebrow eyebrow--light"} --><p class="eyebrow eyebrow--light">See It for Yourself</p><!-- /wp:paragraph -->
<!-- wp:separator {"className":"divider"} --><hr class="wp-block-separator has-alpha-channel-opacity divider"/><!-- /wp:separator -->
<!-- wp:heading --><h2 class="wp-block-heading">Schedule a Tour Today</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>Our leasing team is ready to show you around — in person or virtually.</p><!-- /wp:paragraph -->
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
