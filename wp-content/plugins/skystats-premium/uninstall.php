<?php

/**
 * Uninstalls SkyStats.
 *
 * Tables and options are only removed if they exist.
 * 
 * @since 1.0.0
 *
 * @package SkyStats
 */

// WordPress will handle including this file when the plugin is being uninstalled.
defined( 'WP_UNINSTALL_PLUGIN' ) or exit();

/**
 * Load definitions, configuration, etc.
 */
require_once dirname( __FILE__ ) . '/bootstrap.php';

skystats_uninstall();

/**
 * Uninstalls SkyStats.
 * 
 * @since 1.0.0
 * 
 * @global integer $blog_id Current blog ID, used when not in multisite.
 */
function skystats_uninstall() {

	global $blog_id;

	if ( is_multisite() ) {

		require_once SKYSTATS_FUNCTIONS_PATH . 'multisite.php';

		// Uninstall network
		if ( is_network_admin() ) {

			foreach ( skystats_get_site_ids() as $site_id ) {

				skystats_uninstall_options( 'site', $site_id );

				skystats_uninstall_tables( 'site',  $site_id );

				foreach ( skystats_get_blog_ids_of_site_id( $site_id ) as $_blog_id ) {

					switch_to_blog( $_blog_id );

					skystats_uninstall_options( 'blog', $_blog_id );

					skystats_uninstall_tables( 'blog', $_blog_id );

					restore_current_blog();
				}
			}

			return;
		}

		$site = get_current_site();

		skystats_uninstall_options( 'site', $site->id );

		skystats_uninstall_tables( 'site',  $site->id );

	}

	skystats_uninstall_options( 'blog', $blog_id );

	skystats_uninstall_tables( 'blog', $blog_id );
}

/**
 * Uninstalls options for a particular site type (site or blog).
 * 
 * @since 1.0.0
 * 
 * @param string $site_type site or blog.
 * 
 * @param string|int $site_type_id site or blog id.
 */
function skystats_uninstall_options( $site_type, $site_type_id ) {

	$get_option_function = ( 'site' === $site_type ) ? 'get_site_option' : 'get_option';

	$delete_option_function = ( 'site' === $site_type ) ? 'delete_site_option' : 'delete_option';

	/**
	 * SkyStats option related functions.
	 */
	require_once SKYSTATS_FUNCTIONS_PATH . 'options.php';

	$options = skystats_get_options();

	foreach ( $options[ $site_type ] as $name => $unused ) {

		if ( false === $get_option_function( $name ) ) {

		} else {

			$delete_option_function( $name );
		}
	}
}

/**
 * Uninstalls tables for a site or blog.
 *
 * Tables are only added if they do not exist.
 *
 * @since 1.0.0
 *
 * @param string     $site_type    'site' or 'blog'.
 *
 * @param string|int $site_type_id The Id of the site or blog.
 */
function skystats_uninstall_tables( $site_type, $site_type_id ) {

	static $tables;

	require_once SKYSTATS_API_FUNCTIONS_PATH . 'tables.php';

	// Each table has its own function responsible for setting it up.
	$table_handlers = skystats_get_table_handlers( $site_type );

	global $wpdb;

	foreach ( $table_handlers as $handler ) {
		call_user_func( $handler, $wpdb, 'uninstall' );
	}
}