<?php
/**
 * Search and sharing metadata.
 *
 * - Document titles and meta descriptions per page
 * - Open Graph / Twitter card tags, so shared links show a photo and summary
 * - JSON-LD on the homepage (WebSite + ApartmentComplex)
 * - Sitemap: drops the pages that only 301 to homepage anchors
 * - Author archives, ?author=N and the public users REST route are closed,
 *   so the developer login is not discoverable
 *
 * The copy below is the fallback. A page's Excerpt (enabled for pages here,
 * in the editor sidebar under Page → Excerpt) overrides its description, so
 * the client can change it without a deploy. Titles stay in code — filter
 * `bellclair_seo_pages` to change them.
 *
 * If an SEO plugin is ever activated, the title/description/OG/JSON-LD output
 * steps aside so the two never emit duplicate tags. The sitemap and author
 * lockdown stay on either way.
 */

/**
 * True when a dedicated SEO plugin is handling head metadata.
 */
function bellclair_seo_plugin_active(): bool {
	return defined( 'WPSEO_VERSION' )            // Yoast
		|| defined( 'RANK_MATH_VERSION' )
		|| defined( 'THE_SEO_FRAMEWORK_VERSION' )
		|| defined( 'AIOSEO_VERSION' )
		|| defined( 'SEOPRESS_VERSION' );
}

/**
 * Title + description per page, keyed by page slug ('home' = front page).
 * Titles stay under ~60 characters and descriptions under ~160, which is
 * roughly what Google shows before truncating.
 *
 * Copy follows the client-verified constraints in docs/STATUS.md.
 */
function bellclair_seo_pages(): array {
	return apply_filters( 'bellclair_seo_pages', [
		'home'         => [
			'title'       => 'Bellclair | Boutique Apartments in Downtown Montclair, NJ',
			'description' => 'One- and two-bedroom apartments on Bell Street in downtown Montclair, NJ, with in-unit laundry, a fitness center and garage parking, 12 miles from NYC.',
		],
		'availability' => [
			'title'       => 'Apartment Availability & Pricing | Bellclair Montclair',
			'description' => 'See live apartment availability and pricing at Bellclair in Montclair, NJ, and apply online. Questions? Call the leasing office at 973.655.0997.',
		],
		'gallery'      => [
			'title'       => 'Photo Gallery & Film | Bellclair at Montclair',
			'description' => 'Watch the Bellclair film and browse photos of the renovated residences, the lobby and fitness center, Bell Street and downtown Montclair, NJ.',
		],
		'residents'    => [
			'title'       => 'Resident Portal & Resources | Bellclair Montclair',
			'description' => 'Bellclair residents can pay rent, submit maintenance requests and view lease documents online, and find community guidelines and emergency contacts.',
		],
	] );
}

/**
 * The bellclair_seo_pages() entry for the current request, or [] if none.
 */
function bellclair_seo_current(): array {
	$pages = bellclair_seo_pages();
	if ( is_front_page() ) {
		return $pages['home'] ?? [];
	}
	if ( is_page() ) {
		$post = get_queried_object();
		return $pages[ $post->post_name ?? '' ] ?? [];
	}
	return [];
}

/**
 * Meta description for the current request: the page Excerpt if the client
 * wrote one, otherwise the theme's copy, otherwise the site tagline.
 */
function bellclair_seo_description(): string {
	if ( is_singular() ) {
		$post = get_queried_object();
		if ( $post && has_excerpt( $post ) ) {
			return wp_strip_all_tags( get_the_excerpt( $post ), true );
		}
	}
	$entry = bellclair_seo_current();
	return $entry['description'] ?? (string) get_bloginfo( 'description' );
}

// Excerpt box for pages, so descriptions are editable in the block editor.
add_action( 'init', function (): void {
	add_post_type_support( 'page', 'excerpt' );
} );

// Search Console: Rivergate verifies with a google-site-verification meta tag
// printed here. Bellclair has no property yet — add its own token here once
// the domain is set up (never reuse Rivergate's).

add_filter( 'document_title_separator', fn() => '|' );

add_filter( 'pre_get_document_title', function ( string $title ): string {
	if ( bellclair_seo_plugin_active() ) {
		return $title;
	}
	$entry = bellclair_seo_current();
	return $entry['title'] ?? $title;
} );


// ---------------------------------------------------------------------------
// HEAD TAGS — description, Open Graph, Twitter card
// ---------------------------------------------------------------------------

function bellclair_seo_head_tags(): void {
	if ( bellclair_seo_plugin_active() || is_404() || is_search() ) {
		return;
	}

	$description = bellclair_seo_description();
	$url         = is_front_page() ? home_url( '/' ) : (string) wp_get_canonical_url();

	// Featured image if the page has one, otherwise the 1200×630 aerial.
	$image = [
		'url'    => get_template_directory_uri() . '/assets/images/social/bellclair-share.jpg',
		'width'  => 1200,
		'height' => 630,
		'alt'    => 'Aerial view of Bellclair in Montclair with the New York City skyline beyond',
	];
	if ( is_singular() && has_post_thumbnail() ) {
		$src = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
		if ( $src ) {
			$image = [
				'url'    => $src[0],
				'width'  => (int) $src[1],
				'height' => (int) $src[2],
				'alt'    => (string) get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true ),
			];
		}
	}

	$tags = [
		[ 'name', 'description', $description ],
		[ 'property', 'og:locale', 'en_US' ],
		[ 'property', 'og:type', 'website' ],
		[ 'property', 'og:site_name', get_bloginfo( 'name' ) ],
		[ 'property', 'og:title', wp_get_document_title() ],
		[ 'property', 'og:description', $description ],
		[ 'property', 'og:url', $url ],
		[ 'property', 'og:image', $image['url'] ],
		[ 'property', 'og:image:width', (string) $image['width'] ],
		[ 'property', 'og:image:height', (string) $image['height'] ],
		[ 'property', 'og:image:alt', $image['alt'] ],
		[ 'name', 'twitter:card', 'summary_large_image' ],
	];

	foreach ( $tags as [ $attr, $key, $value ] ) {
		if ( '' === $value ) {
			continue;
		}
		$escaped = 'og:url' === $key || 'og:image' === $key ? esc_url( $value ) : esc_attr( $value );
		printf( "<meta %s=\"%s\" content=\"%s\">\n", $attr, esc_attr( $key ), $escaped );
	}
}
add_action( 'wp_head', 'bellclair_seo_head_tags', 2 );


