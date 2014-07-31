<?php
/**
 * Template tags for using site logos.
 *
 * @package Site_Logo
 */

/**
 * Retrieve the site logo URL or ID (URL by default). Pass in the string 'id' for ID.
 *
 * @uses get_option()
 * @uses esc_url_raw()
 * @uses set_url_scheme()
 * @return mixed The URL or ID of our site logo, false if not set
 * @since 1.0
 */
function get_site_logo( $show = 'url' ) {
	// Bail quietly if the theme hasn't declared support.
	if ( ! current_theme_supports( 'site-logo' ) ) {
		return;
	}

	$logo = get_option( 'site_logo' );

	// Return false if no logo is set
	if ( ! isset( $logo['id'] ) || 0 == $logo['id'] ) {
		return false;
	}

	// Return the ID if specified, otherwise return the URL by default
	if ( 'id' == $show ) {
		return $logo['id'];
	} else {
		return esc_url_raw( set_url_scheme( $logo['url'] ) );
	}
}

/**
 * Determine if a site logo is assigned or not.
 *
 * @uses get_option
 * @return boolean True if there is an active logo, false otherwise
 */
function has_site_logo() {
	$logo = get_option( 'site_logo' );
	return ( isset( $logo['id'] ) && 0 !== $logo['id'] ) ? true : false;
}

/**
 * Output an <img> tag of the site logo, at the size specified
 * in the theme's add_theme_support() declaration.
 *
 * @uses current_theme_supports()
 * @uses get_option()
 * @uses site_logo_theme_size()
 * @uses site_logo_is_customize_preview()
 * @uses esc_url()
 * @uses home_url()
 * @uses esc_attr()
 * @uses wp_get_attachment_image()
 * @since 1.0
 */
function the_site_logo() {
	// Bail quietly if the theme hasn't declared support.
	if ( ! current_theme_supports( 'site-logo' ) ) {
		return;
	}

	$logo = get_option( 'site_logo' );
	$size = site_logo_theme_size();

	// Bail if no logo is set. Leave a placeholder if we're in the Customizer, though (needed for the live preview).
	if ( ! isset( $logo['id'] ) || 0 == $logo['id'] ) {
		if ( site_logo_is_customize_preview() ) {
			printf( '<a href="%1$s" class="site-logo-anchor" style="display:none;"><img class="site-logo" data-size="%2$s" /></a>',
				esc_url( home_url( '/' ) ),
				esc_attr( $size )
			);
		}
		return;
	}

	// We have a logo. Logo is go.
	$html = sprintf( '<a href="%1$s" class="site-logo-anchor" rel="home">%2$s</a>',
		esc_url( home_url( '/' ) ),
		wp_get_attachment_image(
			$logo['id'],
			$size,
			false,
			array(
				'class'     => "site-logo attachment-$size",
				'data-size' => $size,
			)
		)
	);

	echo apply_filters( 'the_site_logo', $html, $logo, $size );
}
