<?php
/**
 * Shared helpers for Bellclair dynamic blocks.
 *
 * These functions were extracted from the legacy templates/page-home.php so the
 * same data + markup can be rendered by block render.php files (front end) and
 * by ServerSideRender previews (block editor).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Are we rendering inside the block editor (ServerSideRender REST request)?
 * Used to suppress scroll-reveal hooks so previews aren't blank (main.js, which
 * adds the .is-visible class, does not run in the editor canvas).
 */
function bellclair_in_editor(): bool {
	return defined( 'REST_REQUEST' ) && REST_REQUEST;
}

/**
 * Returns the ` data-animate` attribute string for front-end output, or an
 * empty string in the editor (where [data-animate] would stay opacity:0).
 *
 * @param string $variant Optional reveal variant: '', 'fade', 'left', 'right', 'scale'.
 */
function bellclair_anim( string $variant = '' ): string {
	if ( bellclair_in_editor() ) {
		return '';
	}
	return $variant ? ' data-animate="' . esc_attr( $variant ) . '"' : ' data-animate';
}

/**
 * Icon data for amenities, keyed by the amenity_icon_key ACF value.
 *
 * Bellclair's own amenities use the preview's 24px line icons (stroked, to sit
 * with the deco type). The remaining keys are Font Awesome Free 6 solid glyphs
 * inherited from Rivergate (Icons: CC BY 4.0), kept so every choice in the
 * amenity_icon_key select still renders something.
 *
 * @return array<string,array{view:string,path:string,stroke?:bool}>
 */
