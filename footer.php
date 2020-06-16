<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #page and #content div and any content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 * @package Ignition
 * @since 1.0
 * @version 1.0
 */

?>

</div><!-- #site-content -->

<footer id="colophon" class="site-footer" role="contentinfo">
		<?php ign_template('src/parts/global/site-footer.php'); ?>
</footer><!-- #colophon -->

</div><!-- #page -->

<?php
$sidebar_icon = ign_get_config( 'sidebar_icon', 'sidebar-icon' );
if($sidebar_icon == 'sidebar-icon'){
	$sidebar_icon = "<span class='$sidebar_icon'></span>";
}
?>

<button aria-label="Toggle Right Panel" data-toggle="open" data-target="#panel-right" aria-expanded="false" class="panel-right-toggle"><?php echo $sidebar_icon; ?></button>

</div><!-- .site-container -->

<?php wp_footer(); ?>

</body>
</html>
