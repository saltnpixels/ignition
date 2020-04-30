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
    <div class="container">
		<?php
		get_template_part( 'template-parts/footer/footer', 'widgets' );

		get_template_part( 'template-parts/footer/site', 'info' );
		?>
    </div><!-- .wrap -->
</footer><!-- #colophon -->

</div><!-- #page -->

<button aria-label="Toggle Right Panel" data-toggle="open" data-target="#panel-right" aria-expanded="false" class="panel-right-toggle"><span class="sidebar-icon"></span></button>

</div><!-- .site-container -->

<?php wp_footer(); ?>


<script>
	var $buoop = {required:{e:-4,f:-3,o:-3,s:-1,c:-3},insecure:true,api:2020.04 };
	function $buo_f(){
		var e = document.createElement("script");
		e.src = "//browser-update.org/update.min.js";
		document.body.appendChild(e);
	};
	try {document.addEventListener("DOMContentLoaded", $buo_f,false)}
	catch(e){window.attachEvent("onload", $buo_f)}
</script>

</body>
</html>
