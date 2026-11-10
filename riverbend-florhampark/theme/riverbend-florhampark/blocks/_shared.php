<?php
/**
 * Shared helpers for Riverbend dynamic blocks.
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
function riverbend_in_editor(): bool {
	return defined( 'REST_REQUEST' ) && REST_REQUEST;
}

/**
 * Returns the ` data-animate` attribute string for front-end output, or an
 * empty string in the editor (where [data-animate] would stay opacity:0).
 *
 * @param string $variant Optional reveal variant: '', 'fade', 'left', 'right', 'scale'.
 */
function riverbend_anim( string $variant = '' ): string {
	if ( riverbend_in_editor() ) {
		return '';
	}
	return $variant ? ' data-animate="' . esc_attr( $variant ) . '"' : ' data-animate';
}

/**
 * Icon data for amenities, keyed by the amenity_icon_key ACF value.
 *
 * Riverbend's amenities use the preview's 24px line icons (stroked, to sit with
 * the light display type); lock, leasing and cat are Bellclair's, in the same
 * style. The remaining keys are Font Awesome Free 6 solid glyphs inherited from
 * Rivergate (Icons: CC BY 4.0), kept so every choice in the amenity_icon_key
 * select still renders something.
 *
 * @return array<string,array{view:string,path:string,stroke?:bool}>
 */