function bellclair_amenity_icon_map(): array {
	return [
		// Bellclair line icons (24x24, stroked).
		'fitness'     => [ 'view' => '0 0 24 24', 'stroke' => true, 'path' => '<path d="M6.5 6.5v11M17.5 6.5v11M3.5 9.5v5M20.5 9.5v5M6.5 12h11"/>' ],
		'lock'        => [ 'view' => '0 0 24 24', 'stroke' => true, 'path' => '<rect x="5" y="11" width="14" height="10" rx="1"/><path d="M8 11V7.5a4 4 0 0 1 8 0V11"/><path d="M12 15v2.5"/>' ],
		'garage'      => [ 'view' => '0 0 24 24', 'stroke' => true, 'path' => '<path d="M3 21V9.5L12 4l9 5.5V21"/><path d="M7 21v-8.5h10V21M7 15.5h10M7 18.5h10"/>' ],
		'leasing'     => [ 'view' => '0 0 24 24', 'stroke' => true, 'path' => '<circle cx="8" cy="15" r="4"/><path d="M10.8 12.2 19 4M15.5 7.5l2.5 2.5M13.5 9.5l2 2"/>' ],
		'ev'          => [ 'view' => '0 0 24 24', 'stroke' => true, 'path' => '<rect x="4" y="3" width="10" height="18" rx="1"/><path d="M9.6 7.5 7.6 11.5h3l-2 4"/><path d="M14 9h2.5a1.5 1.5 0 0 1 1.5 1.5v6a1.5 1.5 0 0 0 3 0V7.5L19 5.5"/>' ],
		'cat'         => [ 'view' => '0 0 24 24', 'stroke' => true, 'path' => '<path d="M5 20.5v-8.2L4 4.5l4.2 3.3h7.6L20 4.5l-1 7.8v8.2z"/><path d="M9.5 13.2v.1M14.5 13.2v.1M11 16.5l1 .8 1-.8"/>' ],
		'maintenance' => [ 'view' => '0 0 24 24', 'stroke' => true, 'path' => '<path d="M14.7 6.3a4 4 0 0 0-5.2 5.2L3.8 17.2a1.5 1.5 0 0 0 2.1 2.1l5.7-5.7a4 4 0 0 0 5.2-5.2l-2.5 2.5-2.1-.5-.5-2.1z"/>' ],
		// Inherited Font Awesome solid glyphs.
		'pool'          => [ 'view' => '0 0 576 512', 'path' => '<path d="M128 127.7C128 74.9 170.9 32 223.7 32c48.3 0 89 36 95 83.9l1 8.2c2.2 17.5-10.2 33.5-27.8 35.7s-33.5-10.2-35.7-27.8l-1-8.2c-2-15.9-15.5-27.8-31.5-27.8c-17.5 0-31.7 14.2-31.7 31.7l0 96.3 192 0 0-96.3C384 74.9 426.9 32 479.7 32c48.3 0 89 36 95 83.9l1 8.2c2.2 17.5-10.2 33.5-27.8 35.7s-33.5-10.2-35.7-27.8l-1-8.2c-2-15.9-15.5-27.8-31.5-27.8c-17.5 0-31.7 14.2-31.7 31.7L448 361c-1.6 1-3.3 2-4.8 3.1c-18 12.4-40.1 20.3-59.2 20.3c0 0 0 0 0 0l0-96.5-192 0 0 96.5c-19 0-41.2-7.9-59.1-20.3c-1.6-1.1-3.2-2.2-4.9-3.1l0-233.3zM306.5 389.9C329 405.4 356.5 416 384 416c26.9 0 55.4-10.8 77.4-26.1c0 0 0 0 0 0c11.9-8.5 28.1-7.8 39.2 1.7c14.4 11.9 32.5 21 50.6 25.2c17.2 4 27.9 21.2 23.9 38.4s-21.2 27.9-38.4 23.9c-24.5-5.7-44.9-16.5-58.2-25C449.5 469.7 417 480 384 480c-31.9 0-60.6-9.9-80.4-18.9c-5.8-2.7-11.1-5.3-15.6-7.7c-4.5 2.4-9.7 5.1-15.6 7.7c-19.8 9-48.5 18.9-80.4 18.9c-33 0-65.5-10.3-94.5-25.8c-13.4 8.4-33.7 19.3-58.2 25c-17.2 4-34.4-6.7-38.4-23.9s6.7-34.4 23.9-38.4c18.1-4.2 36.2-13.3 50.6-25.2c11.1-9.4 27.3-10.1 39.2-1.7c0 0 0 0 0 0C136.7 405.2 165.1 416 192 416c27.5 0 55-10.6 77.5-26.1c11.1-7.9 25.9-7.9 37 0z"/>' ],
		'clubhouse'     => [ 'view' => '0 0 640 512', 'path' => '<path d="M64 160C64 89.3 121.3 32 192 32l256 0c70.7 0 128 57.3 128 128l0 33.6c-36.5 7.4-64 39.7-64 78.4l0 48-384 0 0-48c0-38.7-27.5-71-64-78.4L64 160zM544 272c0-20.9 13.4-38.7 32-45.3c5-1.8 10.4-2.7 16-2.7c26.5 0 48 21.5 48 48l0 176c0 17.7-14.3 32-32 32l-32 0c-17.7 0-32-14.3-32-32L96 448c0 17.7-14.3 32-32 32l-32 0c-17.7 0-32-14.3-32-32L0 272c0-26.5 21.5-48 48-48c5.6 0 11 1 16 2.7c18.6 6.6 32 24.4 32 45.3l0 48 0 32 32 0 384 0 32 0 0-32 0-48z"/>' ],
		'bbq'           => [ 'view' => '0 0 640 512', 'path' => '<path d="M345.7 48.3L358 34.5c5.4-6.1 13.3-8.8 20.9-8.9c7.2 0 14.3 2.6 19.9 7.8c19.7 18.3 39.8 43.2 55 70.6C469 131.2 480 162.2 480 192.2C480 280.8 408.7 352 320 352c-89.6 0-160-71.3-160-159.8c0-37.3 16-73.4 36.8-104.5c20.9-31.3 47.5-59 70.9-80.2C273.4 2.3 280.7-.2 288 0c14.1 .3 23.8 11.4 32.7 21.6c0 0 0 0 0 0c2 2.3 4 4.6 6 6.7l19 19.9zM384 240.2c0-36.5-37-73-54.8-88.4c-5.4-4.7-13.1-4.7-18.5 0C293 167.1 256 203.6 256 240.2c0 35.3 28.7 64 64 64s64-28.7 64-64zM32 288c0-17.7 14.3-32 32-32l32 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l0 64 448 0 0-64c-17.7 0-32-14.3-32-32s14.3-32 32-32l32 0c17.7 0 32 14.3 32 32l0 96c17.7 0 32 14.3 32 32l0 64c0 17.7-14.3 32-32 32L32 512c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l0-96zM320 480a32 32 0 1 0 0-64 32 32 0 1 0 0 64zm160-32a32 32 0 1 0 -64 0 32 32 0 1 0 64 0zM192 480a32 32 0 1 0 0-64 32 32 0 1 0 0 64z"/>' ],
		'dogrun'        => [ 'view' => '0 0 576 512', 'path' => '<path d="M309.6 158.5L332.7 19.8C334.6 8.4 344.5 0 356.1 0c7.5 0 14.5 3.5 19 9.5L392 32l52.1 0c12.7 0 24.9 5.1 33.9 14.1L496 64l56 0c13.3 0 24 10.7 24 24l0 24c0 44.2-35.8 80-80 80l-32 0-16 0-21.3 0-5.1 30.5-112-64zM416 256.1L416 480c0 17.7-14.3 32-32 32l-32 0c-17.7 0-32-14.3-32-32l0-115.2c-24 12.3-51.2 19.2-80 19.2s-56-6.9-80-19.2L160 480c0 17.7-14.3 32-32 32l-32 0c-17.7 0-32-14.3-32-32l0-230.2c-28.8-10.9-51.4-35.3-59.2-66.5L1 167.8c-4.3-17.1 6.1-34.5 23.3-38.8s34.5 6.1 38.8 23.3l3.9 15.5C70.5 182 83.3 192 98 192l30 0 16 0 159.8 0L416 256.1zM464 80a16 16 0 1 0 -32 0 16 16 0 1 0 32 0z"/>' ],
		'elevator'      => [ 'view' => '0 0 512 512', 'path' => '<path d="M132.7 4.7l-64 64c-4.6 4.6-5.9 11.5-3.5 17.4s8.3 9.9 14.8 9.9l128 0c6.5 0 12.3-3.9 14.8-9.9s1.1-12.9-3.5-17.4l-64-64c-6.2-6.2-16.4-6.2-22.6 0zM64 128c-35.3 0-64 28.7-64 64L0 448c0 35.3 28.7 64 64 64l384 0c35.3 0 64-28.7 64-64l0-256c0-35.3-28.7-64-64-64L64 128zm96 96a48 48 0 1 1 0 96 48 48 0 1 1 0-96zM80 400c0-26.5 21.5-48 48-48l64 0c26.5 0 48 21.5 48 48l0 16c0 17.7-14.3 32-32 32l-96 0c-17.7 0-32-14.3-32-32l0-16zm192 0c0-26.5 21.5-48 48-48l64 0c26.5 0 48 21.5 48 48l0 16c0 17.7-14.3 32-32 32l-96 0c-17.7 0-32-14.3-32-32l0-16zm32-128a48 48 0 1 1 96 0 48 48 0 1 1 -96 0zM356.7 91.3c6.2 6.2 16.4 6.2 22.6 0l64-64c4.6-4.6 5.9-11.5 3.5-17.4S438.5 0 432 0L304 0c-6.5 0-12.3 3.9-14.8 9.9s-1.1 12.9 3.5 17.4l64 64z"/>' ],
		'balcony'       => [ 'view' => '0 0 576 512', 'path' => '<path d="M575.8 255.5c0 18-15 32.1-32 32.1l-32 0 .7 160.2c0 2.7-.2 5.4-.5 8.1l0 16.2c0 22.1-17.9 40-40 40l-16 0c-1.1 0-2.2 0-3.3-.1c-1.4 .1-2.8 .1-4.2 .1L416 512l-24 0c-22.1 0-40-17.9-40-40l0-24 0-64c0-17.7-14.3-32-32-32l-64 0c-17.7 0-32 14.3-32 32l0 64 0 24c0 22.1-17.9 40-40 40l-24 0-31.9 0c-1.5 0-3-.1-4.5-.2c-1.2 .1-2.4 .2-3.6 .2l-16 0c-22.1 0-40-17.9-40-40l0-112c0-.9 0-1.9 .1-2.8l0-69.7-32 0c-18 0-32-14-32-32.1c0-9 3-17 10-24L266.4 8c7-7 15-8 22-8s15 2 21 7L564.8 231.5c8 7 12 15 11 24z"/>' ],
		'washer'        => [ 'view' => '0 0 512 512', 'path' => '<path d="M208 96a48 48 0 1 0 0-96 48 48 0 1 0 0 96zM320 256a64 64 0 1 0 0-128 64 64 0 1 0 0 128zM416 32a32 32 0 1 0 -64 0 32 32 0 1 0 64 0zm0 160c0 27.6-11.7 52.5-30.4 70.1C422.1 275.7 448 310.8 448 352c0 53-43 96-96 96l-192 0c-53 0-96-43-96-96s43-96 96-96l88.4 0c-15.2-17-24.4-39.4-24.4-64L96 192c-53 0-96 43-96 96L0 416c0 53 43 96 96 96l320 0c53 0 96-43 96-96l0-128c0-53-43-96-96-96zM160 288c-35.3 0-64 28.7-64 64s28.7 64 64 64l192 0c35.3 0 64-28.7 64-64s-28.7-64-64-64l-32 0-160 0z"/>' ],
		'transit'       => [ 'view' => '0 0 448 512', 'path' => '<path d="M96 0C43 0 0 43 0 96L0 352c0 48 35.2 87.7 81.1 94.9l-46 46C28.1 499.9 33.1 512 43 512l39.7 0c8.5 0 16.6-3.4 22.6-9.4L160 448l128 0 54.6 54.6c6 6 14.1 9.4 22.6 9.4l39.7 0c10 0 15-12.1 7.9-19.1l-46-46c46-7.1 81.1-46.9 81.1-94.9l0-256c0-53-43-96-96-96L96 0zM64 96c0-17.7 14.3-32 32-32l256 0c17.7 0 32 14.3 32 32l0 96c0 17.7-14.3 32-32 32L96 224c-17.7 0-32-14.3-32-32l0-96zM224 288a48 48 0 1 1 0 96 48 48 0 1 1 0-96z"/>' ],
		'pet'           => [ 'view' => '0 0 512 512', 'path' => '<path d="M226.5 92.9c14.3 42.9-.3 86.2-32.6 96.8s-70.1-15.6-84.4-58.5s.3-86.2 32.6-96.8s70.1 15.6 84.4 58.5zM100.4 198.6c18.9 32.4 14.3 70.1-10.2 84.1s-59.7-.9-78.5-33.3S-2.7 179.3 21.8 165.3s59.7 .9 78.5 33.3zM69.2 401.2C121.6 259.9 214.7 224 256 224s134.4 35.9 186.8 177.2c3.6 9.7 5.2 20.1 5.2 30.5l0 1.6c0 25.8-20.9 46.7-46.7 46.7c-11.5 0-22.9-1.4-34-4.2l-88-22c-15.3-3.8-31.3-3.8-46.6 0l-88 22c-11.1 2.8-22.5 4.2-34 4.2C84.9 480 64 459.1 64 433.3l0-1.6c0-10.4 1.6-20.8 5.2-30.5zM421.8 282.7c-24.5-14-29.1-51.7-10.2-84.1s54-47.3 78.5-33.3s29.1 51.7 10.2 84.1s-54 47.3-78.5 33.3zM310.1 189.7c-32.3-10.6-46.9-53.9-32.6-96.8s52.1-69.1 84.4-58.5s46.9 53.9 32.6 96.8s-52.1 69.1-84.4 58.5z"/>' ],
		'star'          => [ 'view' => '0 0 576 512', 'path' => '<path d="M316.9 18C311.6 7 300.4 0 288.1 0s-23.4 7-28.8 18L195 150.3 51.4 171.5c-12 1.8-22 10.2-25.7 21.7s-.7 24.2 7.9 32.7L137.8 329 113.2 474.7c-2 12 3 24.2 12.9 31.3s23 8 33.8 2.3l128.3-68.5 128.3 68.5c10.8 5.7 23.9 4.9 33.8-2.3s14.9-19.3 12.9-31.3L438.5 329 542.7 225.9c8.6-8.5 11.7-21.2 7.9-32.7s-13.7-19.9-25.7-21.7L381.2 150.3 316.9 18z"/>' ],
	];
}

