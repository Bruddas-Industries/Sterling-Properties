<?php
/** Pattern part: location section (intro + location-stats block + CTA). */
?>
<!-- wp:group {"tagName":"section","align":"full","anchor":"neighborhood","lock":{"move":true,"remove":true}} -->
<section class="wp-block-group alignfull" id="neighborhood">
<!-- wp:group {"className":"container"} -->
<div class="wp-block-group container">
<!-- wp:group {"className":"section-intro"} -->
<div class="wp-block-group section-intro">
<!-- wp:paragraph {"className":"eyebrow"} -->
<p class="eyebrow">500 Bluff View Circle · Bordentown, NJ</p>
<!-- /wp:paragraph -->
<!-- wp:heading -->
<h2 class="wp-block-heading">Connected to Everything</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>On the Delaware River, near the River Line, minutes from I-295 — Rivergate puts New York and Philadelphia within reach.</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:html -->
<div class="location-photo" data-animate="fade">
<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/aerials/neighborhood-waterfront.jpg" alt="Aerial view of Rivergate along the Delaware River in Bordentown, New Jersey" loading="lazy">
<span class="location-photo__caption">On the Delaware River in Bordentown, New Jersey</span>
</div>
<!-- /wp:html -->
<!-- wp:rivergate/location-stats {"lock":{"move":true,"remove":true}} /-->
<?php // The explorer carries its own Get Directions / Contact actions, so the
      // old "Explore the Neighborhood" button below it would be redundant. ?>
<!-- wp:rivergate/neighborhood-explorer {"lock":{"move":true,"remove":true}} /-->
</div>
<!-- /wp:group -->
</section>
<!-- /wp:group -->
