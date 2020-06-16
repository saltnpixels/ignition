<?php
/**
 * Displays footer site info
 *
 * You are encouraged to add to your footer here.
 * You can make use of the acf theme settings page to add footer fields and output them here
 *
 * @package Ignition
 * @since 1.0
 * @version 1.0
 */

?>


<div class="container">




    <div class="site-info gutters text-center">
        <a target="_blank" href="<?php echo esc_url( 'https://ignition.press/' ); ?>"><?php printf( __(
				'© %s Created by %s with Ignition. Proudly powered by %s', 'ignition' ), date( 'Y' ), 'Eric', 'WordPress' ); ?></a>
    </div><!-- .site-info -->

</div>