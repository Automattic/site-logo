<?php

class Site_Logo_Image_Control extends WP_Customize_Control {

	public function __construct( $wp_customize, $control_id, $args = array() ) {

		// declare these first so they can be overridden
		$this->l10n = array(
			'upload' =>      __( 'Add logo' ),
			'set' =>         __( 'Set as logo' ),
			'choose' =>      __( 'Choose logo' ),
			'change' =>      __( 'Change logo' ),
			'remove' =>      __( 'Remove logo' ),
			'placeholder' => __( 'No logo set' ),
		);

		parent::__construct( $wp_customize, $control_id, $args );
	}

	// this will be critical for your JS constructor
	public $type = 'site_logo';

	// this allows overriding of global labels by a specific control
	public $l10n = array();

	// the type of files that should be allowed by the media modal
	public $mime_type = 'image';

	public function enqueue() {
		// enqueues all needed media resources
		wp_enqueue_media();
		// Except for its templates - those are attached to hooks that don't exist
		// in the Customizer. Just add once
		if ( ! has_action( 'customize_controls_print_footer_scripts', 'wp_print_media_templates' ) )
			add_action( 'customize_controls_print_footer_scripts', 'wp_print_media_templates' );

		// Finally, ensure our control script and style are enqueued
		wp_enqueue_style( 'site-logo-control', plugins_url( '../css/site-logo-control.css', __FILE__ ) );
		wp_enqueue_script( 'site-logo-control', plugins_url( '../js/site-logo-control.js', __FILE__ ), array( 'media-views', 'customize-controls', 'underscore' ), '', true );
	}

	public function has_site_logo() {
		$logo = get_option( 'site_logo' );

		if ( empty( $logo['url'] ) ) {
			return false;
		} else {
			return true;
		}
	}

	public function render_content() {
		// We do this to allow the upload control to specify certain labels
		$l10n = json_encode( $this->l10n );

		printf(
			'<span class="customize-control-title" data-l10n="%s" data-mime="%s">%s</span>',
			esc_attr( $l10n ),
			esc_attr( $this->mime_type ),
			esc_html( $this->label )
		);
		?>
		<div class="current"></div>
		<div class="actions"></div>
	<?php }
}