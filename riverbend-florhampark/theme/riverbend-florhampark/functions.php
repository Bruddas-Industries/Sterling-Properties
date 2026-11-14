<?php
/**
 * Riverbend Florham Park — functions.php
 * Riverbend at Florham Park, forked from bellclair-montclair 1.1.4.
 */

define( 'RIVERBEND_VERSION', '1.0.1' );
// Same referrer-restricted key as Rivergate (OPERATIONS trap 7): every domain
// Riverbend is served from must be in its allowed referrers.
define( 'RIVERBEND_MAPS_API_KEY', 'AIzaSyBC-Y1aEPHAfptChXYDDZy210906U5IKVE' );
// This property's sending address. FluentSMTP picks its connection by matching
// the From address against each connection's sender, exactly and case-sensitively,
// so this must match the sender saved in FluentSMTP character for character
// (inc/email-settings.php; OPERATIONS → Mail).
define( 'RIVERBEND_MAIL_FROM', 'riverbend@spgnj.com' );
// Brand faces are Condor Light, Acme Gothic Extrawide and Area Normal, which are
// commercial and not yet licensed for web. Jost, Syncopate and Manrope stand in
// until the files arrive (see assets/fonts/README.txt and the @font-face block at the end of
// global.css). Set this to '' once the licensed files are self-hosted.
define( 'RIVERBEND_FONTS_URL', 'https://fonts.googleapis.com/css2?family=Jost:wght@300;400&family=Manrope:wght@400;500;600&family=Syncopate&display=swap' );

// Block editor integration: dynamic blocks, block styles, patterns, body class.
require_once get_template_directory() . '/inc/blocks.php';
// Form submissions are saved as Leads before they are emailed; spam checks.
require_once get_template_directory() . '/inc/leads.php';
// Lets a non-Administrator (Sterling IT) configure FluentSMTP.
require_once get_template_directory() . '/inc/email-settings.php';
require_once get_template_directory() . '/inc/inquiry-popup.php';
// Titles, descriptions, Open Graph, JSON-LD, sitemap exclusions, author lockdown.
require_once get_template_directory() . '/inc/seo.php';


// ---------------------------------------------------------------------------
// 1. THEME SETUP
// ---------------------------------------------------------------------------

function riverbend_theme_setup(): void {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', [
        'search-form', 'comment-form', 'comment-list',
        'gallery', 'caption', 'style', 'script',
    ] );
    add_theme_support( 'align-wide' );
    add_theme_support( 'editor-styles' );
    add_theme_support( 'responsive-embeds' );
    add_editor_style( array_filter( [ RIVERBEND_FONTS_URL, 'assets/css/global.css' ] ) );

    register_nav_menus( [
        'primary' => __( 'Primary Navigation', 'riverbend-florhampark' ),
        'footer'  => __( 'Footer Navigation',  'riverbend-florhampark' ),
    ] );
}
add_action( 'after_setup_theme', 'riverbend_theme_setup' );


// ---------------------------------------------------------------------------
// 2. ENQUEUE ASSETS
// ---------------------------------------------------------------------------

function riverbend_enqueue_assets(): void {
    if ( RIVERBEND_FONTS_URL ) {
        wp_enqueue_style( 'riverbend-fonts', RIVERBEND_FONTS_URL, [], null );
    }

    wp_enqueue_style(
        'riverbend-global',
        get_template_directory_uri() . '/assets/css/global.css',
        RIVERBEND_FONTS_URL ? [ 'riverbend-fonts' ] : [],
        RIVERBEND_VERSION
    );

    wp_enqueue_script(
        'riverbend-main',
        get_template_directory_uri() . '/assets/js/main.js',
        [],
        RIVERBEND_VERSION,
        true
    );
}
add_action( 'wp_enqueue_scripts', 'riverbend_enqueue_assets' );

// Open the Google Fonts connections early.
function riverbend_resource_hints( array $urls, string $relation ): array {
    if ( RIVERBEND_FONTS_URL && 'preconnect' === $relation ) {
        $urls[] = 'https://fonts.googleapis.com';
        $urls[] = [ 'href' => 'https://fonts.gstatic.com', 'crossorigin' ];
    }
    return $urls;
}
add_filter( 'wp_resource_hints', 'riverbend_resource_hints', 10, 2 );


// ---------------------------------------------------------------------------
// 3. PAGE TEMPLATES
// ---------------------------------------------------------------------------

