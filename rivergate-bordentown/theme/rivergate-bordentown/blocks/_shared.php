<?php
/**
 * Shared helpers for Rivergate dynamic blocks.
 *
 * These functions were extracted from the legacy templates/page-home.php so the
 * same data + markup can be rendered by block render.php files (front end) and
 * by ServerSideRender previews (block editor).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Are we rendering inside the block editor (ServerSideRender REST request)?
 * Used to suppress scroll-reveal hooks so previews aren't blank (main.js, which
 * adds the .is-visible class, does not run in the editor canvas).
 */
function rivergate_in_editor(): bool {
	return defined( 'REST_REQUEST' ) && REST_REQUEST;
}

/**
 * Returns the ` data-animate` attribute string for front-end output, or an
 * empty string in the editor (where [data-animate] would stay opacity:0).
 *
 * @param string $variant Optional reveal variant: '', 'fade', 'left', 'right', 'scale'.
 */
function rivergate_anim( string $variant = '' ): string {
	if ( rivergate_in_editor() ) {
		return '';
	}
	return $variant ? ' data-animate="' . esc_attr( $variant ) . '"' : ' data-animate';
}

/**
 * SVG path data for amenity icons, keyed by the amenity_icon_key ACF value.
 *
 * @return array<string,string>
 */
