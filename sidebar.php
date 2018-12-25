<?php
/**
 * The sidebar containing the main widget areas
 * with ACF you can switch to using the pull-in sidebar.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Ignition
 * @since 1.0
 * @version 1.0
 */


if ( ! is_active_sidebar( 'sidebar-1' ) ) {
	return;
}
?>


<aside id="secondary" class="widget-area span-4" role="complementary">
	<div data-moveto="#panel-right"  data-moveat="1200" class="sidebar-holder">
		<?php dynamic_sidebar( 'sidebar-1' ); ?>
	</div>
</aside><!-- #secondary -->
