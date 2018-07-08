<?php
/**
 * This file outputs a header with items in a grid
 */
$class   = get_sub_field( 'class' );
$heading = get_sub_field( 'heading' );

?>

<header class="entry-header <?php echo $class; ?> <?php echo $header; ?>">

	<?php $image = get_sub_field( 'cover_image' ); ?>
	<?php $bg_image = ign_get_the_image( get_the_ID(), 'full', $image ); ?>

	<div class="header-image cover-image">
		<?php echo $bg_image; ?>
	</div>

	<div class="container">

		<div class="header-content text-center">
			<?php
			if ( ! $heading ) {
				the_title( '<h1 class="entry-title">', '</h1>' );
			} else {
				echo '<h1>' . $heading . '</h1>';
			}

			the_sub_field( 'header_text' );
			?>

		</div>


	</div>
</header>