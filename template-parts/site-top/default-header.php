<?php
/**
 *
 * This file is used to show the default header when no header block or post type header exists.
 *
 * @package Ignition Press
 * @since 1.0
 * @version 1.0
 */


$bg_image = ign_get_header_image( get_the_ID() );
if ( ! $bg_image && ! get_field( 'no_image', get_the_ID() ) ) {
	$bg_image = get_header_image();
}
?>

<header class="alignfull entry-header default-header layout-center-content <?php if ( $bg_image ) {
	echo 'overlay';
} ?>"
        <?php if ( $bg_image ) { ?>style="background-image: url('<?php echo $bg_image; ?>');" <?php } ?>>

	<div class="header-content container-fluid text-center">
		<?php
		if ( is_single() || is_page() ):
			the_title( '<h1 class="entry-title">', '</h1>' );

		elseif ( is_home() ):
			$label = get_option( 'options_posts_label_plural' ); //can be set in theme settings
			$label = $label ? $label : 'Articles';
			echo '<h1 class="entry-title">' . $label . '</h1>';
		else:
			echo '<h1 class="entry-title">' . get_the_archive_title() . '</h1>';
		endif;

		?>
	</div>

</header>