function riverbend_amenity_icon_map(): array {
	return [
		// Line icons (24x24, stroked). The first six are the Riverbend preview's.
		'clubhouse'   => [ 'view' => '0 0 24 24', 'stroke' => true, 'path' => '<path d="M3 11.5 12 4l9 7.5"/><path d="M5.5 9.5V20h13V9.5"/><path d="M10 20v-5h4v5"/>' ],
		'pool'        => [ 'view' => '0 0 24 24', 'stroke' => true, 'path' => '<path d="M3 17c1.5 0 1.5 1 3 1s1.5-1 3-1 1.5 1 3 1 1.5-1 3-1 1.5 1 3 1 1.5-1 3-1"/><path d="M3 13.5c1.5 0 1.5 1 3 1s1.5-1 3-1 1.5 1 3 1 1.5-1 3-1 1.5 1 3 1 1.5-1 3-1"/><path d="M8 11V5.5a2 2 0 0 1 4 0M14 11V5.5a2 2 0 0 1 4 0M8 8h6"/>' ],
		'bbq'         => [ 'view' => '0 0 24 24', 'stroke' => true, 'path' => '<path d="M4 10h16a8 8 0 0 1-16 0z"/><path d="M8 17.5 6.5 21M16 17.5l1.5 3.5M12 18v3"/><path d="M9 3.5c-.8 1 .8 2 0 3M12 3.5c-.8 1 .8 2 0 3M15 3.5c-.8 1 .8 2 0 3"/>' ],
		'transit'     => [ 'view' => '0 0 24 24', 'stroke' => true, 'path' => '<rect x="5" y="3.5" width="14" height="14" rx="3"/><path d="M5 11h14M8.5 14.5h.01M15.5 14.5h.01M8 17.5 6 21M16 17.5l2 3.5"/>' ],
		'pet'         => [ 'view' => '0 0 24 24', 'stroke' => true, 'path' => '<circle cx="6" cy="10" r="1.8"/><circle cx="9.5" cy="5.8" r="1.8"/><circle cx="14.5" cy="5.8" r="1.8"/><circle cx="18" cy="10" r="1.8"/><path d="M12 11.5c-2.6 0-5 3.6-5 6a2.4 2.4 0 0 0 3.4 2.2 3.8 3.8 0 0 1 3.2 0A2.4 2.4 0 0 0 17 17.5c0-2.4-2.4-6-5-6z"/>' ],
		'grounds'     => [ 'view' => '0 0 24 24', 'stroke' => true, 'path' => '<path d="M12 21v-7"/><path d="M12 14c-4.5 0-7-3-7-7 4.5 0 7 2.5 7 7z"/><path d="M12 12c0-4 2.5-7 7-7 0 4-2.5 7-7 7z"/><path d="M5 21h14"/>' ],
		'fitness'     => [ 'view' => '0 0 24 24', 'stroke' => true, 'path' => '<path d="M6.5 6.5v11M17.5 6.5v11M3.5 9.5v5M20.5 9.5v5M6.5 12h11"/>' ],
		'lock'        => [ 'view' => '0 0 24 24', 'stroke' => true, 'path' => '<rect x="5" y="11" width="14" height="10" rx="1"/><path d="M8 11V7.5a4 4 0 0 1 8 0V11"/><path d="M12 15v2.5"/>' ],
		'garage'      => [ 'view' => '0 0 24 24', 'stroke' => true, 'path' => '<path d="M3 21V9.5L12 4l9 5.5V21"/><path d="M7 21v-8.5h10V21M7 15.5h10M7 18.5h10"/>' ],
		'leasing'     => [ 'view' => '0 0 24 24', 'stroke' => true, 'path' => '<circle cx="8" cy="15" r="4"/><path d="M10.8 12.2 19 4M15.5 7.5l2.5 2.5M13.5 9.5l2 2"/>' ],
		'ev'          => [ 'view' => '0 0 24 24', 'stroke' => true, 'path' => '<rect x="4" y="3" width="10" height="18" rx="1"/><path d="M9.6 7.5 7.6 11.5h3l-2 4"/><path d="M14 9h2.5a1.5 1.5 0 0 1 1.5 1.5v6a1.5 1.5 0 0 0 3 0V7.5L19 5.5"/>' ],
		'cat'         => [ 'view' => '0 0 24 24', 'stroke' => true, 'path' => '<path d="M5 20.5v-8.2L4 4.5l4.2 3.3h7.6L20 4.5l-1 7.8v8.2z"/><path d="M9.5 13.2v.1M14.5 13.2v.1M11 16.5l1 .8 1-.8"/>' ],
		'maintenance' => [ 'view' => '0 0 24 24', 'stroke' => true, 'path' => '<path d="M14.7 6.3a4 4 0 0 0-5.2 5.2L3.8 17.2a1.5 1.5 0 0 0 2.1 2.1l5.7-5.7a4 4 0 0 0 5.2-5.2l-2.5 2.5-2.1-.5-.5-2.1z"/>' ],
		// Inherited Font Awesome solid glyphs.
		'dogrun'        => [ 'view' => '0 0 576 512', 'path' => '<path d="M309.6 158.5L332.7 19.8C334.6 8.4 344.5 0 356.1 0c7.5 0 14.5 3.5 19 9.5L392 32l52.1 0c12.7 0 24.9 5.1 33.9 14.1L496 64l56 0c13.3 0 24 10.7 24 24l0 24c0 44.2-35.8 80-80 80l-32 0-16 0-21.3 0-5.1 30.5-112-64zM416 256.1L416 480c0 17.7-14.3 32-32 32l-32 0c-17.7 0-32-14.3-32-32l0-115.2c-24 12.3-51.2 19.2-80 19.2s-56-6.9-80-19.2L160 480c0 17.7-14.3 32-32 32l-32 0c-17.7 0-32-14.3-32-32l0-230.2c-28.8-10.9-51.4-35.3-59.2-66.5L1 167.8c-4.3-17.1 6.1-34.5 23.3-38.8s34.5 6.1 38.8 23.3l3.9 15.5C70.5 182 83.3 192 98 192l30 0 16 0 159.8 0L416 256.1zM464 80a16 16 0 1 0 -32 0 16 16 0 1 0 32 0z"/>' ],
		'elevator'      => [ 'view' => '0 0 512 512', 'path' => '<path d="M132.7 4.7l-64 64c-4.6 4.6-5.9 11.5-3.5 17.4s8.3 9.9 14.8 9.9l128 0c6.5 0 12.3-3.9 14.8-9.9s1.1-12.9-3.5-17.4l-64-64c-6.2-6.2-16.4-6.2-22.6 0zM64 128c-35.3 0-64 28.7-64 64L0 448c0 35.3 28.7 64 64 64l384 0c35.3 0 64-28.7 64-64l0-256c0-35.3-28.7-64-64-64L64 128zm96 96a48 48 0 1 1 0 96 48 48 0 1 1 0-96zM80 400c0-26.5 21.5-48 48-48l64 0c26.5 0 48 21.5 48 48l0 16c0 17.7-14.3 32-32 32l-96 0c-17.7 0-32-14.3-32-32l0-16zm192 0c0-26.5 21.5-48 48-48l64 0c26.5 0 48 21.5 48 48l0 16c0 17.7-14.3 32-32 32l-96 0c-17.7 0-32-14.3-32-32l0-16zm32-128a48 48 0 1 1 96 0 48 48 0 1 1 -96 0zM356.7 91.3c6.2 6.2 16.4 6.2 22.6 0l64-64c4.6-4.6 5.9-11.5 3.5-17.4S438.5 0 432 0L304 0c-6.5 0-12.3 3.9-14.8 9.9s-1.1 12.9 3.5 17.4l64 64z"/>' ],
		'balcony'       => [ 'view' => '0 0 576 512', 'path' => '<path d="M575.8 255.5c0 18-15 32.1-32 32.1l-32 0 .7 160.2c0 2.7-.2 5.4-.5 8.1l0 16.2c0 22.1-17.9 40-40 40l-16 0c-1.1 0-2.2 0-3.3-.1c-1.4 .1-2.8 .1-4.2 .1L416 512l-24 0c-22.1 0-40-17.9-40-40l0-24 0-64c0-17.7-14.3-32-32-32l-64 0c-17.7 0-32 14.3-32 32l0 64 0 24c0 22.1-17.9 40-40 40l-24 0-31.9 0c-1.5 0-3-.1-4.5-.2c-1.2 .1-2.4 .2-3.6 .2l-16 0c-22.1 0-40-17.9-40-40l0-112c0-.9 0-1.9 .1-2.8l0-69.7-32 0c-18 0-32-14-32-32.1c0-9 3-17 10-24L266.4 8c7-7 15-8 22-8s15 2 21 7L564.8 231.5c8 7 12 15 11 24z"/>' ],
		'washer'        => [ 'view' => '0 0 512 512', 'path' => '<path d="M208 96a48 48 0 1 0 0-96 48 48 0 1 0 0 96zM320 256a64 64 0 1 0 0-128 64 64 0 1 0 0 128zM416 32a32 32 0 1 0 -64 0 32 32 0 1 0 64 0zm0 160c0 27.6-11.7 52.5-30.4 70.1C422.1 275.7 448 310.8 448 352c0 53-43 96-96 96l-192 0c-53 0-96-43-96-96s43-96 96-96l88.4 0c-15.2-17-24.4-39.4-24.4-64L96 192c-53 0-96 43-96 96L0 416c0 53 43 96 96 96l320 0c53 0 96-43 96-96l0-128c0-53-43-96-96-96zM160 288c-35.3 0-64 28.7-64 64s28.7 64 64 64l192 0c35.3 0 64-28.7 64-64s-28.7-64-64-64l-32 0-160 0z"/>' ],
		'star'          => [ 'view' => '0 0 576 512', 'path' => '<path d="M316.9 18C311.6 7 300.4 0 288.1 0s-23.4 7-28.8 18L195 150.3 51.4 171.5c-12 1.8-22 10.2-25.7 21.7s-.7 24.2 7.9 32.7L137.8 329 113.2 474.7c-2 12 3 24.2 12.9 31.3s23 8 33.8 2.3l128.3-68.5 128.3 68.5c10.8 5.7 23.9 4.9 33.8-2.3s14.9-19.3 12.9-31.3L438.5 329 542.7 225.9c8.6-8.5 11.7-21.2 7.9-32.7s-13.7-19.9-25.7-21.7L381.2 150.3 316.9 18z"/>' ],
	];
}

