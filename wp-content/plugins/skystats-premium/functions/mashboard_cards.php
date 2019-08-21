<?php

// Prevent direct access
defined( 'ABSPATH' ) or exit();

/**
 * SkyStats mashboard cards related functions.
 *
 * @since 0.2.9
 *
 * @package SkyStats\Mashboard
 */

/**
 * Return array as key (mashboard card element ID) => value (translated display name) pairs
 *
 * @since 0.2.9
 *
 * @return array
 */
function skystats_get_mashboard_card_names() {
	return array(
		'googleanalytics_1'  => __( 'Google Analytics', SKYSTATS_TEXT_DOMAIN ),
		'facebook_2'         => __( 'Facebook', SKYSTATS_TEXT_DOMAIN ),
		'twitter_3'          => __( 'Twitter', SKYSTATS_TEXT_DOMAIN ),
		'paypal_4'           => __( 'PayPal', SKYSTATS_TEXT_DOMAIN ),
		'youtube_5'          => __( 'YouTube', SKYSTATS_TEXT_DOMAIN ),
		'googleplus_6'       => __( 'Google Plus', SKYSTATS_TEXT_DOMAIN ),
		'linkedin_7'         => __( 'LinkedIn', SKYSTATS_TEXT_DOMAIN ),
		'mailchimp_8'        => __( 'MailChimp', SKYSTATS_TEXT_DOMAIN ),
		'wordpress_9'        => __( 'WordPress', SKYSTATS_TEXT_DOMAIN ),
		'aweber_10'          => __( 'AWeber', SKYSTATS_TEXT_DOMAIN ),
		'googleadwords_11'   => __( 'Google Adwords', SKYSTATS_TEXT_DOMAIN ),
		'campaignmonitor_12' => __( 'Campaign Monitor', SKYSTATS_TEXT_DOMAIN ),
		'vote_13'            => __( 'Vote', SKYSTATS_TEXT_DOMAIN ),
		'upgrading_14'       => __( 'Upgrade', SKYSTATS_TEXT_DOMAIN ),
	);
}

/**
 * Returns array of mashboard cards element IDs.
 *
 * @since 0.2.9
 *
 * @return array
 */
function skystats_get_mashboard_card_identifiers() {
	return array(
		'googleanalytics_1',
		'facebook_2',
		'twitter_3',
		'paypal_4',
		'youtube_5',
		'googleplus_6',
		'linkedin_7',
		'mailchimp_8',
		'wordpress_9',
		'aweber_10',
		'googleadwords_11',
		'campaignmonitor_12',
		'vote_13',
		'upgrading_14',
	);
}

/**
 * Return array as key (mashboard card element ID) => value (1 (enabled) or 0 (disabled)) pairs.
 *
 * @since 0.2.9
 *
 * @return array
 */
function skystats_get_mashboard_cards_enabled_status() {
	return array(
		'googleanalytics_1'  => '1',
		'facebook_2'         => '1',
		'twitter_3'          => '1',
		'paypal_4'           => '0',
		'youtube_5'          => '0',
		'googleplus_6'       => '0',
		'linkedin_7'         => '0',
		'mailchimp_8'        => '1',
		'wordpress_9'        => '0',
		'aweber_10'          => '0',
		'googleadwords_11'   => '1',
		'campaignmonitor_12' => '0',
		'vote_13'            => '0',
		'upgrading_14'       => '0',
	);
}