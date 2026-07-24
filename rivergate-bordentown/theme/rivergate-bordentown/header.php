<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="rg-avail-url" content="<?php echo esc_url( home_url( '/availability/' ) ); ?>">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="site-header" id="site-header" role="banner">
    <nav class="nav-inner" aria-label="<?php esc_attr_e( 'Primary navigation', 'rivergate-bordentown' ); ?>">

        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="nav-logo" aria-label="<?php bloginfo( 'name' ); ?> — Home">
            <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/logos/rivergate-logo-blue.png"
                 alt="<?php bloginfo( 'name' ); ?>" height="40">
        </a>

        <div class="nav-actions">
            <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn btn--nav">
                <?php esc_html_e( 'Schedule a Tour', 'rivergate-bordentown' ); ?>
            </a>
            <button class="nav-toggle"
                    id="nav-toggle"
                    aria-controls="nav-overlay"
                    aria-expanded="false"
                    aria-label="<?php esc_attr_e( 'Toggle navigation menu', 'rivergate-bordentown' ); ?>">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>

    </nav>
</header>

<div id="nav-overlay" class="nav-overlay" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Navigation menu', 'rivergate-bordentown' ); ?>">
    <nav class="nav-overlay__links" aria-label="<?php esc_attr_e( 'Primary navigation', 'rivergate-bordentown' ); ?>">
        <a href="<?php echo esc_url( home_url( '/amenities/' ) ); ?>"><?php esc_html_e( 'Amenities', 'rivergate-bordentown' ); ?></a>
        <a href="<?php echo esc_url( home_url( '/floor-plans/' ) ); ?>"><?php esc_html_e( 'Floor Plans', 'rivergate-bordentown' ); ?></a>
        <a href="<?php echo esc_url( home_url( '/availability/' ) ); ?>"><?php esc_html_e( 'Availability', 'rivergate-bordentown' ); ?></a>
        <a href="<?php echo esc_url( home_url( '/gallery/' ) ); ?>"><?php esc_html_e( 'Gallery', 'rivergate-bordentown' ); ?></a>
        <a href="<?php echo esc_url( home_url( '/location/' ) ); ?>"><?php esc_html_e( 'Location', 'rivergate-bordentown' ); ?></a>
        <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Contact', 'rivergate-bordentown' ); ?></a>
    </nav>
    <div class="nav-overlay__divider"></div>
    <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn btn--primary nav-overlay__cta">
        <?php esc_html_e( 'Schedule a Tour', 'rivergate-bordentown' ); ?>
    </a>
</div>

<main id="main-content" role="main">
