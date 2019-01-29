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
    if( is_admin() && $layout['name'] == 'the_loop' ){
	    $post_type = ign_is_page_archive_header( $post->ID, 'post_type' );
	    if ( get_post_type() == 'page' && $post_type ) {
		    $title = ucfirst( $post_type ) . ' Card List';
	    } else {
		    $title =  'Show Main Editor';
	    }
    }

	// return
	return $title;


}

add_filter('acf/fields/flexible_content/layout_title/name=sections', 'the_loop_layout_title', 10, 4);

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

/**
 * Header styles for acf
 */
function my_acf_admin_head() {
	?>
    <style type="text/css">
        #acf-group_5a79fa1baf007 {
            display: none;
            transition: box-shadow .5s;
        }

        .highlighted {
            box-shadow: 0 0 3px 1px yellow;
        }
    </style>
	<?php
}

add_action( 'acf/input/admin_head', 'my_acf_admin_head' );

/*
 * Javascript for ACF
 * Hiding/Showing the header layout field if "change header checkbox" field is selected.
 */
function acf_js() {
	if ( is_admin() ) {
		$screen = get_current_screen();

		if ( $screen->parent_base == 'edit' && $screen->post_type != 'acf-field-group' ) {
			?>
            <script type="text/javascript">
							let headerLayouts ='';
							let changeHeader = '';

							acf.addAction('prepare', function(){
								headerLayouts = acf.getPostbox("acf-group_5a79fa1baf007");
								changeHeader = acf.getField('field_5c4b66a65ae2c');

								//if header layouts group doesnt exist at all, dont show the change header field either
								if(! headerLayouts){
									changeHeader.hide();
									return;
								}

								//if change header is not checked, hide the header layout field
								if (!changeHeader.val()) {
									headerLayouts.hide();
								}

								changeHeader.$el.on('change', show_hide_header);
								document.querySelector('#page_template').addEventListener('change', ()=>{
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




            </script>
			<?php
		}
	}

}

add_action( 'acf/input/admin_footer', 'acf_js' );
