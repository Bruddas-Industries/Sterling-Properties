<?php
/**
 * Shared helpers for Rivergate dynamic blocks.
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
function rivergate_in_editor(): bool {
	return defined( 'REST_REQUEST' ) && REST_REQUEST;
}

/**
 * Returns the ` data-animate` attribute string for front-end output, or an
 * empty string in the editor (where [data-animate] would stay opacity:0).
 *
 * @param string $variant Optional reveal variant: '', 'fade', 'left', 'right', 'scale'.
 */
function rivergate_anim( string $variant = '' ): string {
	if ( rivergate_in_editor() ) {
		return '';
	}
	return $variant ? ' data-animate="' . esc_attr( $variant ) . '"' : ' data-animate';
}

/**
 * SVG path data for amenity icons, keyed by the amenity_icon_key ACF value.
 *
 * @return array<string,string>
 */
function rivergate_amenity_icon_map(): array {
	return [
		'lock'      => '<rect x="6" y="14" width="28" height="20" rx="2"/><path d="M14 14V10a6 6 0 0 1 12 0v4"/><circle cx="20" cy="24" r="3"/>',
		'pool'      => '<path d="M8 28 C8 20 32 20 32 28"/><ellipse cx="20" cy="28" rx="12" ry="4"/><path d="M20 24 V12"/><circle cx="20" cy="10" r="3"/>',
		'fitness'   => '<rect x="6" y="18" width="10" height="14" rx="1"/><rect x="24" y="10" width="10" height="22" rx="1"/><path d="M16 24 h8"/>',
		'clubhouse' => '<circle cx="20" cy="20" r="12"/><path d="M20 8 v4 M20 28 v4 M8 20 h4 M28 20 h4"/><circle cx="20" cy="20" r="4"/>',
		'bbq'       => '<path d="M10 20 h20"/><path d="M14 20 V14 M26 20 V14"/><ellipse cx="20" cy="20" rx="10" ry="3"/><path d="M17 26 l-2 6 M23 26 l2 6 M20 26 v6"/><path d="M15 14 q2-4 5-4 q3 0 5 4"/>',
		'balcony'   => '<path d="M8 32 L20 8 L32 32 Z"/><path d="M14 32 V22 h12 v10"/>',
		'washer'    => '<rect x="8" y="12" width="24" height="16" rx="2"/><path d="M14 28 v4 M26 28 v4 M12 32 h16"/><path d="M14 18 h4 M14 22 h12"/>',
		'transit'   => '<rect x="6" y="10" width="28" height="18" rx="3"/><path d="M6 20 h28"/><circle cx="13" cy="32" r="2"/><circle cx="27" cy="32" r="2"/><path d="M13 28 v2 M27 28 v2"/>',
		'pet'       => '<path d="M12 10 C12 6 8 4 6 8 C4 12 8 14 12 10 Z"/><path d="M28 10 C28 6 32 4 34 8 C36 12 32 14 28 10 Z"/><ellipse cx="20" cy="22" rx="10" ry="8"/><circle cx="16" cy="21" r="2"/><circle cx="24" cy="21" r="2"/>',
		'star'      => '<path d="M20 6 L22 14 L30 12 L24 18 L28 26 L20 22 L12 26 L16 18 L10 12 L18 14 Z"/>',
	];
}

/**
 * Amenity cards: query the rg_amenity CPT, falling back to hardcoded defaults
 * until the client creates entries.
 *
 * @param int $max Maximum number of amenities to return.
 * @return array<int,array{name:string,icon_key:string,detail:string}>
 */
function rivergate_get_amenities( int $max = 8 ): array {
	$query = new WP_Query( [
		'post_type'      => 'rg_amenity',
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
				'icon_key' => rivergate_field( 'amenity_icon_key', $aid, 'star' ),
				'detail'   => rivergate_field( 'amenity_detail', $aid, '' ),
			];
		}
		wp_reset_postdata();
		return $items;
	}

	return [
		[ 'name' => 'Secured Access',      'icon_key' => 'lock',      'detail' => 'Controlled building entrances and secured garage parking for residents\' peace of mind around the clock.' ],
		[ 'name' => 'Heated Pool',         'icon_key' => 'pool',      'detail' => 'Resort-style heated outdoor swimming pool with sun deck — a private retreat right outside your door, all season long.' ],
		[ 'name' => 'Fitness Studio',      'icon_key' => 'fitness',   'detail' => 'State-of-the-art fitness center with modern equipment — no gym membership required.' ],
		[ 'name' => 'Rivergate Clubhouse', 'icon_key' => 'clubhouse', 'detail' => 'An exclusive community clubhouse — social hub for residents, ideal for private events and everyday gathering.' ],
		[ 'name' => 'Outdoor BBQ Area',    'icon_key' => 'bbq',       'detail' => 'Dedicated outdoor barbecue and entertaining area — perfect for gatherings in a beautifully landscaped setting.' ],
		[ 'name' => 'Private Balconies',   'icon_key' => 'balcony',   'detail' => 'Private balconies in every residence — the perfect perch for morning coffee with river and courtyard views.' ],
		[ 'name' => 'In-Unit Washer/Dryer','icon_key' => 'washer',    'detail' => 'Full-size washer and dryer in every home — the convenience you expect, included.' ],
	];
}

