<?php
/**
 * Show the loop for this page and output the content or cards
 *
 */

/**
 * Reset post data just in case it was touched, or is pointing to something else and then put it back.
 * This can be used to get cards for an archive page.
 */

global $post;

$old_post = $post;
wp_reset_postdata();

?>
<?php
$class   = get_sub_field( 'class' );
$heading = get_sub_field( 'heading' );

$container = get_sub_field( 'container_class' );
$container = ( $container == '' ) ? 'container' : $container;

$loop_class = get_sub_field( 'loop_class' );
$loop_class = ( $loop_class == '' ) ? 'card-grid' : $loop_class;
?>
	<section id="section-<?php echo $section_hash; ?>" class="alignfull <?php echo esc_attr($class); ?>">
		<div class="<?php echo $container; ?>">

			<?php
			$heading = get_sub_field( 'heading' );
			if ( $heading ) {
				?><h2 class="text-center"><?php echo $heading; ?></h2>
				<?php
			} ?>

			<?php
			if ( is_post_type_archive() || is_home() || is_search() || is_archive() ) { ?>

				<div class="loop-holder <?php echo esc_attr($loop_class); ?>">

					<?php
					while ( have_posts() ) : the_post();
						if ( ! file_exists( locate_template( 'template-parts/' . get_post_type() ) ) ) {
							include( locate_template( 'template-parts/post/content.php' ) );
						} else {
							if ( get_post_format() && file_exists( locate_template( 'template-parts/' . get_post_type() . '/content-' . get_post_format() . '.php' ) ) ) {
								include(locate_template( 'template-parts/' . get_post_type() . '/content-' . get_post_format() . '.php' ));
							} else {
								include( locate_template( 'template-parts/' . get_post_type() . '/content.php' ) );
							}
						}
					endwhile;

					?>
				</div>

				<?php if ( get_sub_field( 'pagination' ) == 'yes' ) { ?>
					<div class="card-pagination text-center">
						<?php
						the_posts_pagination( array(
							'prev_text'          => ign_get_svg( array( 'icon' => 'angle-left' ) ) . '<span class="screen-reader-text">' . __( 'Previous page', 'ignition' ) . '</span>',
							'next_text'          => '<span class="screen-reader-text">' . __( 'Next page', 'ignition' ) . '</span>' . ign_get_svg( array( 'icon' => 'angle-right' ) ),
							'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'ignition' ) . ' </span>',
						) );
						?>
					</div>
				<?php } ?>

			<?php } else {
				//global $saving_sections;
//				if ( ! $saving_sections ) {
//					the_content();
//				}
                the_content();
			} ?>


		</div>
	</section>
<?php

//revert back to original post
$post = $old_post;
setup_postdata( $post );


