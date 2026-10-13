<?php
/**
 * bellclair/hero — front-end + editor render.
 *
 * The background loop is cut from VTC's "Wide_Silent" film (title and end cards
 * trimmed). Phones get the 720p encode via <source media>; browsers that ignore
 * `media` take the first source, so the smaller file is the safe fallback.
 * The secondary button opens the full film with sound unless it is given a URL.
 *
 * @var array $attributes Block attributes.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$img = get_template_directory_uri() . '/assets/images';
$vid = get_template_directory_uri() . '/assets/video';

$eyebrow      = (string) ( $attributes['eyebrow'] ?? '' );
$headline     = (string) ( $attributes['headline'] ?? '' );
$subheadline  = (string) ( $attributes['subheadline'] ?? '' );
$video        = ! empty( $attributes['videoUrl'] )       ? $attributes['videoUrl']       : $vid . '/bellclair-hero.mp4';
$video_mobile = ! empty( $attributes['videoMobileUrl'] ) ? $attributes['videoMobileUrl'] : ( empty( $attributes['videoUrl'] ) ? $vid . '/bellclair-hero-720.mp4' : '' );
$poster       = ! empty( $attributes['posterUrl'] )      ? $attributes['posterUrl']      : $img . '/film/hero-poster.jpg';

$p_label = (string) ( $attributes['primaryLabel'] ?? '' );
$p_url   = (string) ( $attributes['primaryUrl'] ?? '#contact' );
$s_label = (string) ( $attributes['secondaryLabel'] ?? '' );
$s_url   = (string) ( $attributes['secondaryUrl'] ?? '' );
?>
<section class="hero hero--video" aria-label="<?php echo esc_attr( sprintf( 'Welcome to %s', get_bloginfo( 'name' ) ) ); ?>">
  <div class="hero__bg">
    <video autoplay muted loop playsinline preload="auto" poster="<?php echo esc_url( $poster ); ?>">
      <?php if ( $video_mobile ) : ?>
        <source src="<?php echo esc_url( $video_mobile ); ?>" type="video/mp4" media="(max-width: 900px)">
      <?php endif; ?>
      <source src="<?php echo esc_url( $video ); ?>" type="video/mp4">
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
      <?php if ( $s_label && $s_url ) : ?>
        <a href="<?php echo esc_url( $s_url ); ?>" class="btn btn--ghost"><?php echo esc_html( $s_label ); ?></a>
      <?php elseif ( $s_label ) : ?>
        <button type="button" class="btn btn--ghost" data-film><svg class="btn__play" viewBox="0 0 10 12" aria-hidden="true"><path d="M0 0l10 6-10 6z"/></svg> <?php echo esc_html( $s_label ); ?></button>
      <?php endif; ?>
    </div>
  </div>
</section>