/**
 * Floor plans: query the rg_floor_plan CPT, falling back to hardcoded defaults.
 *
 * @return array<string,array<string,mixed>> Keyed by plan slug.
 */
function rivergate_get_plans(): array {
	$query = new WP_Query( [
		'post_type'      => 'rg_floor_plan',
		'posts_per_page' => -1,
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
		'post_status'    => 'publish',
	] );

	if ( $query->have_posts() ) {
		$plans = [];
		while ( $query->have_posts() ) {
			$query->the_post();
			$pid  = get_the_ID();
			$slug = sanitize_key( rivergate_field( 'plan_slug', $pid, sanitize_title( get_the_title() ) ) );
			$img_id = (int) rivergate_field( 'plan_image', $pid, 0 );
			$plans[ $slug ] = [
				'name'  => get_the_title(),
				'code'  => rivergate_field( 'plan_code', $pid, '' ),
				'bed'   => (int) rivergate_field( 'plan_beds', $pid, 1 ),
				'bath'  => (float) rivergate_field( 'plan_baths', $pid, 1 ),
				'sqft'  => rivergate_field( 'plan_sqft', $pid, '' ),
				'rent'  => rivergate_field( 'plan_rent', $pid, '' ),
				'tag'   => rivergate_field( 'plan_tag', $pid, '' ),
				'pdf'   => rivergate_field( 'plan_pdf', $pid, '' ),
				'image' => $img_id ? (string) wp_get_attachment_image_url( $img_id, 'large' ) : '',
				'mp'    => rivergate_field( 'plan_matterport', $pid, '' ),
			];
		}
		wp_reset_postdata();
		return $plans;
	}

	// Fallback until the client adds Floor Plan entries — mirrors the preview:
	// real plan images from the theme + Matterport tours where they exist.
	$fp = get_template_directory_uri() . '/assets/images/floorplans/';
	$mp = 'https://my.matterport.com/show/?m=';
	return [
		'wright'     => [ 'name' => 'The Wright',     'code' => 'Plan 1B3', 'bed' => 1, 'bath' => 1, 'sqft' => '863',   'rent' => '', 'tag' => '',             'pdf' => '', 'image' => $fp . 'wright.jpg',     'mp' => $mp . 'ZJFssUi1zKd' ],
		'borden'     => [ 'name' => 'The Borden',     'code' => 'Plan 1B',  'bed' => 1, 'bath' => 1, 'sqft' => '773',   'rent' => '', 'tag' => '',             'pdf' => '', 'image' => $fp . 'borden.jpg',     'mp' => '' ],
		'rivergate'  => [ 'name' => 'The Rivergate',  'code' => 'Plan 1B2', 'bed' => 1, 'bath' => 1, 'sqft' => '737',   'rent' => '', 'tag' => '',             'pdf' => '', 'image' => $fp . 'rivergate.jpg',  'mp' => $mp . '18Ngo5NtdyU' ],
		'chester'    => [ 'name' => 'The Chester',    'code' => 'Plan 2B',  'bed' => 2, 'bath' => 2, 'sqft' => '1,033', 'rent' => '', 'tag' => '',             'pdf' => '', 'image' => $fp . 'chester.jpg',    'mp' => '' ],
		'burlington' => [ 'name' => 'The Burlington', 'code' => 'Plan 2B4', 'bed' => 2, 'bath' => 2, 'sqft' => '1,043', 'rent' => '', 'tag' => '',             'pdf' => '', 'image' => $fp . 'burlington.jpg', 'mp' => '' ],
		'dayton'     => [ 'name' => 'The Dayton',     'code' => 'Plan 2B2', 'bed' => 2, 'bath' => 2, 'sqft' => '1,160', 'rent' => '', 'tag' => 'Most Popular', 'pdf' => '', 'image' => $fp . 'dayton.jpg',     'mp' => $mp . 'FURMkxr2zpm' ],
		'farnsworth' => [ 'name' => 'The Farnsworth', 'code' => 'Plan 2B5', 'bed' => 2, 'bath' => 2, 'sqft' => '1,149', 'rent' => '', 'tag' => '',             'pdf' => '', 'image' => $fp . 'farnsworth.jpg', 'mp' => $mp . 'AHnca1wS9bX' ],
		'edison'     => [ 'name' => 'The Edison',     'code' => 'Plan 2B3', 'bed' => 2, 'bath' => 2, 'sqft' => '1,287', 'rent' => '', 'tag' => '',             'pdf' => '', 'image' => $fp . 'edison.jpg',     'mp' => $mp . 'ghub4RNmR3A' ],
		'hamilton'   => [ 'name' => 'The Hamilton',   'code' => 'Plan 2B6', 'bed' => 2, 'bath' => 2, 'sqft' => '1,342', 'rent' => '', 'tag' => '',             'pdf' => '', 'image' => $fp . 'hamilton.jpg',   'mp' => '' ],
	];
}

