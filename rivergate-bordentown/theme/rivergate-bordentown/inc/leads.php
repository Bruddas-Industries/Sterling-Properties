<?php
/**
 * Website leads.
 *
 * Every form submission (the tour form in blocks/contact-form and the inquiry
 * pop-up in inc/inquiry-popup.php) is saved as a private Lead *before* it is
 * emailed, so an email that fails, bounces or is filtered as spam does not lose
 * the lead: it is listed under Leads in wp-admin with its delivery result.
 *
 * Also holds the spam checks both forms share — a honeypot field and a minimum
 * fill time. They replace a nonce, which protected nothing on a public form and
 * would expire inside a page Varnish had cached for more than a day. Submissions
 * that trip a check are saved straight to the Trash rather than dropped, so a
 * false positive can still be found (and restored) until WordPress empties the
 * Trash after 30 days.
 *
 * @package Rivergate_Bordentown
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Submissions faster than this many seconds after page load are treated as bots. */
const RIVERGATE_LEAD_MIN_SECONDS = 3;

function rivergate_register_lead_type(): void {
	// Leads hold visitors' contact details, so only editors and admins see them:
	// every lead has no author, so editing one maps to edit_others_posts.
	register_post_type( 'rg_lead', [
		'labels'          => [
			'name'               => __( 'Leads', 'rivergate-bordentown' ),
			'singular_name'      => __( 'Lead', 'rivergate-bordentown' ),
			'all_items'          => __( 'All Leads', 'rivergate-bordentown' ),
			'edit_item'          => __( 'Lead', 'rivergate-bordentown' ),
			'search_items'       => __( 'Search Leads', 'rivergate-bordentown' ),
			'not_found'          => __( 'No leads yet.', 'rivergate-bordentown' ),
			'not_found_in_trash' => __( 'No leads in the Trash.', 'rivergate-bordentown' ),
		],
		'public'          => false,
		'show_ui'         => true,
		'show_in_menu'    => true,
		'show_in_rest'    => false,
		'menu_icon'       => 'dashicons-email-alt',
		'supports'        => [ 'title' ],
		'capability_type' => 'post',
		'capabilities'    => [
			'create_posts' => 'do_not_allow',
			'edit_posts'   => 'edit_others_posts',
		],
		'map_meta_cap'    => true,
		'has_archive'     => false,
		'rewrite'         => false,
		'query_var'       => false,
	] );
}
add_action( 'init', 'rivergate_register_lead_type' );


// ---------------------------------------------------------------------------
// Spam checks
// ---------------------------------------------------------------------------

/**
 * Print the honeypot and timing fields. $form keeps the honeypot's id unique when
 * the tour form and the pop-up share a page. Styles: global.css `.form-hp`;
 * main.js section 15 fills in the timing.
 */
function rivergate_lead_trap_fields( string $form ): void {
	$id = 'hp-' . sanitize_key( $form );
	?>
  <div class="form-hp" aria-hidden="true">
    <label for="<?php echo esc_attr( $id ); ?>">Leave this field empty</label>
    <input type="text" id="<?php echo esc_attr( $id ); ?>" name="rg_website" value="" tabindex="-1" autocomplete="off">
  </div>
  <input type="hidden" name="rg_elapsed" value="" data-form-elapsed>
	<?php
}

/**
 * Did this submission trip a spam check? A missing timing value is allowed, so a
 * visitor without JavaScript can still get through.
 */
function rivergate_lead_is_spam(): bool {
	if ( '' !== trim( (string) wp_unslash( $_POST['rg_website'] ?? '' ) ) ) {
		return true;
	}
	$elapsed = (string) wp_unslash( $_POST['rg_elapsed'] ?? '' );
	return '' !== $elapsed && (int) $elapsed < RIVERGATE_LEAD_MIN_SECONDS;
}


// ---------------------------------------------------------------------------
// Saving and sending
// ---------------------------------------------------------------------------

/**
 * Save a submission as a Lead, then email it. Returns true when the visitor
 * should see the success message: the email was sent, or the submission was
 * caught as spam (a bot is never told it was caught).
 *
 * @param string $form    Short form name: 'tour' or 'inquiry'.
 * @param string $label   Human label, used in the lead title and email subject.
 * @param string $to      Recipient address.
 * @param array  $fields  Label => value, already sanitised. Must include 'Name'
 *                        and may include 'Email' (used for Reply-To).
 */
