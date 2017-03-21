<?php

/**
 * Installs SkyStats.
 * 
 * Nothing is actually installed unless it has to be.
 * 
 * In a multisite environment:
 * 
 * 1. If the network administrator wishes to install SkyStats globally, installation is run for the site and each blog contained within the site.
 * 2. Otherwise, SkyStats is simply installed for the current blog and its site.
 * 
 * Otherwise, simply installs SkyStats for the current blog.
 * 
 * @since 1.0.0
 *
 * @package SkyStats
 */

// Prevent direct access
defined( 'ABSPATH' ) or exit();

add_action( 'skystats_check_option_skystats_version', 'skystats_set_mailchimp_visible_on_upgrade', 10, 2 );
/**
 * When upgrading to 0.3.2, when we added support for MailChimp, set MailChimp's visibility status to 1,
 * so that the user doesn't need to set it as visible in their settings to see it first. After which the user
 * can decide whether to show or hide the integration.
 *
 * @param string $current_version The current version of SkyStats installed.
 *
 * @param array $option_functions Array of option names under the following keys: get, update, add. The option names
 *                                are automatically generated for you so they will work whether the current site is a site
 *                                or a blog.
 */
function skystats_set_mailchimp_visible_on_upgrade( $current_version, $option_functions ) {
	/*
	 * This action is only fired if this option already exists (the new default value for MailChimp's visibility is 1)
	 * If the version before the install check was 0.3.2, we have just updated to 0.3.2, when MailChimp support was added.
	 * We need to make the MailChimp mashboard card and detail page initially visible, after which the user can choose to disable it
	 * in their settings.
	 */
	if ( $current_version < '0.3.2' ) {
		$visibility_status_option_name = 'skystats_mashboard_cards_visibility_status';
		$get_option_function = $option_functions['get'];
		$visibility_status = $get_option_function( $visibility_status_option_name );
		if ( isset( $visibility_status['mailchimp_8'] ) && $visibility_status['mailchimp_8'] != '1' ) {
			$update_option_function = $option_functions[ 'update' ];
			$visibility_status['mailchimp_8'] = '1';
			$update_option_function( $visibility_status_option_name, $visibility_status );
		}
	}
}

skystats_install();

/**
 * Installs Skystats.
 * 
 * @since 1.0.0
 */
function skystats_install() {

	global $blog_id;

	// Multisite is disabled.
	if ( ! is_multisite() ) {

		skystats_install_options( 'blog' );

		skystats_install_tables( 'blog' );

	// Multisite is enabled.
	} else {

		// Only install for the current blog and site.
		if ( true !== get_site_option( 'skystats_perform_network_install' ) ) {

			skystats_install_options( 'site' );
			skystats_install_tables( 'site' );

			skystats_install_options( 'blog' );
			skystats_install_tables( 'blog' );

		// Otherwise, network administrator wants to install for whole network.
		} else {

			update_site_option( 'skystats_perform_network_install', false );

			/**
			 * Multisite specific functions.
			 */
			require_once SKYSTATS_FUNCTIONS_PATH . 'multisite.php';

			foreach ( skystats_get_site_ids() as $site_id ) {

				skystats_install_options( 'site' );

				skystats_install_tables( 'site' );

				foreach ( skystats_get_blog_ids_of_site_id( $site_id ) as $_blog_id ) {

					switch_to_blog( $_blog_id );

					skystats_install_options( 'blog' );

					skystats_install_tables( 'blog' );

					restore_current_blog();
				}
			}
		}
	}
}

/**
 * Installs options for a site or blog.
 * 
 * Options are only added if they do not exist.
 * 
 * @since 1.0.0
 * 
 * @param string $site_type 'site' or 'blog'.
 */
function skystats_install_options( $site_type ) {

	require_once SKYSTATS_FUNCTIONS_PATH . 'options.php';

	$options = skystats_get_options();

	$get_option_function = ( 'site' === $site_type ) ? 'get_site_option' : 'get_option';

	$add_option_function = ( 'site' === $site_type ) ? 'add_site_option' : 'add_option';

	$update_option_function = ( 'site' === $site_type ) ? 'update_site_option' : 'update_option';

	$option_functions = array(
		'get' => $get_option_function,
		'add' => $add_option_function,
		'update' => $update_option_function,
	);

	foreach ( $options[ $site_type ] as $name => $values ) {

		if ( false !== $current_option_val = $get_option_function( $name ) ) {

			do_action( "skystats_check_option_{$name}", $current_option_val, $option_functions );

			if ( 'skystats_version' === $name ) {
				// Update version
				if ( $current_option_val !== $values ) {
					$update_option_function($name, $values);
					continue;
				}
			}
			// Check mashboard positions contains the vote and upgrade cards
			if ( 'skystats_mashboard_card_positions' === $name ) {
				// Skip, using new option values
				if ( isset( $current_option_val['postbox-container-1'] ) ) {
					continue;
				}
				$new_option_val = array();
				foreach ( $current_option_val as $column_name => $cards ) {
					if ( 'column_1' === $column_name ) {
						$new_option_val['postbox-container-1'] = $cards;
					}
					if ( 'column_2' === $column_name ) {
						$new_option_val['postbox-container-2'] = $cards;
					}
					if ( 'column_3' === $column_name ) {
						$new_option_val['postbox-container-3'] = $cards;
					}
					if ( 'column_4' === $column_name ) {
						$new_option_val['postbox-container-4'] = $cards;
					}
				}
				$update_option_function( $name, $new_option_val );
			}
			continue;
		}

		$add_option_function( $name, $values );
	}
}

/**
 * Installs tables for a site or blog.
 * 
 * Tables are only added if they do not exist.
 * 
 * @since 1.0.0
 * 
 * @param string     $site_type    'site' or 'blog'.
 */
function skystats_install_tables( $site_type ) {

	require_once SKYSTATS_API_FUNCTIONS_PATH . 'tables.php';

	// Each table has its own function responsible for setting it up.
	$table_handlers = skystats_get_table_handlers( $site_type );

	global $wpdb;

	foreach ( $table_handlers as $handler ) {
		call_user_func( $handler, $wpdb, 'install' );
	}
}