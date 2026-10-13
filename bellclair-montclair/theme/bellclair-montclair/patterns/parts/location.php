<?php
/**
 * Pattern part: neighborhood section (intro + photo + location-stats block +
 * neighborhood explorer). Walk/drive times are measured, not estimated — see the
 * explorer's render.php before changing any of the copy here.
 */
?>
<!-- wp:group {"tagName":"section","align":"full","className":"section--alt","anchor":"neighborhood","lock":{"move":true,"remove":true}} -->
<section class="wp-block-group alignfull section--alt" id="neighborhood">
<!-- wp:group {"className":"container"} -->
<div class="wp-block-group container">
<!-- wp:group {"className":"section-intro"} -->
<div class="wp-block-group section-intro">
<!-- wp:paragraph {"className":"eyebrow"} -->
<p class="eyebrow">Montclair, New Jersey</p>
<!-- /wp:paragraph -->
<!-- wp:heading -->
<h2 class="wp-block-heading">Explore the Neighborhood</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Filter the map by category. Times are measured from Bellclair.</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:html -->
<div class="location-photo" data-animate="fade">
<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/exterior/bloomfield-avenue.jpg" alt="Bellclair's Bloomfield Avenue building in downtown Montclair" loading="lazy">
<span class="location-photo__caption">Bellclair on Bloomfield Avenue</span>
</div>
<!-- /wp:html -->
<!-- wp:bellclair/location-stats {"lock":{"move":true,"remove":true}} /-->
<!-- wp:bellclair/neighborhood-explorer {"lock":{"move":true,"remove":true}} /-->
</div>
<!-- /wp:group -->
</section>
<!-- /wp:group -->