function rivergate_amenity_icon_map(): array {
	// Font Awesome Free 6 solid (Icons: CC BY 4.0). Real icon data rather than
	// hand-drawn paths — each entry carries its own viewBox because Font Awesome
	// glyphs are not all square.
	return [
		'lock'          => [ 'view' => '0 0 448 512', 'path' => '<path d="M144 144l0 48 160 0 0-48c0-44.2-35.8-80-80-80s-80 35.8-80 80zM80 192l0-48C80 64.5 144.5 0 224 0s144 64.5 144 144l0 48 16 0c35.3 0 64 28.7 64 64l0 192c0 35.3-28.7 64-64 64L64 512c-35.3 0-64-28.7-64-64L0 256c0-35.3 28.7-64 64-64l16 0z"/>' ],
		'pool'          => [ 'view' => '0 0 576 512', 'path' => '<path d="M128 127.7C128 74.9 170.9 32 223.7 32c48.3 0 89 36 95 83.9l1 8.2c2.2 17.5-10.2 33.5-27.8 35.7s-33.5-10.2-35.7-27.8l-1-8.2c-2-15.9-15.5-27.8-31.5-27.8c-17.5 0-31.7 14.2-31.7 31.7l0 96.3 192 0 0-96.3C384 74.9 426.9 32 479.7 32c48.3 0 89 36 95 83.9l1 8.2c2.2 17.5-10.2 33.5-27.8 35.7s-33.5-10.2-35.7-27.8l-1-8.2c-2-15.9-15.5-27.8-31.5-27.8c-17.5 0-31.7 14.2-31.7 31.7L448 361c-1.6 1-3.3 2-4.8 3.1c-18 12.4-40.1 20.3-59.2 20.3c0 0 0 0 0 0l0-96.5-192 0 0 96.5c-19 0-41.2-7.9-59.1-20.3c-1.6-1.1-3.2-2.2-4.9-3.1l0-233.3zM306.5 389.9C329 405.4 356.5 416 384 416c26.9 0 55.4-10.8 77.4-26.1c0 0 0 0 0 0c11.9-8.5 28.1-7.8 39.2 1.7c14.4 11.9 32.5 21 50.6 25.2c17.2 4 27.9 21.2 23.9 38.4s-21.2 27.9-38.4 23.9c-24.5-5.7-44.9-16.5-58.2-25C449.5 469.7 417 480 384 480c-31.9 0-60.6-9.9-80.4-18.9c-5.8-2.7-11.1-5.3-15.6-7.7c-4.5 2.4-9.7 5.1-15.6 7.7c-19.8 9-48.5 18.9-80.4 18.9c-33 0-65.5-10.3-94.5-25.8c-13.4 8.4-33.7 19.3-58.2 25c-17.2 4-34.4-6.7-38.4-23.9s6.7-34.4 23.9-38.4c18.1-4.2 36.2-13.3 50.6-25.2c11.1-9.4 27.3-10.1 39.2-1.7c0 0 0 0 0 0C136.7 405.2 165.1 416 192 416c27.5 0 55-10.6 77.5-26.1c11.1-7.9 25.9-7.9 37 0z"/>' ],
		'fitness'       => [ 'view' => '0 0 640 512', 'path' => '<path d="M96 64c0-17.7 14.3-32 32-32l32 0c17.7 0 32 14.3 32 32l0 160 0 64 0 160c0 17.7-14.3 32-32 32l-32 0c-17.7 0-32-14.3-32-32l0-64-32 0c-17.7 0-32-14.3-32-32l0-64c-17.7 0-32-14.3-32-32s14.3-32 32-32l0-64c0-17.7 14.3-32 32-32l32 0 0-64zm448 0l0 64 32 0c17.7 0 32 14.3 32 32l0 64c17.7 0 32 14.3 32 32s-14.3 32-32 32l0 64c0 17.7-14.3 32-32 32l-32 0 0 64c0 17.7-14.3 32-32 32l-32 0c-17.7 0-32-14.3-32-32l0-160 0-64 0-160c0-17.7 14.3-32 32-32l32 0c17.7 0 32 14.3 32 32zM416 224l0 64-192 0 0-64 192 0z"/>' ],
		'clubhouse'     => [ 'view' => '0 0 640 512', 'path' => '<path d="M64 160C64 89.3 121.3 32 192 32l256 0c70.7 0 128 57.3 128 128l0 33.6c-36.5 7.4-64 39.7-64 78.4l0 48-384 0 0-48c0-38.7-27.5-71-64-78.4L64 160zM544 272c0-20.9 13.4-38.7 32-45.3c5-1.8 10.4-2.7 16-2.7c26.5 0 48 21.5 48 48l0 176c0 17.7-14.3 32-32 32l-32 0c-17.7 0-32-14.3-32-32L96 448c0 17.7-14.3 32-32 32l-32 0c-17.7 0-32-14.3-32-32L0 272c0-26.5 21.5-48 48-48c5.6 0 11 1 16 2.7c18.6 6.6 32 24.4 32 45.3l0 48 0 32 32 0 384 0 32 0 0-32 0-48z"/>' ],
		'bbq'           => [ 'view' => '0 0 640 512', 'path' => '<path d="M345.7 48.3L358 34.5c5.4-6.1 13.3-8.8 20.9-8.9c7.2 0 14.3 2.6 19.9 7.8c19.7 18.3 39.8 43.2 55 70.6C469 131.2 480 162.2 480 192.2C480 280.8 408.7 352 320 352c-89.6 0-160-71.3-160-159.8c0-37.3 16-73.4 36.8-104.5c20.9-31.3 47.5-59 70.9-80.2C273.4 2.3 280.7-.2 288 0c14.1 .3 23.8 11.4 32.7 21.6c0 0 0 0 0 0c2 2.3 4 4.6 6 6.7l19 19.9zM384 240.2c0-36.5-37-73-54.8-88.4c-5.4-4.7-13.1-4.7-18.5 0C293 167.1 256 203.6 256 240.2c0 35.3 28.7 64 64 64s64-28.7 64-64zM32 288c0-17.7 14.3-32 32-32l32 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l0 64 448 0 0-64c-17.7 0-32-14.3-32-32s14.3-32 32-32l32 0c17.7 0 32 14.3 32 32l0 96c17.7 0 32 14.3 32 32l0 64c0 17.7-14.3 32-32 32L32 512c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l0-96zM320 480a32 32 0 1 0 0-64 32 32 0 1 0 0 64zm160-32a32 32 0 1 0 -64 0 32 32 0 1 0 64 0zM192 480a32 32 0 1 0 0-64 32 32 0 1 0 0 64z"/>' ],
		'dogrun'        => [ 'view' => '0 0 576 512', 'path' => '<path d="M309.6 158.5L332.7 19.8C334.6 8.4 344.5 0 356.1 0c7.5 0 14.5 3.5 19 9.5L392 32l52.1 0c12.7 0 24.9 5.1 33.9 14.1L496 64l56 0c13.3 0 24 10.7 24 24l0 24c0 44.2-35.8 80-80 80l-32 0-16 0-21.3 0-5.1 30.5-112-64zM416 256.1L416 480c0 17.7-14.3 32-32 32l-32 0c-17.7 0-32-14.3-32-32l0-115.2c-24 12.3-51.2 19.2-80 19.2s-56-6.9-80-19.2L160 480c0 17.7-14.3 32-32 32l-32 0c-17.7 0-32-14.3-32-32l0-230.2c-28.8-10.9-51.4-35.3-59.2-66.5L1 167.8c-4.3-17.1 6.1-34.5 23.3-38.8s34.5 6.1 38.8 23.3l3.9 15.5C70.5 182 83.3 192 98 192l30 0 16 0 159.8 0L416 256.1zM464 80a16 16 0 1 0 -32 0 16 16 0 1 0 32 0z"/>' ],
		'garage'        => [ 'view' => '0 0 640 512', 'path' => '<path d="M0 488L0 171.3c0-26.2 15.9-49.7 40.2-59.4L308.1 4.8c7.6-3.1 16.1-3.1 23.8 0L599.8 111.9c24.3 9.7 40.2 33.3 40.2 59.4L640 488c0 13.3-10.7 24-24 24l-48 0c-13.3 0-24-10.7-24-24l0-264c0-17.7-14.3-32-32-32l-384 0c-17.7 0-32 14.3-32 32l0 264c0 13.3-10.7 24-24 24l-48 0c-13.3 0-24-10.7-24-24zm488 24l-336 0c-13.3 0-24-10.7-24-24l0-56 384 0 0 56c0 13.3-10.7 24-24 24zM128 400l0-64 384 0 0 64-384 0zm0-96l0-80 384 0 0 80-384 0z"/>' ],
		'ev'            => [ 'view' => '0 0 576 512', 'path' => '<path d="M96 0C60.7 0 32 28.7 32 64l0 384c-17.7 0-32 14.3-32 32s14.3 32 32 32l288 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l0-144 16 0c22.1 0 40 17.9 40 40l0 32c0 39.8 32.2 72 72 72s72-32.2 72-72l0-123.7c32.5-10.2 56-40.5 56-76.3l0-32c0-8.8-7.2-16-16-16l-16 0 0-48c0-8.8-7.2-16-16-16s-16 7.2-16 16l0 48-32 0 0-48c0-8.8-7.2-16-16-16s-16 7.2-16 16l0 48-16 0c-8.8 0-16 7.2-16 16l0 32c0 35.8 23.5 66.1 56 76.3L472 376c0 13.3-10.7 24-24 24s-24-10.7-24-24l0-32c0-48.6-39.4-88-88-88l-16 0 0-192c0-35.3-28.7-64-64-64L96 0zM216.9 82.7c6 4 8.5 11.5 6.3 18.3l-25 74.9 57.8 0c6.7 0 12.7 4.2 15 10.4s.5 13.3-4.6 17.7l-112 96c-5.5 4.7-13.4 5.1-19.3 1.1s-8.5-11.5-6.3-18.3l25-74.9L96 208c-6.7 0-12.7-4.2-15-10.4s-.5-13.3 4.6-17.7l112-96c5.5-4.7 13.4-5.1 19.3-1.1z"/>' ],
		'maintenance'   => [ 'view' => '0 0 512 512', 'path' => '<path d="M78.6 5C69.1-2.4 55.6-1.5 47 7L7 47c-8.5 8.5-9.4 22-2.1 31.6l80 104c4.5 5.9 11.6 9.4 19 9.4l54.1 0 109 109c-14.7 29-10 65.4 14.3 89.6l112 112c12.5 12.5 32.8 12.5 45.3 0l64-64c12.5-12.5 12.5-32.8 0-45.3l-112-112c-24.2-24.2-60.6-29-89.6-14.3l-109-109 0-54.1c0-7.5-3.5-14.5-9.4-19L78.6 5zM19.9 396.1C7.2 408.8 0 426.1 0 444.1C0 481.6 30.4 512 67.9 512c18 0 35.3-7.2 48-19.9L233.7 374.3c-7.8-20.9-9-43.6-3.6-65.1l-61.7-61.7L19.9 396.1zM512 144c0-10.5-1.1-20.7-3.2-30.5c-2.4-11.2-16.1-14.1-24.2-6l-63.9 63.9c-3 3-7.1 4.7-11.3 4.7L352 176c-8.8 0-16-7.2-16-16l0-57.4c0-4.2 1.7-8.3 4.7-11.3l63.9-63.9c8.1-8.1 5.2-21.8-6-24.2C388.7 1.1 378.5 0 368 0C288.5 0 224 64.5 224 144l0 .8 85.3 85.3c36-9.1 75.8 .5 104 28.7L429 274.5c49-23 83-72.8 83-130.5zM56 432a24 24 0 1 1 48 0 24 24 0 1 1 -48 0z"/>' ],
		'elevator'      => [ 'view' => '0 0 512 512', 'path' => '<path d="M132.7 4.7l-64 64c-4.6 4.6-5.9 11.5-3.5 17.4s8.3 9.9 14.8 9.9l128 0c6.5 0 12.3-3.9 14.8-9.9s1.1-12.9-3.5-17.4l-64-64c-6.2-6.2-16.4-6.2-22.6 0zM64 128c-35.3 0-64 28.7-64 64L0 448c0 35.3 28.7 64 64 64l384 0c35.3 0 64-28.7 64-64l0-256c0-35.3-28.7-64-64-64L64 128zm96 96a48 48 0 1 1 0 96 48 48 0 1 1 0-96zM80 400c0-26.5 21.5-48 48-48l64 0c26.5 0 48 21.5 48 48l0 16c0 17.7-14.3 32-32 32l-96 0c-17.7 0-32-14.3-32-32l0-16zm192 0c0-26.5 21.5-48 48-48l64 0c26.5 0 48 21.5 48 48l0 16c0 17.7-14.3 32-32 32l-96 0c-17.7 0-32-14.3-32-32l0-16zm32-128a48 48 0 1 1 96 0 48 48 0 1 1 -96 0zM356.7 91.3c6.2 6.2 16.4 6.2 22.6 0l64-64c4.6-4.6 5.9-11.5 3.5-17.4S438.5 0 432 0L304 0c-6.5 0-12.3 3.9-14.8 9.9s-1.1 12.9 3.5 17.4l64 64z"/>' ],
		'balcony'       => [ 'view' => '0 0 576 512', 'path' => '<path d="M575.8 255.5c0 18-15 32.1-32 32.1l-32 0 .7 160.2c0 2.7-.2 5.4-.5 8.1l0 16.2c0 22.1-17.9 40-40 40l-16 0c-1.1 0-2.2 0-3.3-.1c-1.4 .1-2.8 .1-4.2 .1L416 512l-24 0c-22.1 0-40-17.9-40-40l0-24 0-64c0-17.7-14.3-32-32-32l-64 0c-17.7 0-32 14.3-32 32l0 64 0 24c0 22.1-17.9 40-40 40l-24 0-31.9 0c-1.5 0-3-.1-4.5-.2c-1.2 .1-2.4 .2-3.6 .2l-16 0c-22.1 0-40-17.9-40-40l0-112c0-.9 0-1.9 .1-2.8l0-69.7-32 0c-18 0-32-14-32-32.1c0-9 3-17 10-24L266.4 8c7-7 15-8 22-8s15 2 21 7L564.8 231.5c8 7 12 15 11 24z"/>' ],
		'washer'        => [ 'view' => '0 0 512 512', 'path' => '<path d="M208 96a48 48 0 1 0 0-96 48 48 0 1 0 0 96zM320 256a64 64 0 1 0 0-128 64 64 0 1 0 0 128zM416 32a32 32 0 1 0 -64 0 32 32 0 1 0 64 0zm0 160c0 27.6-11.7 52.5-30.4 70.1C422.1 275.7 448 310.8 448 352c0 53-43 96-96 96l-192 0c-53 0-96-43-96-96s43-96 96-96l88.4 0c-15.2-17-24.4-39.4-24.4-64L96 192c-53 0-96 43-96 96L0 416c0 53 43 96 96 96l320 0c53 0 96-43 96-96l0-128c0-53-43-96-96-96zM160 288c-35.3 0-64 28.7-64 64s28.7 64 64 64l192 0c35.3 0 64-28.7 64-64s-28.7-64-64-64l-32 0-160 0z"/>' ],
		'transit'       => [ 'view' => '0 0 448 512', 'path' => '<path d="M96 0C43 0 0 43 0 96L0 352c0 48 35.2 87.7 81.1 94.9l-46 46C28.1 499.9 33.1 512 43 512l39.7 0c8.5 0 16.6-3.4 22.6-9.4L160 448l128 0 54.6 54.6c6 6 14.1 9.4 22.6 9.4l39.7 0c10 0 15-12.1 7.9-19.1l-46-46c46-7.1 81.1-46.9 81.1-94.9l0-256c0-53-43-96-96-96L96 0zM64 96c0-17.7 14.3-32 32-32l256 0c17.7 0 32 14.3 32 32l0 96c0 17.7-14.3 32-32 32L96 224c-17.7 0-32-14.3-32-32l0-96zM224 288a48 48 0 1 1 0 96 48 48 0 1 1 0-96z"/>' ],
		'pet'           => [ 'view' => '0 0 512 512', 'path' => '<path d="M226.5 92.9c14.3 42.9-.3 86.2-32.6 96.8s-70.1-15.6-84.4-58.5s.3-86.2 32.6-96.8s70.1 15.6 84.4 58.5zM100.4 198.6c18.9 32.4 14.3 70.1-10.2 84.1s-59.7-.9-78.5-33.3S-2.7 179.3 21.8 165.3s59.7 .9 78.5 33.3zM69.2 401.2C121.6 259.9 214.7 224 256 224s134.4 35.9 186.8 177.2c3.6 9.7 5.2 20.1 5.2 30.5l0 1.6c0 25.8-20.9 46.7-46.7 46.7c-11.5 0-22.9-1.4-34-4.2l-88-22c-15.3-3.8-31.3-3.8-46.6 0l-88 22c-11.1 2.8-22.5 4.2-34 4.2C84.9 480 64 459.1 64 433.3l0-1.6c0-10.4 1.6-20.8 5.2-30.5zM421.8 282.7c-24.5-14-29.1-51.7-10.2-84.1s54-47.3 78.5-33.3s29.1 51.7 10.2 84.1s-54 47.3-78.5 33.3zM310.1 189.7c-32.3-10.6-46.9-53.9-32.6-96.8s52.1-69.1 84.4-58.5s46.9 53.9 32.6 96.8s-52.1 69.1-84.4 58.5z"/>' ],
		'star'          => [ 'view' => '0 0 576 512', 'path' => '<path d="M316.9 18C311.6 7 300.4 0 288.1 0s-23.4 7-28.8 18L195 150.3 51.4 171.5c-12 1.8-22 10.2-25.7 21.7s-.7 24.2 7.9 32.7L137.8 329 113.2 474.7c-2 12 3 24.2 12.9 31.3s23 8 33.8 2.3l128.3-68.5 128.3 68.5c10.8 5.7 23.9 4.9 33.8-2.3s14.9-19.3 12.9-31.3L438.5 329 542.7 225.9c8.6-8.5 11.7-21.2 7.9-32.7s-13.7-19.9-25.7-21.7L381.2 150.3 316.9 18z"/>' ],
	];
}