/**
 * The full <svg> for an amenity icon. Unknown keys fall back to 'star'.
 */
function riverbend_amenity_icon_svg( string $key, string $class ): string {
	$map   = riverbend_amenity_icon_map();
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
 * Amenity cards: query the rb_amenity CPT, falling back to hardcoded defaults
 * until the client creates entries.
 *
 * Creating ONE entry replaces the whole fallback list (OPERATIONS trap 8), so
 * enter all of them in a single pass.
 *
 * @param int $max Maximum number of amenities to return.
 * @return array<int,array{name:string,icon_key:string,group:string,image:string,detail:string}>
 */
function riverbend_get_amenities( int $max = 12 ): array {
	$query = new WP_Query( [
		'post_type'      => 'rb_amenity',
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
				'icon_key' => riverbend_field( 'amenity_icon_key', $aid, 'star' ),
				'group'    => riverbend_field( 'amenity_group', $aid, 'property' ),
				// 2048x2048, not 'large' (1024): the theme's own photos are 1800-1920px,
				// and this size serves them as-is while still capping huge uploads.
				'image'    => ( $iid = (int) riverbend_field( 'amenity_image', $aid, 0 ) )
					? (string) wp_get_attachment_image_url( $iid, '2048x2048' ) : '',
				'detail'   => riverbend_field( 'amenity_detail', $aid, '' ),
			];
		}
		wp_reset_postdata();
		return $items;
	}

	// The ten amenities on the approved preview, in its order. Sources: the old
	// site's Amenities page, the Sterling portfolio page and the AppFolio listings
	// (EV charging); the pet policy and shuttle times are Nina's (2026-10-22).
	// See docs/STATUS.md "Copy to confirm with the client" before changing it.
	$items = [
		[ 'name' => "Residents' Clubhouse",  'icon_key' => 'clubhouse',   'group' => 'property', 'image' => 'clubhouse/lounge.jpg',            'detail' => "A renovated residents' clubhouse with a fireside lounge and a coffee bar — a comfortable place to meet neighbors." ],
		[ 'name' => 'Fitness Center',        'icon_key' => 'fitness',     'group' => 'property', 'image' => 'amenities/fitness.jpg',           'detail' => 'A fitness center in the clubhouse with cardio equipment and a strength station.' ],
		[ 'name' => 'Pool & Sun Patio',      'icon_key' => 'pool',        'group' => 'property', 'image' => 'amenities/pool.jpg',              'detail' => 'An outdoor pool with a sun patio, lounge chairs, umbrellas and a shaded pergola.' ],
		[ 'name' => 'Outdoor Grilling',      'icon_key' => 'bbq',         'group' => 'property', 'image' => 'amenities/bbq.jpg',               'detail' => 'A BBQ area by the pool with built-in grills and an outdoor dining counter.' ],
		[ 'name' => 'Private Garages',       'icon_key' => 'garage',      'group' => 'property', 'image' => 'exterior/residences-garages.jpg', 'detail' => 'A private garage with a remote electric door opener and a direct entrance into your home.' ],
		[ 'name' => 'Weekday Train Shuttle', 'icon_key' => 'transit',     'group' => 'property', 'image' => '',                                'detail' => 'A complimentary courtesy shuttle to the Chatham train station every weekday: morning runs from 5:22 to 8:27 AM, and evening runs back every 15 minutes from 5:30 to 8:30 PM.' ],
		[ 'name' => 'EV Charging',           'icon_key' => 'ev',          'group' => 'property', 'image' => 'clubhouse/ev-charger.jpg',        'detail' => 'Electric-vehicle charging on site, by the clubhouse.' ],
		[ 'name' => 'Pet-Friendly',          'icon_key' => 'pet',         'group' => 'property', 'image' => '',                                'detail' => 'Cats and dogs are welcome, up to two pets per home. Dogs: $500 non-refundable deposit and $50 a month. Cats: $250 non-refundable deposit and $25 a month.' ],
		[ 'name' => 'Onsite Maintenance',    'icon_key' => 'maintenance', 'group' => 'property', 'image' => '',                                'detail' => 'An onsite maintenance team, with emergency maintenance available 24/7. Submit a request online any time.' ],
		[ 'name' => 'Landscaped Grounds',    'icon_key' => 'grounds',     'group' => 'property', 'image' => 'exterior/garden-benches.jpg',     'detail' => 'Professionally landscaped grounds with garden seating, and snow removal in winter.' ],
	];
	return array_slice( $items, 0, $max );
}

