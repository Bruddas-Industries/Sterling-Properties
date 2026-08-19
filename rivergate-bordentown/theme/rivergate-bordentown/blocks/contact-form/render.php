<?php
/**
 * rivergate/contact-form — tour-request form with wp_mail handling.
 *
 * @var array $attributes Block attributes.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Floor-plan options come from the same source as the plan blocks, so the list
// stays in sync (and stays ordered smallest -> largest by square footage).
$fp_plans = rivergate_get_plans();
$fp_one   = array_filter( $fp_plans, static fn( $p ) => (int) $p['bed'] === 1 );
$fp_two   = array_filter( $fp_plans, static fn( $p ) => (int) $p['bed'] >= 2 );

$recipient = ! empty( $attributes['recipientEmail'] ) && is_email( $attributes['recipientEmail'] )
	? $attributes['recipientEmail']
	: get_option( 'admin_email' );

// Handle submission (front end only; ignored during editor REST preview).
$sent  = false;
$error = false;

if ( ! rivergate_in_editor()
	&& isset( $_POST['rg_contact_nonce'] )
	&& wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['rg_contact_nonce'] ) ), 'rg_contact_form' ) ) {

	$subject = sprintf( '%s — Tour Request', get_bloginfo( 'name' ) );
	$body    = sprintf(
		"Name: %s %s\nEmail: %s\nPhone: %s\nInterested In: %s\nDesired Move-In: %s\nMessage: %s",
		sanitize_text_field( wp_unslash( $_POST['first_name'] ?? '' ) ),
		sanitize_text_field( wp_unslash( $_POST['last_name'] ?? '' ) ),
		sanitize_email( wp_unslash( $_POST['email'] ?? '' ) ),
		sanitize_text_field( wp_unslash( $_POST['phone'] ?? '' ) ),
		sanitize_text_field( wp_unslash( $_POST['unit_type'] ?? '' ) ),
		sanitize_text_field( wp_unslash( $_POST['move_date'] ?? '' ) ),
		sanitize_textarea_field( wp_unslash( $_POST['message'] ?? '' ) )
	);
	$sent = wp_mail( $recipient, $subject, $body );
	if ( ! $sent ) {
		$error = true;
	}
}
?>
<form class="contact-form" method="post" action="#contact" aria-label="Contact form">
  <?php wp_nonce_field( 'rg_contact_form', 'rg_contact_nonce' ); ?>

  <?php if ( $sent ) : ?>
    <div style="background:var(--color-bg-alt);border:1px solid var(--color-border);border-radius:var(--radius-base);padding:var(--space-5);color:var(--color-text);">
      <strong>Message sent!</strong> Our leasing team will be in touch soon.
    </div>
  <?php elseif ( $error ) : ?>
    <div style="background:#FFF5F5;border:1px solid #C62828;border-radius:var(--radius-base);padding:var(--space-5);color:#C62828;">
      Something went wrong. Please call us at <a href="tel:6092980303">609.298.0303</a>.
    </div>
  <?php endif; ?>

  <div class="form-row">
    <div class="form-group">
      <label for="first-name">First Name</label>
      <input type="text" id="first-name" name="first_name" placeholder="Jane" autocomplete="given-name" required>
    </div>
    <div class="form-group">
      <label for="last-name">Last Name</label>
      <input type="text" id="last-name" name="last_name" placeholder="Smith" autocomplete="family-name" required>
    </div>
  </div>
  <div class="form-group">
    <label for="email">Email Address</label>
    <input type="email" id="email" name="email" placeholder="jane@example.com" autocomplete="email" required>
  </div>
  <div class="form-group">
    <label for="phone">Phone Number</label>
    <input type="tel" id="phone" name="phone" placeholder="(609) 000-0000" autocomplete="tel">
  </div>
  <div class="form-group">
    <label for="unit-type">Interested In</label>
    <select id="unit-type" name="unit_type">
      <option value="">Select a floor plan...</option>
      <?php if ( $fp_one ) : ?>
        <optgroup label="One Bedroom">
          <?php foreach ( $fp_one as $slug => $p ) : ?>
            <option value="<?php echo esc_attr( '1br-' . $slug ); ?>"><?php echo esc_html( $p['name'] . ' — ' . $p['sqft'] . ' sq ft' ); ?></option>
          <?php endforeach; ?>
        </optgroup>
      <?php endif; ?>
      <?php if ( $fp_two ) : ?>
        <optgroup label="Two Bedroom">
          <?php foreach ( $fp_two as $slug => $p ) : ?>
            <option value="<?php echo esc_attr( '2br-' . $slug ); ?>"><?php echo esc_html( $p['name'] . ' — ' . $p['sqft'] . ' sq ft' ); ?></option>
          <?php endforeach; ?>
        </optgroup>
      <?php endif; ?>
      <option value="unsure">Not Sure Yet</option>
    </select>
  </div>
  <div class="form-group">
    <label for="move-date">Desired Move-In</label>
    <input type="text" id="move-date" name="move_date" placeholder="e.g. August 2026">
  </div>
  <div class="form-group">
    <label for="message">Message (optional)</label>
    <textarea id="message" name="message" placeholder="Any questions or requests for the leasing team..."></textarea>
  </div>
  <button type="submit" class="btn btn--primary" style="align-self:flex-start;">Send Message</button>
</form>
