<article id="post-<?php the_ID(); ?>" <?php post_class( 'card' ); ?>>
	<div class="header-image cover-image">
		<?php
		if ( has_post_thumbnail() ) {
			the_post_thumbnail( 'post-thumbnail' );
		}
		?>
	</div>

	<header class="card-header">
		<div class="header-content">
			<?php echo ign_get_term_links(); ?>
			<?php the_title( '<h2 class="card-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>
		</div>
	</header>

	<div class="card-content">
		<?php
		the_excerpt();
		?>
	</div><!-- .card-content -->

	<div class="card-meta">
		<?php ign_posted_on(); ?>
		<?php echo ign_comment_link(); ?>
	</div>
</article><!-- #post-## -->
