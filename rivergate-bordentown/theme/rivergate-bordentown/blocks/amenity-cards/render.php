<?php
/**
 * rivergate/amenity-cards — icon card grid from the rg_amenity CPT (with fallback).
 * Shares data + icons with the homepage rivergate/amenities accordion.
 *
 * @var array $attributes Block attributes.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$max       = (int) ( $attributes['maxItems'] ?? 12 );
$icon_map  = rivergate_amenity_icon_map();
$amenities = rivergate_get_amenities( $max );
?>
<div class="card-grid">
  <?php foreach ( $amenities as $a ) :
    $paths = $icon_map[ $a['icon_key'] ] ?? $icon_map['star']; ?>
    <div class="card"<?php echo rivergate_anim(); ?>>
      <svg class="card__icon" viewBox="0 0 40 40" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><?php echo $paths; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG path data ?></svg>
      <h3 class="card__heading"><?php echo esc_html( $a['name'] ); ?></h3>
      <p class="card__text"><?php echo esc_html( $a['detail'] ); ?></p>
    </div>
  <?php endforeach; ?>
</div>
