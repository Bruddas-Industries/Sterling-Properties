<?php
/**
 * rivergate/hero — front-end + editor render.
 *
 * @var array $attributes Block attributes.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$img = get_template_directory_uri() . '/assets/images';
$vid = get_template_directory_uri() . '/assets/video';

$eyebrow     = (string) ( $attributes['eyebrow'] ?? '' );
$headline    = (string) ( $attributes['headline'] ?? '' );
$subheadline = (string) ( $attributes['subheadline'] ?? '' );
$video       = ! empty( $attributes['videoUrl'] )  ? $attributes['videoUrl']  : $vid . '/rivergate-hero.mp4';
$poster      = ! empty( $attributes['posterUrl'] ) ? $attributes['posterUrl'] : $img . '/aerials/aerial-1.jpg';

$p_label = (string) ( $attributes['primaryLabel'] ?? '' );
$p_url   = (string) ( $attributes['primaryUrl'] ?? '#contact' );
$s_label = (string) ( $attributes['secondaryLabel'] ?? '' );
$s_url   = ! empty( $attributes['secondaryUrl'] ) ? $attributes['secondaryUrl'] : rivergate_apply_url();
?>
<section class="hero hero--video" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
  <div class="hero__bg">
    <video autoplay muted loop playsinline preload="auto" poster="<?php echo esc_url( $poster ); ?>">
      <source src="<?php echo esc_url( $video ); ?>" type="video/mp4">
      <img src="<?php echo esc_url( $poster ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?> aerial view">
    </video>
  </div>
  <div class="hero__content">
    <?php if ( $eyebrow ) : ?>
      <span class="eyebrow hero__eyebrow"><?php echo esc_html( $eyebrow ); ?></span>
    <?php endif; ?>
    <?php if ( $headline ) : ?>
      <h1 class="hero__headline"><?php echo esc_html( $headline ); ?></h1>
    <?php endif; ?>
    <?php if ( $subheadline ) : ?>
      <p class="hero__subheadline"><?php echo esc_html( $subheadline ); ?></p>
    <?php endif; ?>
    <div style="display:flex;gap:var(--space-4);flex-wrap:wrap;">
      <?php if ( $p_label ) : ?>
        <a href="<?php echo esc_url( $p_url ); ?>" class="btn btn--primary btn--pulse"><?php echo esc_html( $p_label ); ?></a>
      <?php endif; ?>
      <?php if ( $s_label ) : ?>
        <a href="<?php echo esc_url( $s_url ); ?>" class="btn btn--ghost" target="_blank" rel="noopener noreferrer"><?php echo esc_html( $s_label ); ?></a>
      <?php endif; ?>
    </div>
  </div>
</section>
