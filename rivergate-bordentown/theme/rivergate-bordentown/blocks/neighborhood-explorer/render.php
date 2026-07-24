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
$contact  = home_url( '/contact/' );

$categories = [
	'transit' => [
		'label' => 'Transit & Commuting',
		'color' => '#0472BB',
		'items' => [
			[ 'River Line Light Rail',  'Steps from Rivergate · Bordentown Station' ],
			[ 'Trenton Transit Center', 'NJ Transit + Amtrak · ~10 min via River Line' ],
			[ 'I-295 On-Ramp',          'Minutes away · North to NYC, South to Philadelphia' ],
			[ 'New Jersey Turnpike',    'Exit 7 · ~5 minutes' ],
			[ 'I-195 & Route 206',      'Access to Shore points and the Princeton corridor' ],
		],
	],
	'dining' => [
		'label' => 'Dining & Entertainment',
		'color' => '#C62828',
		'items' => [
			[ 'Bordentown City Dining',   'Restaurants, cafes, and bars on Farnsworth Ave' ],
			[ 'Trenton Restaurant Scene', 'Diverse dining · ~15 min' ],
			[ 'Hamilton Area Dining',     'Chain and local restaurants near Hamilton Marketplace' ],
		],
	],
	'shopping' => [
		'label' => 'Shopping & Retail',
		'color' => '#2E7D32',
		'items' => [
			[ 'Bordentown City',     'Boutiques, galleries, and markets · ~1 mile' ],
			[ 'Hamilton Marketplace','Target, Best Buy, major retail · ~10 min' ],
			[ 'MarketFair',          'Upscale shops, dining & cinema · ~25 min' ],
			[ 'Quaker Bridge Mall',  'Full regional mall · ~20 min' ],
		],
	],
	'rec' => [
		'label' => 'Recreation & Outdoors',
		'color' => '#6A1B9A',
		'items' => [
			[ 'Crystal Lake Park',         'Trails, fishing, open green space · ~5 min' ],
			[ 'Kuser Farm Park',           'Historic park with trails and events · ~15 min' ],
			[ 'Grounds For Sculpture',     'World-class sculpture park · ~20 min' ],
			[ 'Delaware River Waterfront', 'Walking and cycling paths · At your doorstep' ],
			[ 'The Jersey Shore',          'Point Pleasant, Asbury Park, LBI · ~60 min' ],
		],
	],
	'education' => [
		'label' => 'Education & Healthcare',
		'color' => '#E65100',
		'items' => [
			[ 'Bordentown Regional School District', 'K–12 schools serving the community' ],
			[ 'Princeton University',                '~20 min north via Route 206' ],
			[ 'Rider University',                    'Lawrenceville, NJ · ~20 min' ],
			[ 'Capital Health Medical Center',       'Hopewell, NJ · ~20 min' ],
		],
	],
];
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
      <?php foreach ( $categories as $cat => $data ) : ?>
        <div class="poi-category-list" data-category="<?php echo esc_attr( $cat ); ?>">
          <div class="poi-cat-heading"><span class="poi-cat-dot" style="background:<?php echo esc_attr( $data['color'] ); ?>"></span><?php echo esc_html( $data['label'] ); ?></div>
          <?php foreach ( $data['items'] as $i => $item ) : ?>
            <div class="explorer-poi-item" data-poi="<?php echo esc_attr( $item[0] ); ?>"><span class="poi-num-badge" style="background:<?php echo esc_attr( $data['color'] ); ?>"><?php echo (int) $i + 1; ?></span><span><span class="explorer-poi-name"><?php echo esc_html( $item[0] ); ?></span><span class="explorer-poi-detail"><?php echo esc_html( $item[1] ); ?></span></span></div>
          <?php endforeach; ?>
        </div>
      <?php endforeach; ?>
    </div>

    <div class="explorer-actions">
      <a href="https://maps.google.com/?q=500+Bluff+View+Circle+Bordentown+NJ+08505" target="_blank" rel="noopener noreferrer" class="btn btn--primary">Get Directions</a>
      <a href="<?php echo esc_url( $contact ); ?>" class="btn btn--secondary">Contact Leasing Office</a>
    </div>
  </div>

  <div class="explorer__map-panel">
    <?php if ( $maps_key && ! rivergate_in_editor() ) : ?>
      <div id="poi-map" class="explorer-map"></div>
      <script>
      function initRivergateMap() {
        var center = { lat: 40.1386, lng: -74.7204 };
        var map = new google.maps.Map(document.getElementById('poi-map'), {
          zoom: 13, center: center,
          styles: [
            { featureType: 'water', stylers: [{ color: '#C2D2E6' }] },
            { featureType: 'road', elementType: 'geometry', stylers: [{ color: '#FFFFFF' }] },
            { featureType: 'landscape', stylers: [{ color: '#EAF1F9' }] }
          ]
        });
        new google.maps.Marker({ position: center, map: map, title: 'Rivergate Bordentown',
          icon: { path: google.maps.SymbolPath.CIRCLE, scale: 10, fillColor: '#0472BB', fillOpacity: 1, strokeColor: '#fff', strokeWeight: 2 }
        });
      }
      </script>
      <script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo esc_attr( $maps_key ); ?>&callback=initRivergateMap"></script>
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
