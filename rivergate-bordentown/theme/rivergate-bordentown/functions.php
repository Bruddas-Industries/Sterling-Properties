<?php
/**
 * Rivergate Bordentown — functions.php
 * Forked from Sterling Properties base theme.
 * Only constants and text domain differ from the base.
 */

define(
    'RIVERGATE_GOOGLE_FONTS_URL',
    'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=Inter:wght@300;400;500;700&display=swap'
);

define( 'RIVERGATE_VERSION', '1.0.0' );


// ---------------------------------------------------------------------------
// 1. THEME SETUP
// ---------------------------------------------------------------------------

function rivergate_theme_setup(): void {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', [
        'search-form', 'comment-form', 'comment-list',
        'gallery', 'caption', 'style', 'script',
    ] );
    add_filter( 'should_load_separate_core_block_assets', '__return_false' );
    add_theme_support( 'elementor' );

    register_nav_menus( [
        'primary' => __( 'Primary Navigation', 'rivergate-bordentown' ),
        'footer'  => __( 'Footer Navigation',  'rivergate-bordentown' ),
    ] );
}
add_action( 'after_setup_theme', 'rivergate_theme_setup' );


// ---------------------------------------------------------------------------
// 2. ENQUEUE ASSETS
// ---------------------------------------------------------------------------

function rivergate_enqueue_assets(): void {
    wp_enqueue_style( 'rivergate-fonts-preconnect', 'https://fonts.gstatic.com', [], null );
    wp_enqueue_style( 'rivergate-google-fonts', RIVERGATE_GOOGLE_FONTS_URL, [], null );
    wp_enqueue_style(
        'rivergate-global',
        get_template_directory_uri() . '/assets/css/global.css',
        [ 'rivergate-google-fonts' ],
        RIVERGATE_VERSION
    );

    wp_register_script( 'rivergate-scroll', false, [], RIVERGATE_VERSION, true );
    wp_enqueue_script( 'rivergate-scroll' );
    wp_add_inline_script(
        'rivergate-scroll',
        '(function(){
            var h = document.querySelector(".site-header");
            if (!h) return;
            window.addEventListener("scroll", function() {
                h.classList.toggle("is-scrolled", window.scrollY > 10);
            }, { passive: true });
        }())'
    );
}
add_action( 'wp_enqueue_scripts', 'rivergate_enqueue_assets' );


// ---------------------------------------------------------------------------
// 3. ELEMENTOR SUPPORT
// ---------------------------------------------------------------------------

function rivergate_elementor_support(): void {
    add_theme_support( 'elementor' );
}
add_action( 'elementor/init', 'rivergate_elementor_support' );

function rivergate_add_page_templates( array $templates ): array {
    $templates['templates/full-width.php'] = __( 'Rivergate Full Width', 'rivergate-bordentown' );
    return $templates;
}
add_filter( 'theme_page_templates', 'rivergate_add_page_templates' );


// ---------------------------------------------------------------------------
// 4. CLEAN UP WORDPRESS HEAD
// ---------------------------------------------------------------------------

function rivergate_clean_head(): void {
    remove_action( 'wp_head', 'rsd_link' );
    remove_action( 'wp_head', 'wlwmanifest_link' );
    remove_action( 'wp_head', 'wp_generator' );
    remove_action( 'wp_head', 'wp_shortlink_wp_head' );
    remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10 );
}
add_action( 'init', 'rivergate_clean_head' );


// ---------------------------------------------------------------------------
// 5. BODY CLASSES
// ---------------------------------------------------------------------------

function rivergate_body_classes( array $classes ): array {
    if ( function_exists( 'elementor_load_plugin_textdomain' ) ) {
        $classes[] = 'has-elementor';
    }
    if ( is_page() ) {
        $post = get_post();
        if ( $post ) {
            $classes[] = 'page-' . $post->post_name;
        }
    }
    return $classes;
}
add_filter( 'body_class', 'rivergate_body_classes' );


// ---------------------------------------------------------------------------
// 6. NAV WALKER & FALLBACK
// ---------------------------------------------------------------------------

if ( ! function_exists( 'rivergate_nav_fallback' ) ) {
    function rivergate_nav_fallback(): void {
        echo '<a href="' . esc_url( home_url( '/' ) ) . '">'
            . esc_html__( 'Home', 'rivergate-bordentown' )
            . '</a>';
    }
}

if ( ! class_exists( 'Rivergate_Nav_Walker' ) ) :
class Rivergate_Nav_Walker extends Walker_Nav_Menu {

    public function start_el( &$output, $data_object, $depth = 0, $args = null, $current_object_id = 0 ): void {
        $item  = $data_object;
        $url   = $item->url ?? '#';
        $label = $item->title ?? '';

        $output .= sprintf(
            '<a href="%s" role="listitem"%s>%s</a>',
            esc_url( $url ),
            in_array( 'current-menu-item', $item->classes, true ) ? ' aria-current="page"' : '',
            esc_html( $label )
        );
    }

    public function start_lvl( &$output, $depth = 0, $args = null ): void {}
    public function end_lvl( &$output, $depth = 0, $args = null ): void {}
    public function end_el( &$output, $data_object, $depth = 0, $args = null ): void {}
}
endif;
