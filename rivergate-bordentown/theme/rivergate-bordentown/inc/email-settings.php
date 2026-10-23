<?php
/**
 * Who may configure outgoing email (Settings → FluentSMTP).
 *
 * FluentSMTP only lets users with manage_options (Administrators) open its
 * settings, but Sterling's IT contact is an Editor and should not get the rest
 * of the site's settings. FluentSMTP's `fluent_mail/manage_capability` filter
 * swaps that check for our own capability, which every Administrator has and
 * which can be granted to one user without changing their role:
 *
 *     wp user add-cap <login> sterling_manage_email --url=<domain>
 *     wp user remove-cap <login> sterling_manage_email --url=<domain>
 *
 * FluentSMTP keeps its settings per site, so both the grant and the SMTP
 * connection are per site. The capability name is deliberately unprefixed: it
 * means the same thing on every Sterling property.
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