/**
 * The full <svg> for an amenity icon. Unknown keys fall back to 'star'.
 */
function bellclair_amenity_icon_svg( string $key, string $class ): string {
	$map   = bellclair_amenity_icon_map();
	$ico   = $map[ $key ] ?? $map['star'];
	$paint = empty( $ico['stroke'] )
		? 'fill="currentColor"'
		: 'fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"';
	return sprintf(
		'<svg class="%s" viewBox="%s" %s aria-hidden="true">%s</svg>',
		esc_attr( $class ),
		esc_attr( $ico['view'] ),
		$paint,
		$ico['path'] // static icon path data
	);
}

/**
 * Amenity cards: query the bc_amenity CPT, falling back to hardcoded defaults
 * until the client creates entries.
 *
 * Creating ONE entry replaces the whole fallback list (OPERATIONS trap 8), so
 * enter all of them in a single pass.
 *
 * @param int $max Maximum number of amenities to return.
 * @return array<int,array{name:string,icon_key:string,group:string,image:string,detail:string}>
 */
function bellclair_get_amenities( int $max = 8 ): array {
	$query = new WP_Query( [
		'post_type'      => 'bc_amenity',
		'posts_per_page' => $max,
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
		'post_status'    => 'publish',
	] );

	if ( $query->have_posts() ) {
		$items = [];
		while ( $query->have_posts() ) {
			$query->the_post();
			$aid     = get_the_ID();
			$items[] = [
				'name'     => get_the_title(),
				'icon_key' => bellclair_field( 'amenity_icon_key', $aid, 'star' ),
				'group'    => bellclair_field( 'amenity_group', $aid, 'property' ),
				'image'    => ( $iid = (int) bellclair_field( 'amenity_image', $aid, 0 ) )
					? (string) wp_get_attachment_image_url( $iid, 'large' ) : '',
				'detail'   => bellclair_field( 'amenity_detail', $aid, '' ),
			];
		}
		wp_reset_postdata();
		return $items;
	}

	// Same seven as the approved preview. Copy is from the AppFolio listing and
	// the 2026-10-08 client edits (EV Chargers, 24/7 maintenance) — see
	// docs/STATUS.md "Copy to confirm with the client" before changing it.
	$items = [
		[ 'name' => 'Fitness Center',           'icon_key' => 'fitness',     'group' => 'property', 'image' => 'amenities/fitness-2.jpg',       'detail' => 'A state-of-the-art fitness center with cardio machines and free weights — no gym membership required.' ],
		[ 'name' => 'Secured Lobby Entry',      'icon_key' => 'lock',        'group' => 'property', 'image' => 'interior/lobby.jpg',            'detail' => 'An elegant foyer with secured entrances, so only residents and their guests come and go.' ],
		[ 'name' => 'Garage Parking',           'icon_key' => 'garage',      'group' => 'property', 'image' => 'exterior/garage-courtyard.jpg', 'detail' => 'Underground garage parking and on-site reserved spaces, with private garages at select townhome residences.' ],
		[ 'name' => 'On-Site Leasing Office',   'icon_key' => 'leasing',     'group' => 'property', 'image' => 'exterior/leasing-office.jpg',   'detail' => 'Our leasing and management team works on site at 691 Bloomfield Avenue, steps from the residences.' ],
		[ 'name' => 'EV Chargers',              'icon_key' => 'ev',          'group' => 'property', 'image' => 'amenities/ev-charger.jpg',      'detail' => 'ChargePoint electric-vehicle charging on site, so you can charge at home.' ],
		[ 'name' => 'Cats Welcome',             'icon_key' => 'cat',         'group' => 'property', 'image' => '',                              'detail' => 'Cats are welcome at Bellclair. Dogs are not permitted.' ],
		[ 'name' => '24/7 On-Site Maintenance', 'icon_key' => 'maintenance', 'group' => 'property', 'image' => '',                              'detail' => 'Our on-site maintenance team is available 24/7. Submit a request online any time, or call for emergencies around the clock.' ],
	];
	return array_slice( $items, 0, $max );
}

