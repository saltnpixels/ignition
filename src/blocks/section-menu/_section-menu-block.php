<?php

function section_menu_register_block() {
// Section Menu Block
	acf_register_block_type( array(
		'name'            => 'section-menu',
		'title'           => __( 'Menu' ),
		'description'     => __( 'creates a menu with links where a user can scroll to a section' ),
		'render_template' => 'src/blocks/section-menu/section_menu-block.php',
		'category'        => 'ign-custom',
		'icon'            => 'menu',
		'keywords'        => array( 'columns', 'text' ),
		'supports'        => array(
			'align'  => array( 'wide', 'full' ),
			'anchor' => true
		)
	) );
}

// Check if function exists and hook into setup and adds all blocks.
if ( function_exists( 'acf_register_block_type' ) ) {
	add_action( 'acf/init', 'section_menu_register_block' );
}

function acf_load_menus( $field ) {

	$locations = get_registered_nav_menus();

	// reset choices
	$field['choices'] = array();

	// loop through array and add to field 'choices'
	if( is_array($locations) ) {

		foreach( $locations as $key => $location ) {

			$field['choices'][ $key ] = $location;

		}

	}


	// return the field
	return $field;

}

add_filter('acf/load_field/name=wp_menu_name', 'acf_load_menus');