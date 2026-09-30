<?php
/**
 * rivergate/page-hero — inner-page hero (image + eyebrow + title).
 *
 * @var array $attributes Block attributes.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$img     = get_template_directory_uri() . '/assets/images';
$eyebrow = (string) ( $attributes['eyebrow'] ?? '' );
$heading = (string) ( $attributes['heading'] ?? '' );
$image   = ! empty( $attributes['imageUrl'] ) ? $attributes['imageUrl'] : $img . '/aerials/aerial-2.jpg';

// Which band of the photo the wide, short hero shows (see .page-hero--focus-* in global.css).
$focus = (string) ( $attributes['imageFocus'] ?? '' );
$class = in_array( $focus, [ 'top', 'center', 'lower', 'bottom' ], true ) ? ' page-hero--focus-' . $focus : '';
?>
<section class="page-hero<?php echo esc_attr( $class ); ?>" aria-label="<?php echo esc_attr( $heading ); ?>">
  <div class="page-hero__bg">
    <img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $heading ); ?>" loading="eager">
  </div>
  <div class="page-hero__content">
    <?php if ( $eyebrow ) : ?>
      <span class="eyebrow"><?php echo esc_html( $eyebrow ); ?></span>
    <?php endif; ?>
    <?php if ( $heading ) : ?>
      <h1><?php echo esc_html( $heading ); ?></h1>
    <?php endif; ?>
  </div>
</section>
