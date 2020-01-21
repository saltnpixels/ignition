<?php


//register ACF Blocks. Add your blocks in here
function register_acf_block_types() {

	// Column block
	acf_register_block_type( array(
		'name'            => 'paragraphs',
		'title'           => __( 'Column Sections' ),
		'description'     => __( 'Allows for multiple sections on a grid' ),
		'render_template' => 'template-parts/acf-blocks/paragraphs/paragraphs.php',
		'category'        => 'ign-custom',
		'icon'            => ign_get_svg( array( "icon" => "view_column" ) ),
		'keywords'        => array( 'columns', 'text' ),
		'supports'        => array(
			'align'  => array( 'wide', 'full' ),
			'anchor' => true
		)
	) );

	// Section Menu Block
	acf_register_block_type( array(
		'name'            => 'section-menu',
		'title'           => __( 'Menu' ),
		'description'     => __( 'creates a menu with links where a user can scroll to a section' ),
		'render_template' => 'template-parts/acf-blocks/section-menu/section_menu.php',
		'category'        => 'ign-custom',
		'icon'            => 'menu',
		'keywords'        => array( 'columns', 'text' ),
		'supports'        => array(
			'align'  => array( 'wide', 'full' ),
			'anchor' => true
		)
	) );


	// Card (loop) Block
	acf_register_block_type( array(
		'name'            => 'cards',
		'title'           => __( 'Cards' ),
		'description'     => __( 'shows a listing of items for a post type or an archive page' ),
		'render_template' => 'template-parts/acf-blocks/cards/cards.php',
		'category'        => 'ign-custom',
		'icon'            => 'editor-table',
		'keywords'        => array( 'archive', 'cards', 'listing' ),
		'supports'        => array(
			'align'  => array( 'wide', 'full' ),
			'anchor' => true
		)
	) );

	// Header Block
	acf_register_block_type( array(
		'name'            => 'header',
		'title'           => __( 'Header' ),
		'description'     => __( 'Overrides the basic header and shows a special one for this post or page.' ),
		'render_template' => 'template-parts/acf-blocks/header/header.php',
		'category'        => 'ign-custom',
		'icon'            => 'schedule',
		'keywords'        => array( 'header', 'hero' ),
		'align'           => 'full',
		'supports'        => array(
			'anchor'   => true,
			'align'    => array( 'wide', 'full' ),
			'multiple' => false
		),

	) );


	//ADD MORE BLOCKS HERE


}


// Check if function exists and hook into setup and adds all blocks.
if ( function_exists( 'acf_register_block_type' ) ) {
	add_action( 'acf/init', 'register_acf_block_types' );
}


/**
 * @param $categories
 * @param $post
 *
 * @return array
 *
 * Adding a new Block category for this theme
 */
function ign_block_categories( $categories, $post ) {
	return array_merge(
		array(
			array(
				'slug'  => 'ign-custom',
				'title' => __( 'Ignition', 'ignition' ),
//				'icon'  => 'marker',
			),
		),
		$categories
	);
}

add_filter( 'block_categories', 'ign_block_categories', 10, 2 );



