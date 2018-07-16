<?php
//full width capable text section where you can have multiple sections arranged on a grid.

$class = get_sub_field( 'class' );

$container = get_sub_field( 'container_class' );
$container = ( $container == '' ) ? 'container' : $container;

$grid_class = get_sub_field( 'grid_class' );
$image = get_sub_field( 'background_image' );
$heading = get_sub_field( 'heading' );
?>

<section id="section-<?php echo $section_hash; ?>" class="alignfull section-paragraphs <?php echo $class; ?>"
	<?php if($image){ ?> style="background-image: url('<?php echo ign_get_the_image_url( get_the_ID(), 'full', $image);
	?>');" <?php } ?>>
	<div class="<?php echo $container; ?>">
		<?php
		if ( $heading ) {
			?><h2><?php echo $heading; ?></h2>
			<?php
		}
		?>

		<?php
		if ( have_rows( 'paragraphs' ) ) { ?>
			<div class="<?php echo $grid_class; ?> paragraphs-holder">
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
</section>


