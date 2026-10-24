<?php
/**
 * Outgoing email (FluentSMTP): one configuration for the whole network, and
 * who may change it.
 *
 * ONE CONFIGURATION. FluentSMTP keeps its settings per site, in the
 * `fluentmail-settings` option. Every Sterling property sends through the same
 * mailbox, so reads and writes of that option are redirected to a network
 * option of the same name: whoever saves FluentSMTP on any property configures
 * all of them, including properties added later. Until someone saves, each site
 * falls back to its own (empty) option, so nothing changes before then. Secrets
 * are encrypted with LOGGED_IN_KEY, which all sites share, so they decrypt
 * everywhere. Email logs stay per site.
 *
 * WHO. FluentSMTP only lets users with manage_options (Administrators) open its
 * settings, but Sterling's IT contact is an Editor and should not get the rest
 * of the site's settings. FluentSMTP's `fluent_mail/manage_capability` filter
 * swaps that check for our own capability, `sterling_manage_email`, which every
 * Administrator has. Anyone else gets it from one network-wide switch: Network
 * Admin → Users → the user → "Can manage email settings" (super admins only).
 * That stores user meta, which multisite shares across sites, so it applies on
 * every property the user belongs to, including ones added later. By WP-CLI:
 *
 *     wp user meta update <login> sterling_manage_email 1
 *     wp user meta delete <login> sterling_manage_email
 *
 * WHICH CONNECTION. FluentSMTP holds one connection per property and picks one
 * by the message's From address; anything unmatched goes out through the
 * default connection, under that property's sender. WordPress's own From is
 * wordpress@<domain>, which matches nothing, so every message from this site
 * is sent as RIVERGATE_MAIL_FROM (functions.php). Each new property needs its own
 * connection in FluentSMTP, and its theme's constant set to that sender.
 *
 * The switch never changes the user's role. Network Admin runs on the main
 * site's theme, which is a property theme, so the checkbox appears there. The
 * names are deliberately unprefixed: they mean the same thing on every Sterling
 * property, and every property theme must carry this file.
 *
 * @package Rivergate_Bordentown
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const RIVERGATE_EMAIL_CAP = 'sterling_manage_email';

add_filter( 'wp_mail_from', static fn(): string => RIVERGATE_MAIL_FROM );

add_filter( 'fluent_mail/manage_capability', static fn(): string => RIVERGATE_EMAIL_CAP );

// Administrators always have it (manage_options does not imply a custom
// capability), and so does anyone switched on network-wide.
add_filter( 'user_has_cap', static function ( array $allcaps, array $caps, array $args, WP_User $user ): array {
	if ( ! empty( $allcaps['manage_options'] ) || get_user_meta( $user->ID, RIVERGATE_EMAIL_CAP, true ) ) {
		$allcaps[ RIVERGATE_EMAIL_CAP ] = true;
	}
	return $allcaps;
}, 10, 4 );

if ( is_multisite() ) {
	add_filter( 'pre_option_fluentmail-settings', static function ( $pre ) {
		$shared = get_site_option( 'fluentmail-settings' );
		return false === $shared ? $pre : $shared;
	} );

	// Save to the network copy. The site's own copy is still written, which is
	// harmless (nothing reads it while the network copy exists) and keeps
	// update_option() returning what FluentSMTP expects.
	add_filter( 'pre_update_option_fluentmail-settings', static function ( $value ) {
		update_site_option( 'fluentmail-settings', $value );
		return $value;
	} );
}


// ---------------------------------------------------------------------------
// The switch as a checkbox on Network Admin → Users → Edit
// ---------------------------------------------------------------------------

/**
 * May the current user flip the switch for $user_id? Super admins, in Network
 * Admin. (On a single-site install, Administrators on the normal Users screen.)
 */
function rivergate_can_grant_email_access( int $user_id ): bool {
	if ( ! current_user_can( 'edit_user', $user_id ) ) {
		return false;
	}
	return is_multisite()
		? is_network_admin() && current_user_can( 'manage_network_users' )
		: current_user_can( 'manage_options' );
}

add_action( 'edit_user_profile', static function ( WP_User $user ): void {
	if ( ! rivergate_can_grant_email_access( $user->ID ) ) {
		return;
	}
	$always  = is_super_admin( $user->ID ) || ( ! is_multisite() && user_can( $user, 'manage_options' ) );
	$granted = $always || (bool) get_user_meta( $user->ID, RIVERGATE_EMAIL_CAP, true );
	?>
	<h2><?php esc_html_e( 'Email settings', 'rivergate-bordentown' ); ?></h2>
	<table class="form-table" role="presentation">
		<tr>
			<th scope="row"><?php esc_html_e( 'FluentSMTP', 'rivergate-bordentown' ); ?></th>
			<td>
				<input type="hidden" name="sterling_email_access_shown" value="1">
				<label for="sterling-manage-email">
					<input type="checkbox" id="sterling-manage-email" name="sterling_manage_email" value="1" <?php checked( $granted ); ?> <?php disabled( $always ); ?>>
					<?php esc_html_e( 'Can manage email settings (Settings → FluentSMTP)', 'rivergate-bordentown' ); ?>
				</label>
				<p class="description">
					<?php
					echo $always
						? esc_html__( 'Super admins always have this.', 'rivergate-bordentown' )
						: esc_html__( 'Applies on every site this user belongs to. Opens only the FluentSMTP screen; their role on each site stays as it is. Site Administrators always have this.', 'rivergate-bordentown' );
					?>
				</p>
			</td>
		</tr>
	</table>
	<?php
} );

// Core has already checked this form's nonce (update-user_{id}) before this runs.
add_action( 'edit_user_profile_update', static function ( int $user_id ): void {
	if ( empty( $_POST['sterling_email_access_shown'] ) || ! rivergate_can_grant_email_access( $user_id ) ) {
		return;
	}
	if ( ! empty( $_POST['sterling_manage_email'] ) ) {
		update_user_meta( $user_id, RIVERGATE_EMAIL_CAP, 1 );
	} else {
		delete_user_meta( $user_id, RIVERGATE_EMAIL_CAP );
	}
} );
