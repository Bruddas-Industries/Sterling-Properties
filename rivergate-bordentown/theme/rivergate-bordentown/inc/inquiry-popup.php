<?php
/**
 * General inquiry pop-up.
 *
 * A leasing-inquiry modal that opens shortly after page load, once per browser
 * session (the "once per session" part is enforced client-side in main.js via
 * sessionStorage). Rendered on wp_footer so it is available on every front-end
 * template without touching each one.
 *
 * Styles live in assets/css/global.css section 26 — shared with the static
 * preview in preview-1/, which uses the same markup.
 *
 * @package Rivergate_Bordentown
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Where inquiry submissions are emailed. Filterable so the address can be moved
 * without editing the theme.
 */
function rivergate_inquiry_recipient(): string {
	$default = 'rivergate@spgnj.com';
	$email   = (string) apply_filters( 'rivergate_inquiry_recipient', $default );
	return is_email( $email ) ? $email : get_option( 'admin_email' );
}

/**
 * Should the pop-up render on this request?
 *
 * Suppressed on the contact page, where the same inquiry form is the page's main
 * content, and on any non-front-end request.
 */
function rivergate_inquiry_popup_enabled(): bool {
	if ( is_admin() || is_feed() || is_robots() || wp_doing_ajax() ) {
		return false;
	}
	if ( is_page( 'contact' ) ) {
		return false;
	}
	return (bool) apply_filters( 'rivergate_inquiry_popup_enabled', true );
}

/**
 * Handle a pop-up submission. Returns 'sent', 'error', or '' when this request
 * is not an inquiry submission.
 */
function rivergate_handle_inquiry_submission(): string {
	if ( ! isset( $_POST['rg_inquiry_nonce'] ) ) {
		return '';
	}
	if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['rg_inquiry_nonce'] ) ), 'rg_inquiry_form' ) ) {
		return 'error';
	}

	$name  = sanitize_text_field( wp_unslash( $_POST['inq_name'] ?? '' ) );
	$email = sanitize_email( wp_unslash( $_POST['inq_email'] ?? '' ) );

	if ( '' === $name || ! is_email( $email ) ) {
		return 'error';
	}

	$body = sprintf(
		"Name: %s\nEmail: %s\nPhone: %s\nInterested In: %s\n\nSubmitted from: %s",
		$name,
		$email,
		sanitize_text_field( wp_unslash( $_POST['inq_phone'] ?? '' ) ),
		sanitize_text_field( wp_unslash( $_POST['inq_interest'] ?? '' ) ),
		esc_url_raw( (string) wp_get_referer() )
	);

	$sent = wp_mail(
		rivergate_inquiry_recipient(),
		sprintf( '%s — Website Inquiry', get_bloginfo( 'name' ) ),
		$body,
		[ 'Reply-To: ' . $name . ' <' . $email . '>' ]
	);

	return $sent ? 'sent' : 'error';
}

/**
 * Render the pop-up markup in the footer.
 */