function riverbend_add_page_templates( array $templates ): array {
    // All pages are built with the block editor on the Block Canvas template,
    // using the "Riverbend Page — *" block patterns (see patterns/page-*.php).
    $templates['templates/page-canvas.php'] = __( 'Block Canvas (Full Width)', 'riverbend-florhampark' );
    $templates['templates/full-width.php']  = __( 'Riverbend Full Width', 'riverbend-florhampark' );
    return $templates;
}
add_filter( 'theme_page_templates', 'riverbend_add_page_templates' );


// ---------------------------------------------------------------------------
// 4. CLEAN UP WORDPRESS HEAD
// ---------------------------------------------------------------------------

function riverbend_clean_head(): void {
    remove_action( 'wp_head', 'rsd_link' );
    remove_action( 'wp_head', 'wlwmanifest_link' );
    remove_action( 'wp_head', 'wp_generator' );
    remove_action( 'wp_head', 'wp_shortlink_wp_head' );
    remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10 );
}
add_action( 'init', 'riverbend_clean_head' );

// Core's users sitemap lists every author of a published page — here, the
// developer login. A property site has no author archives worth indexing.
function riverbend_remove_users_sitemap( $provider, string $name ) {
    return 'users' === $name ? false : $provider;
}
add_filter( 'wp_sitemaps_add_provider', 'riverbend_remove_users_sitemap', 10, 2 );

// Browser/home-screen icon: the square mark from the logo, shipped with the
// theme in assets/images/site-icon/ (one PNG per size core asks for). Feeding
// it through get_site_icon_url lets core emit every <link rel="icon"> tag on
// the front end, wp-admin and login, and makes /favicon.ico redirect here
// instead of to the WordPress logo. A Site Icon set in the Customizer still
// wins — the theme icon only stands in while that option is empty.
function riverbend_site_icon_url( $url, int $size, $blog_id ) {
    if ( get_option( 'site_icon' ) || ( $blog_id && (int) $blog_id !== get_current_blog_id() ) ) {
        return $url;
    }
    $file = 512;
    foreach ( [ 32, 180, 192, 270 ] as $available ) {
        if ( $size <= $available ) {
            $file = $available;
            break;
        }
    }
    return get_template_directory_uri() . '/assets/images/site-icon/site-icon-' . $file . '.png?ver=' . RIVERBEND_VERSION;
}
add_filter( 'get_site_icon_url', 'riverbend_site_icon_url', 10, 3 );


// ---------------------------------------------------------------------------
// 5. BODY CLASSES
// ---------------------------------------------------------------------------

function riverbend_body_classes( array $classes ): array {
    if ( is_page() ) {
        $post = get_post();
        if ( $post ) {
            $classes[] = 'page-' . $post->post_name;
        }
    }
    return $classes;
}
add_filter( 'body_class', 'riverbend_body_classes' );


// ---------------------------------------------------------------------------
// 6. NAV WALKER & FALLBACK
// ---------------------------------------------------------------------------

if ( ! function_exists( 'riverbend_nav_fallback' ) ) {
    function riverbend_nav_fallback(): void {
        echo '<a href="' . esc_url( home_url( '/' ) ) . '">'
            . esc_html__( 'Home', 'riverbend-florhampark' )
            . '</a>';
    }
}

