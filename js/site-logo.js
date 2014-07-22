(function($){
	var api = wp.customize,
		$logo = null,
		$size = null;

	api( 'site_logo', function( value ){
		value.bind( function( newVal, oldVal ){
			$logo = $( '.site-logo' );
			$size = $logo.attr( 'data-size' );

			// Let's update our preview logo.
			if ( newVal && newVal.url ) {
				// If the source was smaller than the size required by the theme, give the biggest we've got.
				if ( ! newVal.sizes[ $size ] )
					$size = 'full';

				$logo.attr({
					height: newVal.sizes[ $size ].height,
					width: newVal.sizes[ $size ].width,
					src: newVal.sizes[ $size ].url
				}).show();
			} else {
				$logo.hide();
			}
		});
	});
})(jQuery);