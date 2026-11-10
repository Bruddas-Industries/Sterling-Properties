<?php
/**
 * Pattern part: contact section (info column + form block).
 *
 * The form's recipient is set on the block so submissions never fall back to
 * admin_email (the developer). Office hours are Nina's (2026-10-22); whether
 * visits are by appointment is unconfirmed (docs/STATUS.md). The hours also live
 * in the JSON-LD in inc/seo.php — change both together.
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
<!-- wp:paragraph --><p>1901 Sterling Drive<br>Florham Park, NJ 07932</p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"contact-label"} --><p class="contact-label">Phone</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p><a href="tel:9736650050">973.665.0050</a></p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"contact-label"} --><p class="contact-label">Email</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p><a href="mailto:riverbend@spgnj.com">riverbend@spgnj.com</a></p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"contact-label"} --><p class="contact-label">Leasing Office Hours</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p>Tuesday &amp; Wednesday: 10:00 AM – 5:00 PM<br>Thursday: 10:00 AM – 6:00 PM<br>Friday: 10:00 AM – 5:00 PM<br>Saturday: 9:00 AM – 5:00 PM<br>Sunday &amp; Monday: Closed</p><!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:riverbend/contact-form {"recipientEmail":"riverbend@spgnj.com","lock":{"move":true,"remove":true}} /-->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
</section>
<!-- /wp:group -->
