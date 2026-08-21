<?php
/**
 * Title: Rivergate Page — Residents Portal
 * Slug: rivergate-bordentown/page-residents
 * Categories: rivergate
 * Description: Residents page — hero, portal quick access, resources, community policies, emergency contacts.
 * Inserter: true
 */
$img = get_template_directory_uri() . '/assets/images';
$pdf = get_template_directory_uri() . '/assets/pdf';
?>
<!-- wp:rivergate/page-hero {"align":"full","lock":{"move":true,"remove":true},"eyebrow":"Current Residents","heading":"Welcome Home","imageUrl":"<?php echo esc_url( $img ); ?>/exterior/exterior-4.jpg"} /-->

<!-- wp:group {"tagName":"section","align":"full","lock":{"move":true,"remove":true}} -->
<section class="wp-block-group alignfull">
<!-- wp:group {"className":"container"} -->
<div class="wp-block-group container">
<!-- wp:group {"className":"section-intro"} -->
<div class="wp-block-group section-intro">
<!-- wp:paragraph {"className":"eyebrow"} --><p class="eyebrow">Resident Services</p><!-- /wp:paragraph -->
<!-- wp:heading --><h2 class="wp-block-heading">Your Online Resident Portal</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>Everything you need to manage your home at Rivergate — from paying rent to submitting a maintenance request — is available through our resident portal powered by AppFolio.</p><!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:group {"className":"portal-grid"} -->
<div class="wp-block-group portal-grid">
<!-- wp:group {"className":"portal-card portal-card--primary"} -->
<div class="wp-block-group portal-card portal-card--primary">
<!-- wp:html --><svg class="portal-card__icon" width="36" height="36" viewBox="0 0 40 40" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><rect x="8" y="10" width="24" height="20" rx="2"/><path d="M14 20 h8 M18 16 v8"/></svg><!-- /wp:html -->
<!-- wp:heading {"level":3} --><h3 class="wp-block-heading">Pay Rent Online</h3><!-- /wp:heading -->
<!-- wp:paragraph --><p>Set up autopay, make one-time payments, and view your payment history — all in one place.</p><!-- /wp:paragraph -->
<!-- wp:buttons -->
<div class="wp-block-buttons">
<!-- wp:button {"className":"is-style-rivergate-ghost"} --><div class="wp-block-button is-style-rivergate-ghost"><a class="wp-block-button__link wp-element-button" href="https://sterlingproperties.appfolio.com/connect" target="_blank" rel="noopener noreferrer">Pay Rent →</a></div><!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
<!-- wp:group {"className":"portal-card portal-card--border"} -->
<div class="wp-block-group portal-card portal-card--border">
<!-- wp:html --><svg class="portal-card__icon" width="36" height="36" viewBox="0 0 40 40" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M10 14 h20 M10 20 h14 M10 26 h8"/><circle cx="30" cy="26" r="6"/><path d="M30 23 v3 l2 2"/></svg><!-- /wp:html -->
<!-- wp:heading {"level":3} --><h3 class="wp-block-heading">Maintenance Requests</h3><!-- /wp:heading -->
<!-- wp:paragraph --><p>Submit and track maintenance requests online. Our team responds promptly to all service requests.</p><!-- /wp:paragraph -->
<!-- wp:buttons -->
<div class="wp-block-buttons">
<!-- wp:button {"className":"is-style-rivergate-secondary"} --><div class="wp-block-button is-style-rivergate-secondary"><a class="wp-block-button__link wp-element-button" href="https://app.pilera.com/index.php/login" target="_blank" rel="noopener noreferrer">Submit Request →</a></div><!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
<!-- wp:group {"className":"portal-card portal-card--alt"} -->
<div class="wp-block-group portal-card portal-card--alt">
<!-- wp:html --><svg class="portal-card__icon" width="36" height="36" viewBox="0 0 40 40" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><rect x="8" y="8" width="24" height="28" rx="2"/><path d="M14 16 h12 M14 22 h12 M14 28 h8"/></svg><!-- /wp:html -->
<!-- wp:heading {"level":3} --><h3 class="wp-block-heading">Lease Documents</h3><!-- /wp:heading -->
<!-- wp:paragraph --><p>Access your lease agreement, addenda, and renewal documents securely through the resident portal.</p><!-- /wp:paragraph -->
<!-- wp:buttons -->
<div class="wp-block-buttons">
<!-- wp:button {"className":"is-style-rivergate-secondary"} --><div class="wp-block-button is-style-rivergate-secondary"><a class="wp-block-button__link wp-element-button" href="https://sterlingproperties.appfolio.com/connect" target="_blank" rel="noopener noreferrer">View Documents →</a></div><!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
</section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","align":"full","className":"section--alt","lock":{"move":true,"remove":true}} -->
<section class="wp-block-group alignfull section--alt">
<!-- wp:group {"className":"container"} -->
<div class="wp-block-group container">
<!-- wp:group {"className":"section-intro"} -->
<div class="wp-block-group section-intro">
<!-- wp:paragraph {"className":"eyebrow"} --><p class="eyebrow">Helpful Links</p><!-- /wp:paragraph -->
<!-- wp:heading --><h2 class="wp-block-heading">Resident Resources</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>Quick access to the most commonly needed documents and information for Rivergate residents.</p><!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:html -->
<div class="resource-list" style="margin-top:var(--space-8);max-width:640px;">
  <a href="<?php echo esc_url( $pdf ); ?>/RivergateBrochure.pdf" class="resource-item" target="_blank" rel="noopener noreferrer">
    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
    <div class="resource-item__text"><span class="resource-item__name">Community Brochure</span><span class="resource-item__desc">PDF · Overview of Rivergate Bordentown</span></div>
    <svg class="resource-item__arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M7 17L17 7M7 7h10v10"/></svg>
  </a>
  <a href="<?php echo esc_url( $pdf ); ?>/Rivergate-Rental-Application.pdf" class="resource-item" target="_blank" rel="noopener noreferrer">
    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
    <div class="resource-item__text"><span class="resource-item__name">Rental Application</span><span class="resource-item__desc">PDF · Printable application form</span></div>
    <svg class="resource-item__arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M7 17L17 7M7 7h10v10"/></svg>
  </a>
  <a href="https://sterlingproperties.appfolio.com/connect" class="resource-item" target="_blank" rel="noopener noreferrer">
    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
    <div class="resource-item__text"><span class="resource-item__name">Move-In / Move-Out Checklist</span><span class="resource-item__desc">Document your residence condition — available in the resident portal</span></div>
    <svg class="resource-item__arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M7 17L17 7M7 7h10v10"/></svg>
  </a>
  <a href="https://sterlingproperties.appfolio.com/connect" class="resource-item" target="_blank" rel="noopener noreferrer">
    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
    <div class="resource-item__text"><span class="resource-item__name">Community Rules &amp; Regulations</span><span class="resource-item__desc">Parking, noise, pet, and community policy guidelines — filed with your lease in the resident portal</span></div>
    <svg class="resource-item__arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M7 17L17 7M7 7h10v10"/></svg>
  </a>
  <a href="<?php echo esc_url( home_url( '/#contact' ) ); ?>" class="resource-item">
    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg>
    <div class="resource-item__text"><span class="resource-item__name">Lease Renewal Information</span><span class="resource-item__desc">Questions about renewing your lease? Contact the leasing office early.</span></div>
    <svg class="resource-item__arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M9 18l6-6-6-6"/></svg>
  </a>
  <a href="<?php echo esc_url( home_url( '/#contact' ) ); ?>" class="resource-item">
    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
    <div class="resource-item__text"><span class="resource-item__name">Renter's Insurance Information</span><span class="resource-item__desc">Requirements and recommendations — ask the leasing office</span></div>
    <svg class="resource-item__arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M9 18l6-6-6-6"/></svg>
  </a>
