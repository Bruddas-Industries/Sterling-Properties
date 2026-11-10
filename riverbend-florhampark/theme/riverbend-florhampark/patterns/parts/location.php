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
<p class="eyebrow">Florham Park, New Jersey</p>
<!-- /wp:paragraph -->
<!-- wp:heading -->
<h2 class="wp-block-heading">Explore the Neighborhood</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Riverbend sits beside the Passaic River at the Chatham line. Filter the map by category; times are measured from Riverbend, with drive times shown without traffic.</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:html -->
<div class="location-photo" data-animate="fade">
<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/exterior/community-drive.jpg" alt="Riverbend's garden-style residences around a landscaped drive" loading="lazy">
<span class="location-photo__caption">Garden-style residences at Riverbend</span>
</div>
<!-- /wp:html -->
<!-- wp:riverbend/location-stats {"lock":{"move":true,"remove":true}} /-->
<!-- wp:riverbend/neighborhood-explorer {"lock":{"move":true,"remove":true}} /-->
</div>
<!-- /wp:group -->
</section>
<!-- /wp:group -->
