<?php
/**
 * bellclair/neighborhood-explorer — filterable POI list + map panel.
 * Tab filtering is handled by main.js (section 9), the map by section 11. The
 * Google Map renders when BELLCLAIR_MAPS_API_KEY is set; if Google refuses the
 * key on this domain, main.js swaps in the OpenStreetMap embed below so the
 * section never shows a grey error box.
 *
 * @var array $attributes Block attributes.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$maps_key = defined( 'BELLCLAIR_MAPS_API_KEY' ) ? BELLCLAIR_MAPS_API_KEY : '';
$contact  = home_url( '/#contact' );

// 7 Bell St (US Census geocoder). The map's home marker and the origin of every
// time below.
$home = [ 'lat' => 40.817948, 'lng' => -74.222495 ];

// Shown when there is no key, in the editor, and (via main.js) if Google
// rejects the key.
$osm_embed = 'https://www.openstreetmap.org/export/embed.html?bbox=-74.2470%2C40.7990%2C-74.1880%2C40.8420&layer=mapnik&marker='
	. $home['lat'] . '%2C' . $home['lng'];

// Each item is [ name, detail, lat, lng, time ]. Coordinates drive the numbered
// map markers, so the badge number always matches the marker. Times are OSRM
// routing figures from 7 Bell St (2026-09-30): foot routing under 25 minutes on
// foot, car routing beyond that. Re-measure rather than guess if a place is
// added or moved — the nearest station is a 22-minute walk, so the copy never
// calls the trains "steps away".
$categories = [
	'transit' => [
		'label' => 'Transit & Commuting',
		'color' => '#A16D29',
		'items' => [
			[ 'Bay Street Station',      'NJ Transit Montclair-Boonton Line · Midtown Direct to NYC', 40.80819, -74.20874, '4 min drive' ],
			[ 'Walnut Street Station',   'NJ Transit Montclair-Boonton Line',                         40.81701, -74.20969, '22 min walk' ],
			[ 'Watchung Plaza Station',  'NJ Transit Montclair-Boonton Line',                         40.82951, -74.20699, '5 min drive' ],
			[ 'Glen Ridge Station',      'NJ Transit Montclair-Boonton Line',                         40.80030, -74.20461, '6 min drive' ],
			[ 'Upper Montclair Station', 'NJ Transit Montclair-Boonton Line',                         40.84211, -74.20940, '5 min drive' ],
		],
	],
	'dining' => [
		'label' => 'Dining & Entertainment',
		'color' => '#0A090C',
		'items' => [
			[ 'Church Street',          'Cafés, restaurants & boutiques off Bloomfield Ave', 40.81483, -74.22235, '5 min walk' ],
			[ 'Wellmont Theater',       'Concerts and live performances',                     40.81266, -74.21665, '13 min walk' ],
			[ 'The Clairidge',          'Montclair Film’s independent cinema',                40.81429, -74.21839, '8 min walk' ],
			[ 'Upper Montclair Village', 'Restaurants and shops around Valley Road',          40.84472, -74.19992, '7 min drive' ],
		],
	],
	'shopping' => [
		'label' => 'Shopping & Groceries',
		'color' => '#B6984E',
		'ink'   => '#0A090C',
		'items' => [
			[ 'Whole Foods Market', 'Groceries at the end of the block', 40.81858, -74.22286, '1 min walk' ],
			[ 'Lackawanna Plaza',   'Shopping center on Bloomfield Ave', 40.81238, -74.21354, '17 min walk' ],
		],
	],
	'rec' => [
		'label' => 'Recreation & Culture',
		'color' => '#6B6458',
		'items' => [
			[ 'Montclair Art Museum',         'American & Native American art',       40.81860, -74.22424, '5 min walk' ],
			[ 'Van Vleck House & Gardens',    'Historic estate gardens, free to visit', 40.82355, -74.22216, '12 min walk' ],
			[ 'Edgemont Memorial Park',       'Pond, paths and open lawns',           40.83070, -74.21254, '4 min drive' ],
			[ 'Anderson Park',                'Olmsted-designed park',                40.83963, -74.21020, '6 min drive' ],
			[ 'Brookdale Park',               '121-acre county park',                 40.83421, -74.18881, '8 min drive' ],
			[ 'Eagle Rock Reservation',       'Trails and Manhattan skyline views',   40.81034, -74.23926, '7 min drive' ],
			[ 'Presby Memorial Iris Gardens', 'Seasonal iris gardens',                40.85104, -74.20571, '7 min drive' ],
		],
	],
	'education' => [
		'label' => 'Education & Healthcare',
		'color' => '#CFC3A6',
		'ink'   => '#0A090C',
		'items' => [
			[ 'Montclair Public Library',    'Main branch',                40.81165, -74.21877, '15 min walk' ],
			[ 'Montclair High School',       'Montclair Public Schools',   40.82324, -74.21284, '19 min walk' ],
			[ 'Montclair State University',  'Public research university', 40.86469, -74.19692, '10 min drive' ],
			[ 'Mountainside Medical Center', 'Full-service hospital',      40.81122, -74.20403, '4 min drive' ],
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
			'ink'      => $data['ink'] ?? '#F8F6F1',
		];
	}
}

$use_google = $maps_key && ! bellclair_in_editor();

if ( $use_google ) {
	wp_add_inline_script(
		'bellclair-main',
		'window.BC_MAP = ' . wp_json_encode(
			[
				'center'   => [ 'lat' => 40.8240, 'lng' => -74.2140 ],
				'zoom'     => 13,
				'property' => [
					'lat'     => $home['lat'],
					'lng'     => $home['lng'],
					'title'   => 'Bellclair at Montclair',
					'address' => '7 & 8 Bell Street, Montclair, NJ',
					'origin'  => '7 Bell St, Montclair, NJ 07042',
				],
				'fallback' => $osm_embed,
				'pois'     => $map_pois,
			]
		) . ';',
		'before'
	);

	// Depends on bellclair-main so the initBellclairMap callback is defined
	// before the Maps API loads and calls it.
	wp_enqueue_script(
		'google-maps',
		'https://maps.googleapis.com/maps/api/js?key=' . rawurlencode( $maps_key ) . '&callback=initBellclairMap',
		[ 'bellclair-main' ],
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
          // Badges default to ivory numerals; the light steps override with black.
          $badge_style = 'background:' . $data['color'];
          if ( ! empty( $data['ink'] ) ) {
              $badge_style .= ';color:' . $data['ink'];
          }
      ?>
        <div class="poi-category-list" data-category="<?php echo esc_attr( $cat ); ?>">
          <div class="poi-cat-heading"><span class="poi-cat-dot" style="background:<?php echo esc_attr( $data['color'] ); ?>"></span><?php echo esc_html( $data['label'] ); ?></div>
          <?php foreach ( $data['items'] as $i => $item ) : ?>
            <div class="explorer-poi-item" data-poi="<?php echo esc_attr( $item[0] ); ?>"
                 data-lat="<?php echo esc_attr( $item[2] ?? '' ); ?>" data-lng="<?php echo esc_attr( $item[3] ?? '' ); ?>"><span class="poi-num-badge" style="<?php echo esc_attr( $badge_style ); ?>"><?php echo (int) $i + 1; ?></span><span><span class="explorer-poi-name"><?php echo esc_html( $item[0] ); ?></span><span class="explorer-poi-detail"><?php echo esc_html( $item[1] ); ?></span><?php if ( ! empty( $item[4] ) ) : ?><span class="explorer-poi-drive"><?php echo esc_html( $item[4] ); ?></span><?php endif; ?></span></div>
          <?php endforeach; ?>
        </div>
      <?php endforeach; ?>
    </div>

    <div class="explorer-actions">
      <a href="https://www.google.com/maps/search/?api=1&amp;query=7+Bell+St%2C+Montclair%2C+NJ+07042" id="explorer-directions" target="_blank" rel="noopener noreferrer" class="btn btn--primary">Get Directions</a>
      <a href="<?php echo esc_url( $contact ); ?>" class="btn btn--secondary">Contact Leasing Office</a>
    </div>
  </div>

  <div class="explorer__map-panel">
    <?php if ( $use_google ) : ?>
      <?php // Markers, styling and list/tab wiring live in main.js section 11. ?>
      <div id="poi-map" class="explorer-map"></div>
    <?php else : ?>
      <div id="poi-map" class="explorer-map" data-fallback="1">
        <iframe title="Map of Bellclair and nearby Montclair" src="<?php echo esc_url( $osm_embed ); ?>" loading="lazy"></iframe>
      </div>
    <?php endif; ?>
  </div>
</div>
