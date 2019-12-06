<?php

/*--------------------------------------------------------------
# Post Extras
--------------------------------------------------------------*/
/**
 * Replaces "[...]" (appended to automatically generated excerpts) with ... and
 * a 'Continue reading' link.
 *
 * @return string 'Continue reading' link prepended with an ellipsis.
 * @since Ignition 1.0
 *
 */
function ignition_excerpt_more( $more ) {
	if ( is_admin() && ! wp_doing_ajax() ) {
		return $more;
	}

	return '&hellip; ';
}

add_filter( 'excerpt_more', 'ignition_excerpt_more' );


/*--------------------------------------------------------------
# Comment override walker
--------------------------------------------------------------*/

/**
 * Override comment section. Now you can change html and css how you want!
 * Taken from class-walker-comment.php html5 comment walker
 */

//html5 comment
function ignition_comments_callback( $comment, $args, $depth ) {

	$tag = ( 'div' === $args['style'] ) ? 'div' : 'li';
	?>
	<<?php echo $tag; ?> id="comment-<?php comment_ID(); ?>"
	<?php comment_class( $args['has_children'] ? 'parent' : '', $comment ); ?>>
	<article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
		<footer class="comment-meta">
			<div class="comment-author vcard">
				<?php if ( 0 != $args['avatar_size'] ) {
					echo get_avatar( $comment, $args['avatar_size'] );
				} ?>
			</div>
			<!-- .comment-author -->

			<div class="comment-name-date">
				<?php printf( __( '%s <span class="says">says:</span>' ), sprintf( '<b class="fn">%s</b>', get_comment_author_link( $comment ) ) ); ?>

				<div class="comment-metadata">
					<a href="<?php echo esc_url( get_comment_link( $comment, $args ) ); ?>">
						<time datetime="<?php comment_time( 'c' ); ?>">
							<?php
							/* translators: 1: comment date, 2: comment time */
							printf( __( '%1$s at %2$s' ), get_comment_date( '', $comment ), get_comment_time() );
							?>
						</time>
					</a>
					<?php edit_comment_link( __( 'Edit' ), '<span class="edit-link">', '</span>' ); ?>
				</div>
				<!-- .comment-metadata -->
			</div>

			<?php if ( '0' == $comment->comment_approved ) : ?>
				<p class="comment-awaiting-moderation">
					<?php _e( 'Your comment is awaiting moderation.' ); ?>
				</p>
			<?php endif; ?>
		</footer>
		<!-- .comment-meta -->

		<div class="comment-content">
			<?php comment_text(); ?>
		</div>
		<!-- .comment-content -->

		<?php
		comment_reply_link( array_merge( $args, array(
			'add_below' => 'div-comment',
			'depth'     => $depth,
			'max_depth' => $args['max_depth'],
			'before'    => '<div class="reply">',
			'after'     => '</div>'
		) ) );
		?>


	</article>
	<!-- .comment-body -->
	<?php
}
