<?php

/**
 * SkyStats definitions.
 * 
 * @since 0.0.1
 *
 * @package SkyStats
 */

// Prevent direct access
defined( 'ABSPATH' ) or exit();

/**
 * The name of the plugin.
 *
 * @since 0.0.1
 * 
 * @var string
 */
define( 'SKYSTATS_NAME', 'SkyStats' );

/**
 * Whether to use minified versions of CSS & JavaScript.
 *
 * Useful for testing there are no inconsistencies between the two
 * versions and/or to improve performance. This is always set to true by default.
 *
 * @since 0.0.1
 *
 * @var bool
 */
define( 'SKYSTATS_USE_MINIFIED_SCRIPTS', true );

/**
 * The mode of the plugin.
 *
 * This directive is, and must be, used by developers/testers only.
 * 
 * Options:
 * 
 * local_dev = Local development
 * 
 * local_prod = Local Production
 * 
 * remote_dev = Remote Development
 * 
 * remote_prod = Remote Production
 * 
 * @since 0.0.1
 * 
 * @var string
 */
define( 'SKYSTATS_MODE', 'remote_prod' );

/**
 * This release version of SkyStats.
 * 
 * @since 0.0.1
 * 
 * @var string
 */
define( 'SKYSTATS_VERSION', '0.3.7' );

/**
 * Name of template to use.
 * 
 * Note: Experimental feature. Changing to a template that doesn't exist or have the necessary files won't work.
 * 
 * @since 0.0.1
 * 
 * @var string
 */
define( 'SKYSTATS_TEMPLATE_NAME', 'default' );

/**
 * Absolute path to SkyStats plugin root.
 *
 * @since 0.0.1
 * 
 * @var string
 */
define( 'SKYSTATS_ROOT_PATH', dirname( dirname( __FILE__ ) ) . '/' );

/**
 * Absolute path to SkyStats plugin includes directory.
 * 
 * @since 0.0.1
 * 
 * @var string
 */
define( 'SKYSTATS_INCLUDES_PATH', SKYSTATS_ROOT_PATH . 'includes/' );

/**
 * Absolute path to SkyStats plugin classes directory
 * 
 * @since 0.0.1
 * 
 * @var string
 */
define( 'SKYSTATS_CLASSES_PATH', SKYSTATS_ROOT_PATH . 'classes/' );

/**
 * Absolute path to SkyStats plugin functions directory.
 * 
 * @since 0.0.1
 * 
 * @var string
 */
define( 'SKYSTATS_FUNCTIONS_PATH', SKYSTATS_ROOT_PATH . 'functions/' );

/**
 * Absolute path to SkyStats plugin API functions directory.
 * 
 * @since 0.0.1
 * 
 * @var string
 */
define( 'SKYSTATS_API_FUNCTIONS_PATH', SKYSTATS_FUNCTIONS_PATH . 'api/' );

/**
 * Absolute path to SkyStats templates directory
 * 
 * @since 0.0.1
 * 
 * @var string
 */
define( 'SKYSTATS_TEMPLATES_PATH', SKYSTATS_ROOT_PATH . 'templates/' );

/**
 * Absolute path to active SkyStats template directory.
 * 
 * @since 0.0.1
 * 
 * @var string
 */
define( 'SKYSTATS_TEMPLATE_PATH', SKYSTATS_TEMPLATES_PATH . SKYSTATS_TEMPLATE_NAME . '/' );

/**
 * Absolute path to active SkyStats template admin directory.
 * 
 * @since 0.0.1
 * 
 * @var string
 */
define( 'SKYSTATS_TEMPLATE_ADMIN_PATH', SKYSTATS_TEMPLATE_PATH . 'admin/' );

/**
 * Absolute URL to SkyStats root.
 *
 * @since 0.0.1
 * 
 * @var string
 */
defined( 'SKYSTATS_ROOT_URL' ) or define( 'SKYSTATS_ROOT_URL', plugin_dir_url( dirname( __FILE__ ) ) );

/**
 * Absolute URL to SkyStats templates directory.
 * 
 * @since 0.0.1
 * 
 * @var string
 */
