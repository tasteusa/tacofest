<?php

/**
 * SkyStats Settings AJAX-related functions.
 * 
 * @since 1.0.0
 *
 * @package SkyStats\Ajax\Settings
 */

defined( 'ABSPATH' ) or exit();

add_action( 'wp_ajax_skystats_ajax_settings_save_settings', 'skystats_ajax_settings_save_settings' );

function skystats_ajax_settings_save_settings() {

	// Brand Name
	$brand_name = ( ! empty( $_POST['brand_name'] ) ) ?
		wp_strip_all_tags( stripslashes( $_POST['brand_name'] ) ) :
		SKYSTATS_NAME;
	update_option( 'skystats_brand_name', $brand_name );

	// Brand Menu Name
	$brand_menu_name = ( ! empty( $_POST['brand_menu_name'] ) ) ?
		wp_strip_all_tags( stripslashes( $_POST['brand_menu_name'] ) ) :
		SKYSTATS_NAME;
	update_option( 'skystats_brand_menu_name', $brand_menu_name );

	// Brand Logo URL
	$brand_logo_image_url = ( ! empty( $_POST['brand_logo_image_url'] ) ) ?
		esc_url( $_POST['brand_logo_image_url'] ) :
		SKYSTATS_DEFAULT_LOGO_IMAGE_URL;
	update_option( 'skystats_brand_logo_image_url', $brand_logo_image_url );

	// Brand Background URL
	$brand_background_image_url = ( ! empty( $_POST['brand_background_image_url'] ) ) ?
		esc_url( $_POST['brand_background_image_url'] ) :
		'';
	update_option( 'skystats_brand_background_image_url', $brand_background_image_url );

	// Brand Background Color
	require_once SKYSTATS_FUNCTIONS_PATH . 'sanitization.php';
	$brand_background_color = ( isset( $_POST['brand_background_color'] ) ) ?
		skystats_sanitize_hex_color( $_POST['brand_background_color'] ) :
		'#333';
	update_option( 'skystats_brand_background_color', $brand_background_color );

	// Date Range Label Color
	$date_range_label_color = ( isset( $_POST['skystats_date_range_label_color'] ) ) ?
		skystats_sanitize_hex_color( $_POST['skystats_date_range_label_color'] ) :
		'#fff';
	update_option( 'skystats_date_range_label_color', $date_range_label_color );

	// Mashboard Menu Name
	$mashboard_menu_name = ( isset( $_POST['skystats_mashboard_menu_name'] ) ) ?
		wp_strip_all_tags( stripslashes( $_POST['skystats_mashboard_menu_name'] ) ) :
		__( 'Mashboard', SKYSTATS_TEXT_DOMAIN );
	update_option( 'skystats_mashboard_menu_name', $mashboard_menu_name );

	// Role Access
	$registered_roles = (array) get_editable_roles();
	$role_access = array();
	if ( ! empty( $_POST['role_access'] ) ) {

		$post_role_access = $_POST['role_access'];

		if ( isset( $registered_roles[ $post_role_access ] ) ) {

			foreach ( $registered_roles as $identifier => $role_data ) {
				$role_access[] = $identifier;
				if ( $post_role_access === $identifier ) {
					break;
				}
			}
			update_option( 'skystats_selected_role_access', $post_role_access );
		}
	}
	update_option( 'skystats_role_access', $role_access );

	// Default Dashboard
	$default_dashboard = ( ! empty( $_POST['default_dashboard'] ) && in_array( $_POST['default_dashboard'], array(
			'skystats_mashboard',
			'wordpress_dashboard',
		)
	) ) ?
		$_POST['default_dashboard'] :
		'skystats_mashboard';
	update_option( 'skystats_default_dashboard', $default_dashboard );

	// Caching
	$cache_mode = ( ! empty( $_POST['cache_mode'] ) && in_array( $_POST['cache_mode'], array(
			'enabled',
			'disabled',
		)
	) ) ?
		$_POST['cache_mode'] :
		'enabled';
	update_option( 'skystats_cache_mode', $cache_mode );

	// Mashboard cards visibility status
	if ( is_array( $_POST['mashboard_cards_visibility_status'] ) && ! empty( $_POST['mashboard_cards_visibility_status'] ) ) {
		require_once SKYSTATS_FUNCTIONS_PATH . 'mashboard_cards.php';
		$mashboard_cards_visibility_status = $_POST['mashboard_cards_visibility_status'];
		$mashboard_card_identifiers = skystats_get_mashboard_card_identifiers();
		$new_mashboard_cards_visibility_status = array();
		foreach ( $mashboard_card_identifiers as $identifier ) {
			$status = isset( $mashboard_cards_visibility_status[ $identifier ] ) && $mashboard_cards_visibility_status [ $identifier] == '1' ? '1' : '0';
			$new_mashboard_cards_visibility_status[ $identifier ] = $status;
		}
		update_option( 'skystats_mashboard_cards_visibility_status', $new_mashboard_cards_visibility_status );
	}

	// Role identifiers allowed to view and access the reports (mashboard & detail pages)
	if ( is_array( $_POST['skystats_reports_users_allowed_access'] ) && ! empty( $_POST['skystats_reports_users_allowed_access'] ) ) {
		$skystats_reports_users_allowed_access = $_POST['skystats_reports_users_allowed_access'];
		$registered_roles = array_keys( (array) get_editable_roles() );
		$role_identifiers = array();
		foreach ( $skystats_reports_users_allowed_access as $role_identifier ) {
			if ( in_array( $role_identifier, $registered_roles ) ) {
				$role_identifiers[] = $role_identifier;
			}
		}
		if ( ! empty( $role_identifiers ) ) {
			update_option( 'skystats_reports_users_allowed_access', $role_identifiers );
		}
	}

	// User ids allowed access to view and edit the Settings
	if ( is_array( $_POST['skystats_settings_users_allowed_access'] ) && ! empty( $_POST['skystats_settings_users_allowed_access'] ) ) {
		$settings_user_ids_allowed_access = $_POST['skystats_settings_users_allowed_access'];
		$user_ids = array();
		foreach ( $settings_user_ids_allowed_access as &$user_id ) {
			$user_id = (string) $user_id;
			if ( ctype_digit( $user_id ) && ! in_array( $user_id, $user_ids ) ) {
				$user_ids[] = $user_id;
			}
		}
		if ( ! empty( $user_ids ) ) {
			update_option( 'skystats_settings_users_allowed_access', $user_ids );
		}
	}

	exit();
}