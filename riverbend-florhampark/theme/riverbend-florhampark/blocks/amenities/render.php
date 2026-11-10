<?php
/**
 * riverbend/amenities — accordion grid (rb_amenity CPT, with fallback) + photo.
 *
 * Opening an amenity swaps the photo and its caption (main.js section 12);
 * amenities without a photo leave the default in place. The panel's second
 * button opens a Matterport tour (Chatham by default) in the lightbox.
 *
 * @var array $attributes Block attributes.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$img       = get_template_directory_uri() . '/assets/images';
$max       = (int) ( $attributes['maxItems'] ?? 12 );
$photo     = ! empty( $attributes['photoUrl'] ) ? $attributes['photoUrl'] : $img . '/amenities/pool-loungers.jpg';
$caption   = (string) ( $attributes['photoCaption'] ?? '' );
$tour_url  = (string) ( $attributes['tourUrl'] ?? '' );
$tour_name = (string) ( $attributes['tourName'] ?? '' );
$amenities = riverbend_get_amenities( $max );
// Community/property amenities only — in-unit features live in the Residence
// Features section further down the page.
$amenities = array_values( array_filter( $amenities, static function ( $a ) {
	return ( $a['group'] ?? 'property' ) !== 'in-unit';
} ) );
?>
<div class="amenities-split">
  <div class="amenity-grid">
    <?php foreach ( $amenities as $a ) :
      $a_img = ! empty( $a['image'] )
          ? ( preg_match( '#^https?://#', $a['image'] ) ? $a['image'] : $img . '/' . $a['image'] )
          : ''; ?>
      <div class="amenity"<?php echo riverbend_anim(); ?> data-amenity-photo="<?php echo esc_url( $a_img ); ?>" data-amenity-name="<?php echo esc_attr( $a['name'] ); ?>">
        <button class="amenity__head" type="button" aria-expanded="false">
          <?php echo riverbend_amenity_icon_svg( (string) $a['icon_key'], 'amenity__icon' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped inside helper ?>
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
    <div class="amenities-photo__overlay">
      <?php if ( $caption ) : ?>
        <span class="amenities-photo__caption"><?php echo esc_html( $caption ); ?></span>
      <?php endif; ?>
      <div style="display:flex;gap:var(--space-3);flex-wrap:wrap;">
        <a href="<?php echo esc_url( home_url( '/gallery/' ) ); ?>" class="btn btn--primary">View Gallery</a>
        <?php if ( $tour_url ) : ?>
          <button type="button" class="btn btn--ghost" data-mp="<?php echo esc_url( $tour_url ); ?>" data-name="<?php echo esc_attr( $tour_name ); ?>">Take a 3D Tour</button>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
