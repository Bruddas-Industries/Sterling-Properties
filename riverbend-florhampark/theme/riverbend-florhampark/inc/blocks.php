<?php
/**
 * Block editor integration for Riverbend Florham Park.
 *
 * - Registers the theme's dynamic blocks (blocks/*) from their block.json.
 * - Adds a "Riverbend" block category + button block styles that map core
 *   buttons onto the theme's .btn appearance.
 * - Enqueues the no-build editor script (assets/js/blocks-editor.js).
 * - Registers a "Riverbend" pattern category (pattern files live in /patterns,
 *   auto-registered by WordPress 6.0+).
 * - Adds the .has-video-hero body class on pages that use riverbend/hero so the
 *   navigation goes transparent over the hero.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/../blocks/_shared.php';

/**
 * Register the dynamic blocks from their block.json directories.
 */
function riverbend_register_blocks(): void {
	$blocks = [
		// Homepage
		'hero', 'shuttle-schedule', 'amenities', 'floor-plans', 'location-stats', 'neighborhood-explorer', 'contact-form',
		// Inner pages
		'page-hero', 'availability-embed',
	];
	foreach ( $blocks as $block ) {
		register_block_type( get_template_directory() . '/blocks/' . $block );
	}
}
add_action( 'init', 'riverbend_register_blocks' );

/**
 * Add a "Riverbend" category to the block inserter.
 */
add_filter( 'block_categories_all', function ( array $categories ): array {
	array_unshift( $categories, [
		'slug'  => 'riverbend',
		'title' => __( 'Riverbend', 'riverbend-florhampark' ),
		'icon'  => null,
	] );
	return $categories;
} );

/**
 * Enqueue the editor-only script that supplies the dynamic blocks' edit UI.
 */
add_action( 'enqueue_block_editor_assets', function (): void {
	wp_enqueue_script(
		'riverbend-blocks-editor',
		get_template_directory_uri() . '/assets/js/blocks-editor.js',
		[ 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-server-side-render', 'wp-i18n' ],
		RIVERBEND_VERSION,
		true
	);
	wp_set_script_translations( 'riverbend-blocks-editor', 'riverbend-florhampark' );
} );

/**
 * Button block styles that map core buttons onto the theme's .btn appearance,
 * plus the "Eyebrow" paragraph style. Visual rules live in global.css.
 */
add_action( 'init', function (): void {
	register_block_style( 'core/button', [ 'name' => 'riverbend-primary',   'label' => __( 'Riverbend Primary',   'riverbend-florhampark' ) ] );
	register_block_style( 'core/button', [ 'name' => 'riverbend-secondary', 'label' => __( 'Riverbend Secondary', 'riverbend-florhampark' ) ] );
	register_block_style( 'core/button', [ 'name' => 'riverbend-ghost',     'label' => __( 'Riverbend Ghost (dark)', 'riverbend-florhampark' ) ] );
	register_block_style( 'core/paragraph', [ 'name' => 'riverbend-eyebrow', 'label' => __( 'Eyebrow', 'riverbend-florhampark' ) ] );
} );

/**
 * Register the "Riverbend" pattern category (priority 9 — before WordPress
 * auto-registers the theme's /patterns files on init).
 */
add_action( 'init', function (): void {
	if ( function_exists( 'register_block_pattern_category' ) ) {
		register_block_pattern_category( 'riverbend', [ 'label' => __( 'Riverbend', 'riverbend-florhampark' ) ] );
	}
}, 9 );

/**
 * Transparent-nav body class on any page whose content includes the hero block.
 */
add_filter( 'body_class', function ( array $classes ): array {
	if ( is_page() && has_block( 'riverbend/hero', get_queried_object() ) ) {
		$classes[] = 'has-video-hero';
	}
	return $classes;
} );
