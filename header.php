<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="site-content">
 *
 * Some stuff are added to the head via wp_head() from functions.php including title tag, fonts, style.css, scripts and more
 *
 * @package ignition
 * @since 1.0
 * @version 1.0
 */

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js no-svg dom-loading">

<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<?php
$app_menu = '';
if ( get_theme_mod( 'app_menu', '' ) ) {
	$app_menu = 'app-menu';
}
?>
<body <?php body_class($app_menu); ?>>
<a class="skip-link screen-reader-text" href="#site-content">
	<?php _e( 'Skip to content', 'ignition' ); ?>
</a>

<div class="site-container" id="site-container">

	<div id="panel-left"></div>
	<div id="panel-right"></div>

	<?php
	$menu_icon = '';
	if ( get_theme_mod( 'menu_icon', 'regular' ) == 'menu' ) {
		$menu_icon = ' navigation-menu-icon--alt';
	} ?>

	<button aria-label="Toggle Left Panel" class="panel-left-toggle hidden" data-toggle="menu-open" data-target="body">
		<span class="navigation-menu-icon <?php echo $menu_icon; ?>"></span>
	</button>

	<div id="page" class="site">


		<?php include( locate_template( 'template-parts/site-top/site-top.php' ) ); ?>

		<div id="site-content" class="site-content">

		