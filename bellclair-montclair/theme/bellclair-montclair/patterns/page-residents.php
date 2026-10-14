<?php
/**
 * Title: Bellclair Page — Residents Portal
 * Slug: bellclair-montclair/page-residents
 * Categories: bellclair
 * Description: Residents page — hero, portal quick access, resources, community guidelines, emergency contacts, CTA.
 * Inserter: true
 *
 * Unverified copy (docs/STATUS.md): quiet hours and the parking rules are
 * carried over from Rivergate, and "Maintenance Requests" points at AppFolio
 * (Rivergate uses Pilera) — the client is confirming both. The early lease
 * termination terms are the client's own (2026-10-14).
 */
$img   = get_template_directory_uri() . '/assets/images';
$apply = bellclair_apply_url();
$portal = 'https://sterlingproperties.appfolio.com/connect';
?>
<!-- wp:bellclair/page-hero {"align":"full","lock":{"move":true,"remove":true},"eyebrow":"Current Residents","heading":"Welcome Home","imageUrl":"<?php echo esc_url( $img ); ?>/interior/lobby.jpg","imageFocus":"center"} /-->

<!-- wp:group {"tagName":"section","align":"full","lock":{"move":true,"remove":true}} -->
<section class="wp-block-group alignfull">
<!-- wp:group {"className":"container"} -->
<div class="wp-block-group container">
<!-- wp:group {"className":"section-intro"} -->
<div class="wp-block-group section-intro">
<!-- wp:paragraph {"className":"eyebrow"} --><p class="eyebrow">Resident Services</p><!-- /wp:paragraph -->
<!-- wp:heading --><h2 class="wp-block-heading">Your Online Resident Portal</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>Everything you need to manage your home at Bellclair — from paying rent to submitting a maintenance request — is available through the Sterling Properties resident portal, powered by AppFolio.</p><!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:group {"className":"portal-grid"} -->
<div class="wp-block-group portal-grid">
<!-- wp:group {"className":"portal-card portal-card--primary"} -->
<div class="wp-block-group portal-card portal-card--primary">
<!-- wp:html --><svg class="portal-card__icon" width="36" height="36" viewBox="0 0 40 40" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><rect x="8" y="10" width="24" height="20" rx="2"/><path d="M14 20 h8 M18 16 v8"/></svg><!-- /wp:html -->
<!-- wp:heading {"level":3} --><h3 class="wp-block-heading">Pay Rent Online</h3><!-- /wp:heading -->
<!-- wp:paragraph {"className":"card__text"} --><p class="card__text">Set up autopay, make one-time payments and view your payment history — all in one place.</p><!-- /wp:paragraph -->
<!-- wp:buttons -->
<div class="wp-block-buttons">
<!-- wp:button {"className":"is-style-bellclair-ghost"} --><div class="wp-block-button is-style-bellclair-ghost"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( $portal ); ?>" target="_blank" rel="noopener noreferrer">Pay Rent →</a></div><!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
<!-- wp:group {"className":"portal-card portal-card--border"} -->
<div class="wp-block-group portal-card portal-card--border">
<!-- wp:html --><svg class="portal-card__icon" width="36" height="36" viewBox="0 0 40 40" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M10 14 h20 M10 20 h14 M10 26 h8"/><circle cx="30" cy="26" r="6"/><path d="M30 23 v3 l2 2"/></svg><!-- /wp:html -->
<!-- wp:heading {"level":3} --><h3 class="wp-block-heading">Maintenance Requests</h3><!-- /wp:heading -->
<!-- wp:paragraph {"className":"card__text"} --><p class="card__text">Submit and track maintenance requests online any time. Our on-site maintenance team is available 24/7.</p><!-- /wp:paragraph -->
<!-- wp:buttons -->
<div class="wp-block-buttons">
<!-- wp:button {"className":"is-style-bellclair-secondary"} --><div class="wp-block-button is-style-bellclair-secondary"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( $portal ); ?>" target="_blank" rel="noopener noreferrer">Submit Request →</a></div><!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
<!-- wp:group {"className":"portal-card portal-card--alt"} -->
<div class="wp-block-group portal-card portal-card--alt">
<!-- wp:html --><svg class="portal-card__icon" width="36" height="36" viewBox="0 0 40 40" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><rect x="8" y="8" width="24" height="28" rx="2"/><path d="M14 16 h12 M14 22 h12 M14 28 h8"/></svg><!-- /wp:html -->
<!-- wp:heading {"level":3} --><h3 class="wp-block-heading">Lease Documents</h3><!-- /wp:heading -->
<!-- wp:paragraph {"className":"card__text"} --><p class="card__text">Access your lease agreement, addenda and renewal documents securely through the resident portal.</p><!-- /wp:paragraph -->
<!-- wp:buttons -->
<div class="wp-block-buttons">
<!-- wp:button {"className":"is-style-bellclair-secondary"} --><div class="wp-block-button is-style-bellclair-secondary"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( $portal ); ?>" target="_blank" rel="noopener noreferrer">View Documents →</a></div><!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
<!-- wp:group {"className":"portal-card portal-card--border"} -->
<div class="wp-block-group portal-card portal-card--border">
<!-- wp:html --><svg class="portal-card__icon" width="36" height="36" viewBox="0 0 40 40" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><circle cx="20" cy="16" r="6"/><path d="M8 34 c0-8 24-8 24 0"/></svg><!-- /wp:html -->
<!-- wp:heading {"level":3} --><h3 class="wp-block-heading">Manage Your Account</h3><!-- /wp:heading -->
<!-- wp:paragraph {"className":"card__text"} --><p class="card__text">Update contact information, add authorized occupants and manage your account preferences.</p><!-- /wp:paragraph -->
<!-- wp:buttons -->
<div class="wp-block-buttons">
<!-- wp:button {"className":"is-style-bellclair-secondary"} --><div class="wp-block-button is-style-bellclair-secondary"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( $portal ); ?>" target="_blank" rel="noopener noreferrer">My Account →</a></div><!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
<!-- wp:group {"className":"portal-note"} -->
<div class="wp-block-group portal-note">
<!-- wp:paragraph --><p>The resident portal is powered by <strong>AppFolio Property Manager</strong>. If you do not have login credentials or are having trouble accessing your account, please contact the leasing office at <a href="tel:9736550997">973.655.0997</a> or <a href="mailto:bellclaire@spgnj.com">bellclaire@spgnj.com</a>.</p><!-- /wp:paragraph -->
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
<!-- wp:paragraph {"className":"eyebrow"} --><p class="eyebrow">Resident Resources</p><!-- /wp:paragraph -->
<!-- wp:heading --><h2 class="wp-block-heading">Helpful Information &amp; Documents</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>Quick access to the forms and information you may need during your residency at Bellclair.</p><!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:html -->
<div class="resource-list" style="margin-top:var(--space-8);max-width:740px;">
  <a href="<?php echo esc_url( $apply ); ?>" class="resource-item" target="_blank" rel="noopener noreferrer">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
    <span class="resource-item__text"><span class="resource-item__name">Rental Application</span><span class="resource-item__desc">Apply online for any available residence</span></span>
    <svg class="resource-item__arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M9 18l6-6-6-6"/></svg>
  </a>
  <a href="<?php echo esc_url( $portal ); ?>" class="resource-item" target="_blank" rel="noopener noreferrer">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
    <span class="resource-item__text"><span class="resource-item__name">Move-In / Move-Out Checklist</span><span class="resource-item__desc">Document your residence condition — available in the resident portal</span></span>
    <svg class="resource-item__arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M9 18l6-6-6-6"/></svg>
  </a>
  <a href="<?php echo esc_url( $portal ); ?>" class="resource-item" target="_blank" rel="noopener noreferrer">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
    <span class="resource-item__text"><span class="resource-item__name">Community Rules &amp; Regulations</span><span class="resource-item__desc">Parking, noise, pet and community guidelines — filed with your lease in the resident portal</span></span>
    <svg class="resource-item__arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M9 18l6-6-6-6"/></svg>
  </a>
  <a href="<?php echo esc_url( home_url( '/#contact' ) ); ?>" class="resource-item">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg>
    <span class="resource-item__text"><span class="resource-item__name">Lease Renewal Information</span><span class="resource-item__desc">Questions about renewing your lease? Contact the leasing office early.</span></span>
    <svg class="resource-item__arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M9 18l6-6-6-6"/></svg>
  </a>
  <a href="<?php echo esc_url( home_url( '/#contact' ) ); ?>" class="resource-item">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
    <span class="resource-item__text"><span class="resource-item__name">Renter's Insurance Information</span><span class="resource-item__desc">Requirements and recommendations — ask the leasing office</span></span>
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
<!-- wp:paragraph {"className":"eyebrow"} --><p class="eyebrow">Community Guidelines</p><!-- /wp:paragraph -->
<!-- wp:heading --><h2 class="wp-block-heading">Living at Bellclair</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>A brief overview of key community policies. Full details are in your lease agreement and community rules document.</p><!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:group {"className":"policy-grid"} -->
<div class="wp-block-group policy-grid">
<!-- wp:group {"className":"policy-block"} -->
<div class="wp-block-group policy-block">
<!-- wp:paragraph {"className":"policy-block__heading"} --><p class="policy-block__heading">Parking</p><!-- /wp:paragraph -->
<!-- wp:list --><ul class="wp-block-list"><!-- wp:list-item --><li>Garage and reserved on-site parking</li><!-- /wp:list-item --><!-- wp:list-item --><li>All vehicles must be registered with the leasing office</li><!-- /wp:list-item --><!-- wp:list-item --><li>Visitor parking available in designated areas</li><!-- /wp:list-item --><!-- wp:list-item --><li>No commercial vehicles or RVs</li><!-- /wp:list-item --></ul><!-- /wp:list -->
</div>
<!-- /wp:group -->
<!-- wp:group {"className":"policy-block"} -->
<div class="wp-block-group policy-block">
<!-- wp:paragraph {"className":"policy-block__heading"} --><p class="policy-block__heading">Pets</p><!-- /wp:paragraph -->
<!-- wp:list --><ul class="wp-block-list"><!-- wp:list-item --><li>Cats are welcome</li><!-- /wp:list-item --><!-- wp:list-item --><li>Dogs are not permitted</li><!-- /wp:list-item --><!-- wp:list-item --><li>Register your cat with the leasing office at move-in</li><!-- /wp:list-item --></ul><!-- /wp:list -->
</div>
<!-- /wp:group -->
<!-- wp:group {"className":"policy-block"} -->
<div class="wp-block-group policy-block">
<!-- wp:paragraph {"className":"policy-block__heading"} --><p class="policy-block__heading">Noise &amp; Quiet Hours</p><!-- /wp:paragraph -->
<!-- wp:list --><ul class="wp-block-list"><!-- wp:list-item --><li>Quiet hours: 10pm – 8am on weekdays</li><!-- /wp:list-item --><!-- wp:list-item --><li>Quiet hours: 11pm – 9am on weekends</li><!-- /wp:list-item --><!-- wp:list-item --><li>Be considerate of shared walls and common areas</li><!-- /wp:list-item --></ul><!-- /wp:list -->
</div>
<!-- /wp:group -->
<!-- wp:group {"className":"policy-block"} -->
<div class="wp-block-group policy-block">
<!-- wp:paragraph {"className":"policy-block__heading"} --><p class="policy-block__heading">Packages &amp; Mail</p><!-- /wp:paragraph -->
<!-- wp:list --><ul class="wp-block-list"><!-- wp:list-item --><li>Mailboxes are located in the lobby</li><!-- /wp:list-item --><!-- wp:list-item --><li>Package delivery instructions provided at move-in</li><!-- /wp:list-item --><!-- wp:list-item --><li>Large deliveries require advance notice to management</li><!-- /wp:list-item --></ul><!-- /wp:list -->
</div>
<!-- /wp:group -->
<!-- wp:group {"className":"policy-block"} -->
<div class="wp-block-group policy-block">
<!-- wp:paragraph {"className":"policy-block__heading"} --><p class="policy-block__heading">Maintenance</p><!-- /wp:paragraph -->
<!-- wp:list --><ul class="wp-block-list"><!-- wp:list-item --><li>Submit requests through the resident portal</li><!-- /wp:list-item --><!-- wp:list-item --><li>Emergency maintenance available 24/7 by phone</li><!-- /wp:list-item --><!-- wp:list-item --><li>Residents are responsible for reporting issues promptly</li><!-- /wp:list-item --></ul><!-- /wp:list -->
</div>
<!-- /wp:group -->
<!-- wp:group {"className":"policy-block"} -->
<div class="wp-block-group policy-block">
<!-- wp:paragraph {"className":"policy-block__heading"} --><p class="policy-block__heading">Move-Out &amp; Lease Termination</p><!-- /wp:paragraph -->
<!-- wp:list --><ul class="wp-block-list"><!-- wp:list-item --><li>60-day written notice required prior to vacating</li><!-- /wp:list-item --><!-- wp:list-item --><li>Early termination option: 60-day written notice plus a two-month rent penalty</li><!-- /wp:list-item --><!-- wp:list-item --><li>Rent is due through the 60-day notice period, in addition to the penalty</li><!-- /wp:list-item --><!-- wp:list-item --><li>Schedule a move-out inspection with the leasing office</li><!-- /wp:list-item --><!-- wp:list-item --><li>Return all keys and fobs on move-out day</li><!-- /wp:list-item --><!-- wp:list-item --><li>Security deposit returned per NJ state law</li><!-- /wp:list-item --></ul><!-- /wp:list -->
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
<!-- wp:paragraph {"className":"eyebrow"} --><p class="eyebrow">Emergency &amp; Contact Information</p><!-- /wp:paragraph -->
<!-- wp:heading --><h2 class="wp-block-heading">Who to Call</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>Keep these numbers handy for emergencies, maintenance and leasing questions.</p><!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:group {"className":"emergency-grid"} -->
<div class="wp-block-group emergency-grid">
<!-- wp:group {"className":"emergency-card emergency-card--urgent"} -->
<div class="wp-block-group emergency-card emergency-card--urgent">
<!-- wp:paragraph {"className":"emergency-card__label"} --><p class="emergency-card__label">Life-Threatening Emergency</p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"emergency-card__name"} --><p class="emergency-card__name">Police, Fire or Medical</p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"emergency-card__contact"} --><p class="emergency-card__contact"><a href="tel:911">911</a></p><!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:group {"className":"emergency-card emergency-card--urgent"} -->
<div class="wp-block-group emergency-card emergency-card--urgent">
<!-- wp:paragraph {"className":"emergency-card__label"} --><p class="emergency-card__label">Non-Emergency Police</p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"emergency-card__name"} --><p class="emergency-card__name">Montclair Police Department</p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"emergency-card__contact"} --><p class="emergency-card__contact"><a href="tel:9737441234">973.744.1234</a></p><!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:group {"className":"emergency-card"} -->
<div class="wp-block-group emergency-card">
<!-- wp:paragraph {"className":"emergency-card__label"} --><p class="emergency-card__label">After-Hours Maintenance</p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"emergency-card__name"} --><p class="emergency-card__name">Bellclair Emergency Line</p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"emergency-card__contact"} --><p class="emergency-card__contact"><a href="tel:9736550997">973.655.0997</a></p><!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:group {"className":"emergency-card"} -->
<div class="wp-block-group emergency-card">
<!-- wp:paragraph {"className":"emergency-card__label"} --><p class="emergency-card__label">Leasing Office</p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"emergency-card__name"} --><p class="emergency-card__name">691 Bloomfield Avenue</p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"emergency-card__contact"} --><p class="emergency-card__contact"><a href="tel:9736550997">973.655.0997</a></p><!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:group {"className":"emergency-card"} -->
<div class="wp-block-group emergency-card">
<!-- wp:paragraph {"className":"emergency-card__label"} --><p class="emergency-card__label">Gas Emergency</p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"emergency-card__name"} --><p class="emergency-card__name">PSE&amp;G — I Smell Gas</p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"emergency-card__contact"} --><p class="emergency-card__contact"><a href="tel:18008807734">1.800.880.7734</a></p><!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:group {"className":"emergency-card"} -->
<div class="wp-block-group emergency-card">
<!-- wp:paragraph {"className":"emergency-card__label"} --><p class="emergency-card__label">Electric Outage</p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"emergency-card__name"} --><p class="emergency-card__name">PSE&amp;G Outage Reporting</p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"emergency-card__contact"} --><p class="emergency-card__contact"><a href="tel:18004367734">1.800.436.7734</a></p><!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
</section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","align":"full","className":"cta-section deco-rules","lock":{"move":true,"remove":true}} -->
<section class="wp-block-group alignfull cta-section deco-rules">
<!-- wp:group {"className":"container"} -->
<div class="wp-block-group container">
<!-- wp:paragraph {"className":"eyebrow"} --><p class="eyebrow">Need Help?</p><!-- /wp:paragraph -->
<!-- wp:separator {"className":"divider"} --><hr class="wp-block-separator has-alpha-channel-opacity divider"/><!-- /wp:separator -->
<!-- wp:heading --><h2 class="wp-block-heading">We're Here for You</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>The Bellclair leasing and management team is on site in Montclair. Don't hesitate to reach out.</p><!-- /wp:paragraph -->
<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons">
<!-- wp:button {"className":"is-style-bellclair-primary"} --><div class="wp-block-button is-style-bellclair-primary"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/#contact' ) ); ?>">Contact the Leasing Office</a></div><!-- /wp:button -->
<!-- wp:button {"className":"is-style-bellclair-ghost"} --><div class="wp-block-button is-style-bellclair-ghost"><a class="wp-block-button__link wp-element-button" href="tel:9736550997">Call 973.655.0997</a></div><!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
</section>
<!-- /wp:group -->
