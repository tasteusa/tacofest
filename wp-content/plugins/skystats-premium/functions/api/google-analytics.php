<?php

/**
 * SkyStats Google Analytics API-related functions.
 * 
 * @since 1.0.0
 *
 * @package SkyStats\API\Google_Analytics
 */

defined( 'ABSPATH' ) or exit();

require_once dirname( __FILE__ ) . '/cache.php';

/**
 * Delete local GA cache.
 * 
 * @since 1.0.0
 */
function skystats_api_google_analytics_delete_local_cache() {
	skystats_api_cache_delete_name_like( 'skystats_cache_google_analytics' );
}

/**
 * Delete cached GA profiles.
 *
 * @since 0.0.1
 */
function skystats_api_google_analytics_delete_profiles() {
	skystats_api_cache_delete( 'skystats_cache_google_analytics_configuration_data' );
}

/**
 * Delete cached GA profile.
 * 
 * @since 1.0.0
 */
function skystats_api_google_analytics_delete_profile() {
	
	delete_option( 'skystats_selected_google_analytics_profile_id' );
}

/**
 * Return URL which allows a user to authenticate/authorize with Google Analytics.
 *
 * @since 0.1.4
 *
 * @param string $redirect_url URL to redirect to on success/failure.
 *
 * @return string
 */
function skystats_api_google_analytics_get_authorization_url( $redirect_url ) {

	$url = add_query_arg( array(
		'licenseKey'    => get_option( 'skystats_license_key' ),
		'licenseDomain' => home_url(),
		'redirectURI'   => $redirect_url,
		'googleAccountEmail' => get_option( 'skystats_selected_google_analytics_google_account_email' ),
	), SKYSTATS_GOOGLE_ANALYTICS_API_AUTHORIZE_URL );

	return $url;
}

/**
 * Return URL which allows a user to deauthenticate/deauthorize with Google Analytics.
 *
 * @since 0.1.4
 *
 * @param string $redirect_url      URL to redirect to on success/failure.
 *
 * @param string $deauthorize_type (Optional) the deauthorize type.
 *
 * @return string
 */
function skystats_api_google_analytics_get_deauthorization_url( $redirect_url, $deauthorize_type = '' ) {

	$url = add_query_arg( array(
		'licenseKey'    => get_option( 'skystats_license_key' ),
		'licenseDomain' => home_url(),
		'redirectURI'   => $redirect_url,
		'googleAccountEmail' => get_option( 'skystats_selected_google_analytics_google_account_email' ),
		'deauthorizeType' => $deauthorize_type,
	), SKYSTATS_GOOGLE_ANALYTICS_API_DEAUTHORIZE_URL );

	return $url;
}

/**
 * Return absolute URL used to fetch Google Analytics Profiles.
 * 
 * @since 1.0.0
 * 
 * @param string|null $license_key (Optional) License key. Defaults to license key persisted in the database.
 * 
 * @return string
 */
function skystats_get_google_analytics_profiles_url( $license_key = null ) {
	
	$license_key = ( null !== $license_key ) ? $license_key : get_option( 'skystats_license_key' );

	$url = add_query_arg( array(
		'license_key'    => $license_key,
		'license_domain' => home_url(),
		'googleAccountEmail' => get_option( 'skystats_selected_google_analytics_google_account_email' ),
	),SKYSTATS_GOOGLE_ANALYTICS_API_GET_CONFIGURATION_DATA_URL );

	return $url;
}

/**
 * Return Google Analytics top data for a specific type.
 * 
 * @since 1.0.0
 * 
 * @param string $data_type 'keywords', 'search_engine_referrals', 'landing_pages', or 'visitor_locations'.
 * 
 * @param string $start_date
 * 
 * @param string $end_date
 * 
 * @return bool|array Boolean false on error, otherwise array of results.
 */
