<?php

defined( 'ABSPATH' ) or exit();

?>

<div class="wrap">
	<div id="skystats-logo-container">
		<?php $current_page_url = esc_attr( admin_url() . 'admin.php?page=skystats-mashboard' ); ?>
		<?php $brand_logo_image_url = esc_attr( get_option( 'skystats_brand_logo_image_url' ) ); ?>
		<a class="skystats-shadowless" href="<?php echo $current_page_url; ?>">
			<?php $id = ( SKYSTATS_DEFAULT_LOGO_IMAGE_URL === $brand_logo_image_url ) ?
				'skystats-default-logo' :
				'';
			?>
			<img id="<?php echo $id; ?>" src="<?php echo $brand_logo_image_url; ?>">
		</a>
	</div>

	<div id="skystats-date-range-container">
		<div id="skystats-start-date-container">
			<label class="skystats-query-parameter-label" id="skystats-date-range-text" for="start_date"><?php _e( 'Date Range:', SKYSTATS_TEXT_DOMAIN ); ?></label>
			<input class="skystats-date" type="text" id="start_date" name="start_date" placeholder="<?php _e( 'MM/DD/YYYY', SKYSTATS_TEXT_DOMAIN ); ?>" required="required" value="">
		</div>
		<div id="skystats-end-date-container">
			<input class="skystats-date" type="text" id="end_date" name="end_sate" placeholder="<?php _e( 'MM/DD/YYYY', SKYSTATS_TEXT_DOMAIN ); ?>" required="required" value="">
			<button class="skystats-button skystats-shadowless" id="date_range" type="submit" name="date_range" value="-1"><?php _e( 'Update', SKYSTATS_TEXT_DOMAIN ); ?></button>
		</div>
	</div>

	<div id="skystats-page-error-container" class="skystats-page-error-container skystats-error-container">
		<p></p>
	</div>

	<!-- Loading Image Container -->
	<div id="skystats-cards-loading-container">
		<img class="skystats-loading-image" src="<?php echo SKYSTATS_TEMPLATE_IMAGES_URL . 'loading-spin.svg'; ?>" width="64" height="64">
	</div>

	<div id="dashboard-widgets" class="metabox-holder">

		<?php $mashboard_integrations_path = dirname( __FILE__ ) . '/mashboard-integrations/'; ?>

		<!-- 1st Column -->
		<div id="postbox-container-1" class="postbox-container skystats-cards-column">

			<?php require_once $mashboard_integrations_path . 'google-analytics.php'; ?>

		</div>

		<!-- 2nd Column -->
		<div id="postbox-container-2" class="postbox-container skystats-cards-column">

			<?php require_once $mashboard_integrations_path . 'facebook.php'; ?>

		</div>

		<!-- 3rd Column -->
		<div id="postbox-container-3" class="postbox-container skystats-cards-column">

			<?php require_once $mashboard_integrations_path . 'twitter.php'; ?>

		</div>

		<!-- 4th Column -->
		<div id="postbox-container-4" class="postbox-container skystats-cards-column">

			<?php

			require_once $mashboard_integrations_path . 'google-adwords.php';

			require_once $mashboard_integrations_path . 'mailchimp.php';

			?>

		</div>
	</div>
</div> <!-- .wrap -->