(function($){
	var api = wp.customize;
	var $logo = null;
	var $size = null;
	api( 'site_logo', function( value ){
		value.bind( function( newVal, oldVal ){
			// Do we have any cached jQuery objects yet?
			if ( ! $logo )
				$logo = $( '.site-logo' );
			if ( ! $size )
				$size = $logo.attr( 'data-size' );

			// Let's update our preview logo.
			if ( newVal && newVal.url ) {
				// If the source was smaller than the size required by the theme, give the biggest we've got.
				if ( ! newVal.sizes[ $size ] )
					$size = 'full';
				$logo.attr( 'height', newVal.sizes[ $size ].height );
				$logo.attr( 'width', newVal.sizes[ $size ].width );
				$logo.attr( 'src', newVal.sizes[ $size ].url ).show();
			} else {
				$logo.hide();
			};
		});
	})
})(jQuery);