<?php
/**
 * Pattern part: residence features grid.
 *
 * Sources: the floor-plan sheets (balcony, walk-in closet, laundry) and the
 * AppFolio listing text (9' ceilings, quartz, stainless, gas heat, central air).
 * Flooring is deliberately not described — AppFolio's "hardwood" is unverified
 * (docs/STATUS.md → Copy to confirm).
 */
?>
<!-- wp:group {"tagName":"section","align":"full","anchor":"residences","lock":{"move":true,"remove":true}} -->
<section class="wp-block-group alignfull" id="residences">
<!-- wp:group {"className":"container"} -->
<div class="wp-block-group container">
<!-- wp:group {"className":"residences-split"} -->
<div class="wp-block-group residences-split">
<!-- wp:group {"className":"residences-split__text"} -->
<div class="wp-block-group residences-split__text">
<!-- wp:group {"className":"section-intro"} -->
<div class="wp-block-group section-intro">
<!-- wp:paragraph {"className":"eyebrow"} -->
<p class="eyebrow">Residence Features</p>
<!-- /wp:paragraph -->
<!-- wp:heading -->
<h2 class="wp-block-heading">Bright Homes with Room to Spread Out</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Open living and dining rooms, kitchens with stainless steel appliances, and a full-size washer and dryer in every home.</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:list {"className":"residence-grid"} -->
<ul class="wp-block-list residence-grid">
<!-- wp:list-item --><li>Private Entrance</li><!-- /wp:list-item -->
<!-- wp:list-item --><li>Private Balcony</li><!-- /wp:list-item -->
<!-- wp:list-item --><li>9-Foot Ceilings</li><!-- /wp:list-item -->
<!-- wp:list-item --><li>Full-Size Washer &amp; Dryer</li><!-- /wp:list-item -->
<!-- wp:list-item --><li>Stainless Steel Appliances</li><!-- /wp:list-item -->
<!-- wp:list-item --><li>Quartz Countertops</li><!-- /wp:list-item -->
<!-- wp:list-item --><li>Walk-In Closets</li><!-- /wp:list-item -->
<!-- wp:list-item --><li>Central Air &amp; Gas Heat</li><!-- /wp:list-item -->
</ul>
<!-- /wp:list -->
</div>
<!-- /wp:group -->
<!-- wp:html -->
<div class="residences-split__photo" data-animate="right">
<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/residences/florham-living-staged.jpg" alt="Florham two-bedroom living and dining room, virtually staged" loading="lazy">
</div>
<!-- /wp:html -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
</section>
<!-- /wp:group -->
