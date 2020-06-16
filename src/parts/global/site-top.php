<?php
/**
 * @package Ignition
 * @since 1.0
 * @version 1.0
 *
 * This shows the very top of the site with logo and navigation.
 * The navigation is using data-moveto to move itself into the left panel when the site hits a max-width of --nav-move, a css variable of 800px by default
 */

?>

<div class="site-top <?php echo ign_get_config( 'logo-position', 'logo-left' ); ?>">
    <div class="site-top-container <?php echo ign_get_config( 'site-top-container', 'container' ); ?>">
        <div class="site-navigation horizontal-menu flex">
			<?php echo ign_logo(); ?>

            <div class="site-navigation__nav-holder" data-moveto="#panel-left" data-moveat="--nav-move"
                 data-moveto-pos="0">
                <nav class="site-navigation__nav" role="navigation"
                     aria-label="<?php _e( 'Top Menu', 'ignition' ); ?>">
					<?php wp_nav_menu( array(
						'theme_location' => 'top-menu',
						'menu_id'        => 'top-menu',
						'container'      => '',
						'fallback_cb'    => 'link_to_menu_editor'
					) ); ?>
                </nav>
            </div>
            <!-- site-navigation__nav-holder -->
        </div>
        <!-- site-navigation -->
    </div>
    <!-- site-top-container -->
</div>
<!-- site-top -->