/**
 * Amenity cards: query the rg_amenity CPT, falling back to hardcoded defaults
 * until the client creates entries.
 *
 * @param int $max Maximum number of amenities to return.
 * @return array<int,array{name:string,icon_key:string,detail:string}>
 */
function rivergate_get_amenities( int $max = 8 ): array {
	$query = new WP_Query( [
		'post_type'      => 'rg_amenity',
		'posts_per_page' => $max,
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
		'post_status'    => 'publish',
	] );

	if ( $query->have_posts() ) {
		$items = [];
		while ( $query->have_posts() ) {
			$query->the_post();
			$aid     = get_the_ID();
			$items[] = [
				'name'     => get_the_title(),
				'icon_key' => rivergate_field( 'amenity_icon_key', $aid, 'star' ),
				'group'    => rivergate_field( 'amenity_group', $aid, 'property' ),
				'image'    => ( $iid = (int) rivergate_field( 'amenity_image', $aid, 0 ) )
					? (string) wp_get_attachment_image_url( $iid, 'large' ) : '',
				'detail'   => rivergate_field( 'amenity_detail', $aid, '' ),
			];
		}
		wp_reset_postdata();
		return $items;
	}

	return [
		[ 'name' => 'Secured Access',                 'icon_key' => 'lock',        'group' => 'property', 'image' => '', 'detail' => 'Controlled building entrances and secured garage parking for residents\' peace of mind around the clock.' ],
		[ 'name' => 'Heated Pool',                    'icon_key' => 'pool',        'group' => 'property', 'image' => 'amenities/amenity-5.jpg', 'detail' => 'Resort-style heated outdoor swimming pool with sun deck — a private retreat right outside your door, all season long.' ],
		[ 'name' => 'Fitness Studio',                 'icon_key' => 'fitness',     'group' => 'property', 'image' => 'amenities/amenity-4.jpg', 'detail' => 'State-of-the-art fitness center with modern equipment — no gym membership required.' ],
		[ 'name' => 'Dog Run',                        'icon_key' => 'dogrun',      'group' => 'property', 'image' => '', 'detail' => 'An on-site dog run for our four-legged residents — a dedicated space to stretch out and play without leaving the community.' ],
		[ 'name' => 'Outdoor BBQ Area',               'icon_key' => 'bbq',         'group' => 'property', 'image' => 'amenities/amenity-2.jpg', 'detail' => 'Dedicated outdoor barbecue and fire pit area — perfect for gatherings in a beautifully landscaped setting.' ],
		[ 'name' => 'Rivergate Clubhouse',            'icon_key' => 'clubhouse',   'group' => 'property', 'image' => 'clubhouse/clubhouse-2.jpg', 'detail' => 'An exclusive community clubhouse — social hub for residents, ideal for private events and everyday gathering.' ],
		[ 'name' => 'Private Garage Spaces',          'icon_key' => 'garage',      'group' => 'property', 'image' => '', 'detail' => 'Free-standing private garages available at select locations, with additional surface parking on-site.' ],
		[ 'name' => 'EV Chargers',                    'icon_key' => 'ev',          'group' => 'property', 'image' => 'amenities/amenity-1.jpg', 'detail' => 'On-site electric vehicle charging stations, reserved for residents while charging.' ],
		[ 'name' => 'Elevator Access',                'icon_key' => 'elevator',        'group' => 'property', 'image' => '', 'detail' => 'Elevator service in select buildings for added convenience and accessibility.' ],
		[ 'name' => 'Full-Time Onsite Maintenance',   'icon_key' => 'maintenance', 'group' => 'property', 'image' => '', 'detail' => 'A full-time maintenance team based on site, so requests are handled quickly by people who know the community.' ],
	];
}

