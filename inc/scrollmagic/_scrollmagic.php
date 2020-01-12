<?php

//scrolling animation
function ign_add_scrollmagic(){
	wp_enqueue_script( 'gsap', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.0.5/gsap.min.js' );
	wp_enqueue_script( 'scrollMagic', 'https://cdnjs.cloudflare.com/ajax/libs/ScrollMagic/2.0.7/ScrollMagic.min.js', array( 'jquery' ) );
	wp_enqueue_script( 'scrollMagic-gsap', 'https://cdnjs.cloudflare.com/ajax/libs/ScrollMagic/2.0.7/plugins/animation.gsap.min.js', array( 'jquery' ) );
	wp_enqueue_script( 'scrollmagic-indicators', 'https://cdnjs.cloudflare.com/ajax/libs/ScrollMagic/2.0.7/plugins/debug.addIndicators.min.js' );
}
//add_action( 'wp_enqueue_scripts', 'ign_add_scrollmagic' );