/**
 * Order plans smallest -> largest by square footage.
 *
 * Applied to both the CPT results and the fallback list so the sequence is the
 * same however the data arrives. Remove this call in bellclair_get_plans() to
 * hand ordering back to the drag-to-reorder menu_order in wp-admin.
 *
 * @param array<string,array<string,mixed>> $plans Keyed by plan slug.
 * @return array<string,array<string,mixed>>
 */
function bellclair_sort_plans_by_sqft( array $plans ): array {
	uasort(
		$plans,
		static function ( array $a, array $b ): int {
			$sa = (int) preg_replace( '/[^0-9]/', '', (string) ( $a['sqft'] ?? '' ) );
			$sb = (int) preg_replace( '/[^0-9]/', '', (string) ( $b['sqft'] ?? '' ) );
			return $sa <=> $sb;
		}
	);
	return $plans;
}

/**
 * Floor plans: query the bc_floor_plan CPT, falling back to hardcoded defaults.
 *
 * @return array<string,array<string,mixed>> Keyed by plan slug.
 */
function bellclair_get_plans(): array {
	$query = new WP_Query( [
		'post_type'      => 'bc_floor_plan',
		'posts_per_page' => -1,
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
		'post_status'    => 'publish',
	] );

	if ( $query->have_posts() ) {
		$plans = [];
		while ( $query->have_posts() ) {
			$query->the_post();
			$pid    = get_the_ID();
			$slug   = sanitize_key( bellclair_field( 'plan_slug', $pid, sanitize_title( get_the_title() ) ) );
			$img_id = (int) bellclair_field( 'plan_image', $pid, 0 );
			$plans[ $slug ] = [
				'name'    => get_the_title(),
				'code'    => bellclair_field( 'plan_code', $pid, '' ),
				'where'   => bellclair_field( 'plan_where', $pid, '' ),
				'bed'     => (int) bellclair_field( 'plan_beds', $pid, 1 ),
				'bath'    => (float) bellclair_field( 'plan_baths', $pid, 1 ),
				'sqft'    => bellclair_field( 'plan_sqft', $pid, '' ),
				'rent'    => bellclair_field( 'plan_rent', $pid, '' ),
				'tag'     => bellclair_field( 'plan_tag', $pid, '' ),
				'laundry' => bellclair_field( 'plan_laundry', $pid, 'In-Unit' ),
				'pdf'     => bellclair_field( 'plan_pdf', $pid, '' ),
				'image'   => $img_id ? (string) wp_get_attachment_image_url( $img_id, 'large' ) : '',
				'mp'      => bellclair_field( 'plan_matterport', $pid, '' ),
			];
		}
		wp_reset_postdata();
		return bellclair_sort_plans_by_sqft( $plans );
	}

	// Fallback until the client adds Floor Plan entries — the eight plans from
	// Nina's "Updated Floorplans" sheets, as in the approved preview. Tours are
	// Matterport models whose titles name the plan (the SharePoint "Dorsey NDR
	// Matterport.docx" lists Ellington's link by mistake). The Duplex sheet has
	// specs but no drawing, so it renders the "coming soon" placeholder.
	$fp = get_template_directory_uri() . '/assets/images/floorplans/';
	$mp = 'https://my.matterport.com/show/?m=';
	return [
		'calloway'   => [ 'name' => 'Calloway',   'code' => '7 Bell St · Floor 1 & 8 Bell St · Floor 3',       'where' => '7 & 8 Bell Street',     'bed' => 1, 'bath' => 1,   'sqft' => '745',   'rent' => '', 'tag' => '', 'laundry' => 'In-Unit', 'pdf' => '', 'image' => $fp . 'calloway.jpg',   'mp' => '' ],
		'armstrong'  => [ 'name' => 'Armstrong',  'code' => '7 Bell St · Floors 1–4',                           'where' => '7 Bell Street',         'bed' => 1, 'bath' => 1,   'sqft' => '775',   'rent' => '', 'tag' => '', 'laundry' => 'In-Unit', 'pdf' => '', 'image' => $fp . 'armstrong.jpg',  'mp' => $mp . 'PVaodWhQSWT' ],
		'holloway'   => [ 'name' => 'Holloway',   'code' => '691 Bloomfield Ave · Floors 1 & 2 · Loft',         'where' => '691 Bloomfield Avenue', 'bed' => 1, 'bath' => 1,   'sqft' => '825',   'rent' => '', 'tag' => '', 'laundry' => 'In-Unit', 'pdf' => '', 'image' => $fp . 'holloway.jpg',   'mp' => '' ],
		'basie'      => [ 'name' => 'Basie',      'code' => '7 Bell St · Floor 1',                              'where' => '7 Bell Street',         'bed' => 1, 'bath' => 1,   'sqft' => '850',   'rent' => '', 'tag' => '', 'laundry' => 'In-Unit', 'pdf' => '', 'image' => $fp . 'basie.jpg',      'mp' => '' ],
		'dorsey'     => [ 'name' => 'Dorsey',     'code' => '7 Bell St · Floors 1–4',                           'where' => '7 Bell Street',         'bed' => 2, 'bath' => 1,   'sqft' => '1,075', 'rent' => '', 'tag' => '', 'laundry' => 'In-Unit', 'pdf' => '', 'image' => $fp . 'dorsey.jpg',     'mp' => $mp . 'hrsGWLg4gYT' ],
		'ellington'  => [ 'name' => 'Ellington',  'code' => '7 Bell St · Floors 1–4 & 8 Bell St · Floors 1–2', 'where' => '7 & 8 Bell Street',     'bed' => 2, 'bath' => 2,   'sqft' => '1,125', 'rent' => '', 'tag' => '', 'laundry' => 'In-Unit', 'pdf' => '', 'image' => $fp . 'ellington.jpg',  'mp' => $mp . 'ah1D28hoCbj' ],
		'duplex'     => [ 'name' => 'Duplex',     'code' => '8 Bell St · Floors 1 & 2',                         'where' => '8 Bell Street',         'bed' => 2, 'bath' => 2,   'sqft' => '1,300', 'rent' => '', 'tag' => '', 'laundry' => '',        'pdf' => '', 'image' => '',                         'mp' => '' ],
		'fitzgerald' => [ 'name' => 'Fitzgerald', 'code' => '8 Bell St · Floor 3 · Two levels',                 'where' => '8 Bell Street',         'bed' => 2, 'bath' => 1.5, 'sqft' => '1,383', 'rent' => '', 'tag' => '', 'laundry' => 'In-Unit', 'pdf' => '', 'image' => $fp . 'fitzgerald.jpg', 'mp' => '' ],
	];
}

