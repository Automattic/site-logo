<?php
/**
 * Our site logo functions and hooks.
 *
 * @package Site_Logo
 * @since 1.0
 */

/**
 * Add our logo uploader to the Customizer.
 *
 * @param object $wp_customize Customizer object.
 * @uses $wp_customize->add_setting
 * @uses $wp_customize->add_control
 * @since 1.0
 */
function site_logo_customize_register( $wp_customize ) {
	// Include our custom control.
	require( __DIR__ . '/class-site-logo-control.php' );

	// Add the setting for our logo value.
	$wp_customize->add_setting( 'site_logo', array(
		'default' => array(
			'url' => false,
			'id' => 0,
		),
		'type'       => 'option',
		'capability' => 'manage_options',
		'transport'  => 'postMessage',
	) );

	// Add our image uploader.
	$wp_customize->add_control( new Site_Logo_Image_Control( $wp_customize, 'site_logo', array(
	    'label'    => __( 'Site Logo', 'site-logo' ),
	    'section'  => 'title_tagline',
	    'settings' => 'site_logo',
	) ) );
}
add_action( 'customize_register', 'site_logo_customize_register' );

function site_logo_preview_enqueue() {
	wp_enqueue_script( 'site-logo-preview', plugins_url( '../js/site-logo.js', __FILE__ ), array( 'media-views' ), '', true );
}
add_action( 'customize_preview_init', 'site_logo_preview_enqueue' );

function site_logo_theme_size() {
	$args = get_theme_support( 'site-logo' );
	$registered = get_intermediate_image_sizes();
	$size = ( isset( $args[0]['size'] ) && in_array( $args[0]['size'], $registered ) ) ? $args[0]['size'] : 'thumbnail';

	return $size;
}
