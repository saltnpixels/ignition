<?php
/**
 *
 * This file is used to show the default header when no header block or section has been chosen.
 *
 * @package ignition
 * @since 1.0
 * @version 1.0
 */

//this file shows the default header for the post/page when none other is specified.
//headers can be overridden by adding a header block to the post/page with gutenberg or with the classic blocks in the header flexible field


$bg_image = ign_get_header_image( get_the_ID() );
?>
<header class="alignfull entry-header default-header layout-center-content <?php if($bg_image){ echo 'overlay';}?>"
        <?php if ( $bg_image ) { ?>style="background-image: url('<?php echo $bg_image; ?>');" <?php } ?>>

	<div class="header-content container-fluid text-center">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
	</div>

</header>
