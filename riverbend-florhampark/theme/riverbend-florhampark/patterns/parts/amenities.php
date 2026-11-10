<?php
/**
 * Pattern part: amenities section (intro + amenities block). The ten amenities
 * are the block's built-in list in blocks/_shared.php until the client adds
 * Amenity entries (OPERATIONS trap 8: one entry replaces the whole list).
 */
?>
<!-- wp:group {"tagName":"section","align":"full","className":"section--alt","anchor":"amenities","lock":{"move":true,"remove":true}} -->
<section class="wp-block-group alignfull section--alt" id="amenities">
<!-- wp:group {"className":"container"} -->
<div class="wp-block-group container">
<!-- wp:group {"className":"section-intro"} -->
<div class="wp-block-group section-intro">
<!-- wp:paragraph {"className":"eyebrow"} -->
<p class="eyebrow">Community Amenities</p>
<!-- /wp:paragraph -->
<!-- wp:heading -->
<h2 class="wp-block-heading">Everyday Comforts, Close to Home</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Select an amenity for a quick detail.</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:riverbend/amenities {"lock":{"move":true,"remove":true}} /-->
</div>
<!-- /wp:group -->
</section>
<!-- /wp:group -->
