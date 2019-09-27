<?php

//this file shows the default header for the site
//headers can be overridden by adding a header block to the post/page with gutenberg
//

$bg_image = ign_get_header_image( get_the_ID() );
?>
<header class="alignfull entry-header layout-center-content <?php if($bg_image){ echo 'overlay';}?>"
        <?php if ( $bg_image ) { ?>style="background-image: url('<?php echo $bg_image; ?>');" <?php } ?>>

	<div class="header-content container-fluid text-center">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
	</div>

</header>
