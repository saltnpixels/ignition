<?php
/**
 * This file outputs a header with items in a grid
 */
$class   = get_sub_field( 'class' );
$heading = get_sub_field( 'heading' );
$image   = get_field( 'header_image' );
$bg_image = ign_get_the_image_url( get_the_ID(), 'header_image', $image );
?>


<header class="entry-header layout-center-content <?php echo $class; ?> <?php echo $header; ?>"
        <?php if ( $bg_image ){ ?>style="background-image: url('<?php echo $bg_image; ?>');"<?php } ?>>

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