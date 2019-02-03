<?php
/**
 * A basic header with an image output above the title
 */
$class   = get_sub_field( 'class' );
$heading = get_sub_field( 'heading' );

$bg_image = ign_get_header_image( get_the_ID() );

?>

<header class="entry-header <?php echo esc_attr( $class ); ?> <?php echo get_row_layout(); ?>">

	<?php
	if ( $bg_image ): ?>

        <div class="header-image cover-image">
			<?php echo $bg_image; ?>
        </div>
	<?php endif; ?>

    <div class="container">

        <div class="header-content text-center">
			<?php
			if ( ! $heading ) {
				the_title( '<h1 class="entry-title">', '</h1>' );
			} else {
				echo '<h1 class="entry-title">' . $heading . '</h1>';
			}

			the_sub_field( 'header_text' );
			?>

        </div>


    </div>
</header>