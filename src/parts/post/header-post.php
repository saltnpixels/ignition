<header <?php ign_block_attrs($block, 'entry-header alignfull layout-center-content');?>>
	<div class="container">
		<h1 class="entry-title text-center"><?php the_title(); ?></h1>
		<div class="entry-description"><?php the_excerpt();?></div>
	</div>
</header>