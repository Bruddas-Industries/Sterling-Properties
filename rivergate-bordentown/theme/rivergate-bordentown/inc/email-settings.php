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
 * swaps that check for our own capability, which every Administrator has and
 * which can be granted to one user without changing their role: tick "Email
 * settings" on that user's profile (Users → All Users → Edit, on the property's
 * own dashboard, not Network Admin), or
 *
 *     wp user add-cap <login> sterling_manage_email --url=<domain>
 *     wp user remove-cap <login> sterling_manage_email --url=<domain>
 *
 * The grant is per site (WordPress keeps capabilities per site). One site is
 * enough to configure the network; grant it on others to read their logs.
 * The names are deliberately unprefixed: they mean the same thing on every
 * Sterling property, and every property theme must carry this file.
 *
 * @package Rivergate_Bordentown
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const RIVERGATE_EMAIL_CAP = 'sterling_manage_email';

add_filter( 'fluent_mail/manage_capability', static fn(): string => RIVERGATE_EMAIL_CAP );

// Administrators keep access: manage_options does not imply a custom capability.
add_filter( 'user_has_cap', static function ( array $allcaps ): array {
	if ( ! empty( $allcaps['manage_options'] ) ) {
		$allcaps[ RIVERGATE_EMAIL_CAP ] = true;
	}
	return $allcaps;
} );

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
// The grant as a checkbox on Users → Edit, for Administrators only
// ---------------------------------------------------------------------------

/** May the current user grant or revoke email access for $user_id? */
function rivergate_can_grant_email_access( int $user_id ): bool {
	return current_user_can( 'manage_options' ) && current_user_can( 'edit_user', $user_id );
}

add_action( 'edit_user_profile', static function ( WP_User $user ): void {
	if ( ! rivergate_can_grant_email_access( $user->ID ) ) {
		return;
	}
	$is_admin = user_can( $user, 'manage_options' );
	$granted  = $is_admin || ! empty( $user->caps[ RIVERGATE_EMAIL_CAP ] );
	?>
	<h2><?php esc_html_e( 'Email settings', 'rivergate-bordentown' ); ?></h2>
	<table class="form-table" role="presentation">
		<tr>
			<th scope="row"><?php esc_html_e( 'FluentSMTP', 'rivergate-bordentown' ); ?></th>
			<td>
				<input type="hidden" name="sterling_email_access_shown" value="1">
				<label for="sterling-manage-email">
					<input type="checkbox" id="sterling-manage-email" name="sterling_manage_email" value="1" <?php checked( $granted ); ?> <?php disabled( $is_admin ); ?>>
					<?php esc_html_e( 'Can manage email settings (Settings → FluentSMTP)', 'rivergate-bordentown' ); ?>
				</label>
				<p class="description">
					<?php
					echo $is_admin
						? esc_html__( 'Administrators always have this.', 'rivergate-bordentown' )
						: esc_html__( 'Opens only the FluentSMTP screen; the role stays as it is. The email settings are shared by every Sterling site, so access on one site is enough to configure them all.', 'rivergate-bordentown' );
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
	$user = get_userdata( $user_id );
	if ( ! $user || user_can( $user, 'manage_options' ) ) {
		return;
	}
	if ( ! empty( $_POST['sterling_manage_email'] ) ) {
		$user->add_cap( RIVERGATE_EMAIL_CAP );
	} else {
		$user->remove_cap( RIVERGATE_EMAIL_CAP );
	}
} );
