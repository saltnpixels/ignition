<?php
/*
* Add different type of headers by editing this block here and in the ACF Settings
* Add a new header to the header_type dropdown and make conditional fields show up for the new type of header.
*
* @param array $block The block settings and attributes.
* @param string $content The block inner HTML (empty).
* @param bool $is_preview True during AJAX preview.
* @param int|string $post_id The post ID this block is saved to.
*/


$block_id = isset( $block['anchor'] ) ? $block['anchor'] : 'section-' . $block['id'];
$bg_image = ign_get_header_image( $post_id );

//type of header
$header_type = get_field( 'header_type' );

?>


<?php if ( $header_type == 'header_paragraphs' ): ?>

	<header id="<?php echo $block_id; ?>" <?php ign_block_class( $block, 'entry-header layout-center-content ' . $header_type ); ?>
	        <?php if ( $bg_image ){ ?>style="background-image: url('<?php echo $bg_image; ?>');"<?php } ?>>
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
	</header>

<?php endif; ?>


<?php //YOUR NEW HEADER TYPE HERE?>
<?php if ( $header_type == 'something' ): ?>
	<header></header>
<?php endif; ?>