/**
 * Server-side render of a single floor-plan option button's inner content.
 * main.js overwrites this identically on the front end; rendering it here gives
 * a meaningful editor preview and a no-JS fallback.
 */
function rivergate_fp_option_inner( array $p ): string {
	$html  = '<span class="fp-option__specs">' . esc_html( $p['bed'] . ' Bed / ' . $p['bath'] . ' Bath / Balcony' ) . '</span>';
	$html .= '<span class="fp-option__name">' . esc_html( $p['name'] ) . '</span>';
	$html .= '<span class="fp-option__size">' . esc_html( $p['sqft'] ) . ' sq ft</span>';
	if ( ! empty( $p['tag'] ) ) {
		$html .= '<span class="fp-option__tag">' . esc_html( $p['tag'] ) . '</span>';
	}
	return $html;
}

/**
 * Server-side render of the floor-plan detail panel. Mirrors the renderDetail()
 * markup in assets/js/main.js so the panel is populated before JS runs.
 */
function rivergate_fp_detail_html( array $p, string $avail_url, string $apply_url ): string {
	$eyebrow = $p['code'] . ( ! empty( $p['tag'] ) ? ' · ' . $p['tag'] : '' );

	$html  = '<p class="fp-detail__eyebrow">' . esc_html( $eyebrow ) . '</p>';
	$html .= '<h3 class="fp-detail__name">' . esc_html( $p['name'] ) . '</h3>';
	$html .= '<div class="fp-detail__specs">';
	$html .= '<div class="fp-spec"><span>Bedrooms</span><span>' . esc_html( $p['bed'] ) . '</span></div>';
	$html .= '<div class="fp-spec"><span>Bathrooms</span><span>' . esc_html( $p['bath'] ) . '</span></div>';
	$html .= '<div class="fp-spec"><span>Square Feet</span><span>' . esc_html( $p['sqft'] ) . '</span></div>';
	if ( ! empty( $p['rent'] ) ) {
		$html .= '<div class="fp-spec"><span>Starting Rent</span><span>' . esc_html( $p['rent'] ) . '</span></div>';
	}
	$html .= '<div class="fp-spec"><span>Balcony</span><span>Yes</span></div>';
	$html .= '</div>';
	// Real floor-plan image (replaces the old placeholder diagram).
	if ( ! empty( $p['image'] ) ) {
		$alt = $p['name'] . ' floor plan — ' . $p['bed'] . ' bed, ' . $p['bath'] . ' bath, ' . $p['sqft'] . ' sq ft';
		$html .= '<img class="fp-plan-img" src="' . esc_url( $p['image'] ) . '" alt="' . esc_attr( $alt ) . '" loading="lazy">';
	}
	$html .= '<div class="fp-detail__actions">';
	// Matterport virtual tour — opens the lightbox; disabled pill where none exists.
	if ( ! empty( $p['mp'] ) ) {
		$html .= '<button type="button" class="btn btn--secondary" data-mp="' . esc_url( $p['mp'] ) . '" data-name="' . esc_attr( $p['name'] ) . '">Take a Virtual Tour</button>';
	} else {
		$html .= '<span class="btn btn--secondary fp-vtour-disabled" aria-disabled="true" title="Virtual tour coming soon">Virtual Tour Coming Soon</span>';
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

/** Appfolio listings URL for Rivergate (Apply Now). */
function rivergate_apply_url(): string {
	return 'https://sterlingproperties.appfolio.com/listings?filters%5Bproperty_list%5D=RIVERGATE+BORDENTOWN';
}
