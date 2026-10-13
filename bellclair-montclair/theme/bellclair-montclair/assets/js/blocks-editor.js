/**
 * Bellclair Montclair — block editor registration (no build step).
 *
 * Registers the theme's dynamic blocks in the editor. Each block previews via
 * ServerSideRender (the canvas shows the real PHP-rendered design) and is edited
 * through Inspector (sidebar) controls. The blocks are also registered server-side
 * from their block.json via register_block_type(); this file supplies the editor
 * `edit` UI that block.json alone cannot.
 */
( function ( wp ) {
	'use strict';

	var el                = wp.element.createElement;
	var Fragment          = wp.element.Fragment;
	var __                = wp.i18n.__;
	var registerBlockType = wp.blocks.registerBlockType;
	var InspectorControls = wp.blockEditor.InspectorControls;
	var useBlockProps     = wp.blockEditor.useBlockProps;
	var MediaUpload       = wp.blockEditor.MediaUpload;
	var MediaUploadCheck  = wp.blockEditor.MediaUploadCheck;
	var PanelBody         = wp.components.PanelBody;
	var TextControl       = wp.components.TextControl;
	var TextareaControl   = wp.components.TextareaControl;
	var RangeControl      = wp.components.RangeControl;
	var SelectControl     = wp.components.SelectControl;
	var Button            = wp.components.Button;
	var ServerSideRender  = wp.serverSideRender;

	/* ---- small control helpers ------------------------------------------- */

	function text( props, attr, label, help ) {
		return el( TextControl, {
			key: attr,
			label: label,
			help: help || undefined,
			value: props.attributes[ attr ] || '',
			onChange: function ( v ) {
				var o = {};
				o[ attr ] = v;
				props.setAttributes( o );
			}
		} );
	}

	function area( props, attr, label ) {
		return el( TextareaControl, {
			key: attr,
			label: label,
			value: props.attributes[ attr ] || '',
			onChange: function ( v ) {
				var o = {};
				o[ attr ] = v;
				props.setAttributes( o );
			}
		} );
	}

	function media( props, urlAttr, idAttr, label, type ) {
		return el( MediaUploadCheck, { key: urlAttr },
			el( 'div', { style: { marginBottom: '16px' } }, [
				el( 'p', { key: 'l', style: { marginBottom: '4px', fontWeight: 600 } }, label ),
				el( MediaUpload, {
					key: 'm',
					allowedTypes: [ type ],
					value: props.attributes[ idAttr ],
					onSelect: function ( m ) {
						var o = {};
						o[ urlAttr ] = m.url;
						if ( idAttr ) {
							o[ idAttr ] = m.id;
						}
						props.setAttributes( o );
					},
					render: function ( obj ) {
						return el( Button, { variant: 'secondary', onClick: obj.open },
							props.attributes[ urlAttr ] ? __( 'Replace', 'bellclair-montclair' ) : __( 'Select', 'bellclair-montclair' )
						);
					}
				} )
			] )
		);
	}

	/* ---- generic dynamic-block registrar --------------------------------- */

	function registerDynamic( name, panels ) {
		registerBlockType( name, {
			edit: function ( props ) {
				var blockProps = useBlockProps();
				return el( Fragment, {}, [
					el( InspectorControls, { key: 'inspector' }, panels( props ) ),
					el( 'div', blockProps,
						el( ServerSideRender, {
							key: 'ssr',
							block: name,
							attributes: props.attributes
						} )
					)
				] );
			},
			save: function () { return null; }
		} );
	}

	/* ---- hero ------------------------------------------------------------- */

	registerDynamic( 'bellclair/hero', function ( props ) {
		return el( PanelBody, { title: __( 'Hero Content', 'bellclair-montclair' ), initialOpen: true }, [
			text( props, 'eyebrow', __( 'Eyebrow', 'bellclair-montclair' ) ),
			text( props, 'headline', __( 'Headline', 'bellclair-montclair' ) ),
			area( props, 'subheadline', __( 'Subheadline', 'bellclair-montclair' ) ),
			media( props, 'videoUrl', null, __( 'Background Video (MP4)', 'bellclair-montclair' ), 'video' ),
			media( props, 'videoMobileUrl', null, __( 'Phone Video (smaller MP4, optional)', 'bellclair-montclair' ), 'video' ),
			media( props, 'posterUrl', 'posterId', __( 'Fallback / Poster Image', 'bellclair-montclair' ), 'image' ),
			text( props, 'primaryLabel', __( 'Primary Button Label', 'bellclair-montclair' ) ),
			text( props, 'primaryUrl', __( 'Primary Button Link', 'bellclair-montclair' ) ),
			text( props, 'secondaryLabel', __( 'Secondary Button Label', 'bellclair-montclair' ) ),
			text( props, 'secondaryUrl', __( 'Secondary Button Link', 'bellclair-montclair' ), __( 'Leave blank to open the Bellclair film.', 'bellclair-montclair' ) )
		] );
	} );

	/* ---- amenities -------------------------------------------------------- */

	registerDynamic( 'bellclair/amenities', function ( props ) {
		return el( PanelBody, { title: __( 'Amenities', 'bellclair-montclair' ), initialOpen: true }, [
			el( 'p', { key: 'note', style: { fontStyle: 'italic' } },
				__( 'Amenity cards come from Amenities → All Amenities. Built-in examples show until you add your own.', 'bellclair-montclair' ) ),
			el( RangeControl, {
				key: 'max',
				label: __( 'Max amenities shown', 'bellclair-montclair' ),
				min: 2, max: 12,
				value: props.attributes.maxItems,
				onChange: function ( v ) { props.setAttributes( { maxItems: v } ); }
			} ),
			media( props, 'photoUrl', 'photoId', __( 'Feature Photo', 'bellclair-montclair' ), 'image' ),
			text( props, 'photoCaption', __( 'Photo Caption', 'bellclair-montclair' ) )
		] );
	} );

	/* ---- floor plans ------------------------------------------------------ */

	registerDynamic( 'bellclair/floor-plans', function ( props ) {
		return el( PanelBody, { title: __( 'Floor Plans', 'bellclair-montclair' ), initialOpen: true }, [
			el( 'p', { key: 'note', style: { fontStyle: 'italic' } },
				__( 'Plans come from Floor Plans → All Floor Plans. Built-in examples show until you add your own.', 'bellclair-montclair' ) ),
			text( props, 'defaultPlan', __( 'Default plan slug', 'bellclair-montclair' ), __( 'Which plan is selected first (e.g. "ellington"). Blank = Dorsey.', 'bellclair-montclair' ) )
		] );
	} );

	/* ---- location stats --------------------------------------------------- */

	registerDynamic( 'bellclair/location-stats', function ( props ) {
		return el( PanelBody, { title: __( 'Location Stats', 'bellclair-montclair' ), initialOpen: true }, [
			text( props, 'stat1Num', __( 'Stat 1 — Number', 'bellclair-montclair' ) ),
			text( props, 'stat1Label', __( 'Stat 1 — Label', 'bellclair-montclair' ) ),
			text( props, 'stat2Num', __( 'Stat 2 — Number', 'bellclair-montclair' ) ),
			text( props, 'stat2Label', __( 'Stat 2 — Label', 'bellclair-montclair' ) ),
			text( props, 'stat3Num', __( 'Stat 3 — Number', 'bellclair-montclair' ) ),
			text( props, 'stat3Label', __( 'Stat 3 — Label', 'bellclair-montclair' ) )
		] );
	} );

	/* ---- contact form ----------------------------------------------------- */

	registerDynamic( 'bellclair/contact-form', function ( props ) {
		return el( PanelBody, { title: __( 'Contact Form', 'bellclair-montclair' ), initialOpen: true }, [
			text( props, 'recipientEmail', __( 'Send submissions to', 'bellclair-montclair' ), __( 'Blank = site admin email.', 'bellclair-montclair' ) )
		] );
	} );

	/* ---- inner-page blocks ----------------------------------------------- */

	registerDynamic( 'bellclair/page-hero', function ( props ) {
		return el( PanelBody, { title: __( 'Page Hero', 'bellclair-montclair' ), initialOpen: true }, [
			text( props, 'eyebrow', __( 'Eyebrow', 'bellclair-montclair' ) ),
			text( props, 'heading', __( 'Page Title', 'bellclair-montclair' ) ),
			media( props, 'imageUrl', 'imageId', __( 'Background Image', 'bellclair-montclair' ), 'image' ),
			el( SelectControl, {
				key: 'imageFocus',
				label: __( 'Image Focus', 'bellclair-montclair' ),
				help: __( 'On desktop the hero shows only a strip of the photo. Pick the part to keep in view.', 'bellclair-montclair' ),
				value: props.attributes.imageFocus || '',
				options: [
					{ label: __( 'Default (upper middle)', 'bellclair-montclair' ), value: '' },
					{ label: __( 'Top', 'bellclair-montclair' ), value: 'top' },
					{ label: __( 'Center', 'bellclair-montclair' ), value: 'center' },
					{ label: __( 'Lower', 'bellclair-montclair' ), value: 'lower' },
					{ label: __( 'Bottom', 'bellclair-montclair' ), value: 'bottom' }
				],
				onChange: function ( v ) { props.setAttributes( { imageFocus: v } ); }
			} )
		] );
	} );

	registerDynamic( 'bellclair/neighborhood-explorer', function () {
		return el( PanelBody, { title: __( 'Neighborhood Explorer', 'bellclair-montclair' ), initialOpen: true },
			el( 'p', { style: { fontStyle: 'italic' } },
				__( 'Points of interest and their measured walk/drive times are maintained in the block template. The map uses BELLCLAIR_MAPS_API_KEY from functions.php; the editor shows the OpenStreetMap fallback.', 'bellclair-montclair' ) )
		);
	} );

	registerDynamic( 'bellclair/film-frame', function ( props ) {
		return el( PanelBody, { title: __( 'Film Frame', 'bellclair-montclair' ), initialOpen: true }, [
			el( 'p', { key: 'note', style: { fontStyle: 'italic' } },
				__( 'Clicking the frame plays the Bellclair film in a lightbox.', 'bellclair-montclair' ) ),
			media( props, 'posterUrl', 'posterId', __( 'Poster Image', 'bellclair-montclair' ), 'image' ),
			text( props, 'label', __( 'Label', 'bellclair-montclair' ) )
		] );
	} );

	registerDynamic( 'bellclair/availability-embed', function ( props ) {
		return el( PanelBody, { title: __( 'Availability Embed', 'bellclair-montclair' ), initialOpen: true }, [
			text( props, 'applyUrl', __( 'AppFolio listings URL', 'bellclair-montclair' ), __( 'Blank = the default Bellclair listings URL.', 'bellclair-montclair' ) )
		] );
	} );

}( window.wp ) );
