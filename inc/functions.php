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
	require( dirname( __FILE__ ) . '/class-site-logo-control.php' );

	// Add a setting to hide header text if the theme isn't supporting the feature itself
	if ( ! current_theme_supports( 'custom-header' ) ) {
		$wp_customize->add_setting( 'site_logo_header_text', array(
			'default'           => 1,
			'sanitize_callback' => 'site_logo_sanitize_checkbox',
			'transport'         => 'postMessage',
		) );

		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'site_logo_header_text', array(
		    'label'    => __( 'Display Header Text', 'site-logo' ),
		    'section'  => 'title_tagline',
		    'settings' => 'site_logo_header_text',
		    'type'     => 'checkbox',
		) ) );
	}

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

/**
 * Enqueue scripts for the Customizer live preview.
 *
 * @uses wp_enqueue_script()
 * @uses plugins_url()
 * @since 1.0
 */
function site_logo_preview_enqueue() {
	wp_enqueue_script( 'site-logo-preview', plugins_url( '../js/site-logo.js', __FILE__ ), array( 'media-views' ), '', true );

	// Don't bother passing in header text classes if the theme supports custom headers.
	if ( ! current_theme_supports( 'custom-header' ) ) {
		$classes = site_logo_get_header_text_classes();
		wp_enqueue_script( 'site-logo-header-text', plugins_url( '../js/site-logo-header-text.js', __FILE__ ), array( 'media-views' ), '', true );
		wp_localize_script( 'site-logo-header-text', 'site_logo_header_classes', $classes );
	}
}
add_action( 'customize_preview_init', 'site_logo_preview_enqueue' );

/**
 * Determine image size to use for the logo.
 *
 * @uses get_theme_support()
 * @return string Size specified in add_theme_support declaration, or 'thumbnail' default
 * @since 1.0
 */
function site_logo_theme_size() {
	$valid_sizes = array( 'thumbnail', 'medium', 'large', 'full' );

	global $_wp_additional_image_sizes;
	if ( isset( $_wp_additional_image_sizes ) ) {
		$valid_sizes = array_merge( $valid_sizes, array_keys( $_wp_additional_image_sizes ) );
	}

	$args = get_theme_support( 'site-logo' );

	$size = ( isset( $args[0]['size'] ) && in_array( $args[0]['size'], $valid_sizes ) ) ? $args[0]['size'] : 'thumbnail';

	return $size;
}

/**
 * Make custom image sizes available to the media manager.
 *
 * @param array $sizes
 * @return array All default and registered custom image sizes.
 */
function site_logo_media_manager_image_sizes( $sizes ) {
	global $_wp_additional_image_sizes;

	if ( isset( $_wp_additional_image_sizes ) ) {
		foreach ( $_wp_additional_image_sizes as $size => $value ) {
			$sizes[ $size ] = '';
		}
	}

	return $sizes;
}
add_filter( 'image_size_names_choose', 'site_logo_media_manager_image_sizes' );

/**
 * Whether the site is being previewed in the Customizer.
 * Duplicate of core function until WP.com has merged 4.0.
 *
 * @since 4.0.0
 *
 * @global WP_Customize_Manager $wp_customize Customizer instance.
 *
 * @return bool True if the site is being previewed in the Customizer, false otherwise.
 */
function site_logo_is_customize_preview() {
	global $wp_customize;

	return is_a( $wp_customize, 'WP_Customize_Manager' ) && $wp_customize->is_preview();
}

/**
 * Sanitize our header text Customizer setting.
 *
 * @param $input
 * @return mixed 1 if checked, empty string if not checked.
 */
function site_logo_sanitize_checkbox( $input ) {
	return ( 1 == $input ) ? 1 : '';
}

/**
 * Hide header text on front-end if necessary.
 *
 * @uses current_theme_supports()
 * @uses get_theme_mod()
 * @uses site_logo_get_header_text_classes()
 * @uses esc_html()
 */
function site_logo_header_text_styles() {
	// Bail if our theme supports custom headers.
	if ( current_theme_supports( 'custom-header' ) ) {
		return;
	}

	$header_text = get_theme_mod( 'site_logo_header_text' );

	// Is Display Header Text unchecked? If so, we need to hide our header text.
	if ( false !== $header_text && ! $header_text ) {
		$classes = site_logo_get_header_text_classes();
		?>
		<!-- Site Logo: hide header text -->
		<style type="text/css">
		<?php echo esc_html( $classes ); ?> {
			position: absolute;
			clip: rect(1px, 1px, 1px, 1px);
		}
		</style>
		<?php
	}
}
add_action( 'wp_head', 'site_logo_header_text_styles' );

/**
 * Get header text classes. If not defined in add_theme_support(), defaults from Underscores will be used.
 *
 * @uses get_theme_support
 * @return string String of classes to hide
 */
function site_logo_get_header_text_classes() {
	$args = get_theme_support( 'site-logo' );
	if ( isset( $args[0][ 'header-text' ] ) ) {
		// Use any classes defined in add_theme_support().
		$classes = $args[0][ 'header-text' ];
	} else {
		// Otherwise, use these defaults, which will work with any Underscores-based theme.
		$classes = array(
			'site-title',
			'site-description',
		);
	}

	// If we've got an array, reduce them to a string for output
	if ( is_array( $classes ) ) {
		$classes = (string) '.' . implode( ', .', $classes );
	} else {
		$classes = (string) '.' . $classes;
	}

	return $classes;
}
