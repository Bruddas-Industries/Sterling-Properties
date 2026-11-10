<?php
/**
 * riverbend/hero — front-end + editor render.
 *
 * A full-screen photo: the main entrance from the October 2026 exterior shoot
 * (Nina, "New Riverbend Photos", 2026-10-30, photo 10). Riverbend has no film
 * yet; if a background video is chosen in the sidebar it plays over the photo,
 * which stays as its poster.
 *
 * @var array $attributes Block attributes.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$img = get_template_directory_uri() . '/assets/images';

$eyebrow     = (string) ( $attributes['eyebrow'] ?? '' );
$headline    = (string) ( $attributes['headline'] ?? '' );
$subheadline = (string) ( $attributes['subheadline'] ?? '' );
$photo       = ! empty( $attributes['imageUrl'] ) ? $attributes['imageUrl'] : $img . '/exterior/main-entrance.jpg';
$photo_alt   = (string) ( $attributes['imageAlt'] ?? '' );
$video       = (string) ( $attributes['videoUrl'] ?? '' );

$p_label = (string) ( $attributes['primaryLabel'] ?? '' );
$p_url   = (string) ( $attributes['primaryUrl'] ?? '#contact' );
$s_label = (string) ( $attributes['secondaryLabel'] ?? '' );
$s_url   = (string) ( $attributes['secondaryUrl'] ?? '' );
?>
<section class="hero hero--video" aria-label="<?php echo esc_attr( sprintf( 'Welcome to %s', get_bloginfo( 'name' ) ) ); ?>">
  <div class="hero__bg">
    <?php if ( $video ) : ?>
      <video autoplay muted loop playsinline preload="auto" poster="<?php echo esc_url( $photo ); ?>">
        <source src="<?php echo esc_url( $video ); ?>" type="video/mp4">
      </video>
    <?php else : ?>
      <img src="<?php echo esc_url( $photo ); ?>" alt="<?php echo esc_attr( $photo_alt ); ?>" loading="eager" fetchpriority="high">
    <?php endif; ?>
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
      <?php if ( $s_label && $s_url ) : ?>
        <a href="<?php echo esc_url( $s_url ); ?>" class="btn btn--ghost"><?php echo esc_html( $s_label ); ?></a>
      <?php endif; ?>
    </div>
  </div>
</section>