/**
 * Order plans smallest -> largest by square footage.
 *
 * Client-requested ordering (2026-08-18), applied to both the CPT results and
 * the fallback list so the sequence is the same however the data arrives.
 * Remove this call in rivergate_get_plans() to hand ordering back to the
 * drag-to-reorder menu_order in wp-admin.
 *
 * @param array<string,array<string,mixed>> $plans Keyed by plan slug.
 * @return array<string,array<string,mixed>>
 */
function rivergate_sort_plans_by_sqft( array $plans ): array {
	uasort(
		$plans,
		static function ( array $a, array $b ): int {
			$sa = (int) preg_replace( '/[^0-9]/', '', (string) ( $a['sqft'] ?? '' ) );
			$sb = (int) preg_replace( '/[^0-9]/', '', (string) ( $b['sqft'] ?? '' ) );
			return $sa <=> $sb;
		}
	);
	return $plans;
}

/**
 * Floor plans: query the rg_floor_plan CPT, falling back to hardcoded defaults.
 *
 * @return array<string,array<string,mixed>> Keyed by plan slug.
 */
function rivergate_get_plans(): array {
	$query = new WP_Query( [
		'post_type'      => 'rg_floor_plan',
		'posts_per_page' => -1,
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
		'post_status'    => 'publish',
	] );

	if ( $query->have_posts() ) {
		$plans = [];
		while ( $query->have_posts() ) {
			$query->the_post();
			$pid  = get_the_ID();
			$slug = sanitize_key( rivergate_field( 'plan_slug', $pid, sanitize_title( get_the_title() ) ) );
			$img_id = (int) rivergate_field( 'plan_image', $pid, 0 );
			$plans[ $slug ] = [
				'name'  => get_the_title(),
				'code'  => rivergate_field( 'plan_code', $pid, '' ),
				'bed'   => (int) rivergate_field( 'plan_beds', $pid, 1 ),
				'bath'  => (float) rivergate_field( 'plan_baths', $pid, 1 ),
				'sqft'  => rivergate_field( 'plan_sqft', $pid, '' ),
				'rent'  => rivergate_field( 'plan_rent', $pid, '' ),
				'tag'   => rivergate_field( 'plan_tag', $pid, '' ),
				'pdf'   => rivergate_field( 'plan_pdf', $pid, '' ),
				'image' => $img_id ? (string) wp_get_attachment_image_url( $img_id, 'large' ) : '',
				'mp'    => rivergate_field( 'plan_matterport', $pid, '' ),
			];
		}
		wp_reset_postdata();
		return rivergate_sort_plans_by_sqft( $plans );
	}

	// Fallback until the client adds Floor Plan entries — mirrors the preview:
	// real plan images from the theme + Matterport tours where they exist.
	$fp = get_template_directory_uri() . '/assets/images/floorplans/';
	$mp = 'https://my.matterport.com/show/?m=';
	// Listed smallest -> largest by square footage (see rivergate_sort_plans_by_sqft).
	return [
		'rivergate'  => [ 'name' => 'The Rivergate',  'code' => 'Plan 1B2', 'bed' => 1, 'bath' => 1, 'sqft' => '737',   'rent' => '', 'tag' => '',             'pdf' => '', 'image' => $fp . 'rivergate.jpg',  'mp' => $mp . '18Ngo5NtdyU' ],
		'borden'     => [ 'name' => 'The Borden',     'code' => 'Plan 1B',  'bed' => 1, 'bath' => 1, 'sqft' => '773',   'rent' => '', 'tag' => '',             'pdf' => '', 'image' => $fp . 'borden.jpg',     'mp' => '' ],
		'wright'     => [ 'name' => 'The Wright',     'code' => 'Plan 1B3', 'bed' => 1, 'bath' => 1, 'sqft' => '863',   'rent' => '', 'tag' => '',             'pdf' => '', 'image' => $fp . 'wright.jpg',     'mp' => $mp . 'ZJFssUi1zKd' ],
		'chester'    => [ 'name' => 'The Chester',    'code' => 'Plan 2B',  'bed' => 2, 'bath' => 2, 'sqft' => '1,033', 'rent' => '', 'tag' => '',             'pdf' => '', 'image' => $fp . 'chester.jpg',    'mp' => '' ],
		'burlington' => [ 'name' => 'The Burlington', 'code' => 'Plan 2B4', 'bed' => 2, 'bath' => 2, 'sqft' => '1,043', 'rent' => '', 'tag' => '',             'pdf' => '', 'image' => $fp . 'burlington.jpg', 'mp' => '' ],
		'farnsworth' => [ 'name' => 'The Farnsworth', 'code' => 'Plan 2B5', 'bed' => 2, 'bath' => 2, 'sqft' => '1,149', 'rent' => '', 'tag' => '',             'pdf' => '', 'image' => $fp . 'farnsworth.jpg', 'mp' => $mp . 'AHnca1wS9bX' ],
		'dayton'     => [ 'name' => 'The Dayton',     'code' => 'Plan 2B2', 'bed' => 2, 'bath' => 2, 'sqft' => '1,160', 'rent' => '', 'tag' => 'Most Popular', 'pdf' => '', 'image' => $fp . 'dayton.jpg',     'mp' => $mp . 'FURMkxr2zpm' ],
		'edison'     => [ 'name' => 'The Edison',     'code' => 'Plan 2B3', 'bed' => 2, 'bath' => 2, 'sqft' => '1,287', 'rent' => '', 'tag' => '',             'pdf' => '', 'image' => $fp . 'edison.jpg',     'mp' => $mp . 'ghub4RNmR3A' ],
		'hamilton'   => [ 'name' => 'The Hamilton',   'code' => 'Plan 2B6', 'bed' => 2, 'bath' => 2, 'sqft' => '1,342', 'rent' => '', 'tag' => '',             'pdf' => '', 'image' => $fp . 'hamilton.jpg',   'mp' => '' ],
	];
}

