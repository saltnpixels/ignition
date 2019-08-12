<?php
/**
 * This file outputs a header with items in a grid
 */
$class = get_sub_field( 'class' );

$container  = get_sub_field( 'container_class' );
$container  = ( $container == '' ) ? 'container' : $container;
$grid_class = get_sub_field( 'grid_class' );
$heading    = get_sub_field( 'heading' );

$bg_image = ign_get_header_image( get_the_ID() );
?>


<header class="entry-header layout-center-content <?php echo esc_attr( $class ); ?> <?php echo get_row_layout(); ?>"
        <?php if ( $bg_image ){ ?>style="background-image: url('<?php echo $bg_image; ?>');"<?php } ?>>

    <div class="<?php echo $container; ?>">

        <div class="header-content">
			<?php
			if ( ! $heading ) {
				the_title( '<h1 class="entry-title">', '</h1>' );
			} else {
				echo '<h1 class="entry-title">' . $heading . '</h1>';
			}
			?>
        </div>

		<?php if ( have_rows( 'header_items' ) ): ?>
            <div class="<?php echo esc_attr( $grid_class ); ?> header-items">
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