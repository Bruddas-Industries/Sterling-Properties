<?php
/** Pattern part: about / intro feature. */
$img = get_template_directory_uri() . '/assets/images';
?>
<!-- wp:group {"align":"full","className":"feature","anchor":"about","lock":{"move":true,"remove":true}} -->
<div class="wp-block-group alignfull feature" id="about">
<!-- wp:group {"className":"feature__image"} -->
<div class="wp-block-group feature__image">
<!-- wp:image {"linkDestination":"none"} -->
<figure class="wp-block-image"><img src="<?php echo esc_url( $img ); ?>/exterior/residences-garages.jpg" alt="Riverbend residences with private entrances and garages along a landscaped drive"/></figure>
<!-- /wp:image -->
</div>
<!-- /wp:group -->
<!-- wp:group {"className":"feature__body"} -->
<div class="wp-block-group feature__body">
<!-- wp:paragraph {"className":"eyebrow"} -->
<p class="eyebrow">Welcome to Riverbend</p>
<!-- /wp:paragraph -->
<!-- wp:heading -->
<h2 class="wp-block-heading">A Small Community with Room to Breathe</h2>
<!-- /wp:heading -->
<!-- wp:separator {"className":"divider"} -->
<hr class="wp-block-separator has-alpha-channel-opacity divider"/>
<!-- /wp:separator -->
<!-- wp:paragraph -->
<p>Riverbend is a small, community-oriented Sterling Properties community of garden-style apartments in Florham Park. Every home has its own private entrance, a balcony, a full-size washer and dryer and a garage, and residents share a renovated clubhouse, a fitness center and a pool.</p>
<!-- /wp:paragraph -->
<!-- wp:buttons -->
<div class="wp-block-buttons">
<!-- wp:button {"className":"is-style-riverbend-secondary"} -->
<div class="wp-block-button is-style-riverbend-secondary"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/#floor-plans' ) ); ?>">View Floor Plans</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