function skystats_get_google_analytics_top_data( $data_type, $start_date, $end_date ) {

	if ( ! in_array( $data_type, array( 'keywords', 'search_engine_referrals', 'landing_pages', 'visitor_locations', true ) ) ) {
		return false;
	}

	switch ( $data_type ) {
		case 'keywords':
			$api_url = SKYSTATS_GOOGLE_ANALYTICS_API_GET_TOP_KEYWORDS_URL;
			break;
		case 'search_engine_referrals':
			$api_url = SKYSTATS_GOOGLE_ANALYTICS_API_GET_TOP_SEARCH_ENGINE_REFERRALS_URL;
			break;
		case 'landing_pages':
			$api_url = SKYSTATS_GOOGLE_ANALYTICS_API_GET_TOP_LANDING_PAGES_URL;
			break;
		case 'visitor_locations':
			$api_url = SKYSTATS_GOOGLE_ANALYTICS_API_GET_TOP_VISITOR_LOCATIONS_URL;
			break;
		default:
			return false;
	}

	require_once SKYSTATS_FUNCTIONS_PATH . 'sanitization.php';

	$profile_id = get_option( 'skystats_selected_google_analytics_profile_id' );
	if ( ! ctype_digit( $profile_id ) ) {
		$profile_id = null;
	}

	$start_date_time = (string) strtotime($start_date);
	$end_date_time = (string) strtotime($end_date);

	$cache_name = "skystats_cache_google_analytics_top_data_{$start_date_time}__{$end_date_time}_{$data_type}_{$profile_id}";

	if ( false !== $data = skystats_api_cache_get( $cache_name ) ) {
		return $data;
	}

	$url = add_query_arg( array(
		'profileID'     => $profile_id,
		'startDate'     => $start_date,
		'endDate'       => $end_date,
		'licenseKey'    => get_option( 'skystats_license_key' ),
		'licenseDomain' => home_url(),
		'googleAccountEmail' => get_option( 'skystats_selected_google_analytics_google_account_email' ),
	), $api_url );

	$response = wp_remote_get( $url, array(
		'timeout'     => SKYSTATS_API_REQUEST_TIMEOUT,
		'sslverify'   => SKYSTATS_API_REQUEST_VERIFY_SSL,
		'compress'    => SKYSTATS_API_REQUEST_COMPRESS,
	) );

	$default_return_value = array( 'data' => null, 'responseType' => 'error', 'responseContext' => 'http_error' );

	if ( is_wp_error( $response ) ) {
		return $default_return_value;
	}

	if ( '' === $body = wp_remote_retrieve_body( $response ) ) {
		return $default_return_value;
	}

	$body = json_decode( $body, true );

	if ( ! is_array( $body ) ) {
		return $default_return_value;
	}

	if ( ! ( array_key_exists( 'data', $body ) && array_key_exists( 'responseType', $body ) && array_key_exists( 'responseContext', $body ) ) ) {
		return $default_return_value;
	}

	if ( isset( $body['responseType'] ) && 'success' === $body['responseType'] ) {
		skystats_api_cache_set( $cache_name, $body );
	}
	
	return $body;
}

/**
 * Return JSON encoded data of a profiles request.
 *
 * @since 0.1.4
 * 
 * @param string $request_type 'fresh' or 'cached'.
 *
 * @return array JSON encoded data.
 */
