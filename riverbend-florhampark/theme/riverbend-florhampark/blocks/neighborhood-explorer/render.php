<?php
/**
 * riverbend/neighborhood-explorer — filterable POI list + map panel.
 * Tab filtering is handled by main.js (section 9), the map by section 11. The
 * Google Map renders when RIVERBEND_MAPS_API_KEY is set; if Google refuses the
 * key on this domain, main.js swaps in the OpenStreetMap embed below so the
 * section never shows a grey error box.
 *
 * @var array $attributes Block attributes.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$maps_key = defined( 'RIVERBEND_MAPS_API_KEY' ) ? RIVERBEND_MAPS_API_KEY : '';
$contact  = home_url( '/#contact' );

// The clubhouse building beside the pool (OpenStreetMap way 1368465163), taken
// as Riverbend's origin: "1901 Sterling Dr" is not in OSM and the Census
// geocoder does not match it. The map's home marker and the origin of every
// time below.
$home = [ 'lat' => 40.7533634, 'lng' => -74.3695411 ];

// Shown when there is no key, in the editor, and (via main.js) if Google
// rejects the key.
$osm_embed = 'https://www.openstreetmap.org/export/embed.html?bbox=-74.5000%2C40.6950%2C-74.2600%2C40.8150&layer=mapnik&marker='
	. $home['lat'] . '%2C' . $home['lng'];

// Each item is [ name, detail, lat, lng, time ]. Coordinates drive the numbered
// map markers, so the badge number always matches the marker. Times are OSRM
// routing figures from the clubhouse (2026-10-20): foot routing under 25
// minutes on foot, car routing beyond that, and drive times are free-flow (no
// traffic), as the section intro says. Riverbend is a driving neighborhood, so
// map directions default to driving and switch to walking only for places whose
// time is a walk (main.js). Re-measure rather than guess if a place is added.
$categories = [
	'transit' => [
		'label' => 'Transit & Commuting',
		'color' => '#A63F35',
		'items' => [
			[ 'Chatham Station',                      'NJ Transit Morris & Essex Line · shuttle stop', 40.74011, -74.38510, '4 min drive' ],
			[ 'Madison Station',                      'NJ Transit Morris & Essex Line',                40.75703, -74.41531, '9 min drive' ],
			[ 'Summit Station',                       'NJ Transit Morris & Essex Line',                40.71657, -74.35770, '11 min drive' ],
			[ 'Morristown Station',                   'NJ Transit Morris & Essex Line',                40.79740, -74.47430, '17 min drive' ],
			[ 'Newark Liberty International Airport', 'Terminal B',                                    40.69002, -74.17597, '24 min drive' ],
		],
	],
	'dining' => [
		'label' => 'Dining & Entertainment',
		'color' => '#232121',
		'items' => [
			[ 'Downtown Chatham',            'Main Street shops and restaurants',          40.74058, -74.38205, '3 min drive' ],
			[ 'Florham Park Town Center',    'Shops and restaurants on Columbia Turnpike', 40.78804, -74.38955, '9 min drive' ],
			[ 'Paper Mill Playhouse',        'Regional theatre in Millburn',               40.72804, -74.30917, '12 min drive' ],
			[ 'Morristown Green',            'Historic green at the heart of downtown',    40.79735, -74.48091, '17 min drive' ],
			[ 'Mayo Performing Arts Center', 'Concerts and touring shows, Morristown',     40.79434, -74.47794, '18 min drive' ],
		],
	],
	'shopping' => [
		'label' => 'Shopping & Groceries',
		'color' => '#3F6894',
		'items' => [
			[ 'Whole Foods Market',      'Groceries on Main Street, Madison',            40.75349, -74.40551, '7 min drive' ],
			[ 'Livingston Mall',         'Enclosed shopping mall',                       40.77690, -74.35385, '7 min drive' ],
			[ 'Trader Joe\'s',           'Groceries on Columbia Turnpike, Florham Park', 40.78950, -74.39123, '9 min drive' ],
			[ 'The Mall at Short Hills', 'Upscale shopping mall',                        40.73988, -74.36355, '10 min drive' ],
		],
	],
	'rec' => [
		'label' => 'Recreation & Outdoors',
		'color' => '#56735A',
		'items' => [
			[ 'Centercourt Athletic Club',            'Racquet club next door on N. Passaic Ave', 40.75097, -74.37082, '5 min walk' ],
			[ 'Memorial Park',                        'Borough park in Chatham',                  40.74197, -74.38014, '22 min walk' ],
			[ 'West Essex Park',                      'County park along the Passaic River',      40.78319, -74.36712, '7 min drive' ],
			[ 'Great Swamp National Wildlife Refuge', 'Boardwalk trails and wildlife blinds',     40.71252, -74.48753, '21 min drive' ],
		],
	],
	'education' => [
		'label' => 'Education & Healthcare',
		'color' => '#A0BEE2',
		'ink'   => '#232121',
		'items' => [
			[ 'Florham Park Public Library',       'Ridgedale Avenue',                  40.78322, -74.39975, '11 min drive' ],
			[ 'Cooperman Barnabas Medical Center', 'Full-service hospital, Livingston', 40.76346, -74.30484, '11 min drive' ],
			[ 'Drew University',                   'Private university, Madison',       40.76054, -74.42816, '12 min drive' ],
			[ 'Fairleigh Dickinson University',    'Florham Campus',                    40.77572, -74.43382, '14 min drive' ],
			[ 'Morristown Medical Center',         'Full-service hospital',             40.78957, -74.46568, '17 min drive' ],
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
			'ink'      => $data['ink'] ?? '#F5F1E9',
		];
	}
}

$use_google = $maps_key && ! riverbend_in_editor();

if ( $use_google ) {
	wp_add_inline_script(
		'riverbend-main',
		'window.RB_MAP = ' . wp_json_encode(
			[
				'center'   => [ 'lat' => 40.7600, 'lng' => -74.3900 ],
				'zoom'     => 12,
				'property' => [
					'lat'     => $home['lat'],
					'lng'     => $home['lng'],
					'title'   => 'Riverbend at Florham Park',
					'address' => '1901 Sterling Drive, Florham Park, NJ',
					'origin'  => '1901 Sterling Dr, Florham Park, NJ 07932',
				],
				'fallback' => $osm_embed,
				'pois'     => $map_pois,
			]
		) . ';',
		'before'
	);

	// Depends on riverbend-main so the initRiverbendMap callback is defined
	// before the Maps API loads and calls it.
	wp_enqueue_script(
		'google-maps',
		'https://maps.googleapis.com/maps/api/js?key=' . rawurlencode( $maps_key ) . '&callback=initRiverbendMap',
		[ 'riverbend-main' ],
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
          // Badges default to cream numerals; the pale sky step overrides with charcoal.
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
      <a href="https://www.google.com/maps/search/?api=1&amp;query=1901+Sterling+Dr%2C+Florham+Park%2C+NJ+07932" id="explorer-directions" target="_blank" rel="noopener noreferrer" class="btn btn--primary">Get Directions</a>
      <a href="<?php echo esc_url( $contact ); ?>" class="btn btn--secondary">Contact Leasing Office</a>
    </div>
  </div>

  <div class="explorer__map-panel">
    <?php if ( $use_google ) : ?>
      <?php // Markers, styling and list/tab wiring live in main.js section 11. ?>
      <div id="poi-map" class="explorer-map"></div>
    <?php else : ?>
      <div id="poi-map" class="explorer-map" data-fallback="1">
        <iframe title="Map of Riverbend and nearby Florham Park" src="<?php echo esc_url( $osm_embed ); ?>" loading="lazy"></iframe>
      </div>
    <?php endif; ?>
  </div>
</div>
