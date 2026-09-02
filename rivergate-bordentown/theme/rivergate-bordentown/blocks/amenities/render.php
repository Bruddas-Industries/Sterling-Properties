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
$max       = (int) ( $attributes['maxItems'] ?? 12 );
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
      $ico = $icon_map[ $a['icon_key'] ] ?? $icon_map['star']; ?>
      <?php $a_img = ! empty( $a['image'] )
          ? ( preg_match( '#^https?://#', $a['image'] ) ? $a['image'] : $img . '/' . $a['image'] )
          : ''; ?>
      <div class="amenity"<?php echo rivergate_anim(); ?> data-amenity-photo="<?php echo esc_url( $a_img ); ?>" data-amenity-name="<?php echo esc_attr( $a['name'] ); ?>">
        <button class="amenity__head" type="button" aria-expanded="false">
          <svg class="amenity__icon" viewBox="<?php echo esc_attr( $ico['view'] ); ?>" fill="currentColor" aria-hidden="true"><?php echo $ico['path']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static icon path data ?></svg>
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
    <?php // Preview parity (Jephsenn 9/1): tour + gallery actions, no caption strip. ?>
    <div class="amenities-photo__overlay">
      <div style="display:flex;gap:var(--space-3);flex-wrap:wrap;">
        <button type="button" class="btn btn--primary" data-mp="https://my.matterport.com/show/?m=RUxNh5SDzmG" data-name="Rivergate Amenities">Take a Virtual Tour</button>
        <a href="<?php echo esc_url( home_url( '/gallery/' ) ); ?>" class="btn btn--ghost">View Gallery</a>
      </div>
    </div>
  </div>
</div>