function skystats_api_google_analytics_get_profiles( $request_type ) {

	$cache_name = 'skystats_cache_google_analytics_configuration_data';

	if ( ( 'cached' === $request_type ) && ( false !== ( $data = skystats_api_cache_get( $cache_name ) ) ) ) {
		return $data;
	}

	$url = add_query_arg( array(
		'licenseKey'    => get_option( 'skystats_license_key' ),
		'licenseDomain' => home_url(),
		'useCache'      => 'enabled' === get_option( 'skystats_cache_mode' ),
		'googleAccountEmail' => get_option( 'skystats_selected_google_analytics_google_account_email' ),
	), SKYSTATS_GOOGLE_ANALYTICS_API_GET_CONFIGURATION_DATA_URL );

	$response = wp_remote_get( $url, array(
		'timeout'     => SKYSTATS_API_REQUEST_TIMEOUT,
		'sslverify'   => SKYSTATS_API_REQUEST_VERIFY_SSL,
		'compress'    => SKYSTATS_API_REQUEST_COMPRESS,
	) );

	$default_return_value = array( 'data' => null, 'responseType' => 'error', 'responseContext' => 'http_error' );

	if ( is_wp_error( $response ) ) {
		return $default_return_value;
	}

	if ( '' === $body = wp_remote_retrieve_body( $response ) ) {
		return $default_return_value;
	}

	$body = json_decode( $body, true );

	if ( ! is_array( $body ) ) {
		return $default_return_value;
	}

	if ( ! ( array_key_exists( 'data', $body ) && array_key_exists( 'responseType', $body ) && array_key_exists( 'responseContext', $body ) ) ) {
		return $default_return_value;
	}

	if ( isset( $body['responseType'] ) && 'success' === $body['responseType'] ) {
		skystats_api_cache_set( $cache_name, $body );
	}

	return $body;
}

/**
 * Return JSON encoded data for the mashboard view.
 *
 * @since 0.1.4
 *
 * @return array Mashboard view request data response.
 */
function skystats_api_google_analytics_get_mashboard_view_data() {

	$profile_id = get_option( 'skystats_selected_google_analytics_profile_id' );

	$start_date_time = (string) strtotime( $_GET['start_date'] );
	$end_date_time = (string) strtotime( $_GET['end_date'] );

	$cache_name = "skystats_cache_google_analytics_mashboard_data_{$start_date_time}__{$end_date_time}_{$profile_id}";

	if ( false !== $data = skystats_api_cache_get( $cache_name ) ) {
		return $data;
	}

	$url = add_query_arg( array(
		'profileID'     => $profile_id,
		'startDate'     => date( 'Y-m-d', $start_date_time ),
		'endDate'       => date( 'Y-m-d', $end_date_time ),
		'licenseKey'    => get_option( 'skystats_license_key' ),
		'licenseDomain' => home_url(),
		'googleAccountEmail' => get_option( 'skystats_selected_google_analytics_google_account_email' ),
	), SKYSTATS_GOOGLE_ANALYTICS_API_GET_MASHBOARD_VIEW_DATA_URL );

	$response = wp_remote_get( $url, array(
		'timeout'     => SKYSTATS_API_REQUEST_TIMEOUT,
		'sslverify'   => SKYSTATS_API_REQUEST_VERIFY_SSL,
		'compress'    => SKYSTATS_API_REQUEST_COMPRESS,
	) );

	$default_return_value = array( 'data' => null, 'responseType' => 'error', 'responseContext' => 'http_error' );

	if ( is_wp_error( $response ) ) {
		return $default_return_value;
	}

	if ( '' === $body = wp_remote_retrieve_body( $response ) ) {
		return $default_return_value;
	}

	$body = json_decode( $body, true );

	if ( ! is_array( $body ) ) {
		return $default_return_value;
	}

	if ( ! ( array_key_exists( 'data', $body ) && array_key_exists( 'responseType', $body ) && array_key_exists( 'responseContext', $body ) ) ) {
		return $default_return_value;
	}

	if ( isset( $body['responseType'] ) && 'success' === $body['responseType'] ) {
		skystats_api_cache_set( $cache_name, $body );
	}

	return $body;
}

/**
 * Return JSON encoded data for the detail view.
 *
 * @since 0.1.4
 *
 * @return array Detail view request data response.
 */
