<?php
/** Pattern part: gallery teaser (split feature + View Full Gallery button). */
$img = get_template_directory_uri() . '/assets/images';
?>
<!-- wp:group {"tagName":"article","align":"full","className":"feature feature\u002d\u002dreverse","lock":{"move":true,"remove":true}} -->
<article class="wp-block-group alignfull feature feature--reverse">
<!-- wp:group {"className":"feature__image"} -->
<div class="wp-block-group feature__image">
<!-- wp:image {"linkDestination":"none"} -->
<figure class="wp-block-image"><img src="<?php echo esc_url( $img ); ?>/exterior/exterior-5.jpg" alt="Rivergate Bordentown waterfront"/></figure>
<!-- /wp:image -->
</div>
<!-- /wp:group -->
<!-- wp:group {"className":"feature__body"} -->
<div class="wp-block-group feature__body">
<!-- wp:paragraph {"className":"eyebrow"} --><p class="eyebrow">Gallery</p><!-- /wp:paragraph -->
<!-- wp:heading --><h2 class="wp-block-heading">See Rivergate for Yourself</h2><!-- /wp:heading -->
<!-- wp:separator {"className":"divider"} --><hr class="wp-block-separator has-alpha-channel-opacity divider"/><!-- /wp:separator -->
<!-- wp:buttons -->
<div class="wp-block-buttons">
<!-- wp:button {"className":"is-style-rivergate-primary"} --><div class="wp-block-button is-style-rivergate-primary"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/gallery/' ) ); ?>">View Full Gallery</a></div><!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
</article>
<!-- /wp:group -->
