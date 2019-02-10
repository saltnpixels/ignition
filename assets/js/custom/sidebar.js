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


