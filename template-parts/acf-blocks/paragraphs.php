<?php

/**
 * Paragraphs Block Template.
 *
 * @param array $block The block settings and attributes.
 * @param string $content The block inner HTML (empty).
 * @param bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */


$block_id = isset($block['anchor']) ? $block['anchor'] : 'section-' . $block['id'];
$image      = ign_get_image_url('background_image' ); //gets the background image url from an acf field


?>

<section id="<?php echo $block_id; ?>" <?php ign_block_class($block); ?> style="background-image: url(<?php echo $image; ?>); background-size: cover;">

	<div class="columns-holder <?php echo esc_attr(get_field( 'container_class' ) ); ?>">
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
