<?php
/**
 * rivergate/amenities — accordion grid (rg_amenity CPT, with fallback) + photo.
 *
 * @var array $attributes Block attributes.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$img       = get_template_directory_uri() . '/assets/images';
$max       = (int) ( $attributes['maxItems'] ?? 8 );
$photo     = ! empty( $attributes['photoUrl'] ) ? $attributes['photoUrl'] : $img . '/clubhouse/clubhouse-1.jpg';
$caption   = (string) ( $attributes['photoCaption'] ?? '' );
$icon_map  = rivergate_amenity_icon_map();
$amenities = rivergate_get_amenities( $max );
// Andrew 8/26: this section is community/property amenities only — in-unit
// features live in the Residence Features section further down the page.
$amenities = array_values( array_filter( $amenities, static function ( $a ) {
	return ( $a['group'] ?? 'property' ) !== 'in-unit';
} ) );
?>
<div class="amenities-split">
  <div class="amenity-grid">
    <?php foreach ( $amenities as $a ) :
      $paths = $icon_map[ $a['icon_key'] ] ?? $icon_map['star']; ?>
      <?php $a_img = ! empty( $a['image'] )
          ? ( preg_match( '#^https?://#', $a['image'] ) ? $a['image'] : $img . '/' . $a['image'] )
          : ''; ?>
      <div class="amenity"<?php echo rivergate_anim(); ?> data-amenity-photo="<?php echo esc_url( $a_img ); ?>" data-amenity-name="<?php echo esc_attr( $a['name'] ); ?>">
        <button class="amenity__head" type="button" aria-expanded="false">
          <svg class="amenity__icon" viewBox="0 0 40 40" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><?php echo $paths; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG path data ?></svg>
          <span class="amenity__name"><?php echo esc_html( $a['name'] ); ?></span>
          <span class="amenity__plus" aria-hidden="true"></span>
        </button>
        <div class="amenity__panel" role="region">
          <p class="amenity__detail"><?php echo esc_html( $a['detail'] ); ?></p>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <div class="amenities-photo">
    <img src="<?php echo esc_url( $photo ); ?>" alt="<?php echo esc_attr( $caption ); ?>" loading="lazy">
    <?php if ( $caption ) : ?>
      <div class="amenities-photo__overlay">
        <p style="color:rgba(248,249,251,0.85);font-size:var(--text-xs);letter-spacing:var(--tracking-wider);text-transform:uppercase;margin:0;">
          <?php echo esc_html( $caption ); ?>
        </p>
      </div>
    <?php endif; ?>
  </div>
</div>
