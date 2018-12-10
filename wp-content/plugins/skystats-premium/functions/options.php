<?php

/**
 * SkyStats option-related functions.
 * 
 * @since 0.0.1
 *
 * @package SkyStats
 */

// Prevent direct access
defined( 'ABSPATH' ) or exit();

/**
 * Returns options used for blogs and sites.
 * 
 * @since 0.0.1
 * 
 * @return array
 */
function skystats_get_options() {

	$options = array();

	$options['site']['skystats_version'] = SKYSTATS_VERSION;
	$options['blog']['skystats_version'] = SKYSTATS_VERSION;

	$options['site']['skystats_perform_network_install']    = false;

	$options['blog']['skystats_license_key']                = null;
	$options['blog']['skystats_brand_name']                 = SKYSTATS_NAME;
	$options['blog']['skystats_brand_menu_name']            = SKYSTATS_NAME;
	$options['blog']['skystats_brand_background_image_url'] = SKYSTATS_DEFAULT_BACKGROUND_IMAGE_URL;
	$options['blog']['skystats_brand_logo_image_url']       = SKYSTATS_DEFAULT_LOGO_IMAGE_URL;
	$options['blog']['skystats_mashboard_menu_name']  = __( 'Mashboard', SKYSTATS_TEXT_DOMAIN );

	// (string) Hex color for background color on SkyStats pages.
	$options['blog']['skystats_brand_background_color']     = '#333';

	// (bool) Whether to remove WordPress Update Notice on SkyStats pages.
	$options['blog']['skystats_remove_update_nag']          = false;

	/*
	 * (string) The role which is allowed access to the SkyStats pages.
	 * Any role above the role is also allowed access.
	 */
	$options['blog']['skystats_selected_role_access']       = '';

	// (string) Location user is redirected upon login (SkyStats Mashboard or WordPress dashboard).
	$options['blog']['skystats_default_dashboard']          = 'skystats_mashboard';

	// (string[]) Contains role identifiers as string-values.
	$options['blog']['skystats_role_access']                = array();

	// (array) Mashboard cards positions
	$options['blog']['skystats_mashboard_card_positions']   = array(
		'postbox-container-1' => array(
			'googleanalytics_1',
			'youtube_5',
			'wordpress_9',
		),
		'postbox-container-2' => array(
			'facebook_2',
			'googleplus_6',
			'aweber_10',
		),
		'postbox-container-3' => array(
			'twitter_3',
			'linkedin_7',
		),
		'postbox-container-4' => array(
			'googleadwords_11',
			'vote_13',
			'paypal_4',
			'mailchimp_8',
			'campaignmonitor_12',
		),
	);

	$options['blog']['skystats_mashboard_cards_visibility_status'] = array(
		// 1 = visible, 0 = hidden (all visible by default)
		'googleanalytics_1' => '1',
		'facebook_2'        => '1',
		'twitter_3'         => '1',
		'paypal_4'          => '1',
		'youtube_5'         => '1',
		'googleplus_6'      => '1',
		'linkedin_7'        => '1',
		'mailchimp_8'       => '1',
		'wordpress_9'       => '1',
		'aweber_10'         => '1',
		'googleadwords_11'  => '1',
		'campaignmonitor_12'=> '1',
		'vote_13'           => '0',
	);

	// (null|string) Selected Google Analytics Google Account Email
	$options['blog']['skystats_selected_google_analytics_google_account_email'] = null;

	// (null|string) Selected Google Analytics Profile ID for data retrieval
	$options['blog']['skystats_selected_google_analytics_profile_id'] = null;

	// (null|string) Selected Facebook Page ID for data retrieval
	$options['blog']['skystats_selected_facebook_page_id'] = null;

	// (string) Whether to use the cache when fetching data for any integration (default: enabled).
	$options['blog']['skystats_cache_mode'] = 'enabled';

	// (null|string) The license type (free, personal, business, developer)
	$options['blog']['skystats_license_type'] = null;

	// (null|string) Adwords Customer/Account Id
	$options['blog']['skystats_google_adwords_selected_customer_id'] = null;

	// (null|string) Adwords Campaign Id
	$options['blog']['skystats_google_adwords_selected_campaign_id'] = null;

	// (array) Adwords accounts
	$options['blog']['skystats_google_adwords_accounts'] = array();

	// (string) Date Range Label Color
	$options['blog']['skystats_date_range_label_color'] = '#fff';

	// (array) identifiers of roles as values that are allowed to view and access the reports (mashboard & detail pages).
	$options['blog']['skystats_reports_users_allowed_access'] = array();

	// (array) Ids of users as values that are allowed to view and change the Settings.
	$options['blog']['skystats_settings_users_allowed_access'] = array();

	return $options;
}