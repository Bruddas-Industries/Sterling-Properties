<?php
/**
 * rivergate/plan-cards — 1BR / 2BR tabbed floor-plan card grid.
 * Reads the rg_floor_plan CPT (with fallback). Tab switching is handled by
 * main.js (section 8: .plan-tab-btn / .plan-panel / #plan-{tab}).
 *
 * @var array $attributes Block attributes.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$plans     = rivergate_get_plans();
$apply_url = rivergate_apply_url();
$contact   = home_url( '/#contact' );

$one = array_filter( $plans, static fn( $p ) => (int) $p['bed'] === 1 );
$two = array_filter( $plans, static fn( $p ) => (int) $p['bed'] >= 2 );

/**
 * Render one floor-plan card.
 */
$render_card = static function ( array $p ) use ( $apply_url, $contact ): string {
	$featured = ! empty( $p['tag'] );
	ob_start(); ?>
	<div class="plan-card <?php echo $featured ? 'plan-card--featured' : 'plan-card--default'; ?>"<?php echo rivergate_anim(); ?>>
		<?php if ( $featured ) : ?>
			<span class="eyebrow" style="margin-bottom:0;"><?php echo esc_html( $p['tag'] ); ?></span>
		<?php endif; ?>
		<h3 style="font-size:var(--text-2xl);"><?php echo esc_html( $p['name'] ); ?></h3>
		<p style="font-size:var(--text-xs);letter-spacing:var(--tracking-wider);text-transform:uppercase;color:var(--color-text-muted);margin:0;"><?php echo esc_html( $p['code'] ); ?></p>
		<div class="plan-meta">
			<div class="plan-meta-item"><span>Beds</span><span><?php echo esc_html( $p['bed'] ); ?></span></div>
			<div class="plan-meta-item"><span>Baths</span><span><?php echo esc_html( $p['bath'] ); ?></span></div>
			<div class="plan-meta-item"><span>Sq Ft</span><span><?php echo esc_html( $p['sqft'] ); ?></span></div>
			<div class="plan-meta-item"><span>Balcony</span><span>Yes</span></div>
		</div>
		<?php if ( ! empty( $p['image'] ) ) : ?>
			<img class="plan-card__img" src="<?php echo esc_url( $p['image'] ); ?>" alt="<?php echo esc_attr( $p['name'] . ' floor plan — ' . $p['bed'] . ' bed, ' . $p['bath'] . ' bath, ' . $p['sqft'] . ' sq ft' ); ?>" loading="lazy">
		<?php endif; ?>
		<div style="display:flex;gap:var(--space-3);flex-wrap:wrap;">
			<?php if ( ! empty( $p['mp'] ) ) : ?>
				<button type="button" class="btn btn--secondary" data-mp="<?php echo esc_url( $p['mp'] ); ?>" data-name="<?php echo esc_attr( $p['name'] ); ?>">Take a Virtual Tour</button>
			<?php else : ?>
				<span class="btn btn--secondary fp-vtour-disabled" aria-disabled="true" title="Virtual tour coming soon">Virtual Tour Coming Soon</span>
			<?php endif; ?>
			<a href="<?php echo esc_url( $contact ); ?>" class="btn btn--secondary">Schedule a Tour</a>
			<?php if ( ! empty( $p['pdf'] ) ) : ?>
				<a href="<?php echo esc_url( $p['pdf'] ); ?>" class="btn btn--primary" target="_blank" rel="noopener noreferrer">Download PDF →</a>
			<?php else : ?>
				<a href="<?php echo esc_url( $apply_url ); ?>" class="btn btn--primary" target="_blank" rel="noopener noreferrer">Apply Now →</a>
			<?php endif; ?>
		</div>
	</div>
	<?php
	return (string) ob_get_clean();
};
?>
<div class="plan-tabs" role="tablist" aria-label="Floor plan categories">
  <button class="plan-tab-btn is-active" role="tab" aria-selected="true"  data-tab="1br">One Bedroom</button>
  <button class="plan-tab-btn"           role="tab" aria-selected="false" data-tab="2br">Two Bedroom</button>
</div>

<div id="plan-1br" class="plan-panel" role="tabpanel">
  <div class="card-grid">
    <?php foreach ( $one as $p ) { echo $render_card( $p ); } // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped within closure ?>
  </div>
</div>

<div id="plan-2br" class="plan-panel" role="tabpanel" hidden>
  <div class="card-grid">
    <?php foreach ( $two as $p ) { echo $render_card( $p ); } // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped within closure ?>
  </div>
</div>
