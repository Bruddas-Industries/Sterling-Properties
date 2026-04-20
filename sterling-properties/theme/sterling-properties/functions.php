<?php
/**
 * Sterling Properties — functions.php
 *
 * Responsibilities:
 *   1. Theme setup (title-tag, menus, thumbnails, Elementor support)
 *   2. Enqueue Google Fonts
 *   3. Enqueue the global CSS stylesheet
 *   4. Minimal scroll-detection script for the sticky nav shadow
 *
 * CUSTOMIZATION GUIDE
 * -------------------
 * FONTS    → Update GOOGLE_FONTS_URL constant below (line ~45) when
 *             swapping typefaces for a new site.
 * MENUS    → Register menu locations in sterling_register_menus() below.
 * SCRIPTS  → Add additional enqueues in sterling_enqueue_assets() below.
 */

// ---------------------------------------------------------------------------
// 0. CONSTANTS — change these when adapting for a new site
// ---------------------------------------------------------------------------

/**
 * Google Fonts URL.
 * Current stack: Cormorant Garamond (headings) + Inter (body).
 *
 * To change fonts:
 *   1. Visit https://fonts.google.com and build a new embed URL.
 *   2. Replace the constant below.
 *   3. Update --font-heading / --font-body in assets/css/global.css.
 */
define(
    'STERLING_GOOGLE_FONTS_URL',
    'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=Inter:wght@300;400;500;700&display=swap'
);

/** Theme version — bump on every deploy to bust CSS/JS caches. */
define( 'STERLING_VERSION', '1.0.0' );


// ---------------------------------------------------------------------------
// 1. THEME SETUP
// ---------------------------------------------------------------------------

/**
 * Register theme features and Elementor compatibility.
 */
function sterling_theme_setup(): void {

    // Allow WordPress to generate the <title> tag automatically.
    add_theme_support( 'title-tag' );

    // Enable featured images (used as fallback in non-Elementor templates).
    add_theme_support( 'post-thumbnails' );

    // HTML5 semantic markup for core WordPress output.
    add_theme_support( 'html5', [
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ] );

    // Disable the WordPress block editor stylesheet (we don't use Gutenberg).
    // Comment this line out if you add Gutenberg support later.
    add_filter( 'should_load_separate_core_block_assets', '__return_false' );

    // Allow Elementor full-width templates (no header/footer injected by theme).
    add_theme_support( 'elementor' );

    // Register navigation menu locations.
    // Add or remove locations here — they appear in Appearance → Menus.
    register_nav_menus( [
        'primary'  => __( 'Primary Navigation', 'sterling-properties' ),
        'footer'   => __( 'Footer Navigation',  'sterling-properties' ),
    ] );
}
add_action( 'after_setup_theme', 'sterling_theme_setup' );


// ---------------------------------------------------------------------------
// 2. ENQUEUE ASSETS
// ---------------------------------------------------------------------------

/**
 * Load Google Fonts and the global stylesheet.
 * Fires on wp_enqueue_scripts (front-end only).
 */
function sterling_enqueue_assets(): void {

    // --- Google Fonts ---
    // Preconnect to Google's font servers for faster loading.
    wp_enqueue_style(
        'sterling-fonts-preconnect',
        'https://fonts.gstatic.com',
        [],
        null
    );

    wp_enqueue_style(
        'sterling-google-fonts',
        STERLING_GOOGLE_FONTS_URL,
        [],
        null   // No version — Google handles cache-busting via the URL itself.
    );

    // --- Global CSS ---
    // All design tokens and base styles live here.
    wp_enqueue_style(
        'sterling-global',
        get_template_directory_uri() . '/assets/css/global.css',
        [ 'sterling-google-fonts' ],   // Depends on fonts being registered first.
        STERLING_VERSION
    );

    // --- Inline scroll detection script ---
    // Registers a footer script that adds .is-scrolled to <header> for the nav shadow.
    // wp_add_inline_script requires a *script* handle, so we register a src-less one here.
    wp_register_script( 'sterling-scroll', false, [], STERLING_VERSION, true );
    wp_enqueue_script( 'sterling-scroll' );
    wp_add_inline_script(
        'sterling-scroll',
        '(function(){
            var h = document.querySelector(".site-header");
            if (!h) return;
            window.addEventListener("scroll", function() {
                h.classList.toggle("is-scrolled", window.scrollY > 10);
            }, { passive: true });
        }())'
    );
}
add_action( 'wp_enqueue_scripts', 'sterling_enqueue_assets' );


// ---------------------------------------------------------------------------
// 3. ELEMENTOR SUPPORT
// ---------------------------------------------------------------------------

/**
 * Tell Elementor this theme is compatible with its features.
 * Required for Elementor Hello-style full-width page rendering.
 */
function sterling_elementor_support(): void {
    add_theme_support( 'elementor' );
}
add_action( 'elementor/init', 'sterling_elementor_support' );

/**
 * Register a "Full Width" page template that strips the header and footer,
 * handing full control to Elementor.
 *
 * To use: in the page editor select "Sterling Full Width" under Page Attributes.
 */
function sterling_add_page_templates( array $templates ): array {
    $templates['templates/full-width.php'] = __( 'Sterling Full Width', 'sterling-properties' );
    return $templates;
}
add_filter( 'theme_page_templates', 'sterling_add_page_templates' );


// ---------------------------------------------------------------------------
// 4. CLEAN UP WORDPRESS HEAD (optional but recommended for performance)
// ---------------------------------------------------------------------------

/**
 * Remove noise from <head> that most marketing sites don't need.
 * Comment out individual lines if any feature is actually required.
 */
function sterling_clean_head(): void {
    remove_action( 'wp_head', 'rsd_link' );
    remove_action( 'wp_head', 'wlwmanifest_link' );
    remove_action( 'wp_head', 'wp_generator' );            // Hide WP version (security).
    remove_action( 'wp_head', 'wp_shortlink_wp_head' );
    remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10 );
}
add_action( 'init', 'sterling_clean_head' );


// ---------------------------------------------------------------------------
// 5. BODY CLASSES
// ---------------------------------------------------------------------------

/**
 * Add useful classes to <body> for CSS targeting.
 *   .has-elementor    → page uses Elementor
 *   .page-{slug}      → current page slug
 */
function sterling_body_classes( array $classes ): array {
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
add_filter( 'body_class', 'sterling_body_classes' );


// ---------------------------------------------------------------------------
// 6. NAV WALKER & FALLBACK
// Defined here so they're available before header.php is ever loaded.
// ---------------------------------------------------------------------------

/**
 * Fallback rendered when no WordPress menu is assigned to 'primary'.
 */
if ( ! function_exists( 'sterling_nav_fallback' ) ) {
    function sterling_nav_fallback(): void {
        echo '<a href="' . esc_url( home_url( '/' ) ) . '">'
            . esc_html__( 'Home', 'sterling-properties' )
            . '</a>';
    }
}

/**
 * Strips <ul>/<li> wrappers so nav items render as bare <a> tags inside
 * the flex .nav-links container.
 */
if ( ! class_exists( 'Sterling_Nav_Walker' ) ) :
class Sterling_Nav_Walker extends Walker_Nav_Menu {

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
