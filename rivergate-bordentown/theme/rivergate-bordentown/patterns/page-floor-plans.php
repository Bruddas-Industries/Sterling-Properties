<?php
/**
 * Title: Rivergate Page — Floor Plans
 * Slug: rivergate-bordentown/page-floor-plans
 * Categories: rivergate
 * Description: Floor Plans page — hero, 1BR/2BR plan cards, every-residence-includes band, availability CTA.
 * Inserter: true
 */
$img = get_template_directory_uri() . '/assets/images';
?>
<!-- wp:rivergate/page-hero {"align":"full","lock":{"move":true,"remove":true},"eyebrow":"One & Two Bedroom Residences","heading":"Floor Plans","imageUrl":"<?php echo esc_url( $img ); ?>/units/living-room-1.jpg"} /-->

<!-- wp:group {"tagName":"section","align":"full","lock":{"move":true,"remove":true}} -->
<section class="wp-block-group alignfull">
<!-- wp:group {"className":"container"} -->
<div class="wp-block-group container">
<!-- wp:group {"className":"section-intro"} -->
<div class="wp-block-group section-intro">
<!-- wp:paragraph {"className":"eyebrow"} --><p class="eyebrow">Distinctive Layouts</p><!-- /wp:paragraph -->
<!-- wp:heading --><h2 class="wp-block-heading">Find Your Perfect Floor Plan</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>Every residence features a private balcony, in-unit washer/dryer, and premium finishes throughout.</p><!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:rivergate/plan-cards {"lock":{"move":true,"remove":true}} /-->
</div>
<!-- /wp:group -->
</section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","align":"full","className":"includes-band","lock":{"move":true,"remove":true}} -->
<section class="wp-block-group alignfull includes-band">
<!-- wp:group {"className":"container"} -->
<div class="wp-block-group container">
<!-- wp:group {"className":"includes-band__head"} -->
<div class="wp-block-group includes-band__head">
<!-- wp:paragraph {"className":"eyebrow eyebrow--light"} --><p class="eyebrow eyebrow--light">Standard in Every Home</p><!-- /wp:paragraph -->
<!-- wp:heading --><h2 class="wp-block-heading">Every Residence Includes</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>No upgrade packages. No extra fees. These are the standard finishes in every Rivergate home.</p><!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:list {"className":"includes-grid"} -->
<ul class="wp-block-list includes-grid">
<!-- wp:list-item --><li>Quartz countertops</li><!-- /wp:list-item -->
<!-- wp:list-item --><li>Stainless steel appliances</li><!-- /wp:list-item -->
<!-- wp:list-item --><li>Luxury vinyl plank flooring</li><!-- /wp:list-item -->
<!-- wp:list-item --><li>Floor-to-ceiling windows</li><!-- /wp:list-item -->
<!-- wp:list-item --><li>Private balcony</li><!-- /wp:list-item -->
<!-- wp:list-item --><li>Full-size in-unit washer/dryer</li><!-- /wp:list-item -->
<!-- wp:list-item --><li>Keyless entry</li><!-- /wp:list-item -->
<!-- wp:list-item --><li>Smart thermostat</li><!-- /wp:list-item -->
<!-- wp:list-item --><li>Walk-in closets</li><!-- /wp:list-item -->
<!-- wp:list-item --><li>Spa bathrooms with tile surround</li><!-- /wp:list-item -->
<!-- wp:list-item --><li>Tile kitchen backsplash</li><!-- /wp:list-item -->
<!-- wp:list-item --><li>USB charging outlets</li><!-- /wp:list-item -->
</ul>
<!-- /wp:list -->
</div>
<!-- /wp:group -->
</section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","align":"full","lock":{"move":true,"remove":true}} -->
<section class="wp-block-group alignfull">
<!-- wp:group {"className":"container"} -->
<div class="wp-block-group container">
<!-- wp:group {"className":"availability-cta"} -->
<div class="wp-block-group availability-cta">
<!-- wp:group {"className":"availability-cta__text"} -->
<div class="wp-block-group availability-cta__text">
<!-- wp:heading --><h2 class="wp-block-heading">Check What's Available Now</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>Live pricing, available units, and online applications are managed through AppFolio — view everything in real time.</p><!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:buttons -->
<div class="wp-block-buttons">
<!-- wp:button {"className":"is-style-rivergate-primary"} --><div class="wp-block-button is-style-rivergate-primary"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/availability/' ) ); ?>">View Live Availability →</a></div><!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
</section>
<!-- /wp:group -->