function rivergate_render_inquiry_popup(): void {
	if ( ! rivergate_inquiry_popup_enabled() ) {
		return;
	}

	$state = rivergate_handle_inquiry_submission();
	$img   = get_template_directory_uri() . '/assets/images/exterior/exterior-entrance-sunset.jpg';
	$first = '';

	if ( 'sent' === $state ) {
		$parts = explode( ' ', trim( sanitize_text_field( wp_unslash( $_POST['inq_name'] ?? '' ) ) ) );
		$first = $parts[0];
	}

	// A submission re-renders the page, so open straight to the result state.
	$open = ( '' !== $state );
	?>
<div class="inq-popup<?php echo $open ? ' is-open' : ''; ?>" id="inq-popup"
     aria-hidden="<?php echo $open ? 'false' : 'true'; ?>"
     <?php echo $open ? 'data-inq-forced="1"' : ''; ?>>
  <div class="inq-popup__backdrop" data-inq-close></div>
  <div class="inq-popup__dialog" role="dialog" aria-modal="true" aria-labelledby="inq-popup-title">
    <button class="inq-popup__close" type="button" aria-label="<?php esc_attr_e( 'Close', 'rivergate-bordentown' ); ?>" data-inq-close>&times;</button>
    <div class="inq-popup__media">
      <img src="<?php echo esc_url( $img ); ?>" alt="" aria-hidden="true" loading="lazy">
    </div>
    <div class="inq-popup__body" id="inq-popup-body">
      <?php if ( 'sent' === $state ) : ?>
        <div class="inq-popup__thanks">
          <span class="eyebrow"><?php esc_html_e( 'Thank You', 'rivergate-bordentown' ); ?></span>
          <h2 class="inq-popup__title" id="inq-popup-title"><?php esc_html_e( 'We’ll be in touch shortly.', 'rivergate-bordentown' ); ?></h2>
          <p><?php
            printf(
              /* translators: %1$s: visitor's first name, %2$s: phone link markup. */
              esc_html__( 'Thanks, %1$s. A member of the Rivergate leasing team will reach out with pricing and availability. Need something sooner? Call %2$s.', 'rivergate-bordentown' ),
              esc_html( $first ),
              '<a href="tel:6092980303">609.298.0303</a>'
            );
          ?></p>
          <a class="btn btn--primary" href="<?php echo esc_url( home_url( '/availability/' ) ); ?>"><?php esc_html_e( 'See What’s Available', 'rivergate-bordentown' ); ?></a>
        </div>
      <?php else : ?>
        <span class="eyebrow"><?php esc_html_e( 'Now Leasing', 'rivergate-bordentown' ); ?></span>
        <h2 class="inq-popup__title" id="inq-popup-title"><?php esc_html_e( 'Let’s Find Your Rivergate Home', 'rivergate-bordentown' ); ?></h2>
        <p class="inq-popup__text"><?php esc_html_e( 'Tell us what you’re looking for and our leasing team will follow up with current pricing and availability.', 'rivergate-bordentown' ); ?></p>

        <?php if ( 'error' === $state ) : ?>
          <p class="inq-popup__error"><?php
            printf(
              /* translators: %s: phone link markup. */
              esc_html__( 'Something went wrong. Please try again, or call us at %s.', 'rivergate-bordentown' ),
              '<a href="tel:6092980303">609.298.0303</a>'
            );
          ?></p>
        <?php endif; ?>

        <form class="inq-popup__form" method="post" action="">
          <?php wp_nonce_field( 'rg_inquiry_form', 'rg_inquiry_nonce' ); ?>
          <div class="form-group">
            <label for="inq-name"><?php esc_html_e( 'Name', 'rivergate-bordentown' ); ?></label>
            <input type="text" id="inq-name" name="inq_name" placeholder="Jane Smith" autocomplete="name" required>
          </div>
          <div class="form-group">
            <label for="inq-email"><?php esc_html_e( 'Email Address', 'rivergate-bordentown' ); ?></label>
            <input type="email" id="inq-email" name="inq_email" placeholder="jane@example.com" autocomplete="email" required>
          </div>
          <div class="form-group">
            <label for="inq-phone"><?php esc_html_e( 'Phone Number', 'rivergate-bordentown' ); ?></label>
            <input type="tel" id="inq-phone" name="inq_phone" placeholder="(609) 000-0000" autocomplete="tel">
          </div>
          <div class="form-group">
            <label for="inq-interest"><?php esc_html_e( 'Interested In', 'rivergate-bordentown' ); ?></label>
            <select id="inq-interest" name="inq_interest">
              <option value=""><?php esc_html_e( 'Select a residence type…', 'rivergate-bordentown' ); ?></option>
              <option value="1br"><?php esc_html_e( 'One Bedroom', 'rivergate-bordentown' ); ?></option>
              <option value="2br"><?php esc_html_e( 'Two Bedroom', 'rivergate-bordentown' ); ?></option>
              <option value="unsure"><?php esc_html_e( 'Not Sure Yet', 'rivergate-bordentown' ); ?></option>
            </select>
          </div>
          <button type="submit" class="btn btn--primary"><?php esc_html_e( 'Send My Inquiry', 'rivergate-bordentown' ); ?></button>
        </form>

        <p class="inq-popup__fineprint"><?php
          printf(
            /* translators: %1$s: phone link markup, %2$s: email link markup. */
            esc_html__( 'Or reach us directly — %1$s · %2$s', 'rivergate-bordentown' ),
            '<a href="tel:6092980303">609.298.0303</a>',
            '<a href="mailto:rivergate@spgnj.com">rivergate@spgnj.com</a>'
          );
        ?></p>
      <?php endif; ?>
    </div>
  </div>
</div>
	<?php
}
add_action( 'wp_footer', 'rivergate_render_inquiry_popup' );