/** Plan whose detail panel opens first on desktop (main.js reads it from the selector). */
function bellclair_default_plan(): string {
	return 'dorsey';
}

/** The small tag on a plan option: the plan's own tag, else "3D Tour" when it has one. */
function bellclair_fp_tag( array $p ): string {
	if ( ! empty( $p['tag'] ) ) {
		return (string) $p['tag'];
	}
	return ! empty( $p['mp'] ) ? '3D Tour' : '';
}

/**
 * Server-side render of a single floor-plan option button's inner content.
 * main.js overwrites this identically on the front end; rendering it here gives
 * a meaningful editor preview and a no-JS fallback.
 */
function bellclair_fp_option_inner( array $p ): string {
	$specs = $p['bed'] . ' Bed / ' . $p['bath'] . ' Bath' . ( ! empty( $p['where'] ) ? ' · ' . $p['where'] : '' );
	$tag   = bellclair_fp_tag( $p );

	$html  = '<span class="fp-option__specs">' . esc_html( $specs ) . '</span>';
	$html .= '<span class="fp-option__name">' . esc_html( $p['name'] ) . '</span>';
	$html .= '<span class="fp-option__size">' . esc_html( $p['sqft'] ) . ' sq ft</span>';
	if ( $tag ) {
		$html .= '<span class="fp-option__tag">' . esc_html( $tag ) . '</span>';
	}
	return $html;
}

