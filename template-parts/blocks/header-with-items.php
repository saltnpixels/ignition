<?php
/**
 * This file outputs a header with items in a grid
 */
$class   = get_sub_field( 'class' );
$heading = get_sub_field( 'heading' );
$image   = get_sub_field( 'background_image' );

?>

<header class="entry-header layout-center-content <?php echo $class; ?> <?php echo $header; ?>"
        style="background-image: url('<?php echo ign_get_the_image_url( get_the_ID(), 'full', $image ); ?>');">

	<div class="container">

		<div class="header-content text-center">
			<?php
			if ( ! $heading ) {
				the_title( '<h1 class="entry-title">', '</h1>' );
			} else {
				echo '<h1>' . $heading . '</h1>';
			}
			?>
		</div>

		<?php if ( have_rows( 'header_items' ) ): ?>
			<div class="flex-grid">
				<?php
				while ( have_rows( 'header_items' ) ): the_row(); ?>
					<div class="header-item <?php echo get_sub_field( 'class' ); ?>">
						<?php the_sub_field( 'header_item' ); ?>
					</div>
				<?php endwhile; ?>
			</div>
		<?php endif; ?>

	</div>
</header>