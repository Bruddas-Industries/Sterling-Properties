<?php
/**
 * Pattern part: contact section (info column + form block).
 *
 * The form's recipient is set on the block so submissions never fall back to
 * admin_email (the developer). bellclaire@spgnj.com is spelled with an e; see
 * docs/STATUS.md. Office hours are still to come from the client.
 */
?>
<!-- wp:group {"tagName":"section","align":"full","anchor":"contact","lock":{"move":true,"remove":true}} -->
<section class="wp-block-group alignfull" id="contact">
<!-- wp:group {"className":"container"} -->
<div class="wp-block-group container">
<!-- wp:group {"className":"contact-grid"} -->
<div class="wp-block-group contact-grid">
<!-- wp:group {"className":"contact-info"} -->
<div class="wp-block-group contact-info">
<!-- wp:paragraph {"className":"eyebrow"} -->
<p class="eyebrow">Get in Touch</p>
<!-- /wp:paragraph -->
<!-- wp:heading -->
<h2 class="wp-block-heading">We'd Love to Show You Around</h2>
<!-- /wp:heading -->
<!-- wp:separator {"className":"divider"} -->
<hr class="wp-block-separator has-alpha-channel-opacity divider"/>
<!-- /wp:separator -->
<!-- wp:paragraph {"className":"contact-label"} --><p class="contact-label">Leasing Office</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p>691 Bloomfield Avenue<br>Montclair, NJ 07042</p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"contact-label"} --><p class="contact-label">Residences</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p>7 &amp; 8 Bell Street and 691 Bloomfield Avenue, Montclair</p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"contact-label"} --><p class="contact-label">Phone</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p><a href="tel:9736550997">973.655.0997</a></p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"contact-label"} --><p class="contact-label">Email</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p><a href="mailto:bellclaire@spgnj.com">bellclaire@spgnj.com</a></p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"contact-label"} --><p class="contact-label">Leasing Office Hours</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p>Call or email to schedule a visit.</p><!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:bellclair/contact-form {"recipientEmail":"bellclaire@spgnj.com","lock":{"move":true,"remove":true}} /-->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
</section>
<!-- /wp:group -->
