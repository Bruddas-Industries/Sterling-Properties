<?php
/**
 * Title: Rivergate Page — Availability
 * Slug: rivergate-bordentown/page-availability
 * Categories: rivergate
 * Description: Availability page — hero, AppFolio embed, how-it-works steps, apply band, CTA.
 * Inserter: true
 */
$img = get_template_directory_uri() . '/assets/images';
?>
<!-- wp:rivergate/page-hero {"align":"full","lock":{"move":true,"remove":true},"eyebrow":"Pricing & Online Applications","heading":"Availability","imageUrl":"<?php echo esc_url( $img ); ?>/aerials/aerial-3.jpg"} /-->

<!-- wp:group {"tagName":"section","align":"full","lock":{"move":true,"remove":true}} -->
<section class="wp-block-group alignfull">
<!-- wp:group {"className":"container"} -->
<div class="wp-block-group container">
<!-- wp:group {"className":"section-intro"} -->
<div class="wp-block-group section-intro">
<!-- wp:paragraph {"className":"eyebrow"} --><p class="eyebrow">Real-Time Listings</p><!-- /wp:paragraph -->
<!-- wp:heading --><h2 class="wp-block-heading">Check What's Available Now</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>Live unit availability, pricing, and online applications are managed through <strong>AppFolio</strong>. Browse open residences and apply directly online — no appointment required.</p><!-- /wp:paragraph -->
</div>
<!-- /wp:group -->

<!-- wp:rivergate/availability-embed {"lock":{"move":true,"remove":true}} /-->

<!-- wp:group {"className":"how-it-works"} -->
<div class="wp-block-group how-it-works">
<!-- wp:group {"className":"how-step"} -->
<div class="wp-block-group how-step">
<!-- wp:paragraph {"className":"how-step__num"} --><p class="how-step__num">1</p><!-- /wp:paragraph -->
<!-- wp:heading {"level":3} --><h3 class="wp-block-heading">Browse Live Listings</h3><!-- /wp:heading -->
<!-- wp:paragraph --><p>See every available residence with current pricing and move-in dates, updated in real time.</p><!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:group {"className":"how-step"} -->
<div class="wp-block-group how-step">
<!-- wp:paragraph {"className":"how-step__num"} --><p class="how-step__num">2</p><!-- /wp:paragraph -->
<!-- wp:heading {"level":3} --><h3 class="wp-block-heading">Apply Online</h3><!-- /wp:heading -->
<!-- wp:paragraph --><p>Submit your application securely through AppFolio in minutes — no office visit needed.</p><!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:group {"className":"how-step"} -->
<div class="wp-block-group how-step">
<!-- wp:paragraph {"className":"how-step__num"} --><p class="how-step__num">3</p><!-- /wp:paragraph -->
<!-- wp:heading {"level":3} --><h3 class="wp-block-heading">Move In</h3><!-- /wp:heading -->
<!-- wp:paragraph --><p>Our leasing team guides you through approval, lease signing, and key handoff from there.</p><!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->

<!-- wp:group {"className":"apply-band"} -->
<div class="wp-block-group apply-band">
<!-- wp:paragraph {"className":"eyebrow eyebrow--light"} --><p class="eyebrow eyebrow--light">Ready When You Are</p><!-- /wp:paragraph -->
<!-- wp:heading --><h2 class="wp-block-heading">Start Your Application</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>Have a floor plan in mind already? Apply now, or reach out to the leasing team with any questions.</p><!-- /wp:paragraph -->
<!-- wp:buttons {"className":"apply-band__actions","layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons apply-band__actions">
<!-- wp:button {"className":"is-style-rivergate-primary"} --><div class="wp-block-button is-style-rivergate-primary"><a class="wp-block-button__link wp-element-button" href="https://sterlingproperties.appfolio.com/listings?filters%5Bproperty_list%5D=RIVERGATE+BORDENTOWN" target="_blank" rel="noopener noreferrer">Apply in AppFolio →</a></div><!-- /wp:button -->
<!-- wp:button {"className":"is-style-rivergate-ghost"} --><div class="wp-block-button is-style-rivergate-ghost"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Schedule a Tour</a></div><!-- /wp:button -->
<!-- wp:button {"className":"is-style-rivergate-ghost"} --><div class="wp-block-button is-style-rivergate-ghost"><a class="wp-block-button__link wp-element-button" href="tel:6092980303">Call 609.298.0303</a></div><!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
</section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","align":"full","className":"cta-section","lock":{"move":true,"remove":true}} -->
<section class="wp-block-group alignfull cta-section">
<!-- wp:group {"className":"container"} -->
<div class="wp-block-group container">
<!-- wp:paragraph {"className":"eyebrow eyebrow--light"} --><p class="eyebrow eyebrow--light">Still Deciding?</p><!-- /wp:paragraph -->
<!-- wp:separator {"className":"divider"} --><hr class="wp-block-separator has-alpha-channel-opacity divider"/><!-- /wp:separator -->
<!-- wp:heading --><h2 class="wp-block-heading">Explore the Floor Plans</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>Compare all eight layouts — one and two bedroom — then come back here to check live availability.</p><!-- /wp:paragraph -->
<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons">
<!-- wp:button {"className":"is-style-rivergate-primary"} --><div class="wp-block-button is-style-rivergate-primary"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/floor-plans/' ) ); ?>">View Floor Plans</a></div><!-- /wp:button -->
<!-- wp:button {"className":"is-style-rivergate-ghost"} --><div class="wp-block-button is-style-rivergate-ghost"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/amenities/' ) ); ?>">See the Amenities</a></div><!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
</section>
<!-- /wp:group -->
