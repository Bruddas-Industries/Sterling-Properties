/**
 * Rivergate Bordentown — block editor registration (no build step).
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
							props.attributes[ urlAttr ] ? __( 'Replace', 'rivergate-bordentown' ) : __( 'Select', 'rivergate-bordentown' )
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

	registerDynamic( 'rivergate/hero', function ( props ) {
		return el( PanelBody, { title: __( 'Hero Content', 'rivergate-bordentown' ), initialOpen: true }, [
			text( props, 'eyebrow', __( 'Eyebrow', 'rivergate-bordentown' ) ),
			text( props, 'headline', __( 'Headline', 'rivergate-bordentown' ) ),
			area( props, 'subheadline', __( 'Subheadline', 'rivergate-bordentown' ) ),
			media( props, 'videoUrl', null, __( 'Background Video (MP4)', 'rivergate-bordentown' ), 'video' ),
			media( props, 'posterUrl', 'posterId', __( 'Fallback / Poster Image', 'rivergate-bordentown' ), 'image' ),
			text( props, 'primaryLabel', __( 'Primary Button Label', 'rivergate-bordentown' ) ),
			text( props, 'primaryUrl', __( 'Primary Button Link', 'rivergate-bordentown' ) ),
			text( props, 'secondaryLabel', __( 'Secondary Button Label', 'rivergate-bordentown' ) ),
			text( props, 'secondaryUrl', __( 'Secondary Button Link', 'rivergate-bordentown' ), __( 'Leave blank to use the Apply Now listings link.', 'rivergate-bordentown' ) )
		] );
	} );

	/* ---- amenities -------------------------------------------------------- */

	registerDynamic( 'rivergate/amenities', function ( props ) {
		return el( PanelBody, { title: __( 'Amenities', 'rivergate-bordentown' ), initialOpen: true }, [
			el( 'p', { key: 'note', style: { fontStyle: 'italic' } },
				__( 'Amenity cards come from Amenities → All Amenities. Built-in examples show until you add your own.', 'rivergate-bordentown' ) ),
			el( RangeControl, {
				key: 'max',
				label: __( 'Max amenities shown', 'rivergate-bordentown' ),
				min: 2, max: 12,
				value: props.attributes.maxItems,
				onChange: function ( v ) { props.setAttributes( { maxItems: v } ); }
			} ),
			media( props, 'photoUrl', 'photoId', __( 'Feature Photo', 'rivergate-bordentown' ), 'image' ),
			text( props, 'photoCaption', __( 'Photo Caption', 'rivergate-bordentown' ) )
		] );
	} );

	/* ---- floor plans ------------------------------------------------------ */

	registerDynamic( 'rivergate/floor-plans', function ( props ) {
		return el( PanelBody, { title: __( 'Floor Plans', 'rivergate-bordentown' ), initialOpen: true }, [
			el( 'p', { key: 'note', style: { fontStyle: 'italic' } },
				__( 'Plans come from Floor Plans → All Floor Plans. Built-in examples show until you add your own.', 'rivergate-bordentown' ) ),
			text( props, 'defaultPlan', __( 'Default plan slug', 'rivergate-bordentown' ), __( 'Which plan is selected first (e.g. "wright"). Blank = first plan.', 'rivergate-bordentown' ) )
		] );
	} );

	/* ---- location stats --------------------------------------------------- */

	registerDynamic( 'rivergate/location-stats', function ( props ) {
		return el( PanelBody, { title: __( 'Location Stats', 'rivergate-bordentown' ), initialOpen: true }, [
			text( props, 'stat1Num', __( 'Stat 1 — Number', 'rivergate-bordentown' ) ),
			text( props, 'stat1Label', __( 'Stat 1 — Label', 'rivergate-bordentown' ) ),
			text( props, 'stat2Num', __( 'Stat 2 — Number', 'rivergate-bordentown' ) ),
			text( props, 'stat2Label', __( 'Stat 2 — Label', 'rivergate-bordentown' ) ),
			text( props, 'stat3Num', __( 'Stat 3 — Number', 'rivergate-bordentown' ) ),
			text( props, 'stat3Label', __( 'Stat 3 — Label', 'rivergate-bordentown' ) )
		] );
	} );

	/* ---- contact form ----------------------------------------------------- */

	registerDynamic( 'rivergate/contact-form', function ( props ) {
		return el( PanelBody, { title: __( 'Contact Form', 'rivergate-bordentown' ), initialOpen: true }, [
			text( props, 'recipientEmail', __( 'Send submissions to', 'rivergate-bordentown' ), __( 'Blank = site admin email.', 'rivergate-bordentown' ) )
		] );
	} );

	/* ---- inner-page blocks ----------------------------------------------- */

	registerDynamic( 'rivergate/page-hero', function ( props ) {
		return el( PanelBody, { title: __( 'Page Hero', 'rivergate-bordentown' ), initialOpen: true }, [
			text( props, 'eyebrow', __( 'Eyebrow', 'rivergate-bordentown' ) ),
			text( props, 'heading', __( 'Page Title', 'rivergate-bordentown' ) ),
			media( props, 'imageUrl', 'imageId', __( 'Background Image', 'rivergate-bordentown' ), 'image' )
		] );
	} );

	registerDynamic( 'rivergate/amenity-cards', function ( props ) {
		return el( PanelBody, { title: __( 'Amenity Cards', 'rivergate-bordentown' ), initialOpen: true }, [
			el( 'p', { key: 'note', style: { fontStyle: 'italic' } },
				__( 'Cards come from Amenities → All Amenities (shared with the homepage). Built-in examples show until you add your own.', 'rivergate-bordentown' ) ),
			el( RangeControl, {
				key: 'max',
				label: __( 'Max amenities shown', 'rivergate-bordentown' ),
				min: 2, max: 12,
				value: props.attributes.maxItems,
				onChange: function ( v ) { props.setAttributes( { maxItems: v } ); }
			} )
		] );
	} );

	registerDynamic( 'rivergate/plan-cards', function () {
		return el( PanelBody, { title: __( 'Floor-Plan Cards', 'rivergate-bordentown' ), initialOpen: true },
			el( 'p', { style: { fontStyle: 'italic' } },
				__( 'One/Two bedroom cards come from Floor Plans → All Floor Plans (grouped by bedroom count). Built-in examples show until you add your own.', 'rivergate-bordentown' ) )
		);
	} );

	registerDynamic( 'rivergate/neighborhood-explorer', function () {
		return el( PanelBody, { title: __( 'Neighborhood Explorer', 'rivergate-bordentown' ), initialOpen: true },
			el( 'p', { style: { fontStyle: 'italic' } },
				__( 'Points of interest are maintained in the block template. The map uses RIVERGATE_MAPS_API_KEY from functions.php.', 'rivergate-bordentown' ) )
		);
	} );

	registerDynamic( 'rivergate/availability-embed', function ( props ) {
		return el( PanelBody, { title: __( 'Availability Embed', 'rivergate-bordentown' ), initialOpen: true }, [
			text( props, 'applyUrl', __( 'AppFolio listings URL', 'rivergate-bordentown' ), __( 'Blank = the default Rivergate listings URL.', 'rivergate-bordentown' ) )
		] );
	} );

}( window.wp ) );
