<?php
/**
 * The sidebar containing the main widget areas
 * the data-moveat will move the entire sidebar-holder into the right panel at 1200 pixels
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


<aside id="secondary" class="widget-area span-4 sidebar-bg" role="complementary">
	<div data-moveto="#panel-right"  data-moveat="1200" class="sidebar-holder ">
		<?php dynamic_sidebar( 'sidebar-1' ); ?>
	</div>
</aside><!-- #secondary -->