function rivergate_submit_lead( string $form, string $label, string $to, array $fields ): bool {
	$spam  = rivergate_lead_is_spam();
	$name  = trim( (string) ( $fields['Name'] ?? '' ) );
	$email = (string) ( $fields['Email'] ?? '' );
	$body  = implode( "\n", array_map(
		static fn( $k, $v ) => $k . ': ' . $v,
		array_keys( $fields ),
		$fields
	) );

	// wp_insert_post() and update_post_meta() unslash what they are given.
	$lead_id = wp_insert_post( wp_slash( [
		'post_type'    => 'rg_lead',
		'post_status'  => 'private',
		'post_author'  => 0,
		'post_title'   => sprintf( '%s — %s', '' !== $name ? $name : __( '(no name)', 'rivergate-bordentown' ), $label ),
		'post_content' => $body,
		'meta_input'   => [
			'_rg_lead_form'      => $form,
			'_rg_lead_fields'    => $fields,
			'_rg_lead_recipient' => $to,
			'_rg_lead_delivery'  => $spam ? 'blocked' : 'pending',
		],
	] ) );
	$lead_id = is_int( $lead_id ) ? $lead_id : 0;

	if ( $spam ) {
		if ( $lead_id ) {
			wp_trash_post( $lead_id );
		}
		return true;
	}

	$headers = is_email( $email ) ? [ 'Reply-To: ' . ( '' !== $name ? $name . ' ' : '' ) . '<' . $email . '>' ] : [];
	$error   = '';
	$catch   = static function ( WP_Error $e ) use ( &$error ) {
		$error = $e->get_error_message();
	};

	add_action( 'wp_mail_failed', $catch );
	$sent = wp_mail( $to, sprintf( '%s — %s', get_bloginfo( 'name' ), $label ), $body, $headers );
	remove_action( 'wp_mail_failed', $catch );

	if ( $lead_id ) {
		update_post_meta( $lead_id, '_rg_lead_delivery', $sent ? 'sent' : 'failed' );
		if ( '' !== $error ) {
			update_post_meta( $lead_id, '_rg_lead_error', wp_slash( $error ) );
		}
	}

	return $sent;
}


// ---------------------------------------------------------------------------
// wp-admin: list columns and the read-only lead view
// ---------------------------------------------------------------------------

function rivergate_lead_delivery_label( int $post_id ): string {
	$to = (string) get_post_meta( $post_id, '_rg_lead_recipient', true );
	switch ( get_post_meta( $post_id, '_rg_lead_delivery', true ) ) {
		case 'sent':
			/* translators: %s: recipient email address. */
			return sprintf( __( 'Emailed to %s', 'rivergate-bordentown' ), $to );
		case 'failed':
			/* translators: %s: recipient email address. */
			return sprintf( __( 'FAILED to email %s', 'rivergate-bordentown' ), $to );
		case 'blocked':
			return __( 'Caught as spam — not emailed', 'rivergate-bordentown' );
		default:
			return __( 'Not emailed', 'rivergate-bordentown' );
	}
}

add_filter( 'manage_rg_lead_posts_columns', static function ( array $cols ): array {
	$date = $cols['date'] ?? null;
	unset( $cols['date'] );
	$cols['rg_form']     = __( 'Form', 'rivergate-bordentown' );
	$cols['rg_contact']  = __( 'Contact', 'rivergate-bordentown' );
	$cols['rg_delivery'] = __( 'Email', 'rivergate-bordentown' );
	if ( $date ) {
		$cols['date'] = $date;
	}
	return $cols;
} );

add_action( 'manage_rg_lead_posts_custom_column', static function ( string $col, int $post_id ): void {
	$fields = (array) get_post_meta( $post_id, '_rg_lead_fields', true );
	switch ( $col ) {
		case 'rg_form':
			echo 'inquiry' === get_post_meta( $post_id, '_rg_lead_form', true )
				? esc_html__( 'Inquiry pop-up', 'rivergate-bordentown' )
				: esc_html__( 'Tour request', 'rivergate-bordentown' );
			break;
		case 'rg_contact':
			echo esc_html( implode( ' · ', array_filter( [ $fields['Email'] ?? '', $fields['Phone'] ?? '' ] ) ) );
			break;
		case 'rg_delivery':
			echo esc_html( rivergate_lead_delivery_label( $post_id ) );
			break;
	}
}, 10, 2 );

// Leads are records, not drafts: no Quick Edit.
add_filter( 'post_row_actions', static function ( array $actions, WP_Post $post ): array {
	if ( 'rg_lead' === $post->post_type ) {
		unset( $actions['inline hide-if-no-js'] );
	}
	return $actions;
}, 10, 2 );

add_action( 'add_meta_boxes_rg_lead', static function (): void {
	add_meta_box( 'rg_lead_details', __( 'Submission', 'rivergate-bordentown' ), 'rivergate_render_lead_box', 'rg_lead', 'normal', 'high' );
} );

function rivergate_render_lead_box( WP_Post $post ): void {
	$fields = (array) get_post_meta( $post->ID, '_rg_lead_fields', true );
	$error  = (string) get_post_meta( $post->ID, '_rg_lead_error', true );
	echo '<table class="widefat striped"><tbody>';
	foreach ( $fields as $label => $value ) {
		$value = (string) $value;
		if ( 'Email' === $label && is_email( $value ) ) {
			$cell = '<a href="mailto:' . esc_attr( $value ) . '">' . esc_html( $value ) . '</a>';
		} elseif ( 'Phone' === $label && '' !== $value ) {
			$cell = '<a href="tel:' . esc_attr( preg_replace( '/[^0-9+]/', '', $value ) ) . '">' . esc_html( $value ) . '</a>';
		} else {
			$cell = nl2br( esc_html( $value ) );
		}
		printf( '<tr><th style="width:10em">%s</th><td>%s</td></tr>', esc_html( $label ), $cell ); // phpcs:ignore WordPress.Security.EscapeOutput
	}
	printf(
		'<tr><th>%s</th><td>%s%s</td></tr>',
		esc_html__( 'Delivery', 'rivergate-bordentown' ),
		esc_html( rivergate_lead_delivery_label( $post->ID ) ),
		'' !== $error ? '<br><code>' . esc_html( $error ) . '</code>' : ''
	);
	echo '</tbody></table>';
}