if ( ! class_exists( 'Riverbend_Nav_Walker' ) ) :
class Riverbend_Nav_Walker extends Walker_Nav_Menu {

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
function riverbend_field( string $name, $post_id = false, $fallback = '' ) {
    if ( ! function_exists( 'get_field' ) ) {
        return $fallback;
    }
    $val = get_field( $name, $post_id );
    return ( $val !== false && $val !== null && $val !== '' ) ? $val : $fallback;
}


// ---------------------------------------------------------------------------
// 8. CUSTOM POST TYPES
// ---------------------------------------------------------------------------

function riverbend_register_post_types(): void {

    register_post_type( 'rb_amenity', [
        'labels'       => [
            'name'          => __( 'Amenities',    'riverbend-florhampark' ),
            'singular_name' => __( 'Amenity',      'riverbend-florhampark' ),
            'add_new_item'  => __( 'Add Amenity',  'riverbend-florhampark' ),
            'edit_item'     => __( 'Edit Amenity', 'riverbend-florhampark' ),
            'all_items'     => __( 'All Amenities','riverbend-florhampark' ),
        ],
        'public'       => false,
        'show_ui'      => true,
        'show_in_menu' => true,
        'menu_icon'    => 'dashicons-star-filled',
        'supports'     => [ 'title', 'page-attributes' ],
        'has_archive'  => false,
        'rewrite'      => false,
    ] );

    register_post_type( 'rb_floor_plan', [
        'labels'       => [
            'name'          => __( 'Floor Plans',    'riverbend-florhampark' ),
            'singular_name' => __( 'Floor Plan',     'riverbend-florhampark' ),
            'add_new_item'  => __( 'Add Floor Plan', 'riverbend-florhampark' ),
            'edit_item'     => __( 'Edit Floor Plan','riverbend-florhampark' ),
            'all_items'     => __( 'All Floor Plans','riverbend-florhampark' ),
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
add_action( 'init', 'riverbend_register_post_types' );


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
// 10. LEGACY URL REDIRECTS
//     riverbendatflorhampark.com was a 2016 WordPress site (Wellsbrook /
//     parallelus-vellum themes) before this one. Its pages are indexed and
//     bookmarked, so once the domain points here each old URL is sent to its
//     new home instead of 404ing. Inventory: the old site's page and portfolio
//     sitemaps and its header links (2026-11-13). The theme-demo pages that
//     shipped with the old theme (samples, features, our-work, blog) are left
//     to 404 so search engines drop them.
//
//     Only requests that would otherwise 404 are redirected, so a real page
//     at the same path always wins. /residents/ exists on both sites and
//     needs no entry.
//
//     NOTE: 301 is cached hard by browsers. While iterating pre-launch you can
//     drop this to 302 with:
//         add_filter( 'riverbend_legacy_redirect_status', fn() => 302 );
// ---------------------------------------------------------------------------

// Old path (relative to the site root, no slashes at either end) => new
// target. A trailing "/*" matches everything below that path.
function riverbend_legacy_redirect_map(): array {
    return apply_filters(
        'riverbend_legacy_redirects',
        [
            'amenities'                         => '/#amenities',
            'floor-plans'                       => '/#floor-plans',
            'photo-gallery'                     => '/gallery/',
            'portfolio/*'                       => '/gallery/',      // old gallery photo pages
            'view-availability'                 => '/availability/',
            'request-availability-the-madison'    => '/availability/',
            'request-availability-the-livingston' => '/availability/',
            'request-availability-the-chatham'    => '/availability/',
            'request-availability-the-florham'    => '/availability/',
            'thank-request-availability'        => '/availability/',
            'location'                          => '/#neighborhood',
            'contact'                           => '/#contact',
            'thank-you'                         => '/#contact',
            'about'                             => '/#about',
            'residents-4-blocks'                => '/residents/',
        ]
    );
}

/**
 * The redirect target for a request path, or '' if none applies.
 *
 * @param string $path Request path relative to the site root, e.g. "floor-plans".
 */
function riverbend_legacy_redirect_target( string $path ): string {
    $path = strtolower( trim( $path, '/' ) );
    if ( '' === $path ) {
        return '';
    }
    foreach ( riverbend_legacy_redirect_map() as $old => $target ) {
        $old = strtolower( trim( $old, '/' ) );
        if ( str_ends_with( $old, '/*' ) ) {
            $prefix = substr( $old, 0, -2 );
            if ( $path === $prefix || str_starts_with( $path, $prefix . '/' ) ) {
                return $target;
            }
        } elseif ( $path === $old ) {
            return $target;
        }
    }
    return '';
}

function riverbend_legacy_url_redirects(): void {
    if ( ! is_404() ) {
        return;
    }
    $request = (string) wp_parse_url( (string) ( $_SERVER['REQUEST_URI'] ?? '' ), PHP_URL_PATH );
    // Strip the site's own path, which is not "/" while it lives on the staging host.
    $base = (string) wp_parse_url( home_url( '/' ), PHP_URL_PATH );
    if ( '' !== $base && str_starts_with( $request, $base ) ) {
        $request = substr( $request, strlen( $base ) );
    }
    $target = riverbend_legacy_redirect_target( rawurldecode( $request ) );
    if ( '' === $target ) {
        return;
    }
    $status = (int) apply_filters( 'riverbend_legacy_redirect_status', 301 );
    wp_safe_redirect( home_url( $target ), $status );
    exit;
}
// Priority 1: before core's redirect_canonical(), which would otherwise try to
// guess a "close" page for a 404.
add_action( 'template_redirect', 'riverbend_legacy_url_redirects', 1 );