define( 'SKYSTATS_TEMPLATES_URL', SKYSTATS_ROOT_URL . 'templates/' );

/**
 * Absolute URL to active SkyStats template directory.
 * 
 * @since 0.0.1
 * 
 * @var string
 */
define( 'SKYSTATS_TEMPLATE_URL', SKYSTATS_TEMPLATES_URL . SKYSTATS_TEMPLATE_NAME . '/' );

/**
 * Absolute URL to SkyStats static (css, js, and images) folder.
 *
 * @since 0.0.1
 * 
 * @var string
 */
define( 'SKYSTATS_TEMPLATE_STATIC_URL', SKYSTATS_TEMPLATE_URL . 'static/' );

/**
 * Absolute URL to SkyStats images folder.
 *
 * @since 0.0.1
 * 
 * @var string
 */
define( 'SKYSTATS_TEMPLATE_IMAGES_URL', SKYSTATS_TEMPLATE_STATIC_URL . 'images/' );

/**
 * Absolute URL to SkyStats default background image.
 * 
 * @since 0.0.1
 * 
 * @var string
 */
define( 'SKYSTATS_DEFAULT_BACKGROUND_IMAGE_URL', SKYSTATS_TEMPLATE_IMAGES_URL . 'background.jpg' );

/**
 * Absolute URL to SkyStats default logo image.
 * 
 * @since 0.0.1
 * 
 * @var string
 */
define( 'SKYSTATS_DEFAULT_LOGO_IMAGE_URL', SKYSTATS_TEMPLATE_IMAGES_URL . 'logo.png' );

/**
 * Absolute URL to SkyStats CSS folder.
 * 
 * @since 0.0.1
 * 
 * @var string
 */
define( 'SKYSTATS_TEMPLATE_CSS_URL', SKYSTATS_TEMPLATE_STATIC_URL . 'css/' );

/**
 * Absolute URL to SkyStats minified CSS folder.
 * 
 * @since 0.0.1
 * 
 * @var string
 */
define( 'SKYSTATS_TEMPLATE_CSS_MIN_URL' , SKYSTATS_TEMPLATE_CSS_URL );

/**
 * Absolute URL to SkyStats JavaScript folder.
 * 
 * @since 0.0.1
 * 
 * @var string
 */
define( 'SKYSTATS_TEMPLATE_JS_URL', SKYSTATS_TEMPLATE_STATIC_URL . 'js/' );

/**
 * Absolute URL to SkyStats minified JavaScript folder.
 * 
 * @since 0.0.1
 * 
 * @var string
 */
define( 'SKYSTATS_TEMPLATE_JS_MIN_URL', SKYSTATS_TEMPLATE_JS_URL );

/**
 * The text domain used by SkyStats for translations.
 *
 * @since 0.0.1
 * 
 * @var string
 */
define( 'SKYSTATS_TEXT_DOMAIN', 'skystats' );

/**
 * Absolute URL to where you can purchase a license key to unlock all SkyStats' features.
 * 
 * @since 0.0.1
 * 
 * @var string
 */
switch ( SKYSTATS_MODE ) {
	case 'remote_dev':
	case 'remote_prod':
		define( 'SKYSTATS_PURCHASE_LICENSE_KEY_URL', 'https://skystats.com/' );
		break;
	case 'local_dev':
	case 'local_prod':
	default:
		define( 'SKYSTATS_PURCHASE_LICENSE_KEY_URL', 'http://skystats-store.dev/' );
		break;
}

/**
 * Absolute URL to the SkyStats store.
 * 
 * @since 0.0.1
 * 
 * @var string
 */
switch ( SKYSTATS_MODE ) {
	case 'remote_dev':
	case 'remote_prod':
		define( 'SKYSTATS_STORE_URL', 'https://skystats.com/' );
		break;
	case 'local_dev':
	case 'local_prod':
	default:
		define( 'SKYSTATS_STORE_URL', 'http://skystats-store.dev/' );
		break;
}

/**
 * Product name.
 * 
 * This shouldn't be modified unless you know what you're doing.
 * 
 * @since 0.0.1
 * 
 * @var string
 */
define( 'SKYSTATS_PRODUCT_NAME', 'SkyStats' );

