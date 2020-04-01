<?php

/**
 * Paragraphs Block Template.
 *
 * @param array $block The block settings and attributes.
 * @param string $content The block inner HTML (empty).
 * @param bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */


$block_id        = ign_get_block_anchor($block);
$image           = wp_get_attachment_image_url( get_field( 'background_image' )['ID'], 'full' ); //gets the background image url from an acf field
$image_placement = get_field('background_image_placement') ? 'image-inside' : '';

?>

<?php if ( $image ): ?>
	<style>
		#<?php echo $block_id;  if($image_placement){ echo ' .columns-holder'; } ?>{
        background-image: url(<?php echo $image; ?>);
        background-size: cover;
    }
	</style>
<?php endif; ?>

<section id="<?php echo $block_id; ?>" <?php ign_block_class( $block, $image_placement ); ?>>

	<div class="columns-holder <?php echo esc_attr( get_field( 'container_class' ) ); ?>">
		<?php
		if ( have_rows( 'paragraphs' ) ) { ?>
			<div class="<?php echo esc_attr( get_field( 'grid_class' ) ); ?> paragraphs-holder">
				<?php while ( have_rows( 'paragraphs' ) ): the_row(); ?>

					<div class="text-section <?php the_sub_field( 'paragraph_class' ); ?>">
							<?php the_sub_field( 'paragraph' ); ?>
					</div>

				<?php endwhile; ?>
			</div>
			<?php
		}
		?>
	</div>
	<!-- Container -->

</section>