/**
 * Server-side render of the floor-plan detail panel. Mirrors renderDetail() in
 * assets/js/main.js so the panel is populated before JS runs.
 */
function bellclair_fp_detail_html( array $p, string $avail_url, string $apply_url ): string {
	$html  = '<p class="fp-detail__eyebrow">' . esc_html( $p['code'] ) . '</p>';
	$html .= '<h3 class="fp-detail__name">' . esc_html( $p['name'] ) . '</h3>';
	$html .= '<div class="fp-detail__specs">';
	$html .= '<div class="fp-spec"><span>Bedrooms</span><span>' . esc_html( $p['bed'] ) . '</span></div>';
	$html .= '<div class="fp-spec"><span>Bathrooms</span><span>' . esc_html( $p['bath'] ) . '</span></div>';
	$html .= '<div class="fp-spec"><span>Square Feet</span><span>' . esc_html( $p['sqft'] ) . '</span></div>';
	if ( ! empty( $p['rent'] ) ) {
		$html .= '<div class="fp-spec"><span>Starting Rent</span><span>' . esc_html( $p['rent'] ) . '</span></div>';
	}
	$html .= '<div class="fp-spec"><span>Laundry</span><span>' . esc_html( ! empty( $p['laundry'] ) ? $p['laundry'] : '—' ) . '</span></div>';
	$html .= '</div>';
	if ( ! empty( $p['image'] ) ) {
		$alt   = $p['name'] . ' floor plan — ' . $p['bed'] . ' bed, ' . $p['bath'] . ' bath, ' . $p['sqft'] . ' sq ft';
		$html .= '<img class="fp-plan-img" src="' . esc_url( $p['image'] ) . '" alt="' . esc_attr( $alt ) . '" loading="lazy">';
	} else {
		$html .= '<div class="fp-diagram"><span class="fp-diagram__watermark">' . esc_html( $p['name'] ) . '</span>'
			. '<span class="fp-diagram__note">Floor plan drawing coming soon</span></div>';
	}
	$html .= '<div class="fp-detail__actions">';
	if ( ! empty( $p['mp'] ) ) {
		$html .= '<button type="button" class="btn btn--secondary" data-mp="' . esc_url( $p['mp'] ) . '" data-name="' . esc_attr( $p['name'] ) . '">Take a 3D Tour</button>';
	}
	$html .= '<a class="btn btn--primary" href="' . esc_url( $avail_url ) . '">Check Availability</a>';
	if ( ! empty( $p['pdf'] ) ) {
		$html .= '<a class="btn btn--secondary" href="' . esc_url( $p['pdf'] ) . '" target="_blank" rel="noopener noreferrer">Download PDF</a>';
	} else {
		$html .= '<a class="btn btn--secondary" href="' . esc_url( $apply_url ) . '" target="_blank" rel="noopener noreferrer">Apply Now</a>';
	}
	$html .= '</div>';

	return $html;
}

/** AppFolio listings URL for Bellclair (Apply Now). The owner LLC is spelled BELLCLAIRE. */
function bellclair_apply_url(): string {
	return 'https://sterlingproperties.appfolio.com/listings?filters%5Bproperty_list%5D=BELLCLAIRE%2C+LLC';
}

/**
 * The full VTC film with sound, played in the film modal (footer.php).
 * Filter `bellclair_film_url` to point it at a Media Library upload instead.
 */
function bellclair_film_url(): string {
	return (string) apply_filters( 'bellclair_film_url', get_template_directory_uri() . '/assets/video/bellclair-film.mp4' );
}