function skystats_api_google_analytics_get_detail_view_data() {

	$profile_id = get_option( 'skystats_selected_google_analytics_profile_id' );

	$start_date_time = (string) strtotime( $_GET['start_date'] );
	$end_date_time = (string) strtotime( $_GET['end_date'] );
	$frequency = isset( $_GET['frequency'] ) && 'monthly' === $_GET['frequency'] ? 'monthly' : 'daily';

	$cache_name = "skystats_cache_google_analytics_detail_data_{$start_date_time}__{$end_date_time}_{$profile_id}_{$frequency}";

	if ( false !== $data = skystats_api_cache_get( $cache_name ) ) {
		return $data;
	}

	$url = add_query_arg( array(
		'profileID'     => $profile_id,
		'startDate'     => date( 'Y-m-d', $start_date_time ),
		'endDate'       => date( 'Y-m-d', $end_date_time ),
		'licenseKey'    => get_option( 'skystats_license_key' ),
		'licenseDomain' => home_url(),
		'frequency'     => $frequency,
		'googleAccountEmail' => get_option( 'skystats_selected_google_analytics_google_account_email' ),
	), SKYSTATS_GOOGLE_ANALYTICS_API_GET_DETAIL_VIEW_DATA_URL );

	$response = wp_remote_get( $url, array(
		'timeout'     => SKYSTATS_API_REQUEST_TIMEOUT,
		'sslverify'   => SKYSTATS_API_REQUEST_VERIFY_SSL,
		'compress'    => SKYSTATS_API_REQUEST_COMPRESS,
	) );

	$default_return_value = array( 'data' => null, 'responseType' => 'error', 'responseContext' => 'http_error' );

	if ( is_wp_error( $response ) ) {
		return $default_return_value;
	}

	if ( '' === $body = wp_remote_retrieve_body( $response ) ) {
		return $default_return_value;
	}

	$body = json_decode( $body, true );

	if ( ! is_array( $body ) ) {
		return $default_return_value;
	}

	if ( ! ( array_key_exists( 'data', $body ) && array_key_exists( 'responseType', $body ) && array_key_exists( 'responseContext', $body ) ) ) {
		return $default_return_value;
	}

	if ( isset( $body['responseType'] ) && 'success' === $body['responseType'] ) {
		skystats_api_cache_set( $cache_name, $body );
	}

	return $body;
}

/**
 * Reset the Google Account email that was selected so a different one can be selected or a new one added the next time.
 *
 * @since 0.3.5
 */
function skystats_api_google_analytics_reset_google_account_email() {
	delete_option( 'skystats_selected_google_analytics_google_account_email' );
}

/**
 * Return Google Accounts available to this license.
 *
 * @since 0.3.5
 *
 * @return array Data containing the accounts or an error.
 */
function skystats_api_google_analytics_get_google_accounts() {

	$url = add_query_arg( array(
		'licenseKey'    => get_option( 'skystats_license_key' ),
		'licenseDomain' => home_url(),
	), SKYSTATS_GOOGLE_ANALYTICS_API_GET_GOOGLE_ACCOUNTS_URL );

	$response = wp_remote_get( $url, array(
		'timeout'     => SKYSTATS_API_REQUEST_TIMEOUT,
		'sslverify'   => SKYSTATS_API_REQUEST_VERIFY_SSL,
		'compress'    => SKYSTATS_API_REQUEST_COMPRESS,
	) );

	$default_return_value = array( 'data' => null, 'responseType' => 'error', 'responseContext' => 'http_error' );

	if ( is_wp_error( $response ) ) {
		return $default_return_value;
	}

	if ( '' === $body = wp_remote_retrieve_body( $response ) ) {
		return $default_return_value;
	}

	$body = json_decode( $body, true );

	if ( ! is_array( $body ) ) {
		return $default_return_value;
	}

	if ( ! ( array_key_exists( 'data', $body ) && array_key_exists( 'responseType', $body ) && array_key_exists( 'responseContext', $body ) ) ) {
		return $default_return_value;
	}

	return $body;
}