// ---------------------------------------------------------------------------
// STRUCTURED DATA — homepage only
// ---------------------------------------------------------------------------

function bellclair_seo_schema(): void {
	if ( bellclair_seo_plugin_active() || ! is_front_page() ) {
		return;
	}

	$home = home_url( '/' );
	$img  = get_template_directory_uri() . '/assets/images/';

	$amenities = [
		'Fitness center', 'Secured lobby entry', 'Garage parking',
		'On-site leasing office', 'EV charging', '24/7 on-site maintenance',
		'In-unit washer and dryer',
	];

	$graph = [
		[
			'@type'         => 'WebSite',
			'@id'           => $home . '#website',
			'url'           => $home,
			'name'          => get_bloginfo( 'name' ),
			'alternateName' => 'Bellclair',
		],
		[
			'@type'       => 'ApartmentComplex',
			'@id'         => $home . '#residence',
			'name'        => get_bloginfo( 'name' ),
			'url'         => $home,
			'description' => bellclair_seo_description(),
			'image'       => [
				$img . 'social/bellclair-share.jpg',
				$img . 'aerials/aerial-bell-street.jpg',
			],
			'logo'        => $img . 'site-icon/site-icon-512.png',
			'telephone'   => '+1-973-655-0997',
			'address'     => [
				'@type'           => 'PostalAddress',
				'streetAddress'   => '7 Bell Street',
				'addressLocality' => 'Montclair',
				'addressRegion'   => 'NJ',
				'postalCode'      => '07042',
				'addressCountry'  => 'US',
			],
			// Same pin the neighborhood-explorer map uses for the property.
			'geo'         => [
				'@type'     => 'GeoCoordinates',
				'latitude'  => 40.817948,
				'longitude' => -74.222495,
			],
			'petsAllowed'   => 'Cats only',
			'amenityFeature' => array_map(
				fn( string $name ) => [ '@type' => 'LocationFeatureSpecification', 'name' => $name, 'value' => true ],
				$amenities
			),
			// Leasing office hours, as in the homepage contact section.
			'openingHoursSpecification' => [
				[
					'@type'     => 'OpeningHoursSpecification',
					'dayOfWeek' => [ 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday' ],
					'opens'     => '10:00',
					'closes'    => '17:00',
				],
			],
			'tourBookingPage' => $home . '#contact',
		],
	];

	printf(
		"<script type=\"application/ld+json\">%s</script>\n",
		wp_json_encode( [ '@context' => 'https://schema.org', '@graph' => $graph ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG )
	);
}
add_action( 'wp_head', 'bellclair_seo_schema', 3 );


// ---------------------------------------------------------------------------
// SITEMAP — leave out pages that only redirect
// ---------------------------------------------------------------------------

// Contact and Location 301 to homepage anchors (functions.php §10). Listing
// them makes Search Console report "Page with redirect" for each.
add_filter( 'wp_sitemaps_posts_query_args', function ( array $args, string $post_type ): array {
	if ( 'page' !== $post_type ) {
		return $args;
	}
	$ids = [];
	foreach ( array_keys( bellclair_legacy_redirect_map() ) as $slug ) {
		$page = get_page_by_path( $slug );
		if ( $page ) {
			$ids[] = $page->ID;
		}
	}
	if ( $ids ) {
		$args['post__not_in'] = array_merge( $args['post__not_in'] ?? [], $ids );
	}
	return $args;
}, 10, 2 );


// ---------------------------------------------------------------------------
// AUTHOR LOCKDOWN — keep the login out of public URLs
// ---------------------------------------------------------------------------

// /author/<login>/ and ?author=N both resolve to an author archive. Priority 1
// runs before core's redirect_canonical, which would otherwise turn ?author=1
// into /author/<login>/ and reveal it.
add_action( 'template_redirect', function (): void {
	if ( is_author() ) {
		wp_safe_redirect( home_url( '/' ), 301 );
		exit;
	}
}, 1 );

// /wp-json/wp/v2/users lists every author of a published page, login slug
// included. The block editor still needs it, so only logged-out requests lose it.
add_filter( 'rest_endpoints', function ( array $endpoints ): array {
	if ( is_user_logged_in() ) {
		return $endpoints;
	}
	foreach ( array_keys( $endpoints ) as $route ) {
		if ( str_starts_with( $route, '/wp/v2/users' ) ) {
			unset( $endpoints[ $route ] );
		}
	}
	return $endpoints;
} );

// oEmbed responses (used when a page link is pasted into another site) name
// the author and link to their archive. Credit the site instead.
add_filter( 'oembed_response_data', function ( array $data ): array {
	$data['author_name'] = get_bloginfo( 'name' );
	$data['author_url']  = home_url( '/' );
	return $data;
} );
