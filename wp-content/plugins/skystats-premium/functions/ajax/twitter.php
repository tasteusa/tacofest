<?php

/**
 * SkyStats Twitter AJAX-related functions.
 * 
 * @since 1.0.0
 *
 * @package SkyStats\Ajax\Twitter
 */

defined( 'ABSPATH' ) or exit();

/**
 * Twitter API related functions.
 * 
 * @since 0.2.5
 */
require_once SKYSTATS_API_FUNCTIONS_PATH . 'twitter.php';

add_action( 'wp_ajax_skystats_ajax_twitter_api_query', 'skystats_ajax_twitter_api_query' );

/**
 * All Twitter AJAX requests are sent through this function.
 * 
 * @since 0.2.5
 */
function skystats_ajax_twitter_api_query() {

	if ( empty( $_GET['query'] ) ) {
		exit();
	}

	$query = wp_strip_all_tags( $_GET['query'] );

	$require_settings_access_queries = array( 'deauthorize', 'get_status' );

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

	if ( 'deauthorize' === $query ) {
		skystats_api_twitter_deauthorize();
		exit();
	}

	if ( 'get_status' === $query ) {

		$status = skystats_api_twitter_get_status();
		echo json_encode( $status );
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

	if ( 'get_mashboard_view_data' === $query ) {
		$mashboard_view_data = skystats_api_twitter_get_mashboard_view_data( $start_date, $end_date );
		echo json_encode( $mashboard_view_data );
		exit();
	}

	if ( 'get_detail_view_data' === $query ) {
		$detail_view_data = skystats_api_twitter_get_detail_view_data( $start_date, $end_date );
		echo json_encode( $detail_view_data );
		exit();
	}

	exit();
}