/**
 * Order plans smallest -> largest by square footage.
 *
 * Applied to both the CPT results and the fallback list so the sequence is the
 * same however the data arrives. Remove this call in riverbend_get_plans() to
 * hand ordering back to the drag-to-reorder menu_order in wp-admin.
 *
 * @param array<string,array<string,mixed>> $plans Keyed by plan slug.
 * @return array<string,array<string,mixed>>
 */
function riverbend_sort_plans_by_sqft( array $plans ): array {
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
 * Floor plans: query the rb_floor_plan CPT, falling back to hardcoded defaults.
 *
 * @return array<string,array<string,mixed>> Keyed by plan slug.
 */
function riverbend_get_plans(): array {
	$query = new WP_Query( [
		'post_type'      => 'rb_floor_plan',
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
			$slug   = sanitize_key( riverbend_field( 'plan_slug', $pid, sanitize_title( get_the_title() ) ) );
			$img_id = (int) riverbend_field( 'plan_image', $pid, 0 );
			$plans[ $slug ] = [
				'name'    => get_the_title(),
				'code'    => riverbend_field( 'plan_code', $pid, '' ),
				'where'   => riverbend_field( 'plan_where', $pid, '' ),
				'bed'     => (int) riverbend_field( 'plan_beds', $pid, 1 ),
				'bath'    => (float) riverbend_field( 'plan_baths', $pid, 1 ),
				'sqft'    => riverbend_field( 'plan_sqft', $pid, '' ),
				'rent'    => riverbend_field( 'plan_rent', $pid, '' ),
				'tag'     => riverbend_field( 'plan_tag', $pid, '' ),
				'laundry' => riverbend_field( 'plan_laundry', $pid, 'In-Unit' ),
				'pdf'     => riverbend_field( 'plan_pdf', $pid, '' ),
				'image'   => $img_id ? (string) wp_get_attachment_image_url( $img_id, '2048x2048' ) : '', // see amenities
				'mp'      => riverbend_field( 'plan_matterport', $pid, '' ),
			];
		}
		wp_reset_postdata();
		return riverbend_sort_plans_by_sqft( $plans );
	}

	// Fallback until the client adds Floor Plan entries — the four plans from the
	// "Updated Floorplans" sheets (SharePoint), as in the approved preview. Every
	// plan is in Buildings 1–18 and has a balcony, a walk-in closet, laundry and a
	// garage. Tours are the Matterport models on the AppFolio listings; each
	// model's own title names the plan. Florham has no tour yet.
	$fp = get_template_directory_uri() . '/assets/images/floorplans/';
	$mp = 'https://my.matterport.com/show/?m=';
	return [
		'madison'    => [ 'name' => 'Madison',    'code' => 'Buildings 1–18 · Floor 1',              'where' => 'First floor',         'bed' => 1, 'bath' => 1, 'sqft' => '725',   'rent' => '', 'tag' => '', 'laundry' => 'In-Unit', 'pdf' => '', 'image' => $fp . 'madison.jpg',    'mp' => $mp . 'qRBhDU6A7ZQ' ],
		'livingston' => [ 'name' => 'Livingston', 'code' => 'Buildings 1–18 · Floor 1 · With study', 'where' => 'First floor · Study', 'bed' => 1, 'bath' => 1, 'sqft' => '800',   'rent' => '', 'tag' => '', 'laundry' => 'In-Unit', 'pdf' => '', 'image' => $fp . 'livingston.jpg', 'mp' => $mp . 'yGQSWLhuVAq' ],
		'chatham'    => [ 'name' => 'Chatham',    'code' => 'Buildings 1–18 · Floor 2',              'where' => 'Second floor',        'bed' => 2, 'bath' => 2, 'sqft' => '1,050', 'rent' => '', 'tag' => '', 'laundry' => 'In-Unit', 'pdf' => '', 'image' => $fp . 'chatham.jpg',    'mp' => $mp . 'bbuj3Fchx1H' ],
		'florham'    => [ 'name' => 'Florham',    'code' => 'Buildings 1–18 · Floor 2',              'where' => 'Second floor',        'bed' => 2, 'bath' => 2, 'sqft' => '1,225', 'rent' => '', 'tag' => '', 'laundry' => 'In-Unit', 'pdf' => '', 'image' => $fp . 'florham.jpg',    'mp' => '' ],
	];
}

/** Plan whose detail panel opens first on desktop (main.js reads it from the selector). */
function riverbend_default_plan(): string {
	return 'chatham';
}

/** The small tag on a plan option: the plan's own tag, else "3D Tour" when it has one. */
function riverbend_fp_tag( array $p ): string {
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
function riverbend_fp_option_inner( array $p ): string {
	$specs = $p['bed'] . ' Bed / ' . $p['bath'] . ' Bath' . ( ! empty( $p['where'] ) ? ' · ' . $p['where'] : '' );
	$tag   = riverbend_fp_tag( $p );

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
function riverbend_fp_detail_html( array $p, string $avail_url, string $apply_url ): string {
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

/**
 * AppFolio listings URL for Riverbend (Apply Now). Riverbend has no
 * property_list value of its own; the Florham Park city filter returns exactly
 * its four listings (checked 2026-10-20).
 */
function riverbend_apply_url(): string {
	return 'https://sterlingproperties.appfolio.com/listings?filters%5Bcities%5D%5B%5D=Florham+Park';
}
