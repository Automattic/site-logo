<?php
/*
 * Plugin Name: Site Logo
 * Plugin URI: http://wordpress.com
 * Description: Add a logo to your WordPress site. Set it once, and all themes that support it will display it automatically.
 * Author: Automattic
 * Version: 1.0
 * Author URI: http://wordpress.com
 * License: GPL2 or later
 * Text Domain: site-logo
 * Domain Path: /languages/
 */

/**
 * Activate the Site Logo plugin.
 *
 * @uses current_theme_supports()
 * @since 1.0
 */
function site_logo_init() {
	// Only load our code if our theme declares support for site logos.
	if ( current_theme_supports( 'site-logo' ) ) {
		// Load our class for namespacing.
		require( dirname( __FILE__ ) . '/inc/class-site-logo.php' );

		// Load template tags.
		require( dirname( __FILE__ ) . '/inc/functions.php' );
	}
}
add_action( 'init', 'site_logo_init' );