</div>
<!-- /wp:html -->
</div>
<!-- /wp:group -->
</section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","align":"full","lock":{"move":true,"remove":true}} -->
<section class="wp-block-group alignfull">
<!-- wp:group {"className":"container"} -->
<div class="wp-block-group container">
<!-- wp:group {"className":"section-intro"} -->
<div class="wp-block-group section-intro">
<!-- wp:paragraph {"className":"eyebrow"} --><p class="eyebrow">Community Policies</p><!-- /wp:paragraph -->
<!-- wp:heading --><h2 class="wp-block-heading">Rules &amp; Guidelines</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>A summary of key community policies at Rivergate Bordentown. Your lease agreement contains the full terms.</p><!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:group {"className":"policy-grid"} -->
<div class="wp-block-group policy-grid">
<!-- wp:group {"className":"policy-block"} -->
<div class="wp-block-group policy-block">
<!-- wp:paragraph {"className":"policy-block__heading"} --><p class="policy-block__heading">Pet Policy</p><!-- /wp:paragraph -->
<!-- wp:list --><ul class="wp-block-list"><!-- wp:list-item --><li>Cats and dogs welcome</li><!-- /wp:list-item --><!-- wp:list-item --><li>2 pets maximum per unit</li><!-- /wp:list-item --><!-- wp:list-item --><li>Weight limit: 75 lbs per pet</li><!-- /wp:list-item --><!-- wp:list-item --><li>Monthly pet rent applies</li><!-- /wp:list-item --><!-- wp:list-item --><li>One-time pet fee required</li><!-- /wp:list-item --><!-- wp:list-item --><li>Restricted breeds — contact leasing</li><!-- /wp:list-item --></ul><!-- /wp:list -->
</div>
<!-- /wp:group -->
<!-- wp:group {"className":"policy-block"} -->
<div class="wp-block-group policy-block">
<!-- wp:paragraph {"className":"policy-block__heading"} --><p class="policy-block__heading">Parking</p><!-- /wp:paragraph -->
<!-- wp:list --><ul class="wp-block-list"><!-- wp:list-item --><li>Secured garage parking available</li><!-- /wp:list-item --><!-- wp:list-item --><li>Surface lot for residents and guests</li><!-- /wp:list-item --><!-- wp:list-item --><li>Monthly parking fee applies</li><!-- /wp:list-item --><!-- wp:list-item --><li>EV charging — contact for availability</li><!-- /wp:list-item --></ul><!-- /wp:list -->
</div>
<!-- /wp:group -->
<!-- wp:group {"className":"policy-block"} -->
<div class="wp-block-group policy-block">
<!-- wp:paragraph {"className":"policy-block__heading"} --><p class="policy-block__heading">Lease Terms</p><!-- /wp:paragraph -->
<!-- wp:list --><ul class="wp-block-list"><!-- wp:list-item --><li>12-month leases standard</li><!-- /wp:list-item --><!-- wp:list-item --><li>Short-term options available</li><!-- /wp:list-item --><!-- wp:list-item --><li>Renewal offered 90 days prior to expiration</li><!-- /wp:list-item --><!-- wp:list-item --><li>Month-to-month upon approved renewal</li><!-- /wp:list-item --></ul><!-- /wp:list -->
</div>
<!-- /wp:group -->
<!-- wp:group {"className":"policy-block"} -->
<div class="wp-block-group policy-block">
<!-- wp:paragraph {"className":"policy-block__heading"} --><p class="policy-block__heading">Community Rules</p><!-- /wp:paragraph -->
<!-- wp:list --><ul class="wp-block-list"><!-- wp:list-item --><li>Quiet hours: 10pm – 8am</li><!-- /wp:list-item --><!-- wp:list-item --><li>Pool: seasonal hours posted</li><!-- /wp:list-item --><!-- wp:list-item --><li>Guests: 14-night maximum per month</li><!-- /wp:list-item --><!-- wp:list-item --><li>Smoking prohibited in all common areas</li><!-- /wp:list-item --></ul><!-- /wp:list -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
</section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","align":"full","className":"section--alt","lock":{"move":true,"remove":true}} -->
<section class="wp-block-group alignfull section--alt">
<!-- wp:group {"className":"container"} -->
<div class="wp-block-group container">
<!-- wp:group {"className":"section-intro"} -->
<div class="wp-block-group section-intro">
<!-- wp:paragraph {"className":"eyebrow"} --><p class="eyebrow">Contacts</p><!-- /wp:paragraph -->
<!-- wp:heading --><h2 class="wp-block-heading">Emergency &amp; Management Contacts</h2><!-- /wp:heading -->
</div>
<!-- /wp:group -->
<!-- wp:group {"className":"emergency-grid"} -->
<div class="wp-block-group emergency-grid">
<!-- wp:group {"className":"emergency-card emergency-card--urgent"} -->
<div class="wp-block-group emergency-card emergency-card--urgent">
<!-- wp:paragraph {"className":"emergency-card__label"} --><p class="emergency-card__label">Life-Threatening Emergency</p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"emergency-card__name"} --><p class="emergency-card__name">Police / Fire / Medical</p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"emergency-card__contact"} --><p class="emergency-card__contact"><a href="tel:911">911</a></p><!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:group {"className":"emergency-card"} -->
<div class="wp-block-group emergency-card">
<!-- wp:paragraph {"className":"emergency-card__label"} --><p class="emergency-card__label">Leasing Office</p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"emergency-card__name"} --><p class="emergency-card__name">Rivergate Bordentown</p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"emergency-card__contact"} --><p class="emergency-card__contact"><a href="tel:6092980303">609.298.0303</a></p><!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:group {"className":"emergency-card"} -->
<div class="wp-block-group emergency-card">
<!-- wp:paragraph {"className":"emergency-card__label"} --><p class="emergency-card__label">Maintenance Emergency</p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"emergency-card__name"} --><p class="emergency-card__name">After-Hours Line</p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"emergency-card__contact"} --><p class="emergency-card__contact"><a href="tel:6092980303">609.298.0303</a></p><!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:group {"className":"emergency-card"} -->
<div class="wp-block-group emergency-card">
<!-- wp:paragraph {"className":"emergency-card__label"} --><p class="emergency-card__label">Property Management</p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"emergency-card__name"} --><p class="emergency-card__name">Sterling Properties</p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"emergency-card__contact"} --><p class="emergency-card__contact"><a href="mailto:info@sterlingpropertiesnj.com">Email Us</a></p><!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
</section>
<!-- /wp:group -->
