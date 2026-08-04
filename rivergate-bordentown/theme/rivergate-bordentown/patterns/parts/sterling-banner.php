<?php
/** Pattern part: Sterling portfolio banner. */
$img = get_template_directory_uri() . '/assets/images';
?>
<!-- wp:group {"tagName":"section","align":"full","className":"sterling-banner","lock":{"move":true,"remove":true}} -->
<section class="wp-block-group alignfull sterling-banner">
<!-- wp:group {"className":"container"} -->
<div class="wp-block-group container">
<!-- wp:group {"className":"sterling-banner__left"} -->
<div class="wp-block-group sterling-banner__left">
<!-- wp:image {"className":"sterling-banner__logo","linkDestination":"none"} -->
<figure class="wp-block-image sterling-banner__logo"><img src="<?php echo esc_url( $img ); ?>/logos/sterling-logo-white.png" alt="Sterling Properties"/></figure>
<!-- /wp:image -->
<!-- wp:paragraph {"className":"sterling-banner__text"} -->
<p class="sterling-banner__text">Rivergate is one of several distinctive communities by Sterling Properties across New Jersey.</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:buttons -->
<div class="wp-block-buttons">
<!-- wp:button {"className":"is-style-rivergate-ghost"} -->
<div class="wp-block-button is-style-rivergate-ghost"><a class="wp-block-button__link wp-element-button" href="https://sterlingpropertiesnj.com" target="_blank" rel="noopener noreferrer">View the Whole Sterling Portfolio</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
</section>
<!-- /wp:group -->
