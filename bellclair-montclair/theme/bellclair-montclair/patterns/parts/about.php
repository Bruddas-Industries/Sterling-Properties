<?php
/** Pattern part: about / intro feature. */
$img = get_template_directory_uri() . '/assets/images';
?>
<!-- wp:group {"align":"full","className":"feature","anchor":"about","lock":{"move":true,"remove":true}} -->
<div class="wp-block-group alignfull feature" id="about">
<!-- wp:group {"className":"feature__image"} -->
<div class="wp-block-group feature__image">
<!-- wp:image {"linkDestination":"none"} -->
<figure class="wp-block-image"><img src="<?php echo esc_url( $img ); ?>/exterior/entry-stairs.jpg" alt="Lantern-lit brick entry stairs at Bellclair on Bell Street"/></figure>
<!-- /wp:image -->
</div>
<!-- /wp:group -->
<!-- wp:group {"className":"feature__body"} -->
<div class="wp-block-group feature__body">
<!-- wp:paragraph {"className":"eyebrow"} -->
<p class="eyebrow">Welcome to Bellclair</p>
<!-- /wp:paragraph -->
<!-- wp:heading -->
<h2 class="wp-block-heading">A Boutique Address on Bell Street</h2>
<!-- /wp:heading -->
<!-- wp:separator {"className":"divider"} -->
<hr class="wp-block-separator has-alpha-channel-opacity divider"/>
<!-- /wp:separator -->
<!-- wp:paragraph -->
<p>Bellclair is a boutique community by Sterling Properties, set on a quiet street at the edge of downtown Montclair. Spacious one- and two-bedroom residences pair modern kitchens and in-unit laundry with a secured lobby, a fitness center and garage parking — all a short walk from the town's restaurants, museums and shops.</p>
<!-- /wp:paragraph -->
<!-- wp:buttons -->
<div class="wp-block-buttons">
<!-- wp:button {"className":"is-style-bellclair-secondary"} -->
<div class="wp-block-button is-style-bellclair-secondary"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/#floor-plans' ) ); ?>">View Floor Plans</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
