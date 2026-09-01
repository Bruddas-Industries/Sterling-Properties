<?php
/**
 * rivergate/neighborhood-explorer — filterable POI list + map panel.
 * Tab filtering handled by main.js (section 10). The Google Map renders when
 * RIVERGATE_MAPS_API_KEY is set in functions.php; otherwise a fallback shows.
 *
 * @var array $attributes Block attributes.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$maps_key = defined( 'RIVERGATE_MAPS_API_KEY' ) ? RIVERGATE_MAPS_API_KEY : '';
$contact  = home_url( '/#contact' );

// Each item is [ name, detail, lat, lng, drive_minutes ]. Coordinates drive the
// numbered map markers, so the badge number always matches the marker. Drive
// times are real routing figures from 500 Bluff View Circle (free-flow, no
// traffic) - re-measure if a place is added or moved.
$categories = [
	'transit' => [
		'label' => 'Transit & Commuting',
		'color' => '#0472BB',
		'items' => [
			[ 'River Line Light Rail',  'Near Rivergate · Bordentown Station',              40.1486, -74.7191, 5 ],
			[ 'Trenton Transit Center', 'NJ Transit + Amtrak · ~10 min via River Line',     40.2188, -74.7543, 17 ],
			[ 'I-295 On-Ramp',          'Minutes away · North to NYC, South to Philadelphia', 40.1563, -74.7183, 15 ],
			[ 'New Jersey Turnpike',    'Exit 7 · ~5 minutes',                              40.1541, -74.7010, 8 ],
			[ 'I-195 & Route 206',      'Access to Shore points and the Princeton corridor', 40.1450, -74.6850, 7 ],
		],
	],
	'dining' => [
		'label' => 'Dining & Entertainment',
		'color' => '#0A2556',
		'items' => [
			[ 'Bordentown City Dining',   'Restaurants, cafes, and bars on Farnsworth Ave',        40.1483, -74.7162, 7 ],
			[ 'Trenton Restaurant Scene', 'Diverse dining · ~15 min',                             40.2170, -74.7430, 15 ],
			[ 'Hamilton Area Dining',     'Chain and local restaurants near Hamilton Marketplace', 40.2050, -74.7100, 12 ],
		],
	],
	'shopping' => [
		'label' => 'Shopping & Retail',
		'color' => '#3C6992',
		'items' => [
			[ 'Bordentown City',      'Boutiques, galleries, and markets · ~1 mile',   40.1483, -74.7162, 7 ],
			[ 'Hamilton Marketplace', 'Target, Best Buy, major retail · ~10 min',      40.1939, -74.6393, 14 ],
			[ 'MarketFair',           'Upscale shops, dining & cinema · ~25 min',      40.3148, -74.6610, 23 ],
			[ 'Quaker Bridge Mall',   'Full regional mall · ~20 min',                  40.2911, -74.6830, 19 ],
		],
	],
	'rec' => [
		'label' => 'Recreation & Outdoors',
		'color' => '#5C6B7A',
		'items' => [
			[ 'Crystal Lake Park',         'Trails, fishing, open green space · ~5 min',     40.1172, -74.7396, 8 ],
			[ 'Kuser Farm Park',           'Historic park with trails and events · ~15 min', 40.2149, -74.7195, 13 ],
			[ 'Grounds For Sculpture',     'World-class sculpture park · ~20 min',           40.2368, -74.7189, 18 ],
			[ 'Delaware River Waterfront', 'Walking and cycling paths · At your doorstep',   40.1280, -74.7450, 2 ],
		],
	],
	'education' => [
		'label' => 'Education & Healthcare',
		'color' => '#8FA3B8',
		'ink'   => '#0A2556',
		'items' => [
			[ 'Bordentown Regional School District', 'K–12 schools serving the community', 40.1440, -74.7044, 6 ],
			[ 'Princeton University',                '~20 min north via Route 206',        40.3431, -74.6551, 27 ],
			[ 'Rider University',                    'Lawrenceville, NJ · ~20 min',        40.2812, -74.7408, 24 ],
			[ 'Capital Health Medical Center',       'Hopewell, NJ · ~20 min',             40.2919, -74.8031, 30 ],
		],
	],
];

// Map payload for main.js. Built from the same array the list renders from, so
// the two can never drift apart.
$map_pois = [];
foreach ( $categories as $cat => $data ) {
	foreach ( $data['items'] as $i => $item ) {
		if ( ! isset( $item[2], $item[3] ) ) {
			continue; // no coordinates yet — renders in the list, just not on the map
		}
		$map_pois[] = [
			'name'     => $item[0],
			'num'      => $i + 1,
			'category' => $cat,
			'lat'      => (float) $item[2],
			'lng'      => (float) $item[3],
			'color'    => $data['color'],
			'ink'      => $data['ink'] ?? '#ffffff',
		];
	}
}

if ( $maps_key && ! rivergate_in_editor() ) {
	wp_add_inline_script(
		'rivergate-main',
		'window.RG_MAP = ' . wp_json_encode(
			[
				'center'   => [ 'lat' => 40.1700, 'lng' => -74.7250 ],
				'zoom'     => 11,
				'property' => [
					'lat'     => 40.1278,
					'lng'     => -74.7380,
					'title'   => 'Rivergate Bordentown',
					'address' => '500 Bluff View Circle, Bordentown, NJ',
				],
				'pois'     => $map_pois,
			]
		) . ';',
		'before'
	);

	// Depends on rivergate-main so the initRivergateMap callback is defined
	// before the Maps API loads and calls it.
	wp_enqueue_script(
		'google-maps',
		'https://maps.googleapis.com/maps/api/js?key=' . rawurlencode( $maps_key ) . '&callback=initRivergateMap',
		[ 'rivergate-main' ],
		null,
		[
			'in_footer' => true,
			'strategy'  => 'defer',
		]
	);
}
?>
<div class="neighborhood-explorer" id="neighborhood-explorer" data-active-tab="all">
  <div class="explorer__list-panel">
    <div class="explorer__tabs" role="tablist" aria-label="Neighborhood categories">
      <button class="tab-btn is-active" role="tab" aria-selected="true"  data-tab="all" aria-controls="explorer-list">All</button>
      <button class="tab-btn" role="tab" aria-selected="false" data-tab="transit"   aria-controls="explorer-list"><span class="tab-dot tab-dot--transit"></span>Transit</button>
      <button class="tab-btn" role="tab" aria-selected="false" data-tab="dining"     aria-controls="explorer-list"><span class="tab-dot tab-dot--dining"></span>Dining</button>
      <button class="tab-btn" role="tab" aria-selected="false" data-tab="shopping"   aria-controls="explorer-list"><span class="tab-dot tab-dot--shopping"></span>Shopping</button>
      <button class="tab-btn" role="tab" aria-selected="false" data-tab="rec"        aria-controls="explorer-list"><span class="tab-dot tab-dot--rec"></span>Recreation</button>
      <button class="tab-btn" role="tab" aria-selected="false" data-tab="education"  aria-controls="explorer-list"><span class="tab-dot tab-dot--education"></span>Education</button>
    </div>

    <div class="explorer__list" id="explorer-list" role="tabpanel">
      <?php
      foreach ( $categories as $cat => $data ) :
          // Badges default to white numerals; the light step overrides with navy.
          $badge_style = 'background:' . $data['color'];
          if ( ! empty( $data['ink'] ) ) {
              $badge_style .= ';color:' . $data['ink'];
          }
      ?>
        <div class="poi-category-list" data-category="<?php echo esc_attr( $cat ); ?>">
          <div class="poi-cat-heading"><span class="poi-cat-dot" style="background:<?php echo esc_attr( $data['color'] ); ?>"></span><?php echo esc_html( $data['label'] ); ?></div>
          <?php foreach ( $data['items'] as $i => $item ) : ?>
            <div class="explorer-poi-item" data-poi="<?php echo esc_attr( $item[0] ); ?>"
                 data-lat="<?php echo esc_attr( $item[2] ?? '' ); ?>" data-lng="<?php echo esc_attr( $item[3] ?? '' ); ?>"><span class="poi-num-badge" style="<?php echo esc_attr( $badge_style ); ?>"><?php echo (int) $i + 1; ?></span><span><span class="explorer-poi-name"><?php echo esc_html( $item[0] ); ?></span><span class="explorer-poi-detail"><?php echo esc_html( $item[1] ); ?></span><?php if ( ! empty( $item[4] ) ) : ?><span class="explorer-poi-drive"><?php
            printf( esc_html__( '%d min drive', 'rivergate-bordentown' ), (int) $item[4] );
       ?></span><?php endif; ?></span></div>
          <?php endforeach; ?>
        </div>
      <?php endforeach; ?>
    </div>

    <div class="explorer-actions">
      <a href="https://www.google.com/maps/dir/?api=1&amp;origin=500+Bluff+View+Circle,+Bordentown,+NJ+08505&amp;destination=500+Bluff+View+Circle,+Bordentown,+NJ+08505&amp;travelmode=driving" id="explorer-directions" target="_blank" rel="noopener noreferrer" class="btn btn--primary">Get Directions</a>
      <a href="<?php echo esc_url( $contact ); ?>" class="btn btn--secondary">Contact Leasing Office</a>
    </div>
  </div>

  <div class="explorer__map-panel">
    <?php if ( $maps_key && ! rivergate_in_editor() ) : ?>
      <?php // Markers, styling and list/tab wiring live in main.js section 11. ?>
      <div id="poi-map" class="explorer-map"></div>
    <?php else : ?>
      <div class="explorer-map" style="display:flex;flex-direction:column;align-items:center;justify-content:center;gap:var(--space-4);background:var(--color-bg-alt);">
        <p style="color:var(--color-text-muted);font-size:var(--text-sm);text-align:center;max-width:28ch;">
          Add a Google Maps API key to <code>RIVERGATE_MAPS_API_KEY</code> in functions.php to enable the interactive map.
        </p>
        <a href="https://maps.google.com/?q=500+Bluff+View+Circle+Bordentown+NJ+08505" target="_blank" rel="noopener noreferrer" class="btn btn--secondary">Open in Google Maps</a>
      </div>
    <?php endif; ?>
  </div>
</div>
