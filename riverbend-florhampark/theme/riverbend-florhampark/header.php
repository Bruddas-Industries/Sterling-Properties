<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="rb-avail-url" content="<?php echo esc_url( home_url( '/availability/' ) ); ?>">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="site-header" id="site-header" role="banner">
    <nav class="nav-inner" aria-label="<?php esc_attr_e( 'Primary navigation', 'riverbend-florhampark' ); ?>">

        <?php /* The brand guide's secondary wordmark in two colourways: charcoal on the
                 cream field, cream over the hero photo and the open overlay.
                 global.css 29c shows whichever matches the background. */ ?>
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="nav-logo" aria-label="<?php bloginfo( 'name' ); ?> — Home">
            <img class="logo--on-light" src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/logos/riverbend-logo-charcoal.svg"
                 alt="<?php bloginfo( 'name' ); ?>" width="193" height="44">
            <img class="logo--on-dark" src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/logos/riverbend-logo-cream.svg"
                 alt="<?php bloginfo( 'name' ); ?>" width="193" height="44">
        </a>

        <div class="nav-actions">
            <a href="<?php echo esc_url( home_url( '/availability/' ) ); ?>" class="btn btn--nav">
                <?php esc_html_e( 'See Availability', 'riverbend-florhampark' ); ?>
            </a>
            <button class="nav-toggle"
                    id="nav-toggle"
                    aria-controls="nav-overlay"
                    aria-expanded="false"
                    aria-label="<?php esc_attr_e( 'Toggle navigation menu', 'riverbend-florhampark' ); ?>">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>

    </nav>
</header>

<div id="nav-overlay" class="nav-overlay" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Navigation menu', 'riverbend-florhampark' ); ?>">
    <nav class="nav-overlay__links" aria-label="<?php esc_attr_e( 'Primary navigation', 'riverbend-florhampark' ); ?>">
        <?php /* Availability leads; Residents closes. The section links scroll
                 the homepage; Availability, Gallery and Residents are pages. */ ?>
        <a href="<?php echo esc_url( home_url( '/availability/' ) ); ?>"><?php esc_html_e( 'Availability', 'riverbend-florhampark' ); ?></a>
        <a href="<?php echo esc_url( home_url( '/#amenities' ) ); ?>"><?php esc_html_e( 'Amenities', 'riverbend-florhampark' ); ?></a>
        <a href="<?php echo esc_url( home_url( '/#residences' ) ); ?>"><?php esc_html_e( 'Residences', 'riverbend-florhampark' ); ?></a>
        <a href="<?php echo esc_url( home_url( '/#floor-plans' ) ); ?>"><?php esc_html_e( 'Floor Plans', 'riverbend-florhampark' ); ?></a>
        <a href="<?php echo esc_url( home_url( '/gallery/' ) ); ?>"><?php esc_html_e( 'Gallery', 'riverbend-florhampark' ); ?></a>
        <a href="<?php echo esc_url( home_url( '/#neighborhood' ) ); ?>"><?php esc_html_e( 'Neighborhood', 'riverbend-florhampark' ); ?></a>
        <a href="<?php echo esc_url( home_url( '/residents/' ) ); ?>"><?php esc_html_e( 'Residents', 'riverbend-florhampark' ); ?></a>
    </nav>
    <div class="nav-overlay__divider"></div>
    <?php /* The header .btn--nav is hidden below 640px, so the overlay carries all three. */ ?>
    <div class="nav-overlay__ctas">
        <a href="<?php echo esc_url( home_url( '/availability/' ) ); ?>" class="btn btn--primary nav-overlay__cta">
            <?php esc_html_e( 'See Availability', 'riverbend-florhampark' ); ?>
        </a>
        <a href="<?php echo esc_url( home_url( '/#contact' ) ); ?>" class="btn btn--ghost nav-overlay__cta">
            <?php esc_html_e( 'Schedule a Tour', 'riverbend-florhampark' ); ?>
        </a>
        <a href="<?php echo esc_url( riverbend_apply_url() ); ?>" class="btn btn--ghost nav-overlay__cta" target="_blank" rel="noopener noreferrer">
            <?php esc_html_e( 'Apply Now', 'riverbend-florhampark' ); ?>
        </a>
    </div>
</div>

<main id="main-content" role="main">