/**
 * Absolute URL to the SkyStats API.
 * 
 * @since 0.0.1
 * 
 * @var string
 */
switch ( SKYSTATS_MODE ) {
	case 'remote_dev':
		define( 'SKYSTATS_API_URL', 'http://dev.skystats.com/' );
		break;
	case 'remote_prod':
		define( 'SKYSTATS_API_URL', 'http://app.skystats.com/' );
		break;
	case 'local_dev':
	case 'local_prod':
	default:
		define( 'SKYSTATS_API_URL', 'http://skystats.dev/' );
		break;
}

/**
 * SkyStats Google Analytics API version.
 *
 * @since 0.3.5
 *
 * @var string
 */
define( 'SKYSTATS_GOOGLE_ANALYTICS_API_VERSION', 'v1' );

/**
 * SkyStats Google Analytics API URL.
 *
 * @since 0.3.5
 *
 * @var string
 */
define( 'SKYSTATS_GOOGLE_ANALYTICS_API_URL', SKYSTATS_API_URL . 'api/' . SKYSTATS_GOOGLE_ANALYTICS_API_VERSION . '/googleAnalytics/' );

/**
 * SkyStats Google Analytics API authorize URL.
 *
 * @since 0.3.5
 *
 * @var string
 */
define( 'SKYSTATS_GOOGLE_ANALYTICS_API_AUTHORIZE_URL', SKYSTATS_GOOGLE_ANALYTICS_API_URL . 'authenticate' );

/**
 * SkyStats Google Analytics API deauthorize URL.
 *
 * @since 0.3.5
 *
 * @var string
 */
define( 'SKYSTATS_GOOGLE_ANALYTICS_API_DEAUTHORIZE_URL', SKYSTATS_GOOGLE_ANALYTICS_API_URL . 'deauthorize' );

/**
 * SkyStats Google Analytics API Google accounts URL.
 *
 * @since 0.3.5
 *
 * @var string
 */
define( 'SKYSTATS_GOOGLE_ANALYTICS_API_GET_GOOGLE_ACCOUNTS_URL', SKYSTATS_GOOGLE_ANALYTICS_API_URL . 'getGoogleAccounts' );

/**
 * SkyStats Google Analytics API configuration data URL.
 *
 * @since 0.3.5
 *
 * @var string
 */
define( 'SKYSTATS_GOOGLE_ANALYTICS_API_GET_CONFIGURATION_DATA_URL', SKYSTATS_GOOGLE_ANALYTICS_API_URL . 'getConfigurationData' );

/**
 * SkyStats Google Analytics API Mashboard view data URL.
 *
 * @since 0.3.5
 *
 * @var string
 */
define( 'SKYSTATS_GOOGLE_ANALYTICS_API_GET_MASHBOARD_VIEW_DATA_URL', SKYSTATS_GOOGLE_ANALYTICS_API_URL . 'getMashboardViewData' );

/**
 * SkyStats Google Analytics API Detail view data URL.
 *
 * @since 0.3.5
 *
 * @var string
 */
define( 'SKYSTATS_GOOGLE_ANALYTICS_API_GET_DETAIL_VIEW_DATA_URL', SKYSTATS_GOOGLE_ANALYTICS_API_URL . 'getDetailViewData' );

/**
 * SkyStats Google Analytics API top keywords data URL.
 *
 * @since 0.3.5
 *
 * @var string
 */
define( 'SKYSTATS_GOOGLE_ANALYTICS_API_GET_TOP_KEYWORDS_URL', SKYSTATS_GOOGLE_ANALYTICS_API_URL . 'getTopKeywords' );

/**
 * SkyStats Google Analytics API top search engine referrals data URL.
 *
 * @since 0.3.5
 *
 * @var string
 */
define( 'SKYSTATS_GOOGLE_ANALYTICS_API_GET_TOP_SEARCH_ENGINE_REFERRALS_URL', SKYSTATS_GOOGLE_ANALYTICS_API_URL . 'getTopSearchEngineReferrals' );

/**
 * SkyStats Google Analytics API top landing pages data URL.
 *
 * @since 0.3.5
 *
 * @var string
 */
