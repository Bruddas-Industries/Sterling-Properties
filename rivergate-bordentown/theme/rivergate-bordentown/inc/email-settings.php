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
 * which can be granted to one user without changing their role:
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
