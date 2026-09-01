<?php
/**
 * Rivergate Bordentown — functions.php
 * Base template theme for Sterling Properties network.
 * Fork this file for each new property — update constants and text domain only.
 */

// Brand fonts (Logam + Noyh Geometric Slim) load via @font-face in global.css —
// the .otf files live in assets/fonts/. No external font request needed.
define( 'RIVERGATE_VERSION', '1.13.0' );
define( 'RIVERGATE_MAPS_API_KEY', 'AIzaSyBC-Y1aEPHAfptChXYDDZy210906U5IKVE' ); // Add your Google Maps API key here

// Block editor integration: dynamic blocks, block styles, patterns, body class.
require_once get_template_directory() . '/inc/blocks.php';
require_once get_template_directory() . '/inc/inquiry-popup.php';


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
    add_theme_support( 'align-wide' );
    add_theme_support( 'editor-styles' );
    add_theme_support( 'responsive-embeds' );
    add_editor_style( 'assets/css/global.css' );

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
    wp_enqueue_style(
        'rivergate-global',
        get_template_directory_uri() . '/assets/css/global.css',
        [],
        RIVERGATE_VERSION
    );

    wp_enqueue_script(
        'rivergate-main',
        get_template_directory_uri() . '/assets/js/main.js',
        [],
        RIVERGATE_VERSION,
        true
    );
}
add_action( 'wp_enqueue_scripts', 'rivergate_enqueue_assets' );


// ---------------------------------------------------------------------------
// 3. PAGE TEMPLATES
// ---------------------------------------------------------------------------

function rivergate_add_page_templates( array $templates ): array {
    // All pages are built with the block editor on the Block Canvas template,
    // using the "Rivergate Page — *" block patterns (see patterns/page-*.php).
    $templates['templates/page-canvas.php'] = __( 'Block Canvas (Full Width)', 'rivergate-bordentown' );
    $templates['templates/full-width.php']  = __( 'Rivergate Full Width', 'rivergate-bordentown' );
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


// ---------------------------------------------------------------------------
// 7. HELPER — get ACF field with fallback (graceful when ACF not active)
// ---------------------------------------------------------------------------

/**
 * Wrapper around get_field() that returns $fallback when ACF is not active
 * or when the field has no saved value.
 *
 * @param string    $name     ACF field name.
 * @param int|false $post_id  Post ID, or false for the current post.
 * @param mixed     $fallback Value returned when field is empty or ACF inactive.
 * @return mixed
 */
function rivergate_field( string $name, $post_id = false, $fallback = '' ) {
    if ( ! function_exists( 'get_field' ) ) {
        return $fallback;
    }
    $val = get_field( $name, $post_id );
    return ( $val !== false && $val !== null && $val !== '' ) ? $val : $fallback;
}


// ---------------------------------------------------------------------------
// 8. CUSTOM POST TYPES
// ---------------------------------------------------------------------------

function rivergate_register_post_types(): void {

    register_post_type( 'rg_amenity', [
        'labels'       => [
            'name'          => __( 'Amenities',    'rivergate-bordentown' ),
            'singular_name' => __( 'Amenity',      'rivergate-bordentown' ),
            'add_new_item'  => __( 'Add Amenity',  'rivergate-bordentown' ),
            'edit_item'     => __( 'Edit Amenity', 'rivergate-bordentown' ),
            'all_items'     => __( 'All Amenities','rivergate-bordentown' ),
        ],
        'public'       => false,
        'show_ui'      => true,
        'show_in_menu' => true,
        'menu_icon'    => 'dashicons-star-filled',
        'supports'     => [ 'title', 'page-attributes' ],
        'has_archive'  => false,
        'rewrite'      => false,
    ] );

    register_post_type( 'rg_floor_plan', [
        'labels'       => [
            'name'          => __( 'Floor Plans',    'rivergate-bordentown' ),
            'singular_name' => __( 'Floor Plan',     'rivergate-bordentown' ),
            'add_new_item'  => __( 'Add Floor Plan', 'rivergate-bordentown' ),
            'edit_item'     => __( 'Edit Floor Plan','rivergate-bordentown' ),
            'all_items'     => __( 'All Floor Plans','rivergate-bordentown' ),
        ],
        'public'       => false,
        'show_ui'      => true,
        'show_in_menu' => true,
        'menu_icon'    => 'dashicons-layout',
        'supports'     => [ 'title', 'page-attributes' ],
        'has_archive'  => false,
        'rewrite'      => false,
    ] );
}
add_action( 'init', 'rivergate_register_post_types' );


// ---------------------------------------------------------------------------
// 9. ACF FIELD GROUPS (registered in code — no DB entries required)
//    Requires: Advanced Custom Fields (free or Pro)
//    Install:  Plugins → Add New → search "Advanced Custom Fields"
// ---------------------------------------------------------------------------
// 9. ACF LOCAL JSON — point ACF at the theme's acf-json/ directory
//    Field group definitions live in acf-json/*.json (version-controlled).
//    Requires: Advanced Custom Fields (free or Pro)
// ---------------------------------------------------------------------------

add_filter( 'acf/settings/load_json', function ( array $paths ): array {
    $paths[] = get_template_directory() . '/acf-json';
    return $paths;
} );

add_filter( 'acf/settings/save_json', function (): string {
    return get_stylesheet_directory() . '/acf-json';
} );


// ---------------------------------------------------------------------------
// 10. LEGACY PAGE REDIRECTS
//     The standalone Contact and Location pages were folded into the homepage
//     (#contact and #neighborhood). Nothing links to them any more, but the
//     URLs stay valid so old links and search results still land somewhere
//     sensible instead of 404ing.
//
//     NOTE: 301 is cached hard by browsers. While iterating pre-launch you can
//     drop this to 302 with:
//         add_filter( 'rivergate_legacy_redirect_status', fn() => 302 );
// ---------------------------------------------------------------------------

function rivergate_legacy_page_redirects(): void {
    if ( is_admin() || wp_doing_ajax() || ! is_page() ) {
        return;
    }

    $map = apply_filters(
        'rivergate_legacy_redirects',
        [
            'contact'  => '/#contact',
            'location' => '/#neighborhood',
        ]
    );

    foreach ( $map as $slug => $target ) {
        if ( is_page( $slug ) ) {
            $status = (int) apply_filters( 'rivergate_legacy_redirect_status', 301 );
            wp_safe_redirect( home_url( $target ), $status );
            exit;
        }
    }
}
add_action( 'template_redirect', 'rivergate_legacy_page_redirects' );
