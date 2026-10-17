<?php
/**
 * Create a page from one of the active theme's registered block patterns.
 *
 * Run through scripts/prod.sh insert-page (or locally with Local's WP-CLI):
 *   wp eval-file insert-page.php <pattern-slug> "<Title>" [slug=<page-slug>] [front] [draft] [apply] --url=<site>
 *
 *   front   also set the page as the site's static front page
 *   draft   create it as a draft instead of publishing
 *   apply   actually write; without it this is a dry run
 *
 * The page gets the Block Canvas (Full Width) template, and the content is saved with wp_slash()
 * and re-read to confirm it survived intact (OPERATIONS trap 10 — this is the step that put
 * "Pricing u0026 Online Applications" on the live Bellclair site).
 *
 * Remember trap 9: pattern PHP bakes absolute image URLs for the *current* site URL into the
 * content. Pages inserted while a site is on the staging host need the domain-cutover rewrite.
 */

if ( ! defined( 'WP_CLI' ) ) {
	exit;
}

$positional = array_values( array_filter( $args, function ( $a ) {
	return ! in_array( $a, array( 'front', 'draft', 'apply' ), true ) && 0 !== strpos( $a, 'slug=' );
} ) );
$pattern_slug = isset( $positional[0] ) ? $positional[0] : '';
$title        = isset( $positional[1] ) ? $positional[1] : '';
$page_slug    = '';
foreach ( $args as $a ) {
	if ( 0 === strpos( $a, 'slug=' ) ) {
		$page_slug = substr( $a, 5 );
	}
}
$front  = in_array( 'front', $args, true );
$status = in_array( 'draft', $args, true ) ? 'draft' : 'publish';
$apply  = in_array( 'apply', $args, true );

if ( '' === $pattern_slug || '' === $title ) {
	WP_CLI::error( 'Usage: insert-page.php <pattern-slug> "<Title>" [slug=<page-slug>] [front] [draft] [apply]' );
}

$registry = WP_Block_Patterns_Registry::get_instance();
$pattern  = $registry->get_registered( $pattern_slug );
if ( ! $pattern ) {
	$theme_slug = get_stylesheet();
	$mine       = array_filter( wp_list_pluck( $registry->get_all_registered(), 'name' ), function ( $n ) use ( $theme_slug ) {
		return 0 === strpos( $n, $theme_slug . '/' );
	} );
	WP_CLI::error( "No pattern \"$pattern_slug\" on " . home_url() . " (active theme: $theme_slug). Registered:\n  " . implode( "\n  ", $mine ) );
}

$content   = $pattern['content'];
$page_slug = $page_slug ? $page_slug : sanitize_title( $title );

if ( get_page_by_path( $page_slug ) ) {
	WP_CLI::error( "A page with the path /$page_slug/ already exists. Edit it instead (scripts/safe-edit.php) — re-inserting a pattern over a page would replace its content wholesale." );
}

$top = array_values( array_filter( wp_list_pluck( parse_blocks( $content ), 'blockName' ) ) );
WP_CLI::log( sprintf( 'Site: %s  |  theme: %s', home_url(), get_stylesheet() ) );
WP_CLI::log( sprintf( 'Page "%s" at /%s/ (%s%s) from %s: %d bytes, %d top-level blocks', $title, $page_slug, $status, $front ? ', front page' : '', $pattern_slug, strlen( $content ), count( $top ) ) );
WP_CLI::log( '  ' . implode( ', ', $top ) );

if ( ! $apply ) {
	WP_CLI::success( 'Dry run — nothing written. Re-run with "apply" to create the page.' );
	return;
}

kses_remove_filters(); // WP-CLI has no user, so kses would strip embeds from trusted theme markup.
$post_id = wp_insert_post(
	wp_slash(
		array(
			'post_type'     => 'page',
			'post_status'   => $status,
			'post_title'    => $title,
			'post_name'     => $page_slug,
			'post_content'  => $content,
			'page_template' => 'templates/page-canvas.php',
		)
	),
	true
);
if ( is_wp_error( $post_id ) ) {
	WP_CLI::error( 'Insert failed: ' . $post_id->get_error_message() );
}

clean_post_cache( $post_id );
if ( get_post( $post_id )->post_content !== $content ) {
	WP_CLI::error( "Page $post_id was created but its stored content differs from the pattern. Inspect it before going further." );
}

if ( $front ) {
	update_option( 'show_on_front', 'page' );
	update_option( 'page_on_front', $post_id );
}

WP_CLI::success( sprintf( 'Created page %d (%s)%s. Content verified.', $post_id, get_permalink( $post_id ), $front ? ', set as front page' : '' ) );
