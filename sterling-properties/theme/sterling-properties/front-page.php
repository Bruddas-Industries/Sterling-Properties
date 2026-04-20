<?php
/**
 * front-page.php — Sterling Properties
 *
 * WordPress uses this template automatically when a static front page is set
 * (Settings → Reading → Your homepage displays → A static page).
 *
 * It renders the page's content full-width — no container wrapper — so that
 * hero sections, feature splits, and full-bleed sections all work correctly.
 *
 * HOW TO USE PER SITE:
 *   1. Create a page titled "Home" in Pages → Add New.
 *   2. Switch to Code Editor (⋮ menu → Code editor).
 *   3. Paste the site's homepage HTML as a single Custom HTML block.
 *   4. Publish the page.
 *   5. Go to Settings → Reading → set "A static page" → Homepage: Home.
 *   This template picks it up automatically — no template selection needed.
 */

get_header();

if ( have_posts() ) :
    while ( have_posts() ) :
        the_post();
        the_content();
    endwhile;
endif;

get_footer();
