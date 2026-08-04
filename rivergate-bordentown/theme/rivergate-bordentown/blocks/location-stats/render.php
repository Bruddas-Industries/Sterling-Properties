<?php
/**
 * rivergate/location-stats — three commute stat cards. Copy is always visible;
 * the eye-catch is the travelling border highlight in global.css (no JS).
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
    <div class="loc-stat">
      <span class="loc-stat__num"><?php echo esc_html( $s['num'] ); ?></span>
      <span class="loc-stat__label"><?php echo esc_html( $s['label'] ); ?></span>
    </div>
  <?php endforeach; ?>
</div>
