<?php
/**
 * Title: Bellclair Page — Availability
 * Slug: bellclair-montclair/page-availability
 * Categories: bellclair
 * Description: Availability page — hero, AppFolio embed, how-it-works steps, apply band, CTA.
 * Inserter: true
 */
$img = get_template_directory_uri() . '/assets/images';
?>
<!-- wp:bellclair/page-hero {"align":"full","lock":{"move":true,"remove":true},"eyebrow":"Pricing & Online Applications","heading":"Availability","imageUrl":"<?php echo esc_url( $img ); ?>/exterior/bell-street.jpg","imageFocus":"center"} /-->

<!-- wp:group {"tagName":"section","align":"full","lock":{"move":true,"remove":true}} -->
<section class="wp-block-group alignfull">
<!-- wp:group {"className":"container"} -->
<div class="wp-block-group container">
<!-- wp:group {"className":"section-intro"} -->
<div class="wp-block-group section-intro">
<!-- wp:paragraph {"className":"eyebrow"} --><p class="eyebrow">Real-Time Listings</p><!-- /wp:paragraph -->
<!-- wp:heading --><h2 class="wp-block-heading">Check What's Available Now</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>Live availability, pricing and online applications are all managed through <strong>AppFolio</strong>. Browse open residences below and apply directly online — no appointment required.</p><!-- /wp:paragraph -->
</div>
<!-- /wp:group -->

<!-- wp:bellclair/availability-embed {"lock":{"move":true,"remove":true}} /-->

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
<!-- wp:paragraph --><p>Our leasing team guides you through approval, lease signing and key handoff from there.</p><!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->

<!-- wp:group {"className":"apply-band"} -->
<div class="wp-block-group apply-band">
<!-- wp:paragraph {"className":"eyebrow"} --><p class="eyebrow">Ready When You Are</p><!-- /wp:paragraph -->
<!-- wp:heading --><h2 class="wp-block-heading">Start Your Application</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>Have a floor plan in mind already? Apply now, or reach out to the leasing team with any questions.</p><!-- /wp:paragraph -->
<!-- wp:buttons {"className":"apply-band__actions","layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons apply-band__actions">
<!-- wp:button {"className":"is-style-bellclair-primary"} --><div class="wp-block-button is-style-bellclair-primary"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( bellclair_apply_url() ); ?>" target="_blank" rel="noopener noreferrer">Apply in AppFolio →</a></div><!-- /wp:button -->
<!-- wp:button {"className":"is-style-bellclair-ghost"} --><div class="wp-block-button is-style-bellclair-ghost"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/#contact' ) ); ?>">Schedule a Tour</a></div><!-- /wp:button -->
<!-- wp:button {"className":"is-style-bellclair-ghost"} --><div class="wp-block-button is-style-bellclair-ghost"><a class="wp-block-button__link wp-element-button" href="tel:9736550997">Call 973.655.0997</a></div><!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
</section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","align":"full","className":"cta-section deco-rules","lock":{"move":true,"remove":true}} -->
<section class="wp-block-group alignfull cta-section deco-rules">
<!-- wp:group {"className":"container"} -->
<div class="wp-block-group container">
<!-- wp:paragraph {"className":"eyebrow"} --><p class="eyebrow">Still Deciding?</p><!-- /wp:paragraph -->
<!-- wp:separator {"className":"divider"} --><hr class="wp-block-separator has-alpha-channel-opacity divider"/><!-- /wp:separator -->
<!-- wp:heading --><h2 class="wp-block-heading">Explore the Floor Plans</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>Compare the layouts and take a 3D walkthrough, then come back here to check live availability.</p><!-- /wp:paragraph -->
<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons">
<!-- wp:button {"className":"is-style-bellclair-primary"} --><div class="wp-block-button is-style-bellclair-primary"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/#floor-plans' ) ); ?>">View Floor Plans</a></div><!-- /wp:button -->
<!-- wp:button {"className":"is-style-bellclair-ghost"} --><div class="wp-block-button is-style-bellclair-ghost"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/#amenities' ) ); ?>">See the Amenities</a></div><!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
</section>
<!-- /wp:group -->
