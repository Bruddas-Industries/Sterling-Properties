<?php
/**
 * bellclair/film-frame — poster with a play button. Any [data-film] trigger
 * opens the film modal in footer.php (main.js section 14). Styles: global.css 29d.
 *
 * @var array $attributes Block attributes.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$poster = ! empty( $attributes['posterUrl'] )
	? $attributes['posterUrl']
	: get_template_directory_uri() . '/assets/images/film/film-frame.jpg';
$label  = (string) ( $attributes['label'] ?? '' );
?>
<button type="button" class="film-frame" data-film<?php echo bellclair_anim( 'fade' ); ?> aria-label="<?php echo esc_attr( sprintf( 'Play the %s film', get_bloginfo( 'name' ) ) ); ?>">
  <img src="<?php echo esc_url( $poster ); ?>" alt="" loading="lazy">
  <span class="film-frame__corners" aria-hidden="true"><span></span></span>
  <span class="film-play" aria-hidden="true"><svg viewBox="0 0 10 12"><path d="M0 0l10 6-10 6z"/></svg></span>
  <?php if ( $label ) : ?>
    <span class="film-frame__label"><?php echo esc_html( $label ); ?></span>
  <?php endif; ?>
</button>
