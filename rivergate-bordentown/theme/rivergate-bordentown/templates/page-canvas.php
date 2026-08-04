<?php
/**
 * Template Name: Block Canvas (Full Width)
 *
 * Edge-to-edge template for block-driven pages such as the homepage. It renders
 * the block content with no constraining wrapper so that `alignfull` section
 * blocks span the viewport while their inner `.container` groups stay centered.
 * The header nav and footer remain locked in PHP.
 */

get_header();

while ( have_posts() ) :
	the_post();
	the_content();
endwhile;

get_footer();
