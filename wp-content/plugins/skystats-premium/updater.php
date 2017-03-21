<?php

// Prevent direct access
defined( 'ABSPATH' ) or exit();

/**
 * Handles automatic plugin updates.
 */
require_once SKYSTATS_CLASSES_PATH . 'SkyStats_Automatic_Updater.php';

add_action( 'admin_init', 'skystats_automatic_updater', 0 );

/**
 * Initialises the SkyStats automatic updater.
 *
 * @since 0.2.8
 */
function skystats_automatic_updater() {
	new SkyStats_Automatic_Updater( SKYSTATS_STORE_URL, dirname(__FILE__) . '/skystats.php', array(
		'version'   => SKYSTATS_VERSION,
		'license'   => get_option( 'skystats_license_key' ),
		'item_name' => SKYSTATS_NAME,
		'author'    => 'Thrive Ideas',
		'url'       => home_url(),
	));
}