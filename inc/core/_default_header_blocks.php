<?php

/**
 * Default Header Blocks
 * This file checks the theme config and takes all post types found in default_acf_header_block and gives it a default block template when a new post is made.
 * If a file header-{post-type} exists inside the post type folder, that will be used and shown as the header block template! Otherwise header-post.php is used
 * You can then go and add a acf group and match it to the new header block template and make new fields!
 *
 * If you dont have such a file, it will default to show header-post.php using the header block acf group
 *
 * if a block claims its missing, than you must have removed a field group that was once associated and old posts are looking for this group
 */
function add_header_block_default() {

	if ( function_exists( 'acf_register_block_type' ) ) {
		$post_types = ign_get_config( 'default_acf_header_block' );

		foreach ( $post_types as $post_type ) {

			$post_type_object = get_post_type_object( $post_type );
			$block_name       = '';

			//if this post type has a header-{post-tyoe} file, we register the block
			//otherwise we just make the posts header the default for this post type
			if ( locate_template( array( 'src/parts/' . $post_type . '/header-' . $post_type . '.php' ), false, false ) ) {
				//found file, so we make a new block and set it as a default block for that post type
				$block_name = 'acf/header-' . $post_type;
				ign_register_header_block( $post_type_object );
			} elseif ( locate_template( array( 'src/parts/post/header-post.php' ), false, false ) ) {
				//use default post header if the header-post file exists
				$block_name = 'acf/header-post';
			}

			if ( $block_name ) {
				$post_type_object->template = array(
					array( $block_name ),
				);
			}

		}
	}
}

add_action( 'init', 'add_header_block_default', 9999 );


//registering the new block
function ign_register_header_block( $post_type ) {
	$title      = '';
	$post_types = '';
	if ( $post_type->name != 'post' ) {
		$title      = ucwords( $post_type->labels->singular_name ) . ' ';
		$post_types = array( $post_type->name );
	}

	acf_register_block_type( array(
		'name'            => 'header-' . $post_type->name,
		'title'           => __( $title . 'Header' ),
		'description'     => __( 'Header Block' ),
		'render_callback' => 'render_default_block',
		'category'        => 'ign-custom',
		'icon'            => 'schedule',
		'keywords'        => array( 'header', 'hero' ),
		'align'           => 'full',
		'post_types'      => $post_types,
		'supports'        => array(
			'anchor'   => true,
			'align'    => array( 'wide', 'full' ),
			'multiple' => false
		),

	) );
}


/**
 * @param $block
 * @param $content
 * @param $is_preview
 * @param $post_id
 *
 * Render header block with a function instead of a template which in turn gets the header-{post-type}.php template with loop fully loaded.
 * the_title will also change on the fly for smooth experience
 */
function render_default_block( $block, $content, $is_preview, $post_id ) {
	if ( $is_preview ) {
		global $post;
		$post = get_post( $post_id );
		setup_postdata( $post );
		//allow copying of page title on backend for smooth experience
		add_filter( 'the_title', 'copy_page_title' );
		add_filter('get_the_excerpt', 'copy_page_excerpt');
	}

	//the magic. Gets the right template file depending on post type! defaults to header-post.php
	ign_template( 'header', array( 'block' => $block, 'content' => $content, 'is_preview' => $is_preview, 'post_id' => $post_id ) );

	if ( $is_preview ) {
		wp_reset_postdata();
		remove_filter( 'the_title', 'copy_page_title' );
		remove_filter('get_the_excerpt', 'copy_page_excerpt');
	}


	//change title when the_title or get_the_title is used in template
    ign_change_title_description($block, $is_preview);
}


/**
 * @param $is_preview
 * on back end we show smooth experince for header blocks by allowing the title and description fields to update the dom
 */
function ign_change_title_description($block, $is_preview){
	if ( $is_preview ):
		?>
        <script>
           let entryTitle = document.querySelector('#<?php echo $block['id'] ?> .entry-title .copy-title')
           let entryDescription = document.querySelector('#<?php echo $block['id'] ?> .copy-excerpt')
           let excerptTextarea = document.querySelector('.editor-post-excerpt__textarea textarea')
           let postTitle = document.querySelector('.editor-post-title__input')


           function changeTextOnInput(blockEl, inputEl, defaultString = 'Add Title'){
              if (blockEl && inputEl) {
                 if (inputEl.value==='') {
                    blockEl.innerHTML = defaultString
                 }
                 inputEl.addEventListener('input', function (e) {
                    blockEl.innerHTML = inputEl.value
                    if (inputEl.value==='') {
                       blockEl.innerHTML = defaultString
                    }
                 })
              }
           }

           changeTextOnInput(entryTitle, postTitle, 'Add Title')
           changeTextOnInput(entryDescription, excerptTextarea, 'Add Excerpt')



        </script>
	<?php endif;
}


//continue to easily use the_title or get_the_title in your block and still see the right text based on the filters output when in gutenberg on back end
function copy_page_title( $example ) {
	return '<span class="copy-title">' . $example . '</span>';
}

function copy_page_excerpt( $example ) {
	return '<div class="copy-excerpt">' . $example . '</div>';
}