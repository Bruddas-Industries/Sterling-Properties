<?php
/** Pattern part: about / intro feature. */
$img = get_template_directory_uri() . '/assets/images';
?>
<!-- wp:group {"align":"full","className":"feature","anchor":"about","lock":{"move":true,"remove":true}} -->
<div class="wp-block-group alignfull feature" id="about">
<!-- wp:group {"className":"feature__image"} -->
<div class="wp-block-group feature__image">
<!-- wp:image {"linkDestination":"none"} -->
<figure class="wp-block-image"><img src="<?php echo esc_url( $img ); ?>/exterior/exterior-entrance-sunset.jpg" alt="The tree-lined entrance drive into Rivergate Bordentown at sunset"/></figure>
<!-- /wp:image -->
</div>
<!-- /wp:group -->
<!-- wp:group {"className":"feature__body"} -->
<div class="wp-block-group feature__body">
<!-- wp:paragraph {"className":"eyebrow"} -->
<p class="eyebrow">Premier Waterfront Community</p>
<!-- /wp:paragraph -->
<!-- wp:heading -->
<h2 class="wp-block-heading">A New Standard for Riverside Living</h2>
<!-- /wp:heading -->
<!-- wp:separator {"className":"divider"} -->
<hr class="wp-block-separator has-alpha-channel-opacity divider"/>
<!-- /wp:separator -->
<!-- wp:paragraph -->
<p>A transit-oriented waterfront community by Sterling Properties — one and two-bedroom residences on the Delaware River.</p>
<!-- /wp:paragraph -->
<!-- wp:buttons -->
<div class="wp-block-buttons">
<!-- wp:button {"className":"is-style-rivergate-secondary"} -->
<div class="wp-block-button is-style-rivergate-secondary"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/#floor-plans' ) ); ?>">View Floor Plans</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