define( 'SKYSTATS_GOOGLE_ANALYTICS_API_GET_TOP_LANDING_PAGES_URL', SKYSTATS_GOOGLE_ANALYTICS_API_URL . 'getTopLandingPages' );

/**
 * SkyStats Google Analytics API top visitor locations URL.
 *
 * @since 0.3.5
 *
 * @var string
 */
define( 'SKYSTATS_GOOGLE_ANALYTICS_API_GET_TOP_VISITOR_LOCATIONS_URL', SKYSTATS_GOOGLE_ANALYTICS_API_URL . 'getTopVisitorLocations' );

/**
 * Timeout for API calls in seconds.
 * 
 * @since 0.0.1
 * 
 * @var int
 */
define( 'SKYSTATS_API_REQUEST_TIMEOUT', 120 );

/**
 * Whether to compress the request body for API calls.
 * 
 * @since 0.0.1
 * 
 * @var bool
 */
define( 'SKYSTATS_API_REQUEST_COMPRESS', true );

/**
 * Whether to check if the SSL certificate is valid for the API domain.
 * 
 * @since 0.0.1
 * 
 * @var bool
 */
define( 'SKYSTATS_API_REQUEST_VERIFY_SSL', false );

if ( ini_get( 'max_execution_time' ) <= 30 ) {
	ini_set( 'max_execution_time', SKYSTATS_API_REQUEST_TIMEOUT );
}

/**
 * URL to the mashboard page.
 *
 * @since 0.1.4
 *
 * @var string
 */
define( 'SKYSTATS_MASHBOARD_PAGE_URL', admin_url( 'admin.php?page=skystats-mashboard' ) );

/**
 * URL to the Facebook detail page.
 *
 * @since 0.1.4
 *
 * @var string
 */
define( 'SKYSTATS_FACEBOOK_DETAIL_PAGE_URL', admin_url( 'admin.php?page=skystats-facebook' ) );

/**
 * URL to the Google Analytics detail page.
 *
 * @since 0.1.4
 *
 * @var string
 */
define( 'SKYSTATS_GOOGLE_ANALYTICS_DETAIL_PAGE_URL', admin_url( 'admin.php?page=skystats-google-analytics' ) );

/**
 * Absolute URL to the Twitter detail page.
 *
 * @since 0.2.5
 *
 * @var string
 */
define( 'SKYSTATS_TWITTER_DETAIL_PAGE_URL', admin_url( 'admin.php?page=skystats-twitter' ) );

/**
 * URL to the Google Adwords detail page.
 *
 * @since 0.2.8
 *
 * @var string
 */
define( 'SKYSTATS_GOOGLE_ADWORDS_DETAIL_PAGE_URL', admin_url( 'admin.php?page=skystats-google-adwords' ) );

/**
 * URL to the MailChimp detail page.
 *
 * @since 0.3.2
 *
 * @var string
 */
define( 'SKYSTATS_MAILCHIMP_DETAIL_PAGE_URL', admin_url( 'admin.php?page=skystats-mailchimp' ) );

/**
 * URL to the settings page.
 *
 * @since 0.1.4
 *
 * @var string
 */
define( 'SKYSTATS_SETTINGS_PAGE_URL', admin_url( 'admin.php?page=skystats-settings' ) );

/**
 * Absolute URL to renew a license key.
 *
 * @since 0.1.5
 *
 * @var string
 */
define( 'SKYSTATS_RENEW_LICENSE_KEY_URL', 'https://skystats.com/checkout/?edd_license_key={LICENSE_KEY}&download_id=29' );

/**
 * Version identifier for script and styles.
 *
 * @since 0.2.5
 *
 * @var string
 */
define( 'SKYSTATS_SCRIPTS_VERSION', 'skystats-premium-037-1463472655' );

/**
 * Authorization popup window complete URL.
 *
 * The URL the popup window redirects to after an integration is setup.
 *
 * @since 0.3.0
 *
 * @var string
 */
define( 'SKYSTATS_AUTH_POPUP_WINDOW_COMPLETE_URL', admin_url( 'admin.php?skystats_auth_popup_window_complete=1' ) );