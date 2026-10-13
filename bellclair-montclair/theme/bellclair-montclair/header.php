<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="bc-avail-url" content="<?php echo esc_url( home_url( '/availability/' ) ); ?>">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="site-header" id="site-header" role="banner">
    <nav class="nav-inner" aria-label="<?php esc_attr_e( 'Primary navigation', 'bellclair-montclair' ); ?>">

        <?php /* Two logos from the brand guide's "Color Usage" panel: black + gold on
                 the ivory field, champagne + gold over the video hero and the open
                 overlay. global.css 29b shows whichever matches the background. */ ?>
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="nav-logo" aria-label="<?php bloginfo( 'name' ); ?> — Home">
            <img class="logo--on-light" src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/logos/bellclair-logo-black-gold.png"
                 alt="<?php bloginfo( 'name' ); ?>" width="111" height="40">
            <img class="logo--on-dark" src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/logos/bellclair-logo-cream-gold.png"
                 alt="<?php bloginfo( 'name' ); ?>" width="111" height="40">
        </a>

        <div class="nav-actions">
            <a href="<?php echo esc_url( home_url( '/availability/' ) ); ?>" class="btn btn--nav">
                <?php esc_html_e( 'See Availability', 'bellclair-montclair' ); ?>
            </a>
            <button class="nav-toggle"
                    id="nav-toggle"
                    aria-controls="nav-overlay"
                    aria-expanded="false"
                    aria-label="<?php esc_attr_e( 'Toggle navigation menu', 'bellclair-montclair' ); ?>">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>

    </nav>
</header>

<div id="nav-overlay" class="nav-overlay" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Navigation menu', 'bellclair-montclair' ); ?>">
    <nav class="nav-overlay__links" aria-label="<?php esc_attr_e( 'Primary navigation', 'bellclair-montclair' ); ?>">
        <?php /* Availability leads; Residents closes. The section links scroll
                 the homepage; Availability, Gallery and Residents are pages. */ ?>
        <a href="<?php echo esc_url( home_url( '/availability/' ) ); ?>"><?php esc_html_e( 'Availability', 'bellclair-montclair' ); ?></a>
        <a href="<?php echo esc_url( home_url( '/#amenities' ) ); ?>"><?php esc_html_e( 'Amenities', 'bellclair-montclair' ); ?></a>
        <a href="<?php echo esc_url( home_url( '/#residences' ) ); ?>"><?php esc_html_e( 'Residences', 'bellclair-montclair' ); ?></a>
        <a href="<?php echo esc_url( home_url( '/#floor-plans' ) ); ?>"><?php esc_html_e( 'Floor Plans', 'bellclair-montclair' ); ?></a>
        <a href="<?php echo esc_url( home_url( '/gallery/' ) ); ?>"><?php esc_html_e( 'Gallery', 'bellclair-montclair' ); ?></a>
        <a href="<?php echo esc_url( home_url( '/#neighborhood' ) ); ?>"><?php esc_html_e( 'Neighborhood', 'bellclair-montclair' ); ?></a>
        <a href="<?php echo esc_url( home_url( '/residents/' ) ); ?>"><?php esc_html_e( 'Residents', 'bellclair-montclair' ); ?></a>
    </nav>
    <div class="nav-overlay__divider"></div>
    <?php /* The header .btn--nav is hidden below 640px, so the overlay carries all three. */ ?>
    <div class="nav-overlay__ctas">
        <a href="<?php echo esc_url( home_url( '/availability/' ) ); ?>" class="btn btn--primary nav-overlay__cta">
            <?php esc_html_e( 'See Availability', 'bellclair-montclair' ); ?>
        </a>
        <a href="<?php echo esc_url( home_url( '/#contact' ) ); ?>" class="btn btn--ghost nav-overlay__cta">
            <?php esc_html_e( 'Schedule a Tour', 'bellclair-montclair' ); ?>
        </a>
        <a href="<?php echo esc_url( bellclair_apply_url() ); ?>" class="btn btn--ghost nav-overlay__cta" target="_blank" rel="noopener noreferrer">
            <?php esc_html_e( 'Apply Now', 'bellclair-montclair' ); ?>
        </a>
    </div>
</div>

<main id="main-content" role="main">
