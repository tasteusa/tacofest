<?php
/**
 * Plugin Name: Contact Form 7 Extender Client
 * Description: Contact form 7 aggregation client plugin
 * Version: 1.0
 * Author: virginiawinefest
 * Author URI: http://virginiawinefest.com/
 */
add_action( 'admin_menu', 'cf7e_setup_pages' );
add_action( 'wpcf7_before_send_mail', 'cf7_before_send_custom' );
add_action( 'admin_init', 'cf7e_settings_init' );

/*
 *
 * Setup plugin pages
 *
 */
function cf7e_setup_pages() {
	add_submenu_page( 'tools.php', 'CF7 Extender Settings Page', 'Contact Form 7 Extender Settings', 'manage_options', 'contact-form-7-extender-settings', 'cf7e_settings_page');
}

/**
 * Register settings section
 */
function cf7e_settings_init() {
	register_setting( 'pluginPage', 'cf7e_settings' );
	add_settings_section(
		'cf7e_pluginPage_section',
		__( 'API key and endpoint url ', 'contact form 7 extender' ),
		'cf7e_settings_section_callback',
		'pluginPage'
	);

	add_settings_field(
		'cf7e_text_field_0',
		__( 'Api key', 'contact form 7 extender' ),
		'cf7e_text_field_0_render',
		'pluginPage',
		'cf7e_pluginPage_section'
	);


	add_settings_field(
		'cf7e_text_field_1',
		__( 'Endpoint Url', 'contact form 7 extender' ),
		'cf7e_text_field_1_render',
		'pluginPage',
		'cf7e_pluginPage_section'
	);

}

/**
 *
 * Render settings fields
 *
 */
function cf7e_text_field_0_render(  ) {
	$options = get_option( 'cf7e_settings' );
	?>
	<input type='text' name='cf7e_settings[cf7e_text_field_0]' value='<?php echo $options['cf7e_text_field_0']; ?>'>
	<?php
}

/**
 *
 * Render settings fields
 *
 */
function cf7e_text_field_1_render(  ) {
	$options = get_option( 'cf7e_settings' );
	?>
	<input type='text' name='cf7e_settings[cf7e_text_field_1]' value='<?php echo $options['cf7e_text_field_1']; ?>'>
	<?php

}

/**
 * Contact Form 7 before send hook. Send data to api endpoint
 *
 * @param object $cf7
 *
 */
function cf7_before_send_custom($cf7) {
	$cf7eSettings = get_option('cf7e_settings');
	if ( !empty($cf7eSettings) ) {
		$postData = [
			'name' => (isset($_POST['name'])) ? $_POST['name'] : $_POST['your-name'],
			'email' => (isset($_POST['email'])) ? $_POST['email'] : $_POST['your-email'],
			'phone' => (isset($_POST['phone'])) ? $_POST['phone'] : $_POST['your-phone'],
			'subject' => (isset($_POST['subject'])) ? $_POST['subject'] : $_POST['your-subject'],
			'message' => (isset($_POST['message'])) ? $_POST['message'] : $_POST['your-message'],
			'site_url' => get_site_url(),
			'api_key' =>  $cf7eSettings['cf7e_text_field_0']
		];

		$endPointUrl = $cf7eSettings['cf7e_text_field_1'];
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
		curl_setopt($ch, CURLOPT_URL, $endPointUrl);
		$result = curl_exec($ch);
	}

}

/**
 *
 * Section description callback
 *
 */
function cf7e_settings_section_callback(  ) {
	echo __( 'Contact Form 7 Settings Page', 'contact form 7 extender' );
}

/**
 *
 * Render settings page
 *
 */
function cf7e_settings_page() {
	if (isset($_POST['submit']) && !empty($_POST['cf7e_settings']) ) {
		if ( !get_option('cf7e_settings') ) {
			add_option('cf7e_settings', $_POST['cf7e_settings']);
		} else {
			update_option('cf7e_settings', $_POST['cf7e_settings']);
		}
	}
	echo "<h1>" . __('Contact Form 7 Settings Page') . "</h1>";
	echo "<hr />";
	echo "<form action='' method='post'>";
	settings_fields( 'pluginPage' );
	do_settings_sections( 'pluginPage' );
	submit_button();
	echo "</form>";
}