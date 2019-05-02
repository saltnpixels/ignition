<?php

/*--------------------------------------------------------------
# ACF functions if not installed. Just some text
--------------------------------------------------------------*/
function plugin_init() {

	if ( ! function_exists( 'the_row' ) ) {
		function the_row() {
			return __( 'Please install ACF Pro to use this function properly', 'ignition' );
		}
	}

	if ( ! function_exists( 'get_field' ) ) {
		function get_field( $field = '', $id = 0 ) {
			return get_post_meta( $id, $field, true );
		}
	}

	if ( ! function_exists( 'the_field' ) ) {
		function the_field( $field = '', $id = 0 ) {
			return __( 'Please install ACF Pro to use this function properly', 'ignition' );
		}
	}

	if ( ! function_exists( 'the_sub_field' ) ) {
		function the_sub_field( $field = '', $id = 0 ) {
			return __( 'Please install ACF Pro to use this function properly', 'ignition' );
		}
	}

	if ( ! function_exists( 'get_sub_field' ) ) {
		function get_sub_field( $field = '', $id = 0 ) {
			return __( 'Please install ACF Pro to use this funciton properly', 'ignition' );
		}
	}
}

add_action( 'plugins_loaded', 'plugin_init' );

/**
 * @param $field
 *
 * @return mixed
 * Makes the loop field display archive or the content depending on what is being looked at.
 */
function acf_loop_field( $field ) {
	global $post;
	if ( $post && is_admin() ) {
		$screen = get_current_screen();
		if ( $screen->parent_base == 'edit' && $screen->post_type != 'acf-field-group' ) {

			$post_type = ign_is_page_archive_header( $post->ID, 'post_type' );
			if ( is_admin() && get_post_type() == 'page' && $post_type ) {
				$field['layouts']['5afb034fab739']['label'] = ucfirst( $post_type ) . ' Card List';
			} else {
				$field['layouts']['5afb034fab739']['label'] = ucfirst( $post_type ) . 'Main Editor';
			}
		}

	}

	return $field;
}

add_filter( 'acf/load_field/name=sections', 'acf_loop_field' );

//Make sure the title remains as either the card list or the editor.
function the_loop_layout_title( $title, $field, $layout, $i ) {
	// remove layout title from text
	global $post;
	if ( is_admin() && $layout['name'] == 'the_loop' ) {
		$post_type = ign_is_page_archive_header( $post->ID, 'post_type' );
		if ( get_post_type() == 'page' && $post_type ) {
			$title = ucfirst( $post_type ) . ' Card List';
		} else {
			$title = 'Main Editor';
		}
	}

	// return
	return $title;


}

add_filter( 'acf/fields/flexible_content/layout_title/name=sections', 'the_loop_layout_title', 10, 4 );

/**
 * @param $post_id
 * Save the sections altogether just in case a new theme is used and the pages need to be exported. Also lets SEO work and searching works
 */
$saving_sections = false;
function save_sections( $post_id ) {
//must be saving a post. not any options
	global $post;
	if ( $post ) {
		$id = $post->ID;
		if ( $post_id == $id ) {
			if ( function_exists( 'have_rows' ) && have_rows( 'sections', $id ) ) {
				ob_start();
				setup_postdata( $post );
				global $saving_sections;
				$saving_sections = true;
				//run through all sections and make sure to not save any edit link links
				locate_template( 'template-parts/acf-blocks/sections.php', true );
				$sections = ob_get_clean();
				update_post_meta( $id, 'acf_all_sections', $sections );
				$saving_sections = false;
			}
		}
	}
}

add_action( 'acf/save_post', 'save_sections', '99' );


//add styles for acf back end
function my_acf_admin_enqueue_scripts() {
	wp_enqueue_style( 'ign_acf_styles', get_template_directory_uri() . '/inc/acf_extras/acf_styles.css' );

	//js should only happen on edit screens
	if ( is_admin() ) {
		$screen = get_current_screen();
		if ( $screen->post_type != 'acf-field-group' ) {
			wp_enqueue_script( 'ign_acf_scripts', get_template_directory_uri() . '/inc/acf_extras/acf_scripts.js', '', wp_get_theme()->get( 'Version' ) );
		}
	}


}

add_action( 'acf/input/admin_enqueue_scripts', 'my_acf_admin_enqueue_scripts', 99 );


//add new field Admin only. wont show field unless your an admin
function ign_admin_field_settings( $field ) {

	acf_render_field_setting( $field, array(
		'label'        => __( 'Admin Only?' ),
		'instructions' => '',
		'name'         => 'admin_only',
		'type'         => 'true_false',
		'ui'           => 1,
	), true );

}

add_action( 'acf/render_field_settings', 'ign_admin_field_settings' );


function ign_admin_prepare_field( $field ) {
	// bail early if no 'admin_only' setting
	if ( empty( $field['admin_only'] ) ) {
		return $field;
	}
	// return false if is not admin (removes field)
	if ( ! current_user_can( 'administrator' ) ) {
		return false;
	}

	return $field;
}

add_filter( 'acf/prepare_field', 'ign_admin_prepare_field' );


//add new field to a custom field. ign data attribute
//This attribute allows you to make a input field send its values as a class to another field. useful when making text sections and wanting to see them on a grid
function ign_data_field_settings( $field ) {

	acf_render_field_setting( $field, array(
		'label'        => __( 'Set Value as Class' ),
		'instructions' => 'The value of this custom field will now be added a class to the matching closest selector. Use .acf-row to affect this fields container.',
		'name'         => 'ign_set_data',
		'type'         => 'text',
		'placeholder'  => '.acf-row',
		'ui'           => 99
	), true );

}

add_action( 'acf/render_field_settings/type=text', 'ign_data_field_settings' );
add_action( 'acf/render_field_settings/type=true_false', 'ign_data_field_settings' );


/**
 * @param $field
 * adds a data attribute to the text input
 * then with js we give that value as a class to another element based on selector chosen
 */
function ign_show_data_field( $field ) {

	if ( ! empty( $field['ign_set_data'] ) ) {
		//this script runs once and the repeated fields just get cloned.
		echo '
            <script>
            {
           //get the ign data attribute and set it to the input field
         let acfinput = document.querySelector("#' . $field['id'] . '");
         //set the attribute value to the selector chosen
          acfinput.setAttribute("data-ign-class", "' . $field['ign_set_data'] . '");
          
         }
        </script>';

	}
}

add_filter( 'acf/render_field', 'ign_show_data_field', 11, 1 );


//change title of sections flexible field
function ign_section_titles( $title, $field, $layout, $i ) {
	//if sub field for layout title exists use it instead.
	$layout_title = get_sub_field( 'section_title' ); //get section title field if exists
	if ( ! $layout_title ) {
		$layout_title = get_sub_field( 'title' ); //try to find a title field
	}
	if ( ! $layout_title ) {
		$layout_title = get_sub_field( 'heading' ); //try to find a title field
	}

	if ( $layout_title ) {
		$title = '<strong>' . $layout_title . '</strong> - ' . $title;
	}

	// return
	return $title;

}

// name
add_filter( 'acf/fields/flexible_content/layout_title/name=sections', 'ign_section_titles', 10, 4 );