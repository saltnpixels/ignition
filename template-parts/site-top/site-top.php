<?php
//if using the customizer for navigation and site-top area.
if ( get_theme_mod( 'site_top_use_customizer', 'yes' ) == 'yes' ):

	$container = get_theme_mod( 'site_top_contained', 'container' ) == 'container' ? 'container' : 'container-fluid';
	$logo_position = get_theme_mod( 'site_top_layout', 'logo-left' );


	?>


	<div class="site-top <?php echo $logo_position; ?>">
		<div class="site-top-container <?php echo $container; ?>">

		<div class="site-navigation horizontal-menu flex">
			<?php echo ign_logo(); ?>

			<div class="site-navigation__nav-holder" data-moveto="#panel-left" data-moveat="800">
				<nav class="site-navigation__nav" role="navigation"
				     aria-label="<?php _e( 'Top Menu', 'ignition' ); ?>">
					<?php wp_nav_menu( array(
						'theme_location' => 'top',
						'menu_id' => 'top-menu',
						'container' => ''
					) ); ?>
				</nav>
			</div>
			<!-- site-navigation__nav-holder -->
		</div>
		<!-- site-navigation -->
		</div>
	</div>
	<!-- site-top -->
<?php endif;

//ADD YOUR OWN CODE HERE IF NOT USING THE CUSTOMIZER
