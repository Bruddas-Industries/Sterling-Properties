<?php
/**
 * riverbend/floor-plans — plan selector (rb_floor_plan CPT, with fallback).
 *
 * Server-renders the option labels and the default plan's detail panel so the
 * block previews correctly in the editor and works without JS. On the front end
 * main.js re-renders identically and wires up click selection, reading the PLANS
 * object injected below.
 *
 * @var array $attributes Block attributes.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$plans = riverbend_get_plans();
if ( empty( $plans ) ) {
	return;
}

$slugs     = array_keys( $plans );
$requested = sanitize_key( (string) ( $attributes['defaultPlan'] ?? '' ) );
$default   = ( $requested && isset( $plans[ $requested ] ) ) ? $requested
	: ( isset( $plans[ riverbend_default_plan() ] ) ? riverbend_default_plan() : $slugs[0] );

$avail_url = home_url( '/availability/' );
$apply_url = riverbend_apply_url();

// Inject the PLANS object for main.js (front end only).
if ( ! riverbend_in_editor() && wp_script_is( 'riverbend-main', 'registered' ) ) {
	wp_add_inline_script( 'riverbend-main', 'var PLANS = ' . wp_json_encode( $plans ) . ';', 'before' );
}
?>
<div class="fp-selector" data-default-plan="<?php echo esc_attr( $default ); ?>">
  <div class="fp-list" id="fp-list" role="listbox" aria-label="Floor plans">
    <?php foreach ( $slugs as $slug ) :
      $is_active = ( $slug === $default ); ?>
      <button class="fp-option<?php echo $is_active ? ' is-active' : ''; ?>"
              role="option"
              data-plan="<?php echo esc_attr( $slug ); ?>"
              aria-selected="<?php echo $is_active ? 'true' : 'false'; ?>"
              type="button"><?php
        echo riverbend_fp_option_inner( $plans[ $slug ] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped inside helper
      ?></button>
    <?php endforeach; ?>
  </div>
  <div id="fp-detail" class="fp-detail" aria-live="polite"><?php
    echo riverbend_fp_detail_html( $plans[ $default ], $avail_url, $apply_url ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped inside helper
  ?></div>
</div>
