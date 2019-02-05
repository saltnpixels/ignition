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
			return __( 'Please install ACF Pro to use this function properly', 'ignition' );
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
				$field['layouts']['5afb034fab739']['label'] = ucfirst( $post_type ) . 'Show Main Editor';
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
			$title = 'Show Main Editor';
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
function save_sections( $post_id ) {
//must be saving a post. not any options
	global $post;
	if ( $post ) {
		$id = $post->ID;
		if ( $post_id == $id ) {
			if ( function_exists( 'have_rows' ) && have_rows( 'sections', $id ) ) {
				ob_start();
				setup_postdata( $post );
				//run through all sections
				locate_template( 'template-parts/acf-blocks/sections.php', true );
				$sections = ob_get_clean();
				update_post_meta( $id, 'acf_all_sections', $sections );
			}
		}
	}
}

add_action( 'acf/save_post', 'save_sections', '99' );


//add styles for acf back end
function my_acf_admin_enqueue_scripts() {
	wp_enqueue_style( 'ign_acf_styles', get_template_directory_uri() . '/inc/acf_extras/acf_styles.css' );
}

add_action( 'acf/input/admin_enqueue_scripts', 'my_acf_admin_enqueue_scripts' );


/*
 * Javascript for ACF
 * Hiding/Showing the header layout field if "change header checkbox" field is selected.
 * Allow adding classes that affect the actual fields layout on back end so it matches front end.
 */
function acf_js() {
	if ( is_admin() ) {
		$screen = get_current_screen();

		//if this is the edit page screen
		if ( $screen->parent_base == 'edit' && $screen->post_type != 'acf-field-group' ) {
			?>
            <script type="text/javascript">
                let headerLayouts = '';
                let changeHeader = '';

                acf.addAction('prepare', function () {
                    headerLayouts = acf.getPostbox("acf-group_5a79fa1baf007");
                    changeHeader = acf.getField('field_5c4b66a65ae2c');

                    //if header layouts group doesnt exist at all, dont show the change header field either
                    if (!headerLayouts) {
                        changeHeader.hide();
                        return;
                    }

                    //if change header is not checked, hide the header layout field
                    if (!changeHeader.val()) {
                        headerLayouts.hide();
                    }

                    changeHeader.$el.on('change', show_hide_header);
                    document.querySelector('#page_template').addEventListener('change', () => {
                        setTimeout(show_hide_header, 500);
                    });
                });

                function show_hide_header() {
                    if (changeHeader.val()) {
                        headerLayouts.show();
                        $('html,body').animate({scrollTop: 0}, 'slow');
                        headerLayouts.$el.addClass('highlighted');
                        setTimeout(function () {
                            headerLayouts.$el.removeClass('highlighted');
                        }, 2000);
                    } else {
                        headerLayouts.hide();
                    }
                }


                //allow special data-ign-class to change dynamically
                document.addEventListener("DOMContentLoaded", function () {
                    //get the data attribute
                    let igndataattributes = document.querySelectorAll('[data-ign-class]');
                    igndataattributes.forEach(acfinput => {
                        changeIgnClasses(acfinput);
                    });
                });

                //anytime this input changes, change the class
                document.addEventListener("change", function (event) {

                    if (event.target.matches('[data-ign-class]')) {
                        changeIgnClasses(event.target);
                    }
                });

                function changeIgnClasses(acfInput) {
                    //to do anything there must be a value set

                        let dataValue = acfInput.getAttribute('data-ign-class');

                        //find the data attribute as a selector
                        //first go up only to the nearest set of fields. if nothing is found query all the way up.
                        let acfSelector = acfInput.closest(".acf-fields").querySelector(dataValue);
                        if (acfSelector == null) {
                            acfSelector = acfInput.closest(dataValue);
                        }

                        //remove previous values if any
                        if (acfInput.getAttribute('data-last-value')) {
                            let lastValues = acfInput.getAttribute('data-last-value').split(' ');
                            lastValues = lastValues.filter(Boolean); //remove any empty strings
                            acfSelector.classList.remove(...lastValues);
                        }

                        //set class on the queried selector if there is a value
                    if(acfInput.value !== ' ' && acfInput.value){
                        console.log(acfInput);

                        let classes = acfInput.value.split(" "); //if there is more than one class
                        classes = classes.filter(Boolean);
                        acfSelector.classList.add(...classes);
                        acfInput.setAttribute('data-last-value', acfInput.value);
                    }




                }

            </script>
			<?php
		}
	}

}

add_action( 'acf/input/admin_footer', 'acf_js' );


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
		'label'        => __( 'Set ign data' ),
		'instructions' => 'This field adds a data attribute. The value of this custom field will now add a class to the matching closest selector. Use .acf-row to affect this fields container.',
		'name'         => 'ign_set_data',
		'type'         => 'text',
		'ui'           => 99
	), true );

}

add_action( 'acf/render_field_settings/type=text', 'ign_data_field_settings' );


function ign_show_data_field( $field ) {

	if ( ! empty( $field['ign_set_data'] ) ) {
		//this script runs once and the repeated fields just get cloned.
		echo '
            <script>
            {
           //get the ign data class attribute and set it to the input field
         let acfinput = document.querySelector("#' . $field['id'] . '");
          acfinput.setAttribute("data-ign-class", "' . $field['ign_set_data'] . '");
          
         }
        </script>';

	}
}

add_filter( 'acf/render_field', 'ign_show_data_field', 11, 1 );