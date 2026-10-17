<?php
/**
 * Edit one post's block content on a live site without corrupting it.
 *
 * Run through scripts/prod.sh (or locally with Local's WP-CLI):
 *   wp eval-file safe-edit.php <post-id> <spec> [apply] [allow-structure-change] --url=<site>
 *
 * <spec> is either
 *   .json  {"replacements": [{"find": "…", "replace": "…", "count": 1}, …]}
 *          each "find" must occur exactly "count" times (default 1), or nothing is written
 *   .html  the complete new post_content — e.g. `wp post get <id> --field=post_content`, edited
 *
 * Without "apply" it reports what would change and writes nothing. With "apply" it backs up the
 * current content to ~/premigration/edits/, saves with wp_slash() (OPERATIONS trap 10), re-reads
 * the post and confirms the stored content is byte-identical to what was intended.
 *
 * Refuses when the edit changes the page's top-level block sequence (unless
 * allow-structure-change), unbalances the block comments, adds content outside any block, or
 * adds a block-comment escape that has lost its backslash (`u0026` instead of `\u0026`). Those
 * are the failures that have put broken pages on the live sites before.
 */

if ( ! defined( 'WP_CLI' ) ) {
	exit;
}

$post_id   = isset( $args[0] ) ? (int) $args[0] : 0;
$spec_path = isset( $args[1] ) ? $args[1] : '';
$apply     = in_array( 'apply', $args, true );
$allow     = in_array( 'allow-structure-change', $args, true );

$post = get_post( $post_id );
if ( ! $post ) {
	WP_CLI::error( "No post $post_id on " . home_url() );
}
if ( ! is_readable( $spec_path ) ) {
	WP_CLI::error( "Cannot read spec $spec_path" );
}

WP_CLI::log( sprintf( 'Site: %s  |  Post %d: "%s" (%s, %s)', home_url(), $post->ID, $post->post_title, $post->post_type, $post->post_status ) );

$before = $post->post_content;
$after  = $before;

if ( preg_match( '/\.json$/i', $spec_path ) ) {
	$spec = json_decode( file_get_contents( $spec_path ), true );
	if ( ! is_array( $spec ) || empty( $spec['replacements'] ) ) {
		WP_CLI::error( 'Spec JSON needs a non-empty "replacements" array.' );
	}
	foreach ( $spec['replacements'] as $i => $r ) {
		$find     = (string) $r['find'];
		$expected = isset( $r['count'] ) ? (int) $r['count'] : 1;
		$found    = '' === $find ? 0 : substr_count( $after, $find );
		if ( $found !== $expected ) {
			WP_CLI::error( sprintf( 'Replacement %d: found %d occurrence(s) of the "find" text, expected %d. Nothing written.', $i + 1, $found, $expected ) );
		}
		$pos = strpos( $after, $find );
		WP_CLI::log( sprintf( "\n[%d] x%d  …%s«%s»%s…", $i + 1, $found, substr( $after, max( 0, $pos - 50 ), min( 50, $pos ) ), $find, substr( $after, $pos + strlen( $find ), 50 ) ) );
		WP_CLI::log( sprintf( '     -> «%s»', $r['replace'] ) );
		$after = str_replace( $find, (string) $r['replace'], $after );
	}
} else {
	$after = file_get_contents( $spec_path );
	WP_CLI::log( sprintf( 'Full replacement: %d -> %d bytes', strlen( $before ), strlen( $after ) ) );
}

if ( $after === $before ) {
	WP_CLI::error( 'The edit changes nothing.' );
}

/** Top-level block names, content outside blocks, and block-comment balance. */
function sterling_block_shape( $content ) {
	$top      = array();
	$freeform = 0;
	$walk     = function ( $blocks, $depth ) use ( &$walk, &$top, &$freeform ) {
		foreach ( $blocks as $b ) {
			if ( null === $b['blockName'] ) {
				if ( '' !== trim( $b['innerHTML'] ) ) {
					$freeform++;
				}
				continue;
			}
			if ( 0 === $depth ) {
				$top[] = $b['blockName'];
			}
			$walk( $b['innerBlocks'], $depth + 1 );
		}
	};
	$walk( parse_blocks( $content ), 0 );

	// Attribute JSON never contains a raw ">" (WordPress escapes it), so [^>] is safe here.
	$all     = preg_match_all( '/<!--\s+wp:/', $content );
	$selfies = preg_match_all( '/<!--\s+wp:[^>]*\/-->/', $content );
	$closers = preg_match_all( '/<!--\s+\/wp:/', $content );

	// Block-comment escapes that lost their backslash: u002d / u0026 / u003c / u003e / u0022.
	$stripped = preg_match_all( '/(?<!\\\\)u00(2d|26|3c|3e|22)/', $content );

	return array(
		'top'      => $top,
		'freeform' => $freeform,
		'balanced' => ( $all - $selfies ) === $closers,
		'stripped' => $stripped,
	);
}

$s0       = sterling_block_shape( $before );
$s1       = sterling_block_shape( $after );
$problems = array();

if ( $s0['top'] !== $s1['top'] && ! $allow ) {
	$problems[] = "Top-level blocks changed:\n  before: " . implode( ', ', $s0['top'] ) . "\n  after:  " . implode( ', ', $s1['top'] );
}
if ( ! $s1['balanced'] ) {
	$problems[] = 'Block comments are unbalanced after the edit (an opener or closer was dropped).';
}
if ( $s1['freeform'] > $s0['freeform'] ) {
	$problems[] = sprintf( 'Content outside any block went from %d to %d chunk(s).', $s0['freeform'], $s1['freeform'] );
}
if ( $s1['stripped'] > $s0['stripped'] ) {
	$problems[] = sprintf( 'Unescaped "u00xx" sequences went from %d to %d — a block-comment escape lost its backslash (trap 10).', $s0['stripped'], $s1['stripped'] );
}

if ( $problems ) {
	WP_CLI::error( "Refusing to write:\n- " . implode( "\n- ", $problems ) );
}
WP_CLI::log( sprintf( "\nStructure check passed: %d top-level blocks, balanced, no new freeform content.", count( $s1['top'] ) ) );

if ( ! $apply ) {
	WP_CLI::success( 'Dry run — nothing written. Re-run with "apply" to save.' );
	return;
}

$dir = rtrim( getenv( 'HOME' ), '/' ) . '/premigration/edits';
wp_mkdir_p( $dir );
$backup = sprintf( '%s/blog%d-post%d-%s.html', $dir, get_current_blog_id(), $post_id, gmdate( 'Ymd-His' ) );
if ( false === file_put_contents( $backup, $before ) ) {
	WP_CLI::error( "Could not write backup $backup — nothing saved." );
}

// WP-CLI runs with no user, so kses would filter the content (iframes, embeds). This is trusted markup.
kses_remove_filters();
$result = wp_update_post( wp_slash( array( 'ID' => $post_id, 'post_content' => $after ) ), true );
if ( is_wp_error( $result ) ) {
	WP_CLI::error( 'Save failed: ' . $result->get_error_message() . " (backup: $backup)" );
}

clean_post_cache( $post_id );
$saved = get_post( $post_id )->post_content;
if ( $saved !== $after ) {
	WP_CLI::error( "Saved content differs from what was intended. Restore with:\n  wp post update $post_id $backup --url=" . home_url() );
}

WP_CLI::success( "Saved and verified. Backup: $backup\nNext: purge Varnish (scripts/prod.sh purge <domain>) and record the change in docs/STATUS.md." );
