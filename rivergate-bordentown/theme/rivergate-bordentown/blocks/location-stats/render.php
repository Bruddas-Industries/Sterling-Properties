<?php
/**
 * rivergate/location-stats — three tap-to-reveal stat cards (toggled by main.js).
 *
 * @var array $attributes Block attributes.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$stats = [
	[ 'num' => $attributes['stat1Num'] ?? '', 'label' => $attributes['stat1Label'] ?? '' ],
	[ 'num' => $attributes['stat2Num'] ?? '', 'label' => $attributes['stat2Label'] ?? '' ],
	[ 'num' => $attributes['stat3Num'] ?? '', 'label' => $attributes['stat3Label'] ?? '' ],
];
?>
<div class="loc-stats">
  <?php foreach ( $stats as $s ) :
    if ( '' === $s['num'] && '' === $s['label'] ) {
      continue;
    } ?>
    <button class="loc-stat" type="button" aria-expanded="false">
      <span class="loc-stat__num"><?php echo esc_html( $s['num'] ); ?></span>
      <span class="loc-stat__hint">Tap to reveal</span>
      <span class="loc-stat__label"><span><?php echo esc_html( $s['label'] ); ?></span></span>
    </button>
  <?php endforeach; ?>
</div>
