<?php
/**
 * Title: Rivergate Page — Contact
 * Slug: rivergate-bordentown/page-contact
 * Categories: rivergate
 * Description: Contact page — hero, quick-contact strip, info + tour-request form, map.
 * Inserter: true
 */
$img = get_template_directory_uri() . '/assets/images';
?>
<!-- wp:rivergate/page-hero {"align":"full","lock":{"move":true,"remove":true},"eyebrow":"Leasing Office","heading":"Contact & Schedule a Tour","imageUrl":"<?php echo esc_url( $img ); ?>/clubhouse/clubhouse-1.jpg"} /-->

<!-- wp:group {"align":"full","className":"quick-contact-strip","lock":{"move":true,"remove":true}} -->
<div class="wp-block-group alignfull quick-contact-strip">
<!-- wp:group {"className":"container"} -->
<div class="wp-block-group container">
<!-- wp:group --><div class="wp-block-group">
<!-- wp:paragraph {"className":"qc-item__label"} --><p class="qc-item__label">Address</p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"qc-item__value"} --><p class="qc-item__value">500 Bluff View Circle, Bordentown NJ</p><!-- /wp:paragraph -->
</div><!-- /wp:group -->
<!-- wp:group --><div class="wp-block-group">
<!-- wp:paragraph {"className":"qc-item__label"} --><p class="qc-item__label">Phone</p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"qc-item__value"} --><p class="qc-item__value"><a href="tel:6092980303">609.298.0303</a></p><!-- /wp:paragraph -->
</div><!-- /wp:group -->
<!-- wp:group --><div class="wp-block-group">
<!-- wp:paragraph {"className":"qc-item__label"} --><p class="qc-item__label">Email</p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"qc-item__value"} --><p class="qc-item__value"><a href="mailto:rivergate@sterlingpropertiesnj.com">rivergate@sterlingpropertiesnj.com</a></p><!-- /wp:paragraph -->
</div><!-- /wp:group -->
<!-- wp:group --><div class="wp-block-group">
<!-- wp:paragraph {"className":"qc-item__label"} --><p class="qc-item__label">Office Hours</p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"qc-item__value"} --><p class="qc-item__value">Tue–Fri 10am–6pm · Sat 10am–5pm · Sun &amp; Mon Closed</p><!-- /wp:paragraph -->
</div><!-- /wp:group -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","align":"full","anchor":"contact","lock":{"move":true,"remove":true}} -->
<section class="wp-block-group alignfull" id="contact">
<!-- wp:group {"className":"container"} -->
<div class="wp-block-group container">
<!-- wp:group {"className":"contact-grid"} -->
<div class="wp-block-group contact-grid">
<!-- wp:group {"className":"contact-info"} -->
<div class="wp-block-group contact-info">
<!-- wp:paragraph {"className":"eyebrow"} --><p class="eyebrow">Get in Touch</p><!-- /wp:paragraph -->
<!-- wp:heading --><h2 class="wp-block-heading">We'd Love to Show You Around</h2><!-- /wp:heading -->
<!-- wp:separator {"className":"divider"} --><hr class="wp-block-separator has-alpha-channel-opacity divider"/><!-- /wp:separator -->
<!-- wp:paragraph {"className":"contact-label"} --><p class="contact-label">Address</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p>500 Bluff View Circle<br>Bordentown, NJ 08505</p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"contact-label"} --><p class="contact-label">Phone</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p><a href="tel:6092980303">609.298.0303</a></p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"contact-label"} --><p class="contact-label">Email</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p><a href="mailto:rivergate@sterlingpropertiesnj.com">rivergate@sterlingpropertiesnj.com</a></p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"contact-label"} --><p class="contact-label">Leasing Office Hours</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p>Tuesday – Friday: 10:00 AM – 6:00 PM<br>Saturday: 10:00 AM – 5:00 PM<br>Sunday &amp; Monday: Closed</p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"contact-label"} --><p class="contact-label">Apply Online</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p><a href="https://sterlingproperties.appfolio.com/listings?filters%5Bproperty_list%5D=RIVERGATE+BORDENTOWN" target="_blank" rel="noopener noreferrer">View Availability &amp; Apply on AppFolio →</a></p><!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:rivergate/contact-form {"lock":{"move":true,"remove":true}} /-->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
</section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","align":"full","lock":{"move":true,"remove":true}} -->
<section class="wp-block-group alignfull">
<!-- wp:group {"className":"container"} -->
<div class="wp-block-group container">
<!-- wp:html -->
<iframe class="contact-map" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3049.5!2d-74.7204!3d40.1386!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2s500+Bluff+View+Circle%2C+Bordentown%2C+NJ+08505!5e0!3m2!1sen!2sus!4v1" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Rivergate Bordentown — 500 Bluff View Circle, Bordentown NJ"></iframe>
<!-- /wp:html -->
</div>
<!-- /wp:group -->
</section>
<!-- /wp:group -->
