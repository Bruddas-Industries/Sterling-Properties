<?php
/**
 * Block editor integration for Rivergate Bordentown.
 *
 * - Registers the theme's dynamic blocks (blocks/*) from their block.json.
 * - Adds a "Rivergate" block category + button block styles that map core
 *   buttons onto the theme's .btn appearance.
 * - Enqueues the no-build editor script (assets/js/blocks-editor.js).
 * - Registers a "Rivergate" pattern category (pattern files live in /patterns,
 *   auto-registered by WordPress 6.0+).
 * - Adds the .has-video-hero body class on pages that use rivergate/hero so the
 *   navigation goes transparent over the hero.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/../blocks/_shared.php';

/**
 * Register the dynamic blocks from their block.json directories.
 */
function rivergate_register_blocks(): void {
	$blocks = [
		// Homepage
		'hero', 'amenities', 'floor-plans', 'location-stats', 'contact-form',
		// Inner pages
		'page-hero', 'amenity-cards', 'plan-cards', 'neighborhood-explorer', 'availability-embed',
	];
	foreach ( $blocks as $block ) {
		register_block_type( get_template_directory() . '/blocks/' . $block );
	}
}
add_action( 'init', 'rivergate_register_blocks' );

/**
 * Add a "Rivergate" category to the block inserter.
 */
add_filter( 'block_categories_all', function ( array $categories ): array {
	array_unshift( $categories, [
		'slug'  => 'rivergate',
		'title' => __( 'Rivergate', 'rivergate-bordentown' ),
		'icon'  => null,
	] );
	return $categories;
} );

/**
 * Enqueue the editor-only script that supplies the dynamic blocks' edit UI.
 */
add_action( 'enqueue_block_editor_assets', function (): void {
	wp_enqueue_script(
		'rivergate-blocks-editor',
		get_template_directory_uri() . '/assets/js/blocks-editor.js',
		[ 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-server-side-render', 'wp-i18n' ],
		RIVERGATE_VERSION,
		true
	);
	wp_set_script_translations( 'rivergate-blocks-editor', 'rivergate-bordentown' );
} );

/**
 * Button block styles that map core buttons onto the theme's .btn appearance,
 * plus the "Eyebrow" paragraph style. Visual rules live in global.css.
 */
add_action( 'init', function (): void {
	register_block_style( 'core/button', [ 'name' => 'rivergate-primary',   'label' => __( 'Rivergate Primary',   'rivergate-bordentown' ) ] );
	register_block_style( 'core/button', [ 'name' => 'rivergate-secondary', 'label' => __( 'Rivergate Secondary', 'rivergate-bordentown' ) ] );
	register_block_style( 'core/button', [ 'name' => 'rivergate-ghost',     'label' => __( 'Rivergate Ghost (dark)', 'rivergate-bordentown' ) ] );
	register_block_style( 'core/paragraph', [ 'name' => 'rivergate-eyebrow', 'label' => __( 'Eyebrow', 'rivergate-bordentown' ) ] );
} );

/**
 * Register the "Rivergate" pattern category (priority 9 — before WordPress
 * auto-registers the theme's /patterns files on init).
 */
add_action( 'init', function (): void {
	if ( function_exists( 'register_block_pattern_category' ) ) {
		register_block_pattern_category( 'rivergate', [ 'label' => __( 'Rivergate', 'rivergate-bordentown' ) ] );
	}
}, 9 );

/**
 * Transparent-nav body class on any page whose content includes the hero block.
 */
add_filter( 'body_class', function ( array $classes ): array {
	if ( is_page() && has_block( 'rivergate/hero', get_queried_object() ) ) {
		$classes[] = 'has-video-hero';
	}
	return $classes;
} );