/**
 * Server-side render of a single floor-plan option button's inner content.
 * main.js overwrites this identically on the front end; rendering it here gives
 * a meaningful editor preview and a no-JS fallback.
 */
function rivergate_fp_option_inner( array $p ): string {
	$html  = '<span class="fp-option__specs">' . esc_html( $p['bed'] . ' Bed / ' . $p['bath'] . ' Bath / Balcony' ) . '</span>';
	$html .= '<span class="fp-option__name">' . esc_html( $p['name'] ) . '</span>';
	$html .= '<span class="fp-option__size">' . esc_html( $p['sqft'] ) . ' sq ft</span>';
	if ( ! empty( $p['tag'] ) ) {
		$html .= '<span class="fp-option__tag">' . esc_html( $p['tag'] ) . '</span>';
	}
	return $html;
}

/**
 * Server-side render of the floor-plan detail panel. Mirrors the renderDetail()
 * markup in assets/js/main.js so the panel is populated before JS runs.
 */
function rivergate_fp_detail_html( array $p, string $avail_url, string $apply_url ): string {
	$eyebrow = $p['code'] . ( ! empty( $p['tag'] ) ? ' · ' . $p['tag'] : '' );

	$html  = '<p class="fp-detail__eyebrow">' . esc_html( $eyebrow ) . '</p>';
	$html .= '<h3 class="fp-detail__name">' . esc_html( $p['name'] ) . '</h3>';
	$html .= '<div class="fp-detail__specs">';
	$html .= '<div class="fp-spec"><span>Bedrooms</span><span>' . esc_html( $p['bed'] ) . '</span></div>';
	$html .= '<div class="fp-spec"><span>Bathrooms</span><span>' . esc_html( $p['bath'] ) . '</span></div>';
	$html .= '<div class="fp-spec"><span>Square Feet</span><span>' . esc_html( $p['sqft'] ) . '</span></div>';
	if ( ! empty( $p['rent'] ) ) {
		$html .= '<div class="fp-spec"><span>Starting Rent</span><span>' . esc_html( $p['rent'] ) . '</span></div>';
	}
	$html .= '<div class="fp-spec"><span>Balcony</span><span>Yes</span></div>';
	$html .= '</div>';
	// Real floor-plan image (replaces the old placeholder diagram).
	if ( ! empty( $p['image'] ) ) {
		$alt = $p['name'] . ' floor plan — ' . $p['bed'] . ' bed, ' . $p['bath'] . ' bath, ' . $p['sqft'] . ' sq ft';
		$html .= '<img class="fp-plan-img" src="' . esc_url( $p['image'] ) . '" alt="' . esc_attr( $alt ) . '" loading="lazy">';
	}
	$html .= '<div class="fp-detail__actions">';
	// Matterport virtual tour — opens the lightbox; disabled pill where none exists.
	if ( ! empty( $p['mp'] ) ) {
		$html .= '<button type="button" class="btn btn--secondary" data-mp="' . esc_url( $p['mp'] ) . '" data-name="' . esc_attr( $p['name'] ) . '">Take a Virtual Tour</button>';
	} else {
		$html .= '<span class="btn btn--secondary fp-vtour-disabled" aria-disabled="true" title="Virtual tour coming soon">Virtual Tour Coming Soon</span>';
	}
	$html .= '<a class="btn btn--primary" href="' . esc_url( $avail_url ) . '">Check Availability</a>';
	if ( ! empty( $p['pdf'] ) ) {
		$html .= '<a class="btn btn--secondary" href="' . esc_url( $p['pdf'] ) . '" target="_blank" rel="noopener noreferrer">Download PDF</a>';
	} else {
		$html .= '<a class="btn btn--secondary" href="' . esc_url( $apply_url ) . '" target="_blank" rel="noopener noreferrer">Apply Now</a>';
	}
	$html .= '</div>';

	return $html;
}

/** Appfolio listings URL for Rivergate (Apply Now). */
function rivergate_apply_url(): string {
	return 'https://sterlingproperties.appfolio.com/listings?filters%5Bproperty_list%5D=RIVERGATE+BORDENTOWN';
}
