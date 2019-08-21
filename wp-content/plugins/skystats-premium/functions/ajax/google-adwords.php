<?php

/**
 * SkyStats Google Adwords AJAX-related functions.
 *
 * @since 0.2.8
 *
 * @package SkyStats\Ajax\Google_Adwords
 */

defined( 'ABSPATH' ) or exit();

/**
 * Google Adwords API related functions.
 *
 * @since 0.2.8
 */
require_once SKYSTATS_API_FUNCTIONS_PATH . 'google-adwords.php';

add_action( 'wp_ajax_skystats_ajax_google_adwords_api_query', 'skystats_ajax_google_adwords_api_query' );

/**
 * All Google Adwords AJAX requests are sent through this function.
 *
 * @since 0.2.8
 */
function skystats_ajax_google_adwords_api_query() {

	if ( empty( $_GET['query'] ) ) {
		exit();
	}

	$query = wp_strip_all_tags( $_GET['query'] );

	$require_settings_access_queries = array( 'deauthorize', 'get_accounts', 'save_customer_id', 'save_campaign_id' );

	if ( in_array( $query, $require_settings_access_queries ) ) {
		/**
		 * Page access related functions.
		 * @since 0.3.8
		 */
		require_once SKYSTATS_FUNCTIONS_PATH . 'access.php';
		if ( ! skystats_can_current_user_access_settings() ) {
			echo json_encode( array( 'data' => null, 'responseType' => 'error', 'responseContext' => 'user_settings_access_denied' ) );
			exit();
		}
	}

	if ( 'authorize' === $query ) {
		skystats_api_google_adwords_authorize();
		exit();
	}

	if ( 'deauthorize' === $query ) {
		skystats_api_google_adwords_deauthorize( 'site' );
		exit();
	}

	if ( 'deauthorize_license' === $query ) {
		skystats_api_google_adwords_deauthorize( 'license' );
		exit();
	}

	if ( 'get_accounts' === $query ) {
		$accounts = skystats_api_google_adwords_get_accounts();
		echo json_encode( $accounts );
		exit();
	}

	if ( 'save_customer_id' === $query ) {
		skystats_api_google_adwords_save_customer_id();
		exit();
	}

	if ( 'save_campaign_id' === $query ) {
		skystats_api_google_adwords_save_campaign_id();
		exit();
	}

	if ( 'get_campaigns' === $query ) {
		$customerId = isset( $_GET['customer_id']) ? $_GET['customer_id'] : get_option( 'skystats_google_adwords_selected_customer_id');
		$campaignData = skystats_api_google_adwords_get_campaign_data($customerId);
		echo json_encode( $campaignData );
		exit();
	}

	if ( ! empty( $_GET['end_date'] ) && ( $end_date = strtotime( $_GET['end_date'] ) ) ) {
		$end_date = new DateTime( date( 'Y-m-d', $end_date ) );
	} else {
		$end_date = new DateTime( 'yesterday' );
	}

	if ( ! empty( $_GET['start_date'] ) && ( $start_date = strtotime( $_GET['start_date'] ) ) ) {
		$start_date = new DateTime( date( 'Y-m-d', $start_date ) );
	} else {
		$start_date = clone $end_date;
		$start_date->modify('-30 days');
	}

	$start_date = $start_date->format( 'Y-m-d' );
	$end_date = $end_date->format( 'Y-m-d' );

	$customer_id = ( isset( $_GET['customer_id'] ) ) ?
		$_GET['customer_id'] :
		get_option( 'skystats_google_adwords_selected_customer_id' );

	$campaign_id = ( isset( $_GET['campaign_id'] ) ) ?
		$_GET['campaign_id'] :
		get_option( 'skystats_google_adwords_selected_campaign_id' );

	if ( 'get_mashboard_view_data' === $query ) {
		$mashboard_view_data = skystats_api_google_adwords_get_view_data( 'mashboard', $start_date, $end_date, $customer_id, $campaign_id );
		echo json_encode( $mashboard_view_data );
		exit();
	}

	if ( 'get_detail_view_data' === $query ) {
		$detail_view_data = skystats_api_google_adwords_get_view_data( 'detail', $start_date, $end_date, $customer_id, $campaign_id );
		echo json_encode( $detail_view_data );
		exit();
	}

	exit();
}