<?php
/**
 * Title: Riverbend Page — Residents Portal
 * Slug: riverbend-florhampark/page-residents
 * Categories: riverbend
 * Description: Residents page — hero, portal quick access, resources, community guidelines, emergency contacts, CTA.
 * Inserter: true
 *
 * Every guideline line comes from the old site, the portfolio page, the AppFolio
 * listings or Nina (pets and shuttle, 2026-10-22); no rules have been invented.
 * Quiet hours, parking rules, packages and move-out terms are waiting on the
 * client (docs/STATUS.md → Copy to confirm). Police and utility numbers were
 * looked up 2026-10-20 (sources in STATUS).
 */
$img   = get_template_directory_uri() . '/assets/images';
$apply = riverbend_apply_url();
$portal = 'https://sterlingproperties.appfolio.com/connect';
?>
<!-- wp:riverbend/page-hero {"align":"full","lock":{"move":true,"remove":true},"eyebrow":"Current Residents","heading":"Welcome Home","imageUrl":"<?php echo esc_url( $img ); ?>/clubhouse/lounge-seating.jpg","imageFocus":"center"} /-->

<!-- wp:group {"tagName":"section","align":"full","lock":{"move":true,"remove":true}} -->
<section class="wp-block-group alignfull">
<!-- wp:group {"className":"container"} -->
<div class="wp-block-group container">
<!-- wp:group {"className":"section-intro"} -->
<div class="wp-block-group section-intro">
<!-- wp:paragraph {"className":"eyebrow"} --><p class="eyebrow">Resident Services</p><!-- /wp:paragraph -->
<!-- wp:heading --><h2 class="wp-block-heading">Your Online Resident Portal</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>Everything you need to manage your home at Riverbend — from paying rent to submitting a maintenance request — is available through the Sterling Properties resident portal, powered by AppFolio.</p><!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:group {"className":"portal-grid"} -->
<div class="wp-block-group portal-grid">
<!-- wp:group {"className":"portal-card portal-card--primary"} -->
<div class="wp-block-group portal-card portal-card--primary">
<!-- wp:html --><svg class="portal-card__icon" width="36" height="36" viewBox="0 0 40 40" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><rect x="8" y="10" width="24" height="20" rx="2"/><path d="M14 20 h8 M18 16 v8"/></svg><!-- /wp:html -->
<!-- wp:heading {"level":3} --><h3 class="wp-block-heading">Pay Rent Online</h3><!-- /wp:heading -->
<!-- wp:paragraph {"className":"card__text"} --><p class="card__text">View your balance and set up one-time or recurring payments — all in one place.</p><!-- /wp:paragraph -->
<!-- wp:buttons -->
<div class="wp-block-buttons">
<!-- wp:button {"className":"is-style-riverbend-ghost"} --><div class="wp-block-button is-style-riverbend-ghost"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( $portal ); ?>" target="_blank" rel="noopener noreferrer">Pay Rent →</a></div><!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
<!-- wp:group {"className":"portal-card portal-card--border"} -->
<div class="wp-block-group portal-card portal-card--border">
<!-- wp:html --><svg class="portal-card__icon" width="36" height="36" viewBox="0 0 40 40" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M10 14 h20 M10 20 h14 M10 26 h8"/><circle cx="30" cy="26" r="6"/><path d="M30 23 v3 l2 2"/></svg><!-- /wp:html -->
<!-- wp:heading {"level":3} --><h3 class="wp-block-heading">Maintenance Requests</h3><!-- /wp:heading -->
<!-- wp:paragraph {"className":"card__text"} --><p class="card__text">Report heating, air conditioning, electrical, plumbing or other issues online any time, and our onsite team will follow up.</p><!-- /wp:paragraph -->
<!-- wp:buttons -->
<div class="wp-block-buttons">
<!-- wp:button {"className":"is-style-riverbend-secondary"} --><div class="wp-block-button is-style-riverbend-secondary"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( $portal ); ?>" target="_blank" rel="noopener noreferrer">Submit Request →</a></div><!-- /wp:button -->
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
<!-- wp:button {"className":"is-style-riverbend-secondary"} --><div class="wp-block-button is-style-riverbend-secondary"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( $portal ); ?>" target="_blank" rel="noopener noreferrer">View Documents →</a></div><!-- /wp:button -->
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
<!-- wp:button {"className":"is-style-riverbend-secondary"} --><div class="wp-block-button is-style-riverbend-secondary"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( $portal ); ?>" target="_blank" rel="noopener noreferrer">My Account →</a></div><!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
<!-- wp:group {"className":"portal-note"} -->
<div class="wp-block-group portal-note">
<!-- wp:paragraph --><p>The resident portal is powered by <strong>AppFolio Property Manager</strong>. If you do not have login credentials or are having trouble accessing your account, please contact the leasing office at <a href="tel:9736650050">973.665.0050</a> or <a href="mailto:riverbend@spgnj.com">riverbend@spgnj.com</a>.</p><!-- /wp:paragraph -->
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
<!-- wp:heading --><h2 class="wp-block-heading">Helpful Information &amp; Links</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>Quick access to the forms and information you may need during your residency at Riverbend.</p><!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:html -->
<div class="resource-list" style="margin-top:var(--space-8);max-width:740px;">
  <a href="<?php echo esc_url( $apply ); ?>" class="resource-item" target="_blank" rel="noopener noreferrer">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
    <span class="resource-item__text"><span class="resource-item__name">Rental Application</span><span class="resource-item__desc">Apply online for any available residence</span></span>
    <svg class="resource-item__arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M9 18l6-6-6-6"/></svg>
  </a>
  <a href="<?php echo esc_url( home_url( '/#shuttle' ) ); ?>" class="resource-item">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><rect x="5" y="3.5" width="14" height="14" rx="3"/><path d="M5 11h14M8 17.5 6 21M16 17.5l2 3.5"/></svg>
    <span class="resource-item__text"><span class="resource-item__name">Chatham Station Shuttle</span><span class="resource-item__desc">Weekday morning and evening courtesy shuttle — see the full schedule</span></span>
    <svg class="resource-item__arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M9 18l6-6-6-6"/></svg>
  </a>
  <a href="<?php echo esc_url( $portal ); ?>" class="resource-item" target="_blank" rel="noopener noreferrer">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
    <span class="resource-item__text"><span class="resource-item__name">Community Rules &amp; Regulations</span><span class="resource-item__desc">Parking, pet and community guidelines — filed with your lease in the resident portal</span></span>
    <svg class="resource-item__arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M9 18l6-6-6-6"/></svg>
  </a>
  <a href="<?php echo esc_url( home_url( '/#contact' ) ); ?>" class="resource-item">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg>
    <span class="resource-item__text"><span class="resource-item__name">Lease Renewal Information</span><span class="resource-item__desc">Questions about renewing your lease? Contact the leasing office early.</span></span>
    <svg class="resource-item__arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M9 18l6-6-6-6"/></svg>
  </a>
  <a href="https://www.florhamparkboro.net/" class="resource-item" target="_blank" rel="noopener noreferrer">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M3 21h18M5 21V10l7-5 7 5v11"/><path d="M9 21v-6h6v6"/></svg>
    <span class="resource-item__text"><span class="resource-item__name">Borough of Florham Park</span><span class="resource-item__desc">Town services, schools, parks and recreation</span></span>
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
<!-- wp:heading --><h2 class="wp-block-heading">Living at Riverbend</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>A brief overview of key community services. Full details are in your lease agreement and community rules document.</p><!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:group {"className":"policy-grid"} -->
<div class="wp-block-group policy-grid">
<!-- wp:group {"className":"policy-block"} -->
<div class="wp-block-group policy-block">
<!-- wp:paragraph {"className":"policy-block__heading"} --><p class="policy-block__heading">Garages &amp; Parking</p><!-- /wp:paragraph -->
<!-- wp:list --><ul class="wp-block-list"><!-- wp:list-item --><li>Private garages with remote electric door openers</li><!-- /wp:list-item --><!-- wp:list-item --><li>Electric-vehicle charging on site</li><!-- /wp:list-item --><!-- wp:list-item --><li>Ask the leasing office about guest parking</li><!-- /wp:list-item --></ul><!-- /wp:list -->
</div>
<!-- /wp:group -->
<!-- wp:group {"className":"policy-block"} -->
<div class="wp-block-group policy-block">
<!-- wp:paragraph {"className":"policy-block__heading"} --><p class="policy-block__heading">Pets</p><!-- /wp:paragraph -->
<!-- wp:list --><ul class="wp-block-list"><!-- wp:list-item --><li>Cats and dogs welcome, up to two pets per home</li><!-- /wp:list-item --><!-- wp:list-item --><li>Dogs: $500 non-refundable deposit, plus $50 a month</li><!-- /wp:list-item --><!-- wp:list-item --><li>Cats: $250 non-refundable deposit, plus $25 a month</li><!-- /wp:list-item --><!-- wp:list-item --><li>Ask the leasing office for the full pet policy</li><!-- /wp:list-item --></ul><!-- /wp:list -->
</div>
<!-- /wp:group -->
<!-- wp:group {"className":"policy-block"} -->
<div class="wp-block-group policy-block">
<!-- wp:paragraph {"className":"policy-block__heading"} --><p class="policy-block__heading">Chatham Station Shuttle</p><!-- /wp:paragraph -->
<!-- wp:list --><ul class="wp-block-list"><!-- wp:list-item --><li>Complimentary courtesy shuttle on weekdays</li><!-- /wp:list-item --><!-- wp:list-item --><li>Mornings from the front of the Gate House, 5:22 to 8:27 AM</li><!-- /wp:list-item --><!-- wp:list-item --><li>Evenings from Chatham station every 15 minutes, 5:30 to 8:30 PM</li><!-- /wp:list-item --></ul><!-- /wp:list -->
</div>
<!-- /wp:group -->
<!-- wp:group {"className":"policy-block"} -->
<div class="wp-block-group policy-block">
<!-- wp:paragraph {"className":"policy-block__heading"} --><p class="policy-block__heading">Maintenance</p><!-- /wp:paragraph -->
<!-- wp:list --><ul class="wp-block-list"><!-- wp:list-item --><li>Submit requests through the resident portal</li><!-- /wp:list-item --><!-- wp:list-item --><li>Emergency maintenance available 24/7</li><!-- /wp:list-item --><!-- wp:list-item --><li>Report issues promptly so the onsite team can help</li><!-- /wp:list-item --></ul><!-- /wp:list -->
</div>
<!-- /wp:group -->
<!-- wp:group {"className":"policy-block"} -->
<div class="wp-block-group policy-block">
<!-- wp:paragraph {"className":"policy-block__heading"} --><p class="policy-block__heading">Grounds &amp; Snow Removal</p><!-- /wp:paragraph -->
<!-- wp:list --><ul class="wp-block-list"><!-- wp:list-item --><li>Professionally landscaped grounds</li><!-- /wp:list-item --><!-- wp:list-item --><li>Snow removal in winter</li><!-- /wp:list-item --></ul><!-- /wp:list -->
</div>
<!-- /wp:group -->
<!-- wp:group {"className":"policy-block"} -->
<div class="wp-block-group policy-block">
<!-- wp:paragraph {"className":"policy-block__heading"} --><p class="policy-block__heading">Clubhouse &amp; Pool</p><!-- /wp:paragraph -->
<!-- wp:list --><ul class="wp-block-list"><!-- wp:list-item --><li>Renovated clubhouse lounge and fitness center for residents</li><!-- /wp:list-item --><!-- wp:list-item --><li>Outdoor pool, sun patio and BBQ area in season</li><!-- /wp:list-item --><!-- wp:list-item --><li>Ask the leasing office for current hours</li><!-- /wp:list-item --></ul><!-- /wp:list -->
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
<!-- wp:paragraph {"className":"emergency-card__label"} --><p class="emergency-card__label">Police (Non-Emergency)</p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"emergency-card__name"} --><p class="emergency-card__name">Florham Park Police Dispatch</p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"emergency-card__contact"} --><p class="emergency-card__contact"><a href="tel:9733772206">973.377.2206</a></p><!-- /wp:paragraph -->
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
<!-- wp:paragraph {"className":"emergency-card__name"} --><p class="emergency-card__name">JCP&amp;L Outage Reporting</p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"emergency-card__contact"} --><p class="emergency-card__contact"><a href="tel:18885444877">1.888.544.4877</a></p><!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:group {"className":"emergency-card"} -->
<div class="wp-block-group emergency-card">
<!-- wp:paragraph {"className":"emergency-card__label"} --><p class="emergency-card__label">Maintenance &amp; Leasing</p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"emergency-card__name"} --><p class="emergency-card__name">Riverbend Leasing Office</p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"emergency-card__contact"} --><p class="emergency-card__contact"><a href="tel:9736650050">973.665.0050</a></p><!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
</section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","align":"full","className":"cta-section wave-texture","lock":{"move":true,"remove":true}} -->
<section class="wp-block-group alignfull cta-section wave-texture">
<!-- wp:group {"className":"container"} -->
<div class="wp-block-group container">
<!-- wp:paragraph {"className":"eyebrow"} --><p class="eyebrow">Need Help?</p><!-- /wp:paragraph -->
<!-- wp:separator {"className":"divider"} --><hr class="wp-block-separator has-alpha-channel-opacity divider"/><!-- /wp:separator -->
<!-- wp:heading --><h2 class="wp-block-heading">We're Here for You</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>The Riverbend leasing and management team is on site in Florham Park. Don't hesitate to reach out.</p><!-- /wp:paragraph -->
<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons">
<!-- wp:button {"className":"is-style-riverbend-primary"} --><div class="wp-block-button is-style-riverbend-primary"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/#contact' ) ); ?>">Contact the Leasing Office</a></div><!-- /wp:button -->
<!-- wp:button {"className":"is-style-riverbend-ghost"} --><div class="wp-block-button is-style-riverbend-ghost"><a class="wp-block-button__link wp-element-button" href="tel:9736650050">Call 973.665.0050</a></div><!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
</section>
<!-- /wp:group -->
