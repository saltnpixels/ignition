//create menu and sidebar button sizing
//the buttons need to sit outside site-top, otherwise they get covered by panels when they are open because site top is under opened panels.
//this makes sure the buttons are centered, but still  on top of site-top and the menu the pops open



jQuery( function( $ ) {

	let $siteContent = $( '.site-content' );

	if ( $( '#secondary' ).length ) {

		//clean it of whitespaces or :empty wont hide it in css
		let secondary = document.querySelector( '#secondary' );
		secondary.innerHTML = secondary.innerHTML.trim();

		if ( $( '.sidebar-template' ).hasClass( 'header-above' ) ) {

			//move header out of article so its above sidebar and article and add class active which shows sidebar once header is moved
			$siteContent.prepend( $( 'article .entry-header, .archive .entry-header, .page-header, .blog .entry-header' ) );
			$( '.sidebar-template' ).addClass( 'active' );
		}

	}

});


