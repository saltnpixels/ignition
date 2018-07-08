<?php
/**
 * Displays footer widgets if assigned
 *
 * @package Ignition
 * @since 1.0
 * @version 1.0
 */

?>

<?php
//up to 4 widgets for footer area
if ( is_active_sidebar( 'sidebar-2' ) ||
	 is_active_sidebar( 'sidebar-3' ) ||
	 is_active_sidebar( 'sidebar-4' ) ||
	 is_active_sidebar( 'sidebar-5' ) ) :
?>

	<div class="footer-widgets flex">
		<?php
		if ( is_active_sidebar( 'sidebar-2' ) ) { ?>
			<div class="footer-widget-1">
				<?php dynamic_sidebar( 'sidebar-2' ); ?>
			</div>
		<?php }
		if ( is_active_sidebar( 'sidebar-3' ) ) { ?>
			<div class="footer-widget-2">
				<?php dynamic_sidebar( 'sidebar-3' ); ?>
			</div>
		<?php } 
		if ( is_active_sidebar( 'sidebar-4' ) ) { ?>
			<div class="footer-widget-3">
				<?php dynamic_sidebar( 'sidebar-4' ); ?>
			</div>
		<?php } 
		if ( is_active_sidebar( 'sidebar-5' ) ) { ?>
			<div class="footer-widget-4">
				<?php dynamic_sidebar( 'sidebar-5' ); ?>
			</div>
		<?php } ?>
	</div><!-- .widget-area -->

<?php endif; ?>

