<?php
/**
 * rivergate/availability-embed — AppFolio listings iframe + fallback.
 * main.js (section 11) hides the fallback once the iframe loads.
 *
 * @var array $attributes Block attributes.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$apply_url = ! empty( $attributes['applyUrl'] ) ? $attributes['applyUrl'] : rivergate_apply_url();
?>
<div class="appfolio-frame"<?php echo rivergate_anim(); ?>>
  <div id="appfolio-fallback" class="appfolio-fallback">
    <p>The AppFolio listing requires a new tab — click below to view live availability and apply.</p>
    <a href="<?php echo esc_url( $apply_url ); ?>" target="_blank" rel="noopener noreferrer" class="btn btn--primary">
      View Availability &amp; Apply in AppFolio →
    </a>
  </div>
  <iframe
    src="<?php echo esc_url( $apply_url ); ?>"
    width="100%"
    height="650"
    frameborder="0"
    title="Rivergate Bordentown — Available Units"
    loading="lazy"
    style="display:block;position:absolute;inset:0;height:100%;">
  </iframe>
</div